<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class BookingController extends Controller
{
    public function historyPage()
    {
        return view('layouts.customer.riwayatBooking.history');
    }

    public function adminPage()
    {
        return view('layouts.admin.booking.index');
    }

    public function createPage()
    {
        $customer_id = DB::table('customers')
            ->where('user_id', session('user_id'))
            ->value('customer_id');

        $vehicles = DB::table('vehicles')
            ->where('customer_id', $customer_id)
            ->get();

        $schedules = DB::table('service_schedules')
            ->where('status', 'available')
            ->orderBy('service_date')
            ->orderBy('service_time')
            ->paginate(6);

        return view(
            'layouts.customer.booking.create',
            compact(
                'vehicles',
                'schedules'
            )
        );
    }
    public function store(Request $request)
    {
        $request->validate([
            'vehicle_id' => 'required',
            'schedule_id' => 'required',
            'service_type' => 'required'
        ]);

        DB::beginTransaction();

        try {

            $customer_id = DB::table('customers')
                ->where('user_id', session('user_id'))
                ->value('customer_id');

            if (!$customer_id) {

                return response()->json([
                    'success' => false,
                    'message' => 'Customer tidak ditemukan'
                ], 404);

            }

            $schedule = DB::table('service_schedules')
                ->where('schedule_id', $request->schedule_id)
                ->lockForUpdate()
                ->first();

            if (!$schedule) {

                return response()->json([
                    'success' => false,
                    'message' => 'Jadwal tidak ditemukan'
                ], 404);

            }

            if ($schedule->status == 'full') {

                return response()->json([
                    'success' => false,
                    'message' => 'Jadwal sudah penuh'
                ], 422);

            }

            if ($schedule->quota <= 0) {

                return response()->json([
                    'success' => false,
                    'message' => 'Kuota sudah habis'
                ], 422);

            }

            $booking_id = DB::table('bookings')
                ->insertGetId([
                    'customer_id' => $customer_id,
                    'vehicle_id' => $request->vehicle_id,
                    'schedule_id' => $request->schedule_id,
                    'type_service' => $request->service_type,
                    'complaint' => $request->complaint,
                    'status' => 'pending',
                    'created_at' => now(),
                    'updated_at' => now()
                ], 'booking_id');

            $newQuota = $schedule->quota - 1;

            DB::table('service_schedules')
                ->where('schedule_id', $request->schedule_id)
                ->update([
                    'quota' => $newQuota,
                    'status' => $newQuota == 0 ? 'full' : 'available',
                    'updated_at' => now()
                ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Booking berhasil dibuat',
                'booking_id' => $booking_id,
                'redirect' => '/dashboard'
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);

        }
    }
    public function historyList()
    {
        $customer_id = DB::table('customers')
            ->where('user_id', session('user_id'))
            ->value('customer_id');

        $bookings = DB::table('bookings as b')
            ->join('vehicles as v', 'b.vehicle_id', '=', 'v.vehicle_id')
            ->join('service_schedules as s', 'b.schedule_id', '=', 's.schedule_id')
            ->where('b.customer_id', $customer_id)
            ->select(
                'b.booking_id',
                'b.type_service',
                'b.complaint',
                'b.status',
                'b.booking_date',
                'v.vehicle_type',
                'v.plate_number',
                's.service_date',
                's.service_time'
            )
            ->orderByDesc('b.booking_id')
            ->get();

        return response()->json($bookings);
    }

    public function cancelBooking($id)
    {
        DB::beginTransaction();

        try {

            $booking = DB::table('bookings')
                ->where('booking_id', $id)
                ->first();

            if (!$booking) {

                return response()->json([
                    'success' => false,
                    'message' => 'Booking tidak ditemukan'
                ], 404);

            }

            if ($booking->status != 'pending') {

                return response()->json([
                    'success' => false,
                    'message' => 'Booking tidak dapat dibatalkan'
                ], 422);

            }

            DB::table('bookings')
                ->where('booking_id', $id)
                ->update([
                    'status' => 'cancelled',
                    'updated_at' => now()
                ]);

            $schedule = DB::table('service_schedules')
                ->where('schedule_id', $booking->schedule_id)
                ->lockForUpdate()
                ->first();

            $newQuota = $schedule->quota + 1;
            $status = $newQuota > 0
                ? 'available'
                : 'closed';

            DB::table('service_schedules')
                ->where('schedule_id', $booking->schedule_id)
                ->update([
                    'quota' => $newQuota,
                    'status' => $status,
                    'updated_at' => now()
                ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Booking berhasil dibatalkan'
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);

        }
    }

    public function adminList()
    {
        $bookings = DB::table('bookings as b')
            ->join('customers as c','b.customer_id','=','c.customer_id')
            ->join('vehicles as v','b.vehicle_id','=','v.vehicle_id')
            ->join('service_schedules as s','b.schedule_id','=','s.schedule_id')
            ->select(
                'b.*',
                'c.customer_name',
                'v.vehicle_type',
                'v.plate_number',
                's.service_date',
                's.service_time'
            )
            ->orderByDesc('booking_id')
            ->get();

        return response()->json($bookings);
    }

    public function approve($id)
    {
        DB::table('bookings')
            ->where('booking_id',$id)
            ->update([
                'status'=>'approved',
                'updated_at'=>now()
            ]);

        return response()->json([
            'success'=>true,
            'message'=>'Booking berhasil disetujui'
        ]);
    }

    public function complete($id)
    {
        DB::table('bookings')
            ->where('booking_id',$id)
            ->update([
                'status'=>'completed',
                'updated_at'=>now()
            ]);

        return response()->json([
            'success'=>true,
            'message'=>'Service selesai'
        ]);
    }

    public function cancel($id)
    {
        $booking = DB::table('bookings')
            ->where('booking_id',$id)
            ->first();

        DB::table('bookings')
            ->where('booking_id',$id)
            ->update([
                'status'=>'cancelled',
                'updated_at'=>now()
            ]);

        if($booking){

            DB::table('service_schedules')
                ->where('schedule_id',$booking->schedule_id)
                ->increment('quota');

            DB::table('service_schedules')
                ->where('schedule_id',$booking->schedule_id)
                ->update([
                    'status'=>'available',
                    'updated_at'=>now()
                ]);
        }

        return response()->json([
            'success'=>true,
            'message'=>'Booking dibatalkan'
        ]);
    }


}
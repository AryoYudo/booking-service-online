<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ManagerController extends Controller
{
    public function recommendationPage()
    {
        return view('layouts.manager.recommendation.index');
    }

    public function recommendationData()
    {
        $totalBooking = DB::table('bookings')->count();

        $completedBooking = DB::table('bookings')
            ->where('status', 'completed')
            ->count();

        $totalComplaint = DB::table('complaints')
            ->count();

        $totalCustomer = DB::table('customers')
            ->count();

        $successRate = $totalBooking > 0
            ? round(($completedBooking / $totalBooking) * 100)
            : 0;

        $topServices = DB::table('bookings')
            ->select(
                'type_service',
                DB::raw('count(*) as total')
            )
            ->groupBy('type_service')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $topCustomers = DB::table('bookings')
            ->join(
                'customers',
                'customers.customer_id',
                '=',
                'bookings.customer_id'
            )
            ->select(
                'customers.customer_name',
                DB::raw('count(*) as total_booking')
            )
            ->groupBy(
                'customers.customer_name'
            )
            ->orderByDesc('total_booking')
            ->limit(5)
            ->get();

        $busySchedule = DB::table('bookings')
            ->join(
                'service_schedules',
                'service_schedules.schedule_id',
                '=',
                'bookings.schedule_id'
            )
            ->select(
                'service_schedules.service_time',
                DB::raw('count(*) as total')
            )
            ->groupBy('service_schedules.service_time')
            ->orderByDesc('total')
            ->first();

        $insights = [];

        $insights[] = "Total booking mencapai {$totalBooking} transaksi";

        $insights[] = "Tingkat penyelesaian service {$successRate}%";

        $insights[] = "Keluhan customer sebanyak {$totalComplaint} laporan";

        if($busySchedule){
            $insights[] = "Jam tersibuk berada pada {$busySchedule->service_time}";
        }

        $recommendations = [];

        if($totalComplaint > 5){
            $recommendations[] =
            "Tingkatkan kualitas pelayanan untuk menekan jumlah keluhan";
        }

        if($successRate < 80){
            $recommendations[] =
            "Percepat proses penyelesaian booking yang masih pending";
        }

        if($busySchedule){
            $recommendations[] =
            "Tambah kuota service pada jam {$busySchedule->service_time}";
        }

        $recommendations[] =
        "Berikan promo kepada customer dengan booking terbanyak";

        return response()->json([
            'total_booking' => $totalBooking,
            'success_rate' => $successRate,
            'total_complaint' => $totalComplaint,
            'total_customer' => $totalCustomer,
            'top_services' => $topServices,
            'top_customers' => $topCustomers,
            'insights' => $insights,
            'recommendations' => $recommendations
        ]);
    }
}

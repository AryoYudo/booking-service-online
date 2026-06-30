<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class CustomerExperienceController extends Controller
{
    public function index()
    {
        return view('layouts.manager.CustomerExperience.index');
    }

    public function dashboardData()
    {
        $totalCustomer = DB::table('customers')->count();

        $totalBooking = DB::table('bookings')->count();

        $completedBooking = DB::table('bookings')
            ->where('status','completed')
            ->count();

        $cancelledBooking = DB::table('bookings')
            ->where('status','cancelled')
            ->count();

        $openComplaint = DB::table('complaints')
            ->where('status','open')
            ->count();

        $resolvedComplaint = DB::table('complaints')
            ->whereIn('status',['answered','closed'])
            ->count();

        $bookingChart = DB::table('bookings')
            ->selectRaw("
                EXTRACT(MONTH FROM booking_date) as month,
                COUNT(*) as total
            ")
            ->groupByRaw("EXTRACT(MONTH FROM booking_date)")
            ->orderByRaw("EXTRACT(MONTH FROM booking_date)")
            ->get();

        $complaintChart = DB::table('complaints')
            ->selectRaw("
                EXTRACT(MONTH FROM created_at) as month,
                COUNT(*) as total
            ")
            ->groupByRaw("EXTRACT(MONTH FROM created_at)")
            ->orderByRaw("EXTRACT(MONTH FROM created_at)")
            ->get();

        $topCustomers = DB::table('bookings as b')
            ->join('customers as c','b.customer_id','=','c.customer_id')
            ->selectRaw('c.customer_name, COUNT(*) as total_booking')
            ->groupBy('c.customer_name')
            ->orderByDesc('total_booking')
            ->limit(5)
            ->get();

        return response()->json([
            'total_customer' => $totalCustomer,
            'total_booking' => $totalBooking,
            'completed_booking' => $completedBooking,
            'cancelled_booking' => $cancelledBooking,
            'open_complaint' => $openComplaint,
            'resolved_complaint' => $resolvedComplaint,
            'booking_chart' => $bookingChart,
            'complaint_chart' => $complaintChart,
            'top_customers' => $topCustomers
        ]);
    }
}
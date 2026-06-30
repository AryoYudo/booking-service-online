<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function dashboardPage()
    {
        return view('layouts.customer.dashboard');
    }

    public function dashboard()
    {
        $customer_id = DB::table('customers')
            ->where('user_id', session('user_id'))
            ->value('customer_id');

        $activeBookings = DB::table('bookings')
            ->join(
                'service_schedules',
                'service_schedules.schedule_id',
                '=',
                'bookings.schedule_id'
            )
            ->where('bookings.customer_id', $customer_id)
            ->whereIn('bookings.status', ['pending','approved','completed'])
            ->select(
                'bookings.*',
                'service_schedules.service_date',
                'service_schedules.service_time'
            )
            ->orderByDesc('bookings.booking_date')
            ->limit(3)
            ->get();

        $vehicles = DB::table('vehicles')
            ->where('customer_id', $customer_id)
            ->get();

        $totalBooking = DB::table('bookings')
            ->where('customer_id', $customer_id)
            ->count();

        $completedBooking = DB::table('bookings')
            ->where('customer_id', $customer_id)
            ->where('status', 'completed')
            ->count();

        $activeBookingCount = DB::table('bookings')
            ->where('customer_id', $customer_id)
            ->whereIn('status', ['pending','approved'])
            ->count();

        return view(
            'layouts.customer.dashboard',
            compact(
                'activeBookings',
                'vehicles',
                'totalBooking',
                'completedBooking',
                'activeBookingCount'
            )
        );
    }
}

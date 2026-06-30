<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function customerPage()
    {
        return view('layouts.admin.customer.index');
    }

    public function customerList()
    {
        $customers = DB::table('customers as c')
            ->leftJoin('vehicles as v', 'c.customer_id', '=', 'v.customer_id')
            ->select(
                'c.customer_id',
                'c.customer_name',
                'c.email',
                'c.phone_number',
                DB::raw('COUNT(v.vehicle_id) as total_vehicle')
            )
            ->groupBy(
                'c.customer_id',
                'c.customer_name',
                'c.email',
                'c.phone_number'
            )
            ->orderByDesc('c.customer_id')
            ->get();

        return response()->json($customers);
    }
}

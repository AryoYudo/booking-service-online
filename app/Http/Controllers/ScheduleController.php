<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    public function index()
    {
        return view('layouts.admin.schedule.index');
    }

    public function list()
    {
        $schedules = DB::table('service_schedules')
            ->orderBy('service_date')
            ->orderBy('service_time')
            ->get();

        return response()->json($schedules);
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_date' => 'required',
            'service_time' => 'required',
            'quota' => 'required|numeric|min:1'
        ]);

        DB::table('service_schedules')->insert([
            'service_date' => $request->service_date,
            'service_time' => $request->service_time,
            'quota' => $request->quota,
            'status' => 'available',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Jadwal berhasil ditambahkan'
        ]);
    }

    public function delete($id)
    {
        DB::table('service_schedules')
            ->where('schedule_id', $id)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Jadwal berhasil dihapus'
        ]);
    }
}
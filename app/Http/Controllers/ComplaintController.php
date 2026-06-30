<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ComplaintController extends Controller
{
    public function index()
    {
        return view('layouts.customer.keluhanSaran.complaint');
    }

    public function adminPage()
    {
        return view('layouts.admin.complaint.index');
    }

    public function list()
    {
        $customer_id = DB::table('customers')
            ->where('user_id', session('user_id'))
            ->value('customer_id');

        $complaints = DB::table('complaints')
            ->where('customer_id', $customer_id)
            ->orderByDesc('complaint_id')
            ->get();

        return response()->json($complaints);
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|max:200',
            'complaint_text' => 'required'
        ]);

        $customer_id = DB::table('customers')
            ->where('user_id', session('user_id'))
            ->value('customer_id');

        DB::table('complaints')->insert([
            'customer_id' => $customer_id,
            'subject' => $request->subject,
            'complaint_text' => $request->complaint_text,
            'status' => 'open',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Keluhan berhasil dikirim'
        ]);
    }

    public function adminList()
    {
        $complaints = DB::table('complaints as c')
            ->join('customers as cs', 'c.customer_id', '=', 'cs.customer_id')
            ->select(
                'c.*',
                'cs.customer_name'
            )
            ->orderByDesc('c.complaint_id')
            ->get();

        return response()->json($complaints);
    }

    public function reply(Request $request, $id)
    {
        $request->validate([
            'admin_response' => 'required'
        ]);

        DB::table('complaints')
            ->where('complaint_id', $id)
            ->update([
                'admin_response' => $request->admin_response,
                'status' => 'answered',
                'updated_at' => now()
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Balasan berhasil dikirim'
        ]);
    }

    public function close($id)
    {
        DB::table('complaints')
            ->where('complaint_id', $id)
            ->update([
                'status' => 'closed',
                'updated_at' => now()
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Keluhan ditutup'
        ]);
    }

}
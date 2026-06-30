<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function loginPage()
    {
        return view('layouts.auth.auth');
    }

    public function registerPage()
    {
        return view('layouts.auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'username'         => 'required|unique:users,username',
            'password'         => 'required|min:6',

            'customer_name'    => 'required',
            'email'            => 'required|email',
            'phone_number'     => 'required',
            'address'          => 'required',

            'plate_number'     => 'required',
            'vehicle_type'     => 'required',
            'manufacture_year' => 'required|numeric',
        ]);

        DB::beginTransaction();

        try {

           $userId = DB::table('users')->insertGetId([
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'role' => 'customer',
                'created_at' => now(),
                'updated_at' => now(),
            ], 'user_id');

            $customerId = DB::table('customers')->insertGetId([
                'user_id' => $userId,
                'customer_name' => $request->customer_name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'created_at' => now(),
                'updated_at' => now(),
            ], 'customer_id');

            DB::table('vehicles')->insert([
                'customer_id' => $customerId,
                'plate_number' => $request->plate_number,
                'vehicle_type' => $request->vehicle_type,
                'manufacture_year' => $request->manufacture_year,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'Registrasi berhasil',
                'redirect' => url('/login')
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $user = DB::table('users')
            ->where('username',$request->username)
            ->first();

        if(!$user){

            return response()->json([
                'success' => false,
                'message' => 'Username tidak ditemukan'
            ],401);
        }

        if(!Hash::check($request->password,$user->password)){

            return response()->json([
                'success' => false,
                'message' => 'Password salah'
            ],401);
        }

        session([
            'user_id' => $user->user_id,
            'username' => $user->username,
            'role' => $user->role,
            'is_login' => true
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'redirect' => '/dashboard'
        ]);
    }
    public function logout(Request $request)
    {
        session()->flush();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil',
            'redirect' => '/login'
        ]);
    }
}
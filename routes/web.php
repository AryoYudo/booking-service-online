<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CustomerExperienceController;
use App\Http\Controllers\ManagerController;

Route::get('/login', [AuthController::class, 'loginPage']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::get('/register', [AuthController::class, 'registerPage']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('login')->group(function () {

    // user customer

    Route::get('/dashboard', [DashboardController::class, 'dashboardPage']);
    Route::get('/dashboard', [DashboardController::class, 'dashboard']);
    Route::get('/booking/create', [BookingController::class, 'createPage']);
    Route::post('/booking/store', [BookingController::class, 'store']);
    Route::get('/booking/history', [BookingController::class, 'historyPage']);
    Route::get('/booking/history/list', [BookingController::class, 'historyList']);
    Route::post('/booking/cancel/{id}', [BookingController::class, 'cancelBooking']);

    Route::get('/complaint', [ComplaintController::class, 'index']);
    Route::get('/complaint/list', [ComplaintController::class, 'list']);
    Route::post('/complaint/store', [ComplaintController::class, 'store']);


    // admin dan manager
    Route::get('/admin/complaints', [ComplaintController::class, 'adminPage']);
    Route::get('/admin/complaints/list', [ComplaintController::class, 'adminList']);
    Route::post('/admin/complaints/reply/{id}', [ComplaintController::class, 'reply']);
    Route::post('/admin/complaints/close/{id}', [ComplaintController::class, 'close']);
    
    Route::get('/customer', [CustomerController::class, 'customerPage']);
    Route::get('/customer/list', [CustomerController::class, 'customerList']);
    
    Route::get('/schedule',[ScheduleController::class,'index']);
    Route::get('/schedule/list',[ScheduleController::class,'list']);
    Route::post('/schedule/store',[ScheduleController::class,'store']);
    Route::delete('/schedule/delete/{id}',[ScheduleController::class,'delete']);

    Route::get('/admin/booking',[BookingController::class,'adminPage']);
    Route::get('/admin/booking/list',[BookingController::class,'adminList']);
    Route::post('/admin/booking/approve/{id}',[BookingController::class,'approve']);
    Route::post('/admin/booking/complete/{id}',[BookingController::class,'complete']);
    Route::post('/admin/booking/cancel/{id}',[BookingController::class,'cancel']);

    Route::get('/report', [ReportController::class,'index']);
    Route::get('/report/data', [ReportController::class,'getReport']);
    Route::get('/report/pdf', [ReportController::class,'generatePdf']);

    Route::get( '/manager/customer-experience', [CustomerExperienceController::class,'index'] );
    Route::get( '/manager/customer-experience/data', [CustomerExperienceController::class,'dashboardData'] );

    Route::get('/manager/recommendation', [ManagerController::class, 'recommendationPage']);
    Route::get('/manager/recommendation/data', [ManagerController::class, 'recommendationData']);
    // Route::post('/booking/store', [BookingController::class, 'store']);

});
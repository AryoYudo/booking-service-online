<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index()
    {
        return view('layouts.admin.report.index');
    }

    public function getReport(Request $request)
    {
        if($request->type == 'booking'){

            $data = DB::table('bookings as b')
                ->join('customers as c','b.customer_id','=','c.customer_id')
                ->join('vehicles as v','b.vehicle_id','=','v.vehicle_id')
                ->join('service_schedules as s','b.schedule_id','=','s.schedule_id')
                ->select(
                    'b.booking_id',
                    'c.customer_name',
                    'v.plate_number',
                    'b.type_service',
                    'b.status',
                    's.service_date'
                )
                ->whereBetween('s.service_date',[
                    $request->start_date,
                    $request->end_date
                ])
                ->get();

            return response()->json($data);
        }

        if($request->type == 'complaint'){

            $data = DB::table('complaints as c')
                ->join('customers as cs','c.customer_id','=','cs.customer_id')
                ->select(
                    'cs.customer_name',
                    'c.subject',
                    'c.status',
                    'c.created_at'
                )
                ->whereBetween(
                    DB::raw('DATE(c.created_at)'),
                    [$request->start_date,$request->end_date]
                )
                ->get();

            return response()->json($data);
        }

        return response()->json([]);
    }

    public function generatePdf(Request $request)
    {
        if($request->type == 'booking'){

            $data = DB::table('bookings as b')
                ->join('customers as c','b.customer_id','=','c.customer_id')
                ->join('vehicles as v','b.vehicle_id','=','v.vehicle_id')
                ->join('service_schedules as s','b.schedule_id','=','s.schedule_id')
                ->select(
                    'c.customer_name',
                    'v.plate_number',
                    'b.type_service',
                    'b.status',
                    's.service_date'
                )
                ->whereBetween('s.service_date',[
                    $request->start_date,
                    $request->end_date
                ])
                ->get();

            $pdf = Pdf::loadView(
                'layouts.admin.report.booking_pdf',
                compact('data')
            );

            return $pdf->download('laporan-booking.pdf');
        }

        if($request->type == 'complaint'){

            $data = DB::table('complaints as cp')
                ->join('customers as c','cp.customer_id','=','c.customer_id')
                ->select(
                    'c.customer_name',
                    'cp.subject',
                    'cp.complaint_text',
                    'cp.status',
                    'cp.created_at'
                )
                ->whereBetween(
                    DB::raw('DATE(cp.created_at)'),
                    [
                        $request->start_date,
                        $request->end_date
                    ]
                )
                ->get();

            $pdf = Pdf::loadView(
                'layouts.admin.report.complaint_pdf',
                compact('data')
            );

            return $pdf->download('laporan-keluhan.pdf');
        }
    }
}
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

<style>

body{
    font-family: DejaVu Sans, sans-serif;
    font-size:12px;
    color:#333;
}

.header{
    border-bottom:3px solid #dc3545;
    padding-bottom:15px;
    margin-bottom:25px;
}

.company{
    font-size:24px;
    font-weight:bold;
    color:#dc3545;
}

.subtitle{
    color:#666;
    font-size:12px;
}

.report-info{
    margin-top:10px;
    font-size:11px;
    color:#666;
}

.summary-box{
    background:#f8f9fa;
    border:1px solid #ddd;
    padding:12px;
    border-radius:8px;
    margin-bottom:20px;
}

.summary-title{
    font-weight:bold;
    margin-bottom:5px;
}

.complaint-card{
    border:1px solid #ddd;
    border-radius:10px;
    padding:15px;
    margin-bottom:15px;
}

.complaint-header{
    margin-bottom:10px;
}

.customer-name{
    font-size:14px;
    font-weight:bold;
}

.badge{
    display:inline-block;
    padding:4px 10px;
    border-radius:12px;
    font-size:10px;
    font-weight:bold;
}

.open{
    background:#fff3cd;
}

.answered{
    background:#d1e7dd;
}

.closed{
    background:#e2e3e5;
}

.label{
    font-weight:bold;
}

.response-box{
    margin-top:10px;
    padding:10px;
    background:#f8f9fa;
    border-left:4px solid #198754;
}

.footer{
    margin-top:50px;
    text-align:right;
}

</style>

</head>

<body>

<div class="header">

    <div class="company">
        HONDA SERVICE BOOKING
    </div>

    <div class="subtitle">
        Laporan Keluhan Customer
    </div>

    <div class="report-info">
        Dicetak : {{ now()->format('d M Y H:i') }}
    </div>

</div>

<div class="summary-box">

    <div class="summary-title">
        Ringkasan Laporan
    </div>

    Total Keluhan :
    <strong>{{ count($data) }}</strong>

</div>

@foreach($data as $row)

<div class="complaint-card">

    <div class="complaint-header">

        <div class="customer-name">
            {{ $row->customer_name }}
        </div>

        @php
            $statusClass = strtolower($row->status);
        @endphp

        <span class="badge {{ $statusClass }}">
            {{ strtoupper($row->status) }}
        </span>

    </div>

    <p>
        <span class="label">Subject :</span>
        {{ $row->subject }}
    </p>

    <p>
        <span class="label">Tanggal :</span>
        {{ date('d M Y', strtotime($row->created_at)) }}
    </p>

    <p>
        <span class="label">Keluhan :</span><br>
        {{ $row->complaint_text }}
    </p>

    @if(!empty($row->admin_response))

    <div class="response-box">

        <strong>Respon Admin</strong>

        <br><br>

        {{ $row->admin_response }}

    </div>

    @endif

</div>

@endforeach

<div class="footer">

    Manager Operasional

    <br><br><br><br>

    _______________________

</div>

</body>
</html>
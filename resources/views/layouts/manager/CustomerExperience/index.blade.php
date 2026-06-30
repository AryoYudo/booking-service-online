@extends('layouts.app')

@section('content')

<div class="container py-4">

    <div class="hero-cx mb-4">

        <div class="row align-items-center">

            <div class="col-lg-8">

                <span class="hero-badge">
                    <i class="bi bi-stars"></i>
                    Customer Experience
                </span>

                <h2 class="fw-bold mt-3 mb-2">
                    Customer Experience Dashboard
                </h2>

                <p class="mb-0 opacity-75">
                    Monitor kualitas layanan, loyalitas pelanggan, dan performa operasional bengkel Honda secara real-time.
                </p>

            </div>

            <div class="col-lg-4 text-end">

                <div>

                    <small>Experience Score</small>

                    <h1 id="experienceScore">
                        0%
                    </h1>

                    <span>
                        Excellent Service
                    </span>

                </div>

            </div>

        </div>

    </div>

    <div class="row g-4 mb-4">

        <div class="col-md-2">
            <div class="stats-card">
                <i class="bi bi-people text-primary"></i>
                <h3 id="totalCustomer">0</h3>
                <small>Total Customer</small>
            </div>
        </div>

        <div class="col-md-2">
            <div class="stats-card">
                <i class="bi bi-journal-check text-success"></i>
                <h3 id="totalBooking">0</h3>
                <small>Total Booking</small>
            </div>
        </div>

        <div class="col-md-2">
            <div class="stats-card">
                <i class="bi bi-check-circle text-success"></i>
                <h3 id="completedBooking">0</h3>
                <small>Completed</small>
            </div>
        </div>

        <div class="col-md-2">
            <div class="stats-card">
                <i class="bi bi-x-circle text-danger"></i>
                <h3 id="cancelledBooking">0</h3>
                <small>Cancelled</small>
            </div>
        </div>

        <div class="col-md-2">
            <div class="stats-card">
                <i class="bi bi-chat-left-text text-warning"></i>
                <h3 id="openComplaint">0</h3>
                <small>Open Complaint</small>
            </div>
        </div>

        <div class="col-md-2">
            <div class="stats-card">
                <i class="bi bi-patch-check text-info"></i>
                <h3 id="resolvedComplaint">0</h3>
                <small>Resolved</small>
            </div>
        </div>

    </div>

    

    <div class="row g-4 mt-1">

        <div class="col-lg-6">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <h5 class="fw-bold mb-3">
                        Top Customer
                    </h5>

                    <div id="topCustomerList"></div>

                </div>

            </div>

        </div>

        <div class="col-lg-6">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <h5 class="fw-bold mb-3">
                        Insight Manager
                    </h5>

                    <div id="insightBox"></div>

                </div>

            </div>

        </div>

    </div>
    
    <div class="row g-4">

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">
                        Trend Booking
                    </h5>

                    <canvas id="bookingChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">
                        Trend Keluhan
                    </h5>

                    <canvas id="complaintChart"></canvas>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection
@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

loadDashboard();

function loadDashboard(){

    $.ajax({

        url:'/manager/customer-experience/data',

        type:'GET',

        success:function(res){

            $('#totalCustomer').text(res.total_customer);
            $('#totalBooking').text(res.total_booking);
            $('#completedBooking').text(res.completed_booking);
            $('#cancelledBooking').text(res.cancelled_booking);
            $('#openComplaint').text(res.open_complaint);
            $('#resolvedComplaint').text(res.resolved_complaint);

            renderBookingChart(res.booking_chart);
            renderComplaintChart(res.complaint_chart);

            let customerHtml='';

            let rank = 1;

            res.top_customers.forEach(item=>{

                let medal = '🥉';

                if(rank == 1) medal = '🥇';
                if(rank == 2) medal = '🥈';

                customerHtml += `
                    <div class="customer-rank">

                        <div class="d-flex align-items-center gap-3">

                            <div class="rank-badge">
                                ${medal}
                            </div>

                            <div>

                                <div class="fw-bold">
                                    ${item.customer_name}
                                </div>

                                <small class="text-muted">
                                    Pelanggan Aktif
                                </small>

                            </div>

                        </div>

                        <span class="badge bg-success">
                            ${item.total_booking} Booking
                        </span>

                    </div>
                `;

                rank++;

            });

            let score = 0;
            if(res.total_booking > 0){

                score = Math.round(
                    (res.completed_booking / res.total_booking) * 100
                );

            }

            $('#experienceScore').text(score + '%');

            $('#topCustomerList').html(customerHtml);

            $('#insightBox').html(`

                <div class="ai-card">

                    <div class="ai-header">
                        <i class="bi bi-stars"></i>
                        AI Insight
                    </div>

                    <ul>

                        <li>
                            Total booking mencapai ${res.total_booking}.
                        </li>

                        <li>
                            Keluhan aktif tersisa ${res.open_complaint}.
                        </li>

                        <li>
                            ${res.completed_booking} booking telah selesai.
                        </li>

                        <li>
                            Total customer aktif ${res.total_customer}.
                        </li>

                    </ul>

                </div>

                `);

        }

    });

}

function renderBookingChart(data){

    new Chart(
        document.getElementById('bookingChart'),
        {
            type:'line',
            data:{
                labels:data.map(x=>'Bulan '+x.month),
                datasets:[{
                    data:data.map(x=>x.total),
                    fill:false
                }]
            }
        }
    );

}

function renderComplaintChart(data){

    new Chart(
        document.getElementById('complaintChart'),
        {
            type:'bar',
            data:{
                labels:data.map(x=>'Bulan '+x.month),
                datasets:[{
                    data:data.map(x=>x.total)
                }]
            }
        }
    );

}

</script>

@endpush
@push('styles')
<style>

.hero-cx{
    background:linear-gradient(135deg,#dc3545,#ff6b6b);
    color:#fff;
    border-radius:28px;
    padding:40px;
    overflow:hidden;
    position:relative;
    box-shadow:0 15px 40px rgba(220,53,69,.25);
}

.hero-cx::after{
    content:'';
    position:absolute;
    width:300px;
    height:300px;
    background:rgba(255,255,255,.08);
    border-radius:50%;
    top:-120px;
    right:-80px;
}

.hero-badge{
    background:rgba(255,255,255,.15);
    padding:8px 14px;
    border-radius:999px;
    font-size:.9rem;
}

.cx-score{
    background:rgba(255,255,255,.18);
    border:1px solid rgba(255,255,255,.25);
    backdrop-filter:blur(8px);
    padding:20px;
    border-radius:20px;
    text-align:center;
}

.cx-score h1{
    font-size:3rem;
    font-weight:700;
    margin:0;
}

.stats-card{
    background:#fff;
    border:2px solid #eee;
    border-radius:24px;
    padding:22px;
    transition:.3s;
    height:100%;
    cursor:pointer;
}

.stats-card:hover{
    transform:translateY(-5px);
    border-color:#dc3545;
    background:#fff5f5;
    box-shadow:0 15px 35px rgba(220,53,69,.15);
}

.stats-top{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:15px;
}

.stats-top i{
    font-size:1.8rem;
}

.stats-card h3{
    font-size:2rem;
    font-weight:700;
    margin:0;
    color:#212529;
}

.stats-card small{
    color:#6c757d;
}

.card{
    border-radius:24px !important;
    overflow:hidden;
    transition:.3s;
}

.card:hover{
    transform:translateY(-3px);
}

.card-body{
    padding:24px;
}

.card h5{
    font-weight:700;
}

.customer-rank{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:16px;
    border-radius:16px;
    transition:.3s;
    margin-bottom:10px;
}

.customer-rank:hover{
    background:#fff5f5;
}

.rank-badge{
    width:45px;
    height:45px;
    border-radius:50%;
    background:#ffe5e5;
    border:1px solid #ffd1d1;
    display:flex;
    justify-content:center;
    align-items:center;
    font-size:1.2rem;
    font-weight:700;
    color:#dc3545;
}

.customer-info{
    display:flex;
    align-items:center;
    gap:12px;
}

.customer-name{
    font-weight:600;
}

.customer-booking{
    font-size:.9rem;
    color:#6c757d;
}

.ai-card{
    background:linear-gradient(135deg,#fff,#fff5f5);
    border:2px solid #ffd8dd;
    border-radius:20px;
    padding:20px;
}

.ai-header{
    font-weight:700;
    color:#dc3545;
    margin-bottom:15px;
}

.ai-card ul{
    padding-left:18px;
    margin-bottom:0;
}

.ai-card li{
    margin-bottom:12px;
}

.insight-item{
    background:#fff5f5;
    border-left:4px solid #dc3545;
    padding:15px;
    border-radius:12px;
    margin-bottom:12px;
    transition:.3s;
}

.insight-item:hover{
    transform:translateX(4px);
}

canvas{
    max-height:320px !important;
}

</style>
@endpush
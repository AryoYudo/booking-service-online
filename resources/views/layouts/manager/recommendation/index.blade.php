@extends('layouts.app')

@section('content')

<div class="container py-4">

    <div class="hero-report mb-4">

        <div class="row align-items-center">

            <div class="col-md-8">

                <h2 class="fw-bold">
                    Rekomendasi & Insight Manager
                </h2>

                <p class="mb-0 opacity-75">
                    Executive dashboard untuk membantu pengambilan keputusan
                </p>

            </div>

            <div class="col-md-4 text-end">
                <i class="bi bi-lightbulb display-3"></i>
            </div>

        </div>

    </div>

    <div class="row g-4 mb-4">

        <div class="col-md-3">
            <div class="stats-card">
                <h3 id="totalBooking">0</h3>
                <small>Total Booking</small>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stats-card">
                <h3 id="successRate">0%</h3>
                <small>Success Rate</small>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stats-card">
                <h3 id="totalComplaint">0</h3>
                <small>Total Complaint</small>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stats-card">
                <h3 id="totalCustomer">0</h3>
                <small>Total Customer</small>
            </div>
        </div>

    </div>

    <div class="row g-4">

        <div class="col-lg-6">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <h5 class="fw-bold mb-3">
                        Insight Bisnis
                    </h5>

                    <div id="insightList"></div>

                </div>

            </div>

        </div>

        <div class="col-lg-6">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <h5 class="fw-bold mb-3">
                        Rekomendasi Sistem
                    </h5>

                    <div id="recommendationList"></div>

                </div>

            </div>

        </div>

    </div>

    <div class="row g-4 mt-1">

        <div class="col-lg-6">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <h5 class="fw-bold mb-3">
                        Top Service
                    </h5>

                    <div id="topServiceList"></div>

                </div>

            </div>

        </div>

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

    </div>

</div>

@endsection
@push('scripts')
<script>

loadData();

function loadData(){

    $.ajax({

        url:'/manager/recommendation/data',

        type:'GET',

        success:function(res){

            $('#totalBooking').text(res.total_booking);
            $('#successRate').text(res.success_rate + '%');
            $('#totalComplaint').text(res.total_complaint);
            $('#totalCustomer').text(res.total_customer);

            let insightHtml='';

            res.insights.forEach(item=>{

                insightHtml += `
                    <div class="insight-box">
                        <i class="bi bi-graph-up-arrow me-2"></i>
                        ${item}
                    </div>
                `;

            });

            $('#insightList').html(insightHtml);

            let recHtml='';

            res.recommendations.forEach(item=>{

                recHtml += `
                    <div class="recommend-box">
                        <i class="bi bi-lightbulb me-2"></i>
                        ${item}
                    </div>
                `;

            });

            $('#recommendationList').html(recHtml);

            let serviceHtml='';

            res.top_services.forEach(item=>{

                serviceHtml += `
                    <div class="progress-item">
                        <div class="d-flex justify-content-between">
                            <span>${item.type_service}</span>
                            <strong>${item.total}</strong>
                        </div>
                        <div class="progress mt-2">
                            <div class="progress-bar bg-danger"
                                 style="width:${item.total * 5}%"></div>
                        </div>
                    </div>
                `;

            });

            $('#topServiceList').html(serviceHtml);

            let customerHtml = '';

            res.top_customers.forEach((item,index)=>{

                let medal = '';

                if(index == 0){
                    medal = '🥇';
                }else if(index == 1){
                    medal = '🥈';
                }else if(index == 2){
                    medal = '🥉';
                }else{
                    medal = '#' + (index + 1);
                }

                customerHtml += `
                <div class="customer-rank">

                    <div class="d-flex align-items-center">

                        <div class="rank-badge">
                            ${medal}
                        </div>

                        <div class="ms-3">

                            <div class="customer-name">
                                ${item.customer_name}
                            </div>

                            <small class="text-muted">
                                Customer Loyal
                            </small>

                        </div>

                    </div>

                    <div class="text-end">

                        <div class="fw-bold text-danger">
                            ${item.total_booking}
                        </div>

                        <small class="text-muted">
                            Booking
                        </small>

                    </div>

                </div>
                `;

            });

            $('#topCustomerList').html(customerHtml);

        }

    });

}

</script>
@endpush
@push('styles')
<style>
    .hero-report{
    background:linear-gradient(135deg,#dc3545,#ff6b6b);
    color:white;
    border-radius:24px;
    padding:35px;
}

.stats-card{
    background:white;
    border:2px solid #eee;
    border-radius:20px;
    padding:25px;
    text-align:center;
    transition:.3s;
}

.stats-card:hover{
    transform:translateY(-5px);
    border-color:#dc3545;
    background:#fff5f5;
}

.insight-box,
.recommend-box{
    background:#fff5f5;
    border-left:4px solid #dc3545;
    padding:15px;
    border-radius:12px;
    margin-bottom:12px;
}

.progress-item{
    margin-bottom:20px;
}

.customer-item{
    display:flex;
    justify-content:space-between;
    padding:14px;
    border-bottom:1px solid #eee;
}
</style>
@endpush

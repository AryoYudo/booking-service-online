@extends('layouts.app')

@section('content')

<div class="container py-4">

    <div class="hero-customer mb-4">

        <div class="row align-items-center">

            <div class="col-md-8">

                <h2 class="fw-bold mb-2">
                    Kelola Customer
                </h2>

                <p class="mb-0">
                    Data seluruh customer Honda Service Booking
                </p>

            </div>

            <div class="col-md-4 text-end">

                <i class="bi bi-people-fill display-4"></i>

            </div>

        </div>

    </div>

    <div class="row g-3 mb-4">

        <div class="col-md-4">
            <div class="stats-card">
                <h3 id="totalCustomer">0</h3>
                <small>Total Customer</small>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stats-card">
                <h3 id="totalVehicle">0</h3>
                <small>Total Kendaraan</small>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stats-card">
                <h3 id="avgVehicle">0</h3>
                <small>Rata-rata Kendaraan</small>
            </div>
        </div>

    </div>

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <input
                type="text"
                id="searchCustomer"
                class="form-control"
                placeholder="Cari customer...">

        </div>

    </div>

    <div id="customerList"></div>

</div>

@endsection
@push('scripts')

<script>

let customers = [];

$(document).ready(function(){

    loadCustomers();

    $('#searchCustomer').on('keyup',function(){

        renderCustomer(
            $(this).val().toLowerCase()
        );

    });

});

function loadCustomers(){

    $.ajax({

        url:'/customer/list',
        type:'GET',

        success:function(response){

            customers = response;

            renderCustomer();

        }

    });

}

function renderCustomer(keyword=''){

    let html='';

    let totalVehicle=0;

    let filtered = customers.filter(item => {

        return item.customer_name.toLowerCase().includes(keyword)
            || (item.email ?? '').toLowerCase().includes(keyword);

    });

    filtered.forEach(item=>{

        totalVehicle += parseInt(item.total_vehicle);

        html += `
        <div class="customer-card mb-3">

            <div class="row align-items-center">

                <div class="col-md-8">

                    <div class="d-flex align-items-center">

                        <div class="avatar-circle">

                            ${item.customer_name.charAt(0)}

                        </div>

                        <div>

                            <h5 class="mb-1">
                                ${item.customer_name}
                            </h5>

                            <div class="text-muted">

                                ${item.email ?? '-'}

                            </div>

                            <small class="text-muted">

                                ${item.phone_number ?? '-'}

                            </small>

                        </div>

                    </div>

                </div>

                <div class="col-md-4 text-end">

                    <span class="badge bg-danger-subtle text-danger border px-3 py-2">

                        ${item.total_vehicle} Kendaraan

                    </span>

                </div>

            </div>

        </div>
        `;
    });

    $('#customerList').html(html);

    $('#totalCustomer').text(filtered.length);
    $('#totalVehicle').text(totalVehicle);

    let avg = filtered.length
        ? (totalVehicle / filtered.length).toFixed(1)
        : 0;

    $('#avgVehicle').text(avg);

}

</script>

@endpush
@push('styles')

<style>

.hero-customer{
    background:linear-gradient(135deg,#dc3545,#ff6b6b);
    color:white;
    padding:30px;
    border-radius:24px;
}

.stats-card{
    background:white;
    border:2px solid #eee;
    border-radius:20px;
    padding:20px;
    text-align:center;
    transition:.3s;
}

.stats-card:hover{
    border-color:#dc3545;
    background:#fff5f5;
}

.customer-card{
    background:white;
    border:2px solid #eee;
    border-radius:24px;
    padding:24px;
    transition:.3s;
}

.customer-card:hover{
    border-color:#dc3545;
    background:#fff5f5;
    transform:translateY(-4px);
}

.avatar-circle{
    width:60px;
    height:60px;
    border-radius:50%;
    background:#dc3545;
    color:white;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:24px;
    font-weight:bold;
    margin-right:15px;
}

</style>

@endpush
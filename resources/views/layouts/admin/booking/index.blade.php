@extends('layouts.app')

@section('content')

<div class="container py-4">

    <div class="hero-booking mb-4">

        <div class="row align-items-center">

            <div class="col-md-8">

                <h2 class="fw-bold mb-2">
                    Kelola Booking Service
                </h2>

                <p class="mb-0">
                    Approve, selesaikan atau batalkan booking customer.
                </p>

            </div>

            <div class="col-md-4 text-end">

                <i class="bi bi-journal-check display-4"></i>

            </div>

        </div>

    </div>

    <div class="row g-3 mb-4">

        <div class="col-md-3">
            <div class="stats-card">
                <h3 id="pendingCount">0</h3>
                <small>Pending</small>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stats-card">
                <h3 id="approvedCount">0</h3>
                <small>Approved</small>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stats-card">
                <h3 id="completedCount">0</h3>
                <small>Completed</small>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stats-card">
                <h3 id="cancelledCount">0</h3>
                <small>Cancelled</small>
            </div>
        </div>

    </div>

    <div class="row">

        <div class="col-lg-3">
            <h5 class="mb-3">Pending</h5>
            <div id="pendingList"></div>
        </div>

        <div class="col-lg-3">
            <h5 class="mb-3">Approved</h5>
            <div id="approvedList"></div>
        </div>

        <div class="col-lg-3">
            <h5 class="mb-3">Completed</h5>
            <div id="completedList"></div>
        </div>

        <div class="col-lg-3">
            <h5 class="mb-3">Cancelled</h5>
            <div id="cancelledList"></div>
        </div>

    </div>

</div>

@endsection
@push('scripts')

<script>

$(document).ready(function(){
    loadBookings();
});

function loadBookings(){

    $.get('/admin/booking/list',function(response){

        let pending='';
        let approved='';
        let completed='';
        let cancelled='';

        let p=0,a=0,c=0,x=0;

        response.forEach(item=>{

            let card = `
            <div class="booking-card mb-3">

                <h6>
                    #${item.booking_id}
                </h6>

                <small class="text-muted d-block">
                    ${item.customer_name}
                </small>

                <small class="text-muted d-block">
                    ${item.vehicle_type}
                </small>

                <small class="text-muted d-block mb-2">
                    ${item.plate_number}
                </small>

                <div class="border-top pt-2 mt-2">

                    <small>
                        ${item.service_date}
                    </small><br>

                    <small>
                        ${item.service_time}
                    </small>

                </div>

                ${actionButton(item)}

            </div>
            `;

            if(item.status=='pending'){
                pending += card;
                p++;
            }

            if(item.status=='approved'){
                approved += card;
                a++;
            }

            if(item.status=='completed'){
                completed += card;
                c++;
            }

            if(item.status=='cancelled'){
                cancelled += card;
                x++;
            }

        });

        $('#pendingList').html(pending);
        $('#approvedList').html(approved);
        $('#completedList').html(completed);
        $('#cancelledList').html(cancelled);

        $('#pendingCount').text(p);
        $('#approvedCount').text(a);
        $('#completedCount').text(c);
        $('#cancelledCount').text(x);

    });

}

function actionButton(item){

    if(item.status=='pending'){

        return `
        <div class="d-grid gap-2 mt-3">

            <button
                class="btn btn-success btnApprove"
                data-id="${item.booking_id}">
                Approve
            </button>

            <button
                class="btn btn-outline-danger btnCancel"
                data-id="${item.booking_id}">
                Cancel
            </button>

        </div>
        `;
    }

    if(item.status=='approved'){

        return `
        <button
            class="btn btn-primary w-100 mt-3 btnComplete"
            data-id="${item.booking_id}">
            Complete
        </button>
        `;
    }

    return '';
}

$(document).on('click','.btnApprove',function(){

    let id=$(this).data('id');

    $.post('/admin/booking/approve/'+id,{
        _token:$('meta[name="csrf-token"]').attr('content')
    },function(){

        loadBookings();

    });

});

$(document).on('click','.btnComplete',function(){

    let id=$(this).data('id');

    $.post('/admin/booking/complete/'+id,{
        _token:$('meta[name="csrf-token"]').attr('content')
    },function(){

        loadBookings();

    });

});

$(document).on('click','.btnCancel',function(){

    let id=$(this).data('id');

    $.post('/admin/booking/cancel/'+id,{
        _token:$('meta[name="csrf-token"]').attr('content')
    },function(){

        loadBookings();

    });

});

</script>

@endpush
@push('styles')

<style>

.hero-booking{
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
}

.booking-card{
    background:white;
    border:2px solid #eee;
    border-radius:20px;
    padding:16px;
    transition:.3s;
}

.booking-card:hover{
    border-color:#dc3545;
    background:#fff5f5;
    transform:translateY(-3px);
}

</style>

@endpush
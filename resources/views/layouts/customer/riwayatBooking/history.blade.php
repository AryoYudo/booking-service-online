@extends('layouts.app')

@section('content')

<div class="container py-5">

    <div class="mb-4">

        <h2 class="fw-bold">
            Riwayat Booking
        </h2>

        <p class="text-muted">
            Daftar booking service kendaraan Anda
        </p>

    </div>

    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <div id="bookingList"></div>

        </div>

    </div>

</div>

@endsection

@push('scripts')

<script>

loadBookings();

function loadBookings(){

    $.ajax({
        url:'/booking/history/list',
        type:'GET',

        success:function(response){

            let html = '';

            if(response.length === 0){

                html = `
                    <div class="text-center py-5">
                        <i class="bi bi-inbox display-4 text-muted"></i>
                        <h5 class="mt-3">
                            Belum ada booking
                        </h5>
                    </div>
                `;

                $('#bookingList').html(html);
                return;
            }

            response.forEach(item => {

                let badge = '';

                if(item.status == 'pending'){
                    badge = '<span class="badge bg-warning">Pending</span>';
                }

                if(item.status == 'approved'){
                    badge = '<span class="badge bg-primary">Approved</span>';
                }

                if(item.status == 'completed'){
                    badge = '<span class="badge bg-success">Completed</span>';
                }

                if(item.status == 'cancelled'){
                    badge = '<span class="badge bg-danger">Cancelled</span>';
                }

                html += `
                <div class="history-card mb-3">

                    <div class="history-header">

                        <div>

                            <div class="history-title">
                                ${item.vehicle_type}
                            </div>

                            <div class="text-muted">
                                ${item.plate_number}
                            </div>

                        </div>

                        ${badge}

                    </div>

                    <div class="history-divider"></div>

                    <div class="row g-3">

                        <div class="col-md-4">

                            <div class="info-box">

                                <i class="bi bi-tools text-primary"></i>

                                <div>
                                    <small>Jenis Service</small>
                                    <div class="fw-semibold">
                                        ${item.type_service}
                                    </div>
                                </div>

                            </div>

                        </div>

                        <div class="col-md-4">

                            <div class="info-box">

                                <i class="bi bi-calendar-check text-danger"></i>

                                <div>
                                    <small>Jadwal</small>
                                    <div class="fw-semibold">
                                        ${item.service_date}
                                    </div>
                                    <small class="text-muted">
                                        ${item.service_time}
                                    </small>
                                </div>

                            </div>

                        </div>

                        <div class="col-md-4">

                            <div class="info-box">

                                <i class="bi bi-chat-left-text text-success"></i>

                                <div>
                                    <small>Keluhan</small>
                                    <div class="fw-semibold">
                                        ${item.complaint ?? '-'}
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>

                    ${item.status == 'pending'
                        ?
                        `
                        <div class="mt-4 text-end">

                            <button
                                class="btn btn-outline-danger btnCancel"
                                data-id="${item.booking_id}">

                                <i class="bi bi-x-circle me-1"></i>
                                Batalkan Booking

                            </button>

                        </div>
                        `
                        :
                        ''
                    }

                </div>
                `;
            });

            $('#bookingList').html(html);

        }

    });

}

$(document).on('click','.btnCancel',function(){

    let id = $(this).data('id');

    Swal.fire({
        title:'Batalkan booking?',
        icon:'warning',
        showCancelButton:true,
        confirmButtonColor:'#dc3545'
    })
    .then((result)=>{

        if(!result.isConfirmed){
            return;
        }

        $.ajax({

            url:'/booking/cancel/' + id,
            type:'POST',

            headers:{
                'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
            },

            success:function(response){

                Swal.fire({
                    icon:'success',
                    title:'Berhasil',
                    text:response.message
                });

                loadBookings();

            }

        });

    });

});

</script>

@endpush

@push('styles')

<style>

.history-card{
    border:2px solid #eee;
    border-radius:20px;
    padding:24px;
    transition:.3s;
    background:#fff;
}

.history-card:hover{
    transform:translateY(-5px);
    border-color:#dc3545;
}

.history-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.history-title{
    font-size:1.1rem;
    font-weight:700;
}

.history-divider{
    height:1px;
    background:#eee;
    margin:20px 0;
}

.info-box{
    display:flex;
    align-items:flex-start;
    gap:12px;
}

.info-box i{
    font-size:1.4rem;
}
</style>

@endpush
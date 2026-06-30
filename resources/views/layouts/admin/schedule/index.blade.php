@extends('layouts.app')

@section('content')

<div class="container py-4">

    <div class="hero-schedule mb-4">

        <div class="row align-items-center">

            <div class="col-md-8">

                <h2 class="fw-bold mb-2">
                    Kelola Jadwal Service
                </h2>

                <p class="mb-0">
                    Atur slot service dan kuota booking customer
                </p>

            </div>

            <div class="col-md-4 text-end">

                <i class="bi bi-calendar-week display-4"></i>

            </div>

        </div>

    </div>

    <div class="row g-3 mb-4">

        <div class="col-md-4">
            <div class="stats-card">
                <h3 id="totalSchedule">0</h3>
                <small>Total Jadwal</small>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stats-card">
                <h3 id="availableSchedule">0</h3>
                <small>Available</small>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stats-card">
                <h3 id="closedSchedule">0</h3>
                <small>Closed</small>
            </div>
        </div>

    </div>

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form id="scheduleForm">

                @csrf

                <div class="row g-3">

                    <div class="col-md-4">
                        <input type="date" class="form-control" name="service_date" required>
                    </div>

                    <div class="col-md-3">
                        <input type="time" class="form-control" name="service_time" required>
                    </div>

                    <div class="col-md-2">
                        <input type="number" class="form-control" name="quota" placeholder="Quota" required>
                    </div>

                    <div class="col-md-3">
                        <button class="btn btn-danger w-100">
                            Tambah Jadwal
                        </button>
                    </div>

                </div>

            </form>

        </div>

    </div>

    <div class="row" id="scheduleList"></div>

</div>

@endsection
@push('scripts')

<script>

$(document).ready(function(){

    loadSchedules();

});

$('#scheduleForm').submit(function(e){

    e.preventDefault();

    $.ajax({

        url:'/schedule/store',
        type:'POST',
        data:$(this).serialize(),

        headers:{
            'X-CSRF-TOKEN':
            $('meta[name="csrf-token"]').attr('content')
        },

        success:function(response){

            Swal.fire({
                icon:'success',
                title:'Berhasil',
                text:response.message,
                confirmButtonColor:'#dc3545'
            });

            $('#scheduleForm')[0].reset();

            loadSchedules();
        }

    });

});

function loadSchedules(){
    $.ajax({

        url:'/schedule/list',
        type:'GET',

        success:function(response){

            let html='';

            let available=0;
            let closed=0;

            response.forEach(item=>{

                if(item.status=='available'){
                    available++;
                }

                if(item.status=='closed'){
                    closed++;
                }

                let badge='';

                if(item.status=='available'){

                    badge=`
                    <span class="badge bg-success-subtle text-success border">
                        Available
                    </span>`;
                }
                else{

                    badge=`
                    <span class="badge bg-danger-subtle text-danger border">
                        Closed
                    </span>`;
                }

                html += `
                <div class="col-md-4 mb-4">

                    <div class="schedule-card">

                        <div class="d-flex justify-content-between">

                            <h5>
                                ${item.service_date}
                            </h5>

                            ${badge}

                        </div>

                        <hr>

                        <p>
                            <i class="bi bi-clock me-2"></i>
                            ${item.service_time}
                        </p>

                        <p>
                            <i class="bi bi-people me-2"></i>
                            Kuota : ${item.quota}
                        </p>

                        <button
                            class="btn btn-outline-danger btnDelete w-100"
                            data-id="${item.schedule_id}">

                            Hapus

                        </button>

                    </div>

                </div>
                `;
            });

            $('#scheduleList').html(html);

            $('#totalSchedule').text(response.length);
            $('#availableSchedule').text(available);
            $('#closedSchedule').text(closed);

        }

    });

}

$(document).on('click','.btnDelete',function(){

    let id=$(this).data('id');

    Swal.fire({

        title:'Hapus jadwal?',
        icon:'warning',
        showCancelButton:true,
        confirmButtonColor:'#dc3545'

    }).then((result)=>{

        if(result.isConfirmed){

            $.ajax({

                url:'/schedule/delete/'+id,
                type:'DELETE',

                headers:{
                    'X-CSRF-TOKEN':
                    $('meta[name="csrf-token"]').attr('content')
                },

                success:function(response){

                    Swal.fire({
                        icon:'success',
                        title:'Berhasil',
                        text:response.message,
                        confirmButtonColor:'#dc3545'
                    });

                    loadSchedules();
                }

            });

        }

    });

});

</script>
@endpush
@push('styles')

<style>

.hero-schedule{
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

.schedule-card{
    border:2px solid #eee;
    border-radius:24px;
    padding:24px;
    background:white;
    transition:.3s;
}

.schedule-card:hover{
    border-color:#dc3545;
    background:#fff5f5;
    transform:translateY(-4px);
}

</style>

@endpush
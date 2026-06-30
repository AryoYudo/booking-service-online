@extends('layouts.app')

@section('content')

<div class="container py-4">

    <div class="hero-report mb-4">
        <div class="row align-items-center">

            <div class="col-md-8">
                <h2 class="fw-bold mb-2">
                    Laporan & Analitik
                </h2>

                <p class="mb-0 opacity-75">
                    Pantau performa operasional bengkel Honda secara real-time
                </p>
            </div>

            <div class="col-md-4 text-end">
                <i class="bi bi-file-earmark-bar-graph display-3"></i>
            </div>

        </div>
    </div>
    <div class="row g-3 mb-4">

        <div class="col-md-3">
            <div class="stats-card">
                <i class="bi bi-journal-check text-success"></i>
                <h3 id="totalBooking">0</h3>
                <small>Total Booking</small>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stats-card">
                <i class="bi bi-check-circle text-primary"></i>
                <h3 id="totalCompleted">0</h3>
                <small>Completed</small>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stats-card">
                <i class="bi bi-clock-history text-warning"></i>
                <h3 id="totalPending">0</h3>
                <small>Pending</small>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stats-card">
                <i class="bi bi-x-circle text-danger"></i>
                <h3 id="totalCancelled">0</h3>
                <small>Cancelled</small>
            </div>
        </div>

    </div>

    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-3">
                    <input type="date"
                        id="startDate"
                        class="form-control">
                </div>

                <div class="col-md-3">
                    <input type="date"
                        id="endDate"
                        class="form-control">
                </div>

                <div class="col-md-3">
                    <select
                        id="reportType"
                        class="form-select">

                        <option value="booking">
                            Booking Service
                        </option>

                        <option value="complaint">
                            Keluhan Customer
                        </option>

                    </select>
                </div>

                <div class="col-md-3">

                    <button
                        class="btn btn-danger w-100"
                        id="btnLoad">

                        Tampilkan

                    </button>

                </div>
                <button
                    class="btn btn-dark w-100"
                    id="btnPdf">

                    <i class="bi bi-file-earmark-pdf me-2"></i>
                    Download PDF

                </button>

            </div>

        </div>

    </div>

    <div class="card border-0 shadow-sm mt-4">

        <div class="card-body">

            <div id="reportResult">

                <div class="empty-state">

                    <i class="bi bi-file-earmark-bar-graph"></i>

                    <h5>
                        Belum Ada Data
                    </h5>

                    <p>
                        Pilih periode dan jenis laporan terlebih dahulu
                    </p>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection

@push('scripts')

<script>

$('#btnLoad').click(function(){

    $.ajax({

        url:'/report/data',

        type:'GET',

        data:{
            start_date:$('#startDate').val(),
            end_date:$('#endDate').val(),
            type:$('#reportType').val()
        },

        success:function(response){
            let totalBooking = response.length;
            let totalPending = 0;
            let totalCompleted = 0;
            let totalCancelled = 0;

            response.forEach(row => {

                if(row.status == 'pending'){
                    totalPending++;
                }

                if(row.status == 'completed'){
                    totalCompleted++;
                }

                if(row.status == 'cancelled'){
                    totalCancelled++;
                }

            });

            let html='';

            if(response.length == 0){

                html=`
                <div class="alert alert-warning">
                    Data tidak ditemukan
                </div>
                `;

                $('#reportResult').html(html);

                return;
            }

            html += `
            <table class="table table-hover">

                <thead>
                    <tr>
            `;

            Object.keys(response[0]).forEach(col=>{

                html += `<th>${col}</th>`;

            });

            html += `
                    </tr>
                </thead>
                <tbody>
            `;

            response.forEach(row=>{

                html += '<tr>';

                Object.values(row).forEach(val=>{

                    html += `<td>${val ?? '-'}</td>`;

                });

                html += '</tr>';

            });

            html += `
                </tbody>
            </table>
            `;

            $('#reportResult').html(html);
            $('#totalBooking').text(totalBooking);
            $('#totalPending').text(totalPending);
            $('#totalCompleted').text(totalCompleted);
            $('#totalCancelled').text(totalCancelled);

        }

    });

});
$('#btnPdf').click(function(){

    let startDate = $('#startDate').val();
    let endDate = $('#endDate').val();
    let type = $('#reportType').val();

    if(!startDate || !endDate){

        Swal.fire({
            icon:'warning',
            title:'Periode belum dipilih',
            text:'Silakan pilih tanggal terlebih dahulu',
            confirmButtonColor:'#dc3545'
        });

        return;
    }

    window.open(
        `/report/pdf?start_date=${startDate}&end_date=${endDate}&type=${type}`,
        '_blank'
    );

});

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
    padding:24px;
    text-align:center;
    transition:.3s;
}

.stats-card:hover{
    border-color:#dc3545;
    background:#fff5f5;
    transform:translateY(-4px);
}

.stats-card i{
    font-size:2rem;
    margin-bottom:10px;
}

.stats-card h3{
    font-weight:700;
    margin-bottom:5px;
}

.report-filter-card{
    border-radius:20px;
}

.empty-state{
    text-align:center;
    padding:80px 20px;
}

.empty-state i{
    font-size:4rem;
    color:#dc3545;
}

.empty-state h5{
    margin-top:20px;
    font-weight:700;
}

.table{
    margin-bottom:0;
}

.table thead{
    background:#dc3545;
    color:white;
}

.table th{
    border:none;
}
</style>

@endpush
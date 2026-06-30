@extends('layouts.app')

@section('content')

<div class="container py-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold">
            Booking Service Honda
        </h2>
        <p class="text-muted">
            Booking service tanpa antre
        </p>
    </div>

    <!-- PROGRESS -->

    <div class="row justify-content-center mb-5">
        <div class="col-lg-8">
            <div class="progress" style="height:8px">
                <div
                    id="progressBar"
                    class="progress-bar bg-danger"
                    style="width:25%">
                </div>
            </div>
        </div>
    </div>
    <form id="bookingForm" >
        @csrf

        <!-- STEP 1 -->

        <div class="wizard-step active">
            <div class="card shadow border-0">
                <div class="card-body p-5">
                    <h4 class="mb-4">
                        Pilih Kendaraan
                    </h4>
                        <div class="d-flex flex-wrap gap-4">
                            @foreach($vehicles as $vehicle)
                                <div style="width:220px;">
                                    <label class="vehicle-card w-100">
                                        <input
                                            type="radio"
                                            name="vehicle_id"
                                            value="{{ $vehicle->vehicle_id }}"
                                            hidden>
                                        <div class="vehicle-box">
                                            @php
                                                $type = strtolower(trim($vehicle->type));
                                            @endphp

                                            @if($type == 'motorcycle')
                                                <i class="bi bi-scooter fs-1 text-danger"></i>
                                            @elseif($type == 'car')
                                                <i class="bi bi-car-front-fill fs-1 text-danger"></i>
                                            @else
                                                <i class="bi bi-truck fs-1 text-secondary"></i>
                                            @endif

                                            <h5 class="mt-3">
                                                {{ $vehicle->vehicle_type }}
                                            </h5>

                                            <p class="text-muted mb-1">
                                                {{ $vehicle->plate_number }}
                                            </p>
                                            
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                    </div>
                    <div class="text-end mt-4">
                        <button
                            type="button"
                            class="btn btn-danger nextBtn">
                            Selanjutnya →
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- STEP 2 -->

        <div class="wizard-step">
            <div class="card shadow border-0">
                <div class="card-body p-5">
                    <h4 class="mb-4">
                        Pilih Jenis Service
                    </h4>
                    <div class="d-flex flex-wrap gap-4">
                        @php
                        $services = [
                            ['name' => 'Ganti Oli', 'icon' => 'bi-droplet-fill', 'color' => 'text-warning', 'desc' => 'Penggantian oli mesin'],
                            ['name' => 'Service Ringan', 'icon' => 'bi-tools', 'color' => 'text-primary', 'desc' => 'Pemeriksaan rutin kendaraan'],
                            ['name' => 'Service Lengkap', 'icon' => 'bi-gear-wide-connected', 'color' => 'text-danger', 'desc' => 'Perawatan menyeluruh kendaraan'],
                            ['name' => 'Tune Up', 'icon' => 'bi-speedometer2', 'color' => 'text-success', 'desc' => 'Optimasi performa mesin']
                        ];
                        @endphp

                        @foreach($services as $service)

                        <div style="width:220px;">

                            <label class="service-card w-100">

                                <input
                                    type="radio"
                                    name="service_type"
                                    value="{{ $service['name'] }}"
                                    hidden>

                                <div class="service-box">
                                    <i class="bi {{ $service['icon'] }} fs-1 {{ $service['color'] }}"></i>
                                    <h5 class="mt-3 mb-1">
                                        {{ $service['name'] }}
                                    </h5>
                                    <p class="text-muted small mb-0">
                                        {{ $service['desc'] }}
                                    </p>
                                </div>
                            </label>
                        </div>
                        @endforeach

                    </div>
                    <div class="d-flex justify-content-between mt-4">

                        <button
                            type="button"
                            class="btn btn-secondary prevBtn">
                            ← Kembali
                        </button>
                        <button
                            type="button"
                            class="btn btn-danger nextBtn">
                            Selanjutnya →
                        </button>

                    </div>
                </div>
            </div>
        </div>

        <!-- STEP 3 -->

        <div class="wizard-step">

            <div class="card shadow border-0">

                <div class="card-body p-5">

                    <h4 class="mb-4">
                        Pilih Jadwal
                    </h4>

                        <div class="d-flex flex-wrap gap-4">

                            @foreach($schedules as $schedule)

                            <div style="width:220px;">

                                <label class="schedule-card w-100">

                                    <input
                                        type="radio"
                                        name="schedule_id"
                                        value="{{ $schedule->schedule_id }}"
                                        hidden>

                                    <div class="schedule-box">

                                        <i class="bi bi-calendar-check fs-1 text-danger"></i>

                                        <h5 class="mt-3 mb-1">
                                            {{ date('d M Y', strtotime($schedule->service_date)) }}
                                        </h5>

                                        <p class="text-muted mb-2">
                                            {{ $schedule->service_time }}
                                        </p>

                                        <span class="badge bg-light text-dark border">
                                            Tersedia
                                        </span>

                                    </div>

                                </label>

                            </div>

                            @endforeach

                        </div>

                        @if($schedules->hasPages())
                        <nav class="mt-4">
                            <ul class="pagination justify-content-center">
                                <li class="page-item {{ $schedules->onFirstPage() ? 'disabled' : '' }}">
                                    <a
                                        class="page-link rounded-pill px-3"
                                        href="{{ $schedules->previousPageUrl() }}">
                                        ← Sebelumnya
                                    </a>
                                </li>
                                @foreach($schedules->getUrlRange(1, $schedules->lastPage()) as $page => $url)
                                    <li class="page-item {{ $page == $schedules->currentPage() ? 'active' : '' }}">
                                        <a
                                            class="page-link rounded-circle mx-1"
                                            href="{{ $url }}">
                                            {{ $page }}
                                        </a>
                                    </li>
                                @endforeach

                                <li class="page-item {{ !$schedules->hasMorePages() ? 'disabled' : '' }}">
                                    <a
                                        class="page-link rounded-pill px-3"
                                        href="{{ $schedules->nextPageUrl() }}">
                                        Selanjutnya →
                                    </a>
                                </li>

                            </ul>
                        </nav>
                        @endif
                    <div class="d-flex justify-content-between mt-4">

                        <button
                            type="button"
                            class="btn btn-secondary prevBtn">
                            ← Kembali
                        </button>

                        <button
                            type="button"
                            class="btn btn-danger nextBtn">
                            Selanjutnya →
                        </button>

                    </div>

                </div>

            </div>

        </div>

        <!-- STEP 4 -->

        <div class="wizard-step">
            <div class="card shadow border-0">
                <div class="card-body p-5">
                    <div class="text-center mb-5">
                        <i class="bi bi-check2-circle text-success display-4"></i>
                        <h3 class="fw-bold mt-3">
                            Konfirmasi Booking
                        </h3>
                        <p class="text-muted mb-0">
                            Periksa kembali detail booking sebelum dikirim
                        </p>
                    </div>
                    <div class="card border-0 bg-light mb-4">
                        <div class="card-body">
                            <h5 class="fw-bold mb-3">
                                Ringkasan Booking
                            </h5>
                            <div id="bookingSummary"></div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            Keluhan Awal
                        </label>
                        <textarea
                            class="form-control"
                            name="complaint"
                            rows="4"
                            placeholder="Contoh: Mesin terasa bergetar saat kecepatan tinggi, rem depan berbunyi, dll."></textarea>

                        <small class="text-muted">
                            Jelaskan keluhan kendaraan agar teknisi dapat mempersiapkan pemeriksaan lebih baik.
                        </small>

                    </div>
                    <div class="alert alert-warning border-0">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        Pastikan tanggal, jadwal, dan kendaraan yang dipilih sudah sesuai.
                    </div>
                    <div class="d-flex justify-content-between mt-4">
                        <button
                            type="button"
                            class="btn btn-outline-secondary prevBtn">
                            <i class="bi bi-arrow-left"></i>
                            Kembali
                        </button>
                        <button
                            type="submit"
                            id="btnBooking"
                            class="btn btn-lg px-4 text-white"
                            style="background:#22c55e;border:none;"
                        >

                            <i class="bi bi-check-circle me-2"></i>
                            Konfirmasi Booking
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection

@push('styles')

<style>

.wizard-step{
    display:none;
}

.wizard-step.active{
    display:block;
}

.vehicle-box,
.service-box,
.schedule-box{

    border:2px solid #eee;
    border-radius:20px;
    padding:30px;
    text-align:center;
    cursor:pointer;
    transition:.3s;
}

.vehicle-box:hover,
.service-box:hover,
.schedule-box:hover{

    transform:translateY(-5px);
    border-color:#dc3545;
}

input[type=radio]:checked + .vehicle-box,
input[type=radio]:checked + .service-box,
input[type=radio]:checked + .schedule-box{

    border-color:#dc3545;
    background:#fff5f5;
}
.vehicle-box{
    border:2px solid #eee;
    border-radius:20px;
    padding:30px;
    text-align:center;
    cursor:pointer;
    transition:.3s;
    min-height:220px;

    display:flex;
    flex-direction:column;
    justify-content:center;
    align-items:center;
}
.service-box{
    border:2px solid #eee;
    border-radius:20px;
    padding:30px;
    text-align:center;
    cursor:pointer;
    transition:.3s;
    min-height:220px;
    display:flex;
    flex-direction:column;
    justify-content:center;
    align-items:center;
}

.service-box:hover{
    transform:translateY(-5px);
    border-color:#dc3545;
}

.schedule-box{
    border:2px solid #eee;
    border-radius:20px;
    padding:30px;
    text-align:center;
    cursor:pointer;
    transition:.3s;
    min-height:220px;
    display:flex;
    flex-direction:column;
    justify-content:center;
    align-items:center;
}

.schedule-box:hover{
    transform:translateY(-5px);
    border-color:#dc3545;
}

.pagination .page-link{
    border:none;
    color:#dc3545;
    font-weight:600;
    min-width:42px;
    height:42px;
    display:flex;
    align-items:center;
    justify-content:center;
    box-shadow:0 2px 10px rgba(0,0,0,.05);
}

.pagination .page-item.active .page-link{
    background:#dc3545;
    color:#fff;
}

.pagination .page-link:hover{
    background:#fff5f5;
    color:#dc3545;
}

.pagination .page-item.disabled .page-link{
    opacity:.5;
}

.summary-item{
    background:#fff;
    border:1px solid #ececec;
    border-radius:18px;
    padding:18px;
    display:flex;
    align-items:center;
    gap:15px;
    height:100%;
    transition:.3s;
}

.summary-item:hover{
    transform:translateY(-2px);
    box-shadow:0 8px 20px rgba(0,0,0,.05);
}

.summary-icon{
    width:55px;
    height:55px;
    border-radius:14px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:1.3rem;
}

input[type=radio]:checked + .schedule-box{
    border-color:#dc3545;
    background:#fff5f5;
    box-shadow:0 10px 25px rgba(220,53,69,.15);
}

input[type=radio]:checked + .service-box{
    border-color:#dc3545;
    background:#fff5f5;
    box-shadow:0 10px 25px rgba(220,53,69,.15);
}

</style>

@endpush

@push('scripts')
<script>
$(document).ready(function(){

    let currentStep = 0;

    const steps = $('.wizard-step');

    function updateStep(){

        steps.removeClass('active');

        $(steps[currentStep]).addClass('active');

        let width =
        ((currentStep + 1)
        / steps.length) * 100;

        $('#progressBar')
            .css('width', width + '%');

        generateSummary();
    }

    $('.nextBtn').click(function(){

        currentStep++;

        updateStep();

    });

    $('.prevBtn').click(function(){

        currentStep--;

        updateStep();

    });

    $('#bookingForm').submit(function(e){

        e.preventDefault();

        Swal.fire({
            title: 'Konfirmasi Booking?',
            text: 'Pastikan data booking sudah benar.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Booking',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#22c55e'
        }).then((result) => {

            if(!result.isConfirmed){
                return;
            }

            $.ajax({
                url: '/booking/store',
                type: 'POST',
                data: $(this).serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },

                beforeSend: function(){

                    $('#btnBooking')
                        .prop('disabled', true)
                        .html(`
                            <span class="spinner-border spinner-border-sm me-2"></span>
                            Memproses...
                        `);

                },

                success: function(response){

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message,
                        confirmButtonColor: '#22c55e'
                    }).then(() => {

                        window.location.href = response.redirect;

                    });

                },

                error: function(xhr){

                    let message = 'Terjadi kesalahan';

                    if(xhr.responseJSON?.message){
                        message = xhr.responseJSON.message;
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: message
                    });

                    $('#btnBooking')
                        .prop('disabled', false)
                        .html(`
                            <i class="bi bi-check-circle me-2"></i>
                            Konfirmasi Booking
                        `);

                }

            });

        });

    });
});

function generateSummary(){

    let vehicle = $('input[name=vehicle_id]:checked')
        .closest('label')
        .find('h5')
        .text()
        .trim();

    let service = $('input[name=service_type]:checked')
        .val();

    let scheduleDate = $('input[name=schedule_id]:checked')
        .closest('label')
        .find('h5')
        .text()
        .trim();

    let scheduleTime = $('input[name=schedule_id]:checked')
        .closest('label')
        .find('p')
        .text()
        .trim();

    $('#bookingSummary').html(`
        <div class="row g-3">

            <div class="col-md-4">
                <div class="summary-item">
                    <div class="summary-icon bg-danger-subtle text-danger">
                        <i class="bi bi-car-front"></i>
                    </div>

                    <div>
                        <small>Kendaraan</small>
                        <div class="fw-semibold">${vehicle || '-'}</div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="summary-item">
                    <div class="summary-icon bg-primary-subtle text-primary">
                        <i class="bi bi-tools"></i>
                    </div>

                    <div>
                        <small>Jenis Service</small>
                        <div class="fw-semibold">${service || '-'}</div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="summary-item">
                    <div class="summary-icon bg-warning-subtle text-warning">
                        <i class="bi bi-calendar-check"></i>
                    </div>

                    <div>
                        <small>Jadwal</small>
                        <div class="fw-semibold">${scheduleDate || '-'}</div>
                        <small class="text-muted">${scheduleTime || ''}</small>
                    </div>
                </div>
            </div>

        </div>
    `);

}

</script>

@endpush
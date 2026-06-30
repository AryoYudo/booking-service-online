<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Honda Service Booking</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css" rel="stylesheet">

<style>

:root{
--primary:#E40521;
--primary-soft:#fff0f2;
--bg:#f6f8fb;
--card:#ffffff;
--text:#1f2937;
}.vehicle-card{
    background:linear-gradient(135deg,#2b3038,#3b424d);
    color:#fff;
    border-radius:24px;
    padding:24px;
    position:relative;
    overflow:hidden;
    width:100%;
}

.vehicle-card::after{
    content:'';
    position:absolute;
    width:120px;
    height:120px;
    border-radius:50%;
    background:rgba(255,255,255,.08);
    top:-30px;
    right:-30px;
}

.vehicle-number{
    margin-top:15px;
    background:#fff;
    color:#212529;
    border-radius:12px;
    padding:12px;
    text-align:center;
    font-size:1.2rem;
    font-weight:700;
    letter-spacing:2px;
    width:100%;
}

.vehicle-label{
    color:#ced4da;
    font-size:.9rem;
}

.vehicle-card h4{
    margin-bottom:0;
}

.booking-item{
    border:2px solid #eee;
    border-radius:18px;
    padding:20px;
    transition:.3s;
}

.booking-item:hover{
    background:#fff5f5;
    border-color:#dc3545;
}
body{
background:var(--bg);
font-family:Inter,Segoe UI,sans-serif;
color:var(--text);
}

.navbar{
background:#fff;
border-bottom:1px solid #ececec;
padding:14px 0;
}

.navbar-brand{
font-weight:700;
color:var(--primary)!important;
}

.profile-img{
width:42px;
height:42px;
border-radius:50%;
border:3px solid #fff;
box-shadow:0 5px 15px rgba(0,0,0,.08);
}

.hero{
background:linear-gradient(135deg,#E40521,#ff4d67);
border-radius:28px;
padding:35px;
color:white;
overflow:hidden;
position:relative;
}

.hero::before{
content:'';
position:absolute;
right:-50px;
top:-50px;
width:220px;
height:220px;
border-radius:50%;
background:rgba(255,255,255,.08);
}

.hero h2{
font-weight:700;
margin-bottom:8px;
}

.hero p{
opacity:.9;
margin-bottom:20px;
}

.hero-btn{
background:white;
color:var(--primary);
font-weight:600;
border:none;
padding:10px 20px;
border-radius:12px;
}

.quick-card{
background:white;
border:none;
border-radius:24px;
padding:20px;
transition:.3s;
cursor:pointer;
height:100%;
box-shadow:0 10px 30px rgba(0,0,0,.05);
}

.quick-card:hover{
transform:translateY(-8px);
box-shadow:0 20px 40px rgba(228,5,33,.15);
}

.icon-circle{
width:65px;
height:65px;
border-radius:18px;
display:flex;
align-items:center;
justify-content:center;
font-size:28px;
margin:auto;
margin-bottom:15px;
}

.section-card{
background:white;
border:none;
border-radius:24px;
box-shadow:0 10px 30px rgba(0,0,0,.05);
}

.booking-item{
padding:20px;
border:1px solid #edf0f4;
border-radius:18px;
}

.status-badge{
background:#e8fff0;
color:#16a34a;
padding:6px 12px;
border-radius:999px;
font-size:13px;
font-weight:600;
}

.vehicle-card{
background:linear-gradient(135deg,#212529,#343a40);
color:white;
border-radius:24px;
padding:25px;
position:relative;
overflow:hidden;
}

.vehicle-card::before{
content:'';
position:absolute;
right:-30px;
top:-30px;
width:120px;
height:120px;
background:rgba(255,255,255,.08);
border-radius:50%;
}

.vehicle-label{
font-size:13px;
opacity:.7;
}

.vehicle-number{
font-size:22px;
font-weight:700;
letter-spacing:2px;
}

.progress{
height:8px;
border-radius:20px;
background:#eee;
}

.progress-bar{
background:var(--primary);
}

.section-title{
font-weight:700;
font-size:20px;
}

</style>
</head>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<body>
    <nav class="navbar">
        <div class="container">
            <a class="navbar-brand" href="#">Honda Service Booking</a>

            <div class="dropdown">
                <img
                    src="https://i.pravatar.cc/150?img=12"
                    class="profile-img dropdown-toggle"
                    data-bs-toggle="dropdown"
                    style="cursor:pointer;"
                >

                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <h6 class="dropdown-header">
                            {{ session('username') }}
                        </h6>
                    </li>

                    <li><hr class="dropdown-divider"></li>

                    <li>
                        <a class="dropdown-item text-danger" href="#" id="btnLogout">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <div class="hero mb-4">

            @if(session('role') == 'admin')

                <h2>Halo, Admin {{ session('username') }} 👋</h2>
                <p>
                    Kelola data pelanggan, booking service, mekanik, dan operasional dealer
                    melalui dashboard administrasi Honda.
                </p>
                <a href="/admin/booking" class="hero-btn text-decoration-none text-dark">Kelola Booking</a>

            @elseif(session('role') == 'manager')

                <h2>Halo, Manager {{ session('username') }} 👋</h2>
                <p>
                    Pantau performa layanan, laporan booking, produktivitas mekanik,
                    dan perkembangan operasional dealer secara real-time.
                </p>
                <a href="/reports" class="hero-btn text-decoration-none text-dark">Lihat Laporan</a>

            @else

                <h2>Halo, {{ session('username') }} 👋</h2>
                <p>
                    Selamat datang kembali. Pastikan kendaraan Anda selalu dalam kondisi
                    terbaik dengan servis berkala Honda.
                </p>
                <a href="/booking/create" class="hero-btn text-decoration-none text-dark">Booking Sekarang</a>

            @endif

        </div>
       

        @if(session('is_login') && session('role') == 'customer')
            <div class="row mb-4 g-4">
                <div class="col-md-4">
                    <a href="/booking/create" class="text-decoration-none text-dark">
                        <div class="quick-card text-center">
                            <div class="icon-circle bg-danger-subtle text-danger">
                                <i class="bi bi-calendar-check"></i>
                            </div>
                            <h6 class="fw-bold mb-1">Booking Service</h6>
                            <small class="text-muted">Buat jadwal servis kendaraan</small>
                        </div>
                    </a>
                </div>

                <div class="col-md-4">
                    <a href="/booking/history" class="text-decoration-none text-dark">
                        <div class="quick-card text-center">
                            <div class="icon-circle bg-warning-subtle text-warning">
                                <i class="bi bi-clock-history"></i>
                            </div>
                            <h6 class="fw-bold mb-1">Riwayat Booking</h6>
                            <small class="text-muted">Lihat riwayat service</small>
                        </div>
                    </a>
                </div>

                <div class="col-md-4">
                    <a href="/complaint" class="text-decoration-none text-dark">
                        <div class="quick-card text-center">
                            <div class="icon-circle bg-primary-subtle text-primary">
                                <i class="bi bi-chat-left-text"></i>
                            </div>
                            <h6 class="fw-bold mb-1">Keluhan & Saran</h6>
                            <small class="text-muted">Hubungi dealer Honda</small>
                        </div>
                    </a>
                </div>
            </div>
            <div class="row mt-4 g-4">

                    <div class="col-lg-8">

                        <div class="section-card p-4">

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="section-title mb-0">Booking Aktif</h5>
                                <a href="/booking/history" class="text-danger text-decoration-none">Lihat Semua</a>
                            </div>

                            @forelse($activeBookings as $booking)

                                @php
                                    $progress = 0;

                                    if($booking->status == 'pending'){
                                        $progress = 25;
                                    }elseif($booking->status == 'approved'){
                                        $progress = 70;
                                    }elseif($booking->status == 'completed'){
                                        $progress = 100;
                                    }
                                @endphp

                                <div class="booking-item mb-3">

                                    <div class="d-flex justify-content-between">

                                        <div>

                                            <h5 class="fw-bold">
                                                {{ $booking->type_service }}
                                            </h5>

                                            <p class="text-muted mb-2">
                                                {{ date('d M Y', strtotime($booking->service_date)) }}
                                                •
                                                {{ substr($booking->service_time,0,5) }}
                                            </p>

                                            @if($booking->status == 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($booking->status == 'approved')
                                                <span class="badge bg-primary">Approved</span>
                                            @elseif($booking->status == 'completed')
                                                <span class="badge bg-success">Completed</span>
                                            @endif

                                        </div>

                                        <div class="text-end">
                                            <h6>Progress</h6>
                                            <strong>{{ $progress }}%</strong>
                                        </div>

                                    </div>

                                    <div class="progress mt-3">
                                        <div class="progress-bar bg-danger" style="width:{{ $progress }}%"></div>
                                    </div>

                                </div>

                            @empty

                                <div class="text-center py-5">
                                    <i class="bi bi-calendar-x fs-1 text-muted"></i>
                                    <h5 class="mt-3">Belum Ada Booking</h5>
                                    <a href="/booking/create" class="btn btn-danger mt-2">Booking Sekarang</a>
                                </div>

                            @endforelse

                        </div>

                    </div>

                    <div class="col-lg-4">

                        <div class="section-card p-4">

                            <h5 class="section-title mb-4">
                                Kendaraan Saya
                            </h5>

                            @foreach($vehicles as $vehicle)

                            <div class="vehicle-card mb-3">

                                <div class="vehicle-label mb-2">
                                    Kendaraan Saya
                                </div>

                                <h4 class="fw-bold">
                                    {{ $vehicle->vehicle_type }}
                                </h4>

                                <div class="vehicle-number">
                                    {{ $vehicle->plate_number }}
                                </div>

                                <div class="mt-3">
                                    Tahun {{ $vehicle->manufacture_year }}
                                </div>

                                <div class="mt-4">
                                    <small>Status Kendaraan</small>
                                    <h6 class="mt-1 mb-0">Terdaftar</h6>
                                </div>

                            </div>

                            @endforeach

                        </div>

                    </div>

                </div>
            </div>
        @endif

        @if(session('is_login') && session('role') == 'admin' || session('role') == 'manager')
            <div class="row mb-4 g-4">

                <div class="col">
                    <a href="/customer" class="text-decoration-none">
                        <div class="quick-card text-center">
                            <div class="icon-circle bg-primary-subtle text-primary">
                                <i class="bi bi-people"></i>
                            </div>
                            <h6 class="fw-bold mb-1 text-dark">Customer</h6>
                            <small class="text-muted">Kelola customer</small>
                        </div>
                    </a>
                </div>

                <div class="col">
                    <a href="/schedule" class="text-decoration-none">
                        <div class="quick-card text-center">
                            <div class="icon-circle bg-danger-subtle text-danger">
                                <i class="bi bi-calendar-week"></i>
                            </div>
                            <h6 class="fw-bold mb-1 text-dark">Jadwal</h6>
                            <small class="text-muted">Kelola jadwal service</small>
                        </div>
                    </a>
                </div>

                <div class="col">
                    <a href="/admin/booking" class="text-decoration-none">
                        <div class="quick-card text-center">
                            <div class="icon-circle bg-success-subtle text-success">
                                <i class="bi bi-journal-check"></i>
                            </div>
                            <h6 class="fw-bold mb-1 text-dark">Booking</h6>
                            <small class="text-muted">Kelola booking</small>
                        </div>
                    </a>
                </div>

                <div class="col">
                    <a href="/admin/complaints" class="text-decoration-none">
                        <div class="quick-card text-center">
                            <div class="icon-circle bg-info-subtle text-info">
                                <i class="bi bi-chat-square-text"></i>
                            </div>
                            <h6 class="fw-bold mb-1 text-dark">Keluhan</h6>
                            <small class="text-muted">Respon pelanggan</small>
                        </div>
                    </a>
                </div>

                <div class="col">
                    <a href="/report" class="text-decoration-none">
                        <div class="quick-card text-center">
                            <div class="icon-circle bg-secondary-subtle text-secondary">
                                <i class="bi bi-file-earmark-bar-graph"></i>
                            </div>
                            <h6 class="fw-bold mb-1 text-dark">Laporan</h6>
                            <small class="text-muted">Cetak laporan</small>
                        </div>
                    </a>
                </div>

            </div>
            @if(session('role') == 'manager')
            <div class="row mb-4 g-4">

                <div class="col-md-3">
                    <a href="/manager/customer-experience" class="text-decoration-none">
                        <div class="quick-card text-center">
                            <div class="icon-circle bg-success-subtle text-success">
                                <i class="bi bi-stars"></i>
                            </div>
                            <h6 class="fw-bold mb-1 text-dark">
                                Customer Experience
                            </h6>
                            <small class="text-muted">
                                Analisis kualitas layanan
                            </small>
                        </div>
                    </a>
                </div>

                <div class="col-md-3">
                    <a href="/manager/recommendation" class="text-decoration-none">
                        <div class="quick-card text-center">
                            <div class="icon-circle bg-danger-subtle text-danger">
                                <i class="bi bi-lightbulb"></i>
                            </div>
                            <h6 class="fw-bold mb-1 text-dark">
                                Rekomendasi
                            </h6>
                            <small class="text-muted">
                                Insight dan laporan
                            </small>
                        </div>
                    </a>
                </div>

            </div>
            @endif
        @endif
    </div>
</body>
</html>
<script>
$(document).ready(function () {
    $('#btnLogout').click(function (e) {
        e.preventDefault();
        Swal.fire({
            title: 'Logout?',
            text: 'Sesi Anda akan diakhiri.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Logout',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/logout',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message,
                                timer: 1500,
                                showConfirmButton: false
                            });
                            setTimeout(function () {
                                window.location.href = response.redirect;
                            }, 1500);
                        }
                    },
                    error: function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Gagal logout'
                        });
                    }
                });
            }
        });
    });
});

</script>
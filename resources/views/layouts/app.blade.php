<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Honda Service Booking</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @stack('styles')
</head>
<body>

    <nav class="navbar navbar-expand-lg bg-white border-bottom shadow-sm">
        <div class="container">

            <a class="navbar-brand fw-bold text-danger" href="/dashboard">
                Honda Service Booking
            </a>

            @if(session('is_login'))
                <div class="dropdown">

                    <img
                        src="https://i.pravatar.cc/150?img=12"
                        class="rounded-circle dropdown-toggle"
                        width="45"
                        height="45"
                        style="cursor:pointer;"
                        data-bs-toggle="dropdown"
                    >

                    <ul class="dropdown-menu dropdown-menu-end">

                        <li>
                            <h6 class="dropdown-header">
                                {{ session('username') }}
                            </h6>
                        </li>

                        <li>
                            <small class="dropdown-item-text text-muted">
                                {{ ucfirst(session('role')) }}
                            </small>
                        </li>

                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <a class="dropdown-item" href="/dashboard">
                                <i class="bi bi-house me-2"></i>
                                Dashboard
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item text-danger" href="#" id="btnLogout">
                                <i class="bi bi-box-arrow-right me-2"></i>
                                Logout
                            </a>
                        </li>

                    </ul>

                </div>
            @endif

        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    $(document).ready(function(){

        $('#btnLogout').on('click', function(e){

            e.preventDefault();

            Swal.fire({
                title: 'Logout?',
                text: 'Apakah Anda yakin ingin keluar?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Logout',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#dc3545'
            }).then((result) => {

                if(result.isConfirmed){

                    $.ajax({
                        url: '/logout',
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response){

                            window.location.href = response.redirect;

                        }
                    });

                }

            });

        });

    });
    </script>

    @stack('scripts')

</body>
</html>
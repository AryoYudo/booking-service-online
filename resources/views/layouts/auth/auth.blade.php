<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Honda Car Service Booking</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            overflow: hidden;
            background: #f4f6f9;
        }

        .left-side {
            background:
                linear-gradient(rgba(228, 5, 33, .85),
                    rgba(155, 0, 0, .85)),
                url('https://hondaoutsidejava.co.id/assets/img/06%20Jun%202024/W4/article-07.jpg');
            background-size: cover;
            background-position: center;
            height: 100vh;
            color: white;
        }

        .glass-card {
            background: white;
            border-radius: 25px;
            padding: 40px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, .1);
            width: 100%;
            max-width: 450px;
        }

        .logo {
            width: 120px;
        }

        .btn-honda {
            background: #E40521;
            color: white;
            border: none;
            height: 50px;
            font-weight: 600;
        }

        .btn-honda:hover {
            background: #c4001b;
            color: white;
        }

        .password-wrapper {
            position: relative;
        }

        .toggle-password {
            border: 0;
            padding: 0;
            background: transparent;
            color: inherit;
            position: absolute;
            right: 15px;
            top: 15px;
            cursor: pointer;
            z-index: 10;
        }

        .welcome-title {
            font-size: 3rem;
            font-weight: 700;
        }

        .subtitle {
            font-size: 1.1rem;
            opacity: .9;
        }

        .form-control {
            height: 58px;
        }

        @media(max-width:768px) {

            .left-side {
                display: none;
            }

            body {
                overflow: auto;
            }

        }
    </style>

</head>

<body>

    <div class="container-fluid">

        <div class="row">

            <!-- LEFT SIDE -->

            <div class="col-lg-7 left-side d-flex align-items-center justify-content-center">

                <div class="text-center px-5">

                    <h1 class="welcome-title">
                        Honda Car Service
                    </h1>

                    <p class="subtitle mt-4">

                        Booking service mobil Honda lebih cepat,
                        tanpa antre dan dapat dipantau secara online.

                    </p>

                </div>

            </div>

            <!-- RIGHT SIDE -->

            <div class="col-lg-5 d-flex align-items-center justify-content-center vh-100">

                <div class="glass-card">

                    <div class="text-center mb-4">

                        <img src="/images/honda.png" class="logo mb-3" alt="Honda">

                        <h3>
                            Selamat Datang
                        </h3>

                        <p class="text-muted">
                            Login ke akun Anda
                        </p>

                    </div>

                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">

                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach

                            </ul>
                        </div>
                    @endif
                    <div id="alertContainer"></div>

                    <form id="loginForm">

                        @csrf

                        <div class="form-floating mb-3">

                            <input type="text" name="username" value="{{ old('username') }}" class="form-control"
                                placeholder="Username">

                            <label>
                                Username
                            </label>

                        </div>

                        <div class="password-wrapper mb-3">

                            <div class="form-floating">

                                <input type="password" id="password" name="password" class="form-control"
                                    placeholder="Password">

                                <label>
                                    Password
                                </label>

                            </div>

                            <button type="button" class="bi bi-eye toggle-password"
                                aria-label="Tampilkan password" aria-pressed="false"></button>

                        </div>

                        <button type="button" id="btnLogin" class="btn btn-honda w-100">
                            <span class="btn-text">
                                Login
                            </span>
                            <span class="spinner-border spinner-border-sm d-none"></span>

                        </button>

                    </form>

                    <div class="text-center mt-4">

                        Belum punya akun?

                        <a href="{{ url('/register') }}" class="text-danger fw-bold text-decoration-none">

                            Daftar Sekarang

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function showAlert(type, message) {

            $('#alertContainer').html(`
        <div class="alert alert-${type}">
            ${message}
        </div>
    `);

        }

        $('.toggle-password').on('click', function() {
            const passwordInput = $('#password');
            const isPasswordHidden = passwordInput.attr('type') === 'password';

            passwordInput.attr('type', isPasswordHidden ? 'text' : 'password');
            $(this)
                .toggleClass('bi-eye', !isPasswordHidden)
                .toggleClass('bi-eye-slash', isPasswordHidden)
                .attr('aria-label', isPasswordHidden ? 'Sembunyikan password' : 'Tampilkan password')
                .attr('aria-pressed', isPasswordHidden ? 'true' : 'false');
        });

        $('#btnLogin').click(function() {

            let username = $('input[name="username"]').val();
            let password = $('input[name="password"]').val();

            if (!username || !password) {

                showAlert(
                    'danger',
                    'Username dan password wajib diisi'
                );

                return;
            }

            let button = $(this);

            button.prop('disabled', true);

            button.find('.btn-text').text('Memproses...');

            button.find('.spinner-border').removeClass('d-none');

            $.ajax({

                url: '/login',

                type: 'POST',

                data: {
                    _token: $('input[name="_token"]').val(),
                    username: username,
                    password: password
                },

                success: function(response) {

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {

                        window.location.href = response.redirect;

                    });

                },

                error: function(xhr) {

                    if (xhr.responseJSON) {

                        showAlert(
                            'danger',
                            xhr.responseJSON.message
                        );

                    } else {

                        showAlert(
                            'danger',
                            'Terjadi kesalahan server'
                        );

                    }

                },

                complete: function() {

                    button.prop('disabled', false);

                    button.find('.btn-text').text('Login');

                    button.find('.spinner-border').addClass('d-none');

                }

            });

        });
    </script>
</body>

</html>

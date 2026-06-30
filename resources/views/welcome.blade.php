<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Honda Service Booking</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body{
            min-height:100vh;
            overflow:hidden;
            background:#f4f6f9;
        }

        .left-side{
            background:
            linear-gradient(
                rgba(228,5,33,.85),
                rgba(155,0,0,.85)
            ),
            url('https://images.unsplash.com/photo-1489824904134-891ab64532f1');
            background-size:cover;
            background-position:center;
            height:100vh;
            color:white;
        }

        .glass-card{
            backdrop-filter: blur(20px);
            background:white;
            border-radius:25px;
            padding:40px;
            box-shadow:0 15px 40px rgba(0,0,0,.1);
            width:100%;
            max-width:450px;
        }

        .logo{
            width:90px;
        }

        .btn-honda{
            background:#E40521;
            color:white;
            border:none;
            height:50px;
            font-weight:600;
        }

        .btn-honda:hover{
            background:#c4001b;
            color:white;
        }

        .password-wrapper{
            position:relative;
        }

        .toggle-password{
            position:absolute;
            right:15px;
            top:15px;
            cursor:pointer;
        }

        .welcome-title{
            font-size:3rem;
            font-weight:700;
        }

        .subtitle{
            font-size:1.1rem;
            opacity:.9;
        }

        @media(max-width:768px){
            .left-side{
                display:none;
            }
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">

        <div class="col-lg-7 left-side d-flex align-items-center justify-content-center">

            <div class="text-center px-5">

                <h1 class="welcome-title">
                    Honda Service Booking
                </h1>

                <p class="subtitle mt-4">
                    Booking service motor Honda lebih cepat,
                    tanpa antre dan bisa dipantau secara online.
                </p>

            </div>

        </div>

        <div class="col-lg-5 d-flex align-items-center justify-content-center vh-100">

            <div class="glass-card">

                <div class="text-center mb-4">

                    <img
                        src="https://upload.wikimedia.org/wikipedia/commons/7/7b/Honda_Logo.svg"
                        class="logo mb-3">

                    <h3>Selamat Datang</h3>

                    <p class="text-muted">
                        Login ke akun Anda
                    </p>

                </div>

                <form action="{{ url('/login') }}" method="POST">

                    @csrf

                    <div class="form-floating mb-3">

                        <input
                            type="text"
                            name="username"
                            class="form-control"
                            placeholder="Username">

                        <label>Username</label>

                    </div>

                    <div class="password-wrapper mb-3">

                        <div class="form-floating">

                            <input
                                type="password"
                                name="password"
                                id="password"
                                class="form-control"
                                placeholder="Password">

                            <label>Password</label>

                        </div>

                        <i class="bi bi-eye toggle-password"></i>

                    </div>

                    <button
                        class="btn btn-honda w-100">

                        Login

                    </button>

                </form>

                <div class="text-center mt-4">

                    Belum punya akun?

                    <a href="/register"
                       class="text-danger text-decoration-none fw-bold">
                        Daftar Sekarang
                    </a>

                </div>

            </div>

        </div>

    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
$('.toggle-password').click(function(){

    let input = $('#password');

    if(input.attr('type') === 'password'){
        input.attr('type','text');
        $(this).removeClass('bi-eye').addClass('bi-eye-slash');
    }else{
        input.attr('type','password');
        $(this).removeClass('bi-eye-slash').addClass('bi-eye');
    }

});
</script>

</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Registrasi</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css" rel="stylesheet">

<style>

body{
    background:#f4f6f9;
}

.register-card{
    border:none;
    border-radius:25px;
    overflow:hidden;
    box-shadow:0 10px 30px rgba(0,0,0,.1);
}

.header-register{
    background:#E40521;
    color:white;
    padding:30px;
}

.step{
    display:none;
}

.step.active{
    display:block;
}

.btn-honda{
    background:#E40521;
    color:white;
}

.progress{
    height:8px;
}

</style>

</head>
<body>

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-8">

            <div class="card register-card">

                <div class="header-register text-center">

                    <h3>Registrasi Pelanggan Honda</h3>

                    <small>
                        Buat akun untuk booking service online
                    </small>

                </div>

                <div class="card-body p-5">

                    <div class="progress mb-4">

                        <div
                            id="progressBar"
                            class="progress-bar bg-danger"
                            style="width:33%">
                        </div>

                    </div>
                    <div id="alertContainer"></div>
                    

                    <form id="registerForm">

                        @csrf
                        <input type="hidden" id="current_step" name="current_step" value="{{ old('current_step',0) }}" >

                        <!-- STEP 1 -->

                        <div class="step active">

                            <h5 class="mb-4">
                                Data Akun
                            </h5>

                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="username" value="{{ old('username') }}" placeholder="">
                                <label>Username</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="password" name="password" class="form-control" class="form-control @error('password') is-invalid @enderror" value="{{ old('password') }}" placeholder="">
                                <label>Password</label>
                            </div>

                            <button
                                type="button"
                                class="btn btn-honda nextStep">
                                Selanjutnya
                            </button>

                        </div>

                        <!-- STEP 2 -->

                        <div class="step">

                            <h5 class="mb-4">
                                Data Pelanggan
                            </h5>

                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="customer_name" value="{{ old('customer_name') }}">
                                <label>Nama Lengkap</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}">
                                <label>Email</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="phone_number" value="{{ old('phone_number') }}">
                                <label>No HP</label>
                            </div>

                            <div class="form-floating mb-3">
                                <textarea class="form-control" name="address" style="height:120px">{{ old('address') }}</textarea>
                                <label>Alamat</label>
                            </div>

                            <button
                                type="button"
                                class="btn btn-secondary prevStep">
                                Kembali
                            </button>

                            <button
                                type="button"
                                class="btn btn-honda nextStep">
                                Selanjutnya
                            </button>

                        </div>

                        <!-- STEP 3 -->

                        <div class="step">

                            <h5 class="mb-4">
                                Data Kendaraan
                            </h5>

                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="plate_number" value="{{ old('plate_number') }}">
                                <label>Nomor Polisi</label>
                            </div>

                            <div class="form-floating mb-3">
                                <select class="form-select" name="vehicle_type">
                                    <option value="" disabled selected>Pilih Tipe Kendaraan</option>
                                    <option value="Honda Beat" {{ old('vehicle_type') == 'Honda Beat' ? 'selected' : '' }}>Honda Beat</option>
                                    <option value="Honda Vario 160" {{ old('vehicle_type') == 'Honda Vario 160' ? 'selected' : '' }}>Honda Vario 160</option>
                                    <option value="Honda PCX 160" {{ old('vehicle_type') == 'Honda PCX 160' ? 'selected' : '' }}>Honda PCX 160</option>
                                    <option value="Honda Scoopy" {{ old('vehicle_type') == 'Honda Scoopy' ? 'selected' : '' }}>Honda Scoopy</option>
                                </select>
                                <label>Tipe Kendaraan</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="number" class="form-control" name="manufacture_year" value="{{ old('manufacture_year') }}">
                                <label>Tahun Kendaraan</label>
                            </div>

                            <button
                                type="button"
                                class="btn btn-secondary prevStep">
                                Kembali
                            </button>

                            <button
                                type="button"
                                id="btnRegister"
                                class="btn btn-honda">

                                <span class="btn-text">
                                    Daftar Sekarang
                                </span>

                                <span class="spinner-border spinner-border-sm d-none"></span>

                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>

let current = 0;

const steps = $('.step');

function updateStep() {

    steps.removeClass('active');

    $(steps[current]).addClass('active');

    let width = ((current + 1) / steps.length) * 100;

    $('#progressBar').css('width', width + '%');

    $('#current_step').val(current);

}

updateStep();

function validateCurrentStep() {
    let currentStep = $(steps[current]);

    let valid = true;

    currentStep.find('input, textarea, select').each(function () {
        const value = $(this).val();
        if (!value || value.toString().trim() === '') {
            $(this).addClass('is-invalid');
            valid = false;
        } else {
            $(this).removeClass('is-invalid');
        }
    });
    return valid;
}

$('.nextStep').click(function () {
    if (!validateCurrentStep()) {
        showAlert(
            'danger',
            'Mohon lengkapi data terlebih dahulu'
        );
        return;
    }

    if (current < steps.length - 1) {
        current++;
        updateStep();
    }

});

$('.prevStep').click(function () {
    if (current > 0) {
        current--;
        updateStep();
    }
});

$('input, textarea, select').on('keyup change', function () {

    const value = $(this).val();

    if (value && value.toString().trim() !== '') {
        $(this).removeClass('is-invalid');
    }

});

function showAlert(type, message) {
    $('#alertContainer').html(`
        <div class="alert alert-${type} alert-dismissible fade show">
            ${message}
            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>
        </div>
    `);
}

$('#btnRegister').click(function () {
    if (!validateCurrentStep()) {
        showAlert(
            'danger',
            'Mohon lengkapi seluruh data'
        );
        return;
    }
    let button = $(this);
    button.prop('disabled', true);
    button.find('.btn-text').text('Memproses...');
    button.find('.spinner-border').removeClass('d-none');
    $('.is-invalid').removeClass('is-invalid');
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        }
    });
    $.ajax({
        url: '/register',
        type: 'POST',
        data: $('#registerForm').serialize(),

        success: function(response){

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
            if (xhr.status === 422) {
                let errors = xhr.responseJSON.errors;
                $.each(errors, function(key, value) {

                    $(`[name="${key}"]`)
                        .addClass('is-invalid');
                });

                let firstError = Object.values(errors)[0][0];
                showAlert(
                    'danger',
                    firstError
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
            button.find('.btn-text')
                .text('Daftar Sekarang');
            button.find('.spinner-border')
                .addClass('d-none');

        }

    });

});

</script>

</body>
</html>
@extends('layouts.app')

@section('content')

<div class="container py-5">

    <div class="hero-complaint mb-4">
        <div class="row align-items-center">

            <div class="col-md-8">

                <h2 class="fw-bold mb-2">
                    Keluhan & Bantuan Honda
                </h2>

                <p class="mb-0">
                    Sampaikan kendala, kritik, atau pertanyaan terkait layanan Honda kami.
                </p>

            </div>

            <div class="col-md-4 text-end">

                <i class="bi bi-headset display-3 opacity-50"></i>

            </div>

        </div>
    </div>

    <div class="row g-3 mb-4">

        <div class="col-md-4">
            <div class="stats-card">
                <h3 id="totalComplaint">0</h3>
                <small>Total Keluhan</small>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stats-card">
                <h3 id="openComplaint">0</h3>
                <small>Menunggu Jawaban</small>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stats-card">
                <h3 id="answeredComplaint">0</h3>
                <small>Sudah Dijawab</small>
            </div>
        </div>

    </div>

    <div class="row g-4">

        <div class="col-lg-5">

            <div class="card complaint-form-card border-0">

                <div class="card-body p-4">

                    <div class="d-flex align-items-center mb-4">

                        <div class="icon-circle bg-danger-subtle text-danger me-3">
                            <i class="bi bi-pencil-square"></i>
                        </div>

                        <div>
                            <h5 class="mb-0">
                                Buat Keluhan
                            </h5>

                            <small class="text-muted">
                                Kami akan merespon secepat mungkin
                            </small>
                        </div>

                    </div>

                    <form id="complaintForm">

                        @csrf

                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Subject
                            </label>

                            <input
                                type="text"
                                class="form-control form-control-lg"
                                name="subject"
                                placeholder="Masukkan subject keluhan">

                        </div>

                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Keluhan
                            </label>

                            <textarea
                                class="form-control"
                                rows="6"
                                name="complaint_text"
                                placeholder="Tuliskan keluhan Anda..."></textarea>

                        </div>

                        <button
                            type="submit"
                            class="btn btn-danger w-100 py-3">

                            <i class="bi bi-send me-2"></i>
                            Kirim Keluhan

                        </button>

                    </form>

                </div>

            </div>

        </div>

        <div class="col-lg-7">

            <div id="complaintList"></div>

        </div>

    </div>

</div>

@endsection

@push('scripts')

<script>

$(document).ready(function(){

    loadComplaints();

    $('#complaintForm').submit(function(e){

        e.preventDefault();

        $.ajax({

            url:'/complaint/store',
            type:'POST',
            data:$(this).serialize(),

            headers:{
                'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
            },

            success:function(response){

                Swal.fire({
                    icon:'success',
                    title:'Berhasil',
                    text:response.message,
                    confirmButtonColor:'#dc3545'
                });

                $('#complaintForm')[0].reset();

                loadComplaints();

            },

            error:function(xhr){

                Swal.fire({
                    icon:'error',
                    title:'Oops...',
                    text:xhr.responseJSON?.message ?? 'Terjadi kesalahan',
                    confirmButtonColor:'#dc3545'
                });

            }

        });

    });

});

function loadComplaints(){

    $.ajax({

        url:'/complaint/list',
        type:'GET',

        success:function(response){

            let html = '';

            let total = response.length;
            let open = 0;
            let answered = 0;

            response.forEach(item => {

                let badge = '';

                if(item.status == 'open'){

                    open++;

                    badge = `
                    <span class="badge rounded-pill bg-warning-subtle text-warning border px-3 py-2">
                        Open
                    </span>`;
                }

                if(item.status == 'answered'){

                    answered++;

                    badge = `
                    <span class="badge rounded-pill bg-success-subtle text-success border px-3 py-2">
                        Answered
                    </span>`;
                }

                if(item.status == 'closed'){

                    badge = `
                    <span class="badge rounded-pill bg-secondary-subtle text-secondary border px-3 py-2">
                        Closed
                    </span>`;
                }

                html += `
                <div class="complaint-card mb-3">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <h5 class="mb-1">
                                ${item.subject}
                            </h5>

                            <small class="text-muted">
                                Ticket #${item.complaint_id}
                            </small>

                        </div>

                        ${badge}

                    </div>

                    <div class="history-divider"></div>

                    <div class="mb-3">

                        <small class="text-muted d-block mb-2">
                            Keluhan Anda
                        </small>

                        <p class="mb-0">
                            ${item.complaint_text}
                        </p>

                    </div>

                    ${
                        item.admin_response
                        ?
                        `
                        <div class="admin-response">

                            <div class="d-flex align-items-center mb-2">

                                <i class="bi bi-person-badge text-success me-2"></i>

                                <strong>
                                    Balasan Admin Honda
                                </strong>

                            </div>

                            ${item.admin_response}

                        </div>
                        `
                        :
                        ''
                    }

                </div>
                `;
            });

            if(total == 0){

                html = `
                <div class="text-center py-5">

                    <i class="bi bi-chat-square-text display-4 text-muted"></i>

                    <h5 class="mt-3">
                        Belum Ada Keluhan
                    </h5>

                    <p class="text-muted">
                        Silakan buat keluhan pertama Anda.
                    </p>

                </div>
                `;
            }

            $('#complaintList').html(html);

            $('#totalComplaint').text(total);
            $('#openComplaint').text(open);
            $('#answeredComplaint').text(answered);

        }

    });

}

</script>

@endpush

@push('styles')

<style>

.hero-complaint{
    background:linear-gradient(135deg,#dc3545,#ff6b6b);
    color:#fff;
    padding:30px;
    border-radius:24px;
}

.stats-card{
    background:#fff;
    border:2px solid #eee;
    border-radius:20px;
    padding:20px;
    text-align:center;
    transition:.3s;
}

.stats-card:hover{
    border-color:#dc3545;
    background:#fff5f5;
    transform:translateY(-3px);
}

.complaint-form-card{
    border-radius:24px;
    box-shadow:0 8px 20px rgba(0,0,0,.05);
}

.icon-circle{
    width:55px;
    height:55px;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:1.3rem;
}

.complaint-card{
    border:2px solid #eee;
    border-radius:24px;
    padding:24px;
    background:#fff;
    transition:.3s;
}

.complaint-card:hover{
    transform:translateY(-5px);
    border-color:#dc3545;
    background:#fff5f5;
    box-shadow:0 10px 25px rgba(220,53,69,.12);
}

.history-divider{
    height:1px;
    background:#eee;
    margin:15px 0;
}

.admin-response{
    background:#f8fff9;
    border-left:4px solid #198754;
    padding:15px;
    border-radius:12px;
}

.form-control{
    border-radius:14px;
}

.btn-danger{
    border-radius:14px;
}

</style>

@endpush
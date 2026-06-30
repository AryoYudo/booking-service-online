@extends('layouts.app')

@section('content')

<div class="container py-4">

    <div class="hero-admin mb-4">

        <div class="row align-items-center">

            <div class="col-md-8">

                <h2 class="fw-bold mb-2">
                    Manajemen Keluhan Customer
                </h2>

                <p class="mb-0">
                    Kelola dan respon seluruh keluhan customer Honda.
                </p>

            </div>

            <div class="col-md-4 text-end">

                <i class="bi bi-chat-left-dots display-4"></i>

            </div>

        </div>

    </div>

    <div class="row g-3 mb-4">

        <div class="col-md-3">
            <div class="stats-card">
                <h3 id="totalComplaint">0</h3>
                <small>Total</small>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stats-card">
                <h3 id="openComplaint">0</h3>
                <small>Open</small>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stats-card">
                <h3 id="answeredComplaint">0</h3>
                <small>Answered</small>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stats-card">
                <h3 id="closedComplaint">0</h3>
                <small>Closed</small>
            </div>
        </div>

    </div>

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <input
                type="text"
                id="searchComplaint"
                class="form-control"
                placeholder="Cari subject atau customer...">

        </div>

    </div>

    <div id="complaintList"></div>

</div>

@endsection
@push('scripts')

<script>

let complaints = [];

$(document).ready(function(){

    loadComplaints();

    $('#searchComplaint').on('keyup', function(){

        renderComplaints(
            $(this).val().toLowerCase()
        );

    });

});

function loadComplaints(){

    $.ajax({

        url:'/admin/complaints/list',
        type:'GET',

        success:function(response){

            complaints = response;

            renderComplaints();

        }

    });

}

function renderComplaints(keyword=''){

    let html='';

    let open=0;
    let answered=0;
    let closed=0;

    let filtered = complaints.filter(item => {

        return item.subject.toLowerCase().includes(keyword)
            || item.customer_name.toLowerCase().includes(keyword);

    });

    filtered.forEach(item=>{

        if(item.status=='open') open++;
        if(item.status=='answered') answered++;
        if(item.status=='closed') closed++;

        let badge='';

        if(item.status=='open'){
            badge='<span class="badge bg-warning">Open</span>';
        }

        if(item.status=='answered'){
            badge='<span class="badge bg-success">Answered</span>';
        }

        if(item.status=='closed'){
            badge='<span class="badge bg-secondary">Closed</span>';
        }

        html += `
        <div class="complaint-card mb-3">

            <div class="d-flex justify-content-between">

                <div>

                    <h5>${item.subject}</h5>

                    <small class="text-muted">
                        ${item.customer_name}
                    </small>

                </div>

                ${badge}

            </div>

            <hr>

            <p>
                ${item.complaint_text}
            </p>

            ${
                item.admin_response
                ?
                `
                <div class="admin-response mb-3">

                    <strong>
                        Balasan Admin
                    </strong>

                    <hr>

                    ${item.admin_response}

                </div>
                `
                :
                ''
            }

            ${
                item.status != 'closed'
                ?
                `
                <textarea
                    class="form-control mb-3 replyText"
                    data-id="${item.complaint_id}"
                    rows="3"
                    placeholder="Tulis balasan..."></textarea>

                <div class="d-flex gap-2">

                    <button
                        class="btn btn-success btnReply"
                        data-id="${item.complaint_id}">
                        Kirim Balasan
                    </button>

                    <button
                        class="btn btn-outline-danger btnClose"
                        data-id="${item.complaint_id}">
                        Tutup Tiket
                    </button>

                </div>
                `
                :
                ''
            }

        </div>
        `;
    });

    $('#complaintList').html(html);

    $('#totalComplaint').text(complaints.length);
    $('#openComplaint').text(open);
    $('#answeredComplaint').text(answered);
    $('#closedComplaint').text(closed);
}

$(document).on('click','.btnReply',function(){

    let id=$(this).data('id');

    let responseText =
    $(`.replyText[data-id="${id}"]`).val();

    $.ajax({

        url:'/admin/complaints/reply/'+id,
        type:'POST',

        data:{
            admin_response:responseText
        },

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

            loadComplaints();
        }

    });

});

$(document).on('click','.btnClose',function(){

    let id=$(this).data('id');

    Swal.fire({

        title:'Tutup tiket?',
        icon:'warning',
        showCancelButton:true,
        confirmButtonColor:'#dc3545'

    }).then((result)=>{

        if(result.isConfirmed){

            $.ajax({

                url:'/admin/complaints/close/'+id,
                type:'POST',

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

                    loadComplaints();
                }

            });

        }

    });

});

</script>

@endpush
@push('styles')

<style>

.hero-admin{
    background:linear-gradient(135deg,#dc3545,#ff6b6b);
    color:white;
    padding:30px;
    border-radius:24px;
}

.stats-card{
    border:2px solid #eee;
    border-radius:20px;
    padding:20px;
    text-align:center;
    background:white;
    transition:.3s;
}

.stats-card:hover{
    border-color:#dc3545;
    background:#fff5f5;
}

.complaint-card{
    border:2px solid #eee;
    border-radius:24px;
    padding:24px;
    background:white;
    transition:.3s;
}

.complaint-card:hover{
    border-color:#dc3545;
    background:#fff5f5;
}

.admin-response{
    background:#f8fff9;
    border-left:4px solid #198754;
    padding:15px;
    border-radius:12px;
}

</style>

@endpush
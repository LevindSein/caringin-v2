<?php use Carbon\Carbon; $time = strtotime(Carbon::now());?>
@extends('layout.master')

@section('title')
<title>Data Kasir | BP3C</title>
@endsection

@section('judul')
<h6 class="h2 text-white d-inline-block mb-0">Data Kasir</h6>
@endsection

@section('button')
@if(Session::get('role') == 'master')
<a class="dropdown-toggle btn btn-sm btn-danger" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Menu</a>
<div class="dropdown-menu dropdown-menu-right">
    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#mySisa">Sisa Tagihan</a>
</div>
@endif
@endsection

@section('content')
<span id="form_result"></span>
<div class="row">
    <div class="col-xl-12">
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="form-group text-right">
                    <form action="{{url('master/kasir')}}" method="GET">
                        <input
                            required
                            style="color: #6e707e;background-color: #fff;background-clip: padding-box;border: 1px solid #d1d3e2;border-radius: 0.35rem;"
                            autocomplete="off"
                            type="date"
                            name="tanggal"
                            id="tanggal"
                            value="<?php echo Session::get('masterkasir'); ?>">
                        <input type="submit" class="btn btn-sm btn-info" style="margin-top:-3px;" value="Cari"/>
                    </form>
                </div>
                <div class="text-right">
                    <img src="{{asset('img/updating.gif')}}" style="display:none;" id="refresh-img"/><button class="btn btn-sm btn-primary" id="refresh"><i class="fas fa-sync-alt"></i> Refresh Data</button>
                </div>
                <div class="table-responsive py-4">
                    <table class="table table-flush" width="100%" id="tabel">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center" style="max-width:25%">Kontrol</th>
                                <th class="text-center" style="max-width:25%">Tagihan</th>
                                <th class="text-center" style="min-width:100px;max-width:20%">Pengguna</th>
                                <th class="text-center" style="max-width:20%">Ket</th>
                                <th class="text-center" style="max-width:10%">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('modal')
<div id="confirmRestore" class="modal fade" role="dialog" tabIndex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title titles">Apakah yakin restore pembayaran ?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Pilih "Ya" di bawah ini jika anda yakin untuk merestore pembayaran.</div>
            <div class="modal-footer">
            	<button type="button" name="ya_button" id="ya_button" class="btn btn-danger">Ya</button>
                <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>

<div
    class="modal fade"
    id="myModal"
    tabIndex="-1"
    role="dialog"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title titles" id="exampleModalLabel"></h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="form_edit" class="user" method="POST">
                <div class="modal-body">
                    @csrf
                    <div class="form-group col-lg-12">
                        <input
                            required
                            placeholder="Masukkan Tanggal Penerimaan" 
                            class="form-control"
                            autocomplete="off"
                            type="date"
                            name="edittanggal"
                            id="edittanggal"
                            value="{{Session::get('masterkasir')}}">
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="hidden_ref" id="hidden_ref"/>
                    <input type="submit" class="btn btn-primary btn-sm" value="Submit" />
                </div>
            </form>
        </div>
    </div>
</div>

<div
    class="modal fade"
    id="mySisa"
    role="dialog"
    tabIndex="-1"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Rekap Sisa Tagihan</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form class="user" action="{{url('master/kasir/sisa')}}" target="_blank" method="GET">
                <div class="modal-body">
                    <div class="form-group col-lg-12">
                        <select class="form-control" name="sisatagihan" id="sisatagihan" required>
                            <option selected value="all">Semua</option>
                            <option value="sebagian">Sebagian</option>
                        </select>
                        <div class="form-group col-lg-12" style="display:none;" id="divrekapsisa">
                            <label class="form-control-label" for="sebagian">Blok <span style="color:red;">*</span></label>
                            <div class="form-group">
                                <select class="sebagian" name="sebagian[]" id="sebagian" required multiple></select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-sm">Cetak</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
$(document).ready(function () {
    var dtable = $('#tabel').DataTable({
		serverSide: true,
		ajax: {
            url: "/master/kasir/?tanggal=" + <?php echo Session::get('masterkasir')?>,
            cache:false,
		},
		columns: [
			{ data: 'kd_kontrol', name: 'kd_kontrol', class : 'text-center' },
			{ data: 'tagihan', name: 'tagihan', class : 'text-center' },
			{ data: 'pengguna', name: 'pengguna', class : 'text-center-td' },
			{ data: 'lokasi', name: 'lokasi', class : 'text-center-td' },
			{ data: 'action', name: 'action', class : 'text-center' },
        ],
        stateSave: true,
        lengthMenu: [[10,25,50,100,-1], [10,25,50,100,"All"]],
        language: {
            paginate: {
                previous: "<i class='fas fa-angle-left'>",
                next: "<i class='fas fa-angle-right'>"
            }
        },
        deferRender: true,
        pageLength: 8,
        responsive: true
    }).columns.adjust().draw();

    setInterval(function(){ dtable.ajax.reload(function(){console.log("Refresh Automatic")}, false); }, 60000);
    $('#refresh').click(function(){
        $('#refresh-img').show();
        $('#refresh').removeClass("btn-primary").addClass("btn-success").html('Refreshing');
        dtable.ajax.reload(function(){console.log("Refresh Manual")}, false);
        setTimeout(function(){
            $('#refresh').removeClass("btn-success").addClass("btn-primary").html('<i class="fas fa-sync-alt"></i> Refresh Data');
            $('#refresh-data').text("Refresh Data");
            $('#refresh-img').hide();
        }, 2000);
    });
    
    var restore_id
    $(document).on('click', '.restore', function(){
		restore_id = $(this).attr('id');
		restore_nama = $(this).attr('nama');
		$('.titles').text('Restore ' + restore_nama + ' ?');
		$('#confirmRestore').modal('show');
	});

    $('#ya_button').click(function(){
        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
		$.ajax({       
			url: "/master/kasir/restore/" + restore_id,
            cache:false,
			method:"POST",
			dataType:"json",
			success:function(data)
			{
                $('#tabel').DataTable().ajax.reload(function(){}, false);
                var html = '';
				if(data.errors)
				{
                    html = '<div class="alert alert-danger" id="error-alert"> <strong>Maaf ! </strong>' + data.errors + '</div>';
				}
				if(data.success)
				{
					html = '<div class="alert alert-success" id="success-alert"> <strong>Sukses ! </strong>' + data.success + '</div>';
                }
				$('#form_result').html(html);
                $("#success-alert,#error-alert,#info-alert,#warning-alert")
                    .fadeTo(2000, 1000)
                    .slideUp(2000, function () {
                        $("#success-alert,#error-alert").slideUp(1000);
                });
                $('#confirmRestore').modal('hide');
            },
            error: function(data){
                alert('Oops! Kesalahan Sistem');
                location.reload();
            }
		});
    });

    $("#sebagian").prop('required',false);
            
    $('#sebagian').select2({
        placeholder: '--- Pilih Blok ---',
        ajax: {
            url: "/cari/blok",
            dataType: 'json',
            delay: 250,
            processResults: function (blok) {
                return {
                results:  $.map(blok, function (bl) {
                    return {
                    text: bl.nama,
                    id: bl.nama
                    }
                })
                };
            },
            cache: true
        }
    });

    $("#sisatagihan").change(function() {
        var val = $(this).val();
        if(val === "all") {
            $("#divrekapsisa").hide();
            $("#sebagian").prop('required',false);
            $('#sebagian').val('');
            $('#sebagian').html('');
        }
        else if(val === "sebagian") {
            $("#divrekapsisa").show();
            $("#sebagian").prop('required',true);
        }
    });
});
</script>
<script src="{{asset('js/master/kasir/kasir.js')}}"></script>
@endsection
<?php use App\Models\Tagihan; use Jenssegers\Agent\Agent; $agent = new Agent();?>

@extends('layout.master')

@section('title')
<title>Data Penghapusan | BP3C</title>
@endsection

@section('judul')
<h6 class="h2 text-red d-inline-block mb-0" style="background-color:#fff;border-radius:1rem;padding:0.275rem;">Data Penghapusan</h6>
@endsection

@section('button')
<a 
    href="{{url('tagihan')}}"
    data-toggle="tooltip" data-original-title="Home"
    class="btn btn-sm btn-success home-tagihan">
    <i class="fas fa-home text-white"></i>
</a>
@endsection

@section('content')
<span id="form_result"></span>
<div class="row">
    <div class="col-xl-12">
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="text-right">
                    <img src="{{asset('img/updating.gif')}}" style="display:none;" id="refresh-img"/><button class="btn btn-sm btn-primary" id="refresh"><i class="fas fa-sync-alt"></i> Refresh Data</button>
                </div>
                @if($agent->isDesktop())
                <div class="table-responsive py-4">
                    <table class="table table-flush" width="100%" id="tabelPenghapusan">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center" style="max-width:15%;">Tanggal</th>
                                <th class="text-center" style="max-width:15%;">Kontrol</th>
                                <th class="text-center" style="min-width:80px;max-width:20%;">Nama</th>
                                <th class="text-center">Listrik</th>
                                <th class="text-center">Air&nbsp;Bersih</th>
                                <th class="text-center">K.aman&nbsp;IPK</th>
                                <th class="text-center">Kebersihan</th>
                                <th class="text-center">Air&nbsp;Kotor</th>
                                <th class="text-center">Lainnya</th>
                                <th class="text-center">Jumlah</th>
                                <th class="text-center" style="max-width:10%">Action</th>
                                <th class="text-center" style="max-width:10%">Details</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                @else
                <div class="table-responsive py-4">
                    <table class="table table-flush" width="100%" id="tabelPenghapusan">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center" style="max-width:15%;">Tanggal</th>
                                <th class="text-center" style="max-width:50%;">Kontrol</th>
                                <th class="text-center" style="min-width:80px;max-width:20%;">Nama</th>
                                <th class="text-center" style="max-width:15%">Action</th>
                                <th class="text-center" style="max-width:15%">Details</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
<footer class="footer pt-0">
    <div class="row align-items-center justify-content-lg-between">
        <div class="col-lg-6">
            <div class="copyright text-center text-lg-left text-muted">
                &copy; 2020 <a href="#" class="font-weight-bold ml-1">PT Pengelola Pusat Perdagangan Caringin</a>
            </div>
        </div>
    </div>
</footer>
@endsection

@section('modal')
<div id="confirmModal" class="modal fade" role="dialog" tabIndex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title titles">Restore Tagihan ?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Pilih <b>Submit</b> di bawah ini jika anda yakin untuk melakukan restorasi.</div>
            <div class="modal-footer">
            	<button type="button" name="ok_button" id="ok_button" class="btn btn-danger">Submit</button>
                <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="total-details" tabindex="-1" role="dialog" aria-labelledby="show-details" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="card bg-secondary border-0 mb-0">
                    <div class="card-header bg-gradient-vine">
                        <h1 class="text-white text-center kontrol" style="font-weight:700"></h1>
                        <h2 class="text-white text-center fasilitas" style="font-weight:700"></h2>
                        <h2 class="text-white text-center periode" style="font-weight:700"></h2>
                    </div>
                    <div class="card-body px-lg-3 py-lg-3">
                        <div class="card-body modal-body-short">
                            <div class="card-profile-stats d-flex justify-content-center">
                                <div class="col">
                                    <div class="divListrik">
                                        <span class="heading">Listrik</span>
                                        <div class="row">
                                            <div class="col">
                                                <div class="card-profile-stats d-flex justify-content-between">
                                                    <div>
                                                        <span class="description">Daya</span>
                                                        <span class="heading daya-listrik">&mdash;</span>
                                                    </div>
                                                    <div>
                                                        <span class="description">Awal</span>
                                                        <span class="heading awal-listrik">&mdash;</span>
                                                    </div>
                                                    <div>
                                                        <span class="description">Akhir</span>
                                                        <span class="heading akhir-listrik">&mdash;</span>
                                                    </div>
                                                    <div>
                                                        <span class="description">Pakai</span>
                                                        <span class="heading pakai-listrik">&mdash;</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="card-profile-stats d-flex justify-content-between">
                                                    <div>
                                                        <span class="description">Disc</span>
                                                        <span class="heading diskon-listrik">&mdash;</span>
                                                    </div>
                                                    <div>
                                                        <span class="description">Tagihan</span>
                                                        <span class="heading tagihan-listrik">&mdash;</span>
                                                    </div>
                                                    <div>
                                                        <span class="description">Denda</span>
                                                        <span class="heading denda-listrik">&mdash;</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                    </div>
                                    <div class="divAirBersih">
                                        <span class="heading">Air Bersih</span>
                                        <div class="row">
                                            <div class="col">
                                                <div class="card-profile-stats d-flex justify-content-between">
                                                    <div>
                                                        <span class="description">Awal</span>
                                                        <span class="heading awal-airbersih">&mdash;</span>
                                                    </div>
                                                    <div>
                                                        <span class="description">Akhir</span>
                                                        <span class="heading akhir-airbersih">&mdash;</span>
                                                    </div>
                                                    <div>
                                                        <span class="description">Pakai</span>
                                                        <span class="heading pakai-airbersih">&mdash;</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="card-profile-stats d-flex justify-content-between">
                                                    <div>
                                                        <span class="description">Disc</span>
                                                        <span class="heading diskon-airbersih">&mdash;</span>
                                                    </div>
                                                    <div>
                                                        <span class="description">Tagihan</span>
                                                        <span class="heading tagihan-airbersih">&mdash;</span>
                                                    </div>
                                                    <div>
                                                        <span class="description">Denda</span>
                                                        <span class="heading denda-airbersih">&mdash;</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                    </div>
                                    <div class="divKeamananIpk">
                                        <span class="heading">Keamanan IPK</span>
                                        <div class="row">
                                            <div class="col">
                                                <div class="card-profile-stats d-flex justify-content-between">
                                                    <div>
                                                        <span class="description">Disc</span>
                                                        <span class="heading diskon-keamananipk">&mdash;</span>
                                                    </div>
                                                    <div>
                                                        <span class="description">Tagihan</span>
                                                        <span class="heading tagihan-keamananipk">&mdash;</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                    </div>
                                    <div class="divKebersihan">
                                        <span class="heading">Kebersihan</span>
                                        <div class="row">
                                            <div class="col">
                                                <div class="card-profile-stats d-flex justify-content-between">
                                                    <div>
                                                        <span class="description">Disc</span>
                                                        <span class="heading diskon-kebersihan">&mdash;</span>
                                                    </div>
                                                    <div>
                                                        <span class="description">Tagihan</span>
                                                        <span class="heading tagihan-kebersihan">&mdash;</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                    </div>
                                    <div class="divAirKotor">
                                        <span class="heading">Air Kotor</span>
                                        <div>
                                            <span class="description">Tagihan</span>
                                            <span class="heading tagihan-airkotor">&mdash;</span>
                                        </div>
                                        <hr>
                                    </div>
                                    <div class="divLain">
                                        <span class="heading">Lain - Lain</span>
                                        <div>
                                            <span class="description">Tagihan</span>
                                            <span class="heading tagihan-lain">&mdash;</span>
                                        </div>
                                        <hr>
                                    </div>
                                    <div class="divTagihan">
                                        <span class="heading">Jumlah Tagihan</span>
                                        <div class="row">
                                            <div class="col">
                                                <div class="card-profile-stats d-flex justify-content-between">
                                                    <div>
                                                        <span class="description">Listrik</span>
                                                        <span class="heading tagihan-listrik">&mdash;</span>
                                                    </div>
                                                    <div>
                                                        <span class="description">Keamanan IPK</span>
                                                        <span class="heading tagihan-keamananipk">&mdash;</span>
                                                    </div>
                                                    <div>
                                                        <span class="description">Air Kotor</span>
                                                        <span class="heading tagihan-airkotor">&mdash;</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="card-profile-stats d-flex justify-content-between">
                                                    <div>
                                                        <span class="description">Air Bersih</span>
                                                        <span class="heading tagihan-airbersih">&mdash;</span>
                                                    </div>
                                                    <div>
                                                        <span class="description">Kebersihan</span>
                                                        <span class="heading tagihan-kebersihan">&mdash;</span>
                                                    </div>
                                                    <div>
                                                        <span class="description">Lainnya</span>
                                                        <span class="heading tagihan-lain">&mdash;</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="card-profile-stats d-flex justify-content-between">
                                                    <div>
                                                        <span class="description">Diskon</span>
                                                        <span class="heading tagihan-diskon">&mdash;</span>
                                                    </div>
                                                    <div>
                                                        <span class="description">Denda</span>
                                                        <span class="heading tagihan-denda">&mdash;</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                    </div>
                                    <div>
                                        <span class="description">Pengguna</span>
                                        <span class="heading pengguna"></span>
                                    </div>
                                    <div>
                                        <span class="description">Nomor Los</span>
                                        <span class="heading los"></span>
                                    </div>
                                    <div>
                                        <span class="description">Jumlah Los</span>
                                        <span class="heading jumlah"></span>
                                    </div>
                                    <div>
                                        <span class="description">Lokasi</span>
                                        <span class="heading lokasi" style="font-size:.875rem"></span>
                                    </div>
                                    <div>
                                        <span class="description pembayaran-heading">Pembayaran</span>
                                        <span class="heading pembayaran"></span>
                                    </div>
                                    <div>
                                        <span class="description">Status</span>
                                        <span class="heading status"></span>
                                    </div>
                                    <div>
                                        <span class="description">Terakhir Dipublish Oleh</span>
                                        <span class="heading publisher"></span>
                                    </div>
                                    <div>
                                        <span class="description">Terakhir Dikelola Oleh</span>
                                        <span class="heading pengelola"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="button" class="btn btn-white" data-dismiss="modal">&times; Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>

$(document).ready(function(){
    <?php if($agent->isDesktop()){ ?>
    var dtable = $('#tabelPenghapusan').DataTable({
		serverSide: true,
		ajax: {
			url: "/tagihan/penghapusan",
            cache:false,
		},
		columns: [
            { data: 'tgl_hapus'      , name: 'tgl_hapus'      , class : 'text-center' },
            { data: 'kd_kontrol'     , name: 'kd_kontrol'     , class : 'text-center' },
            { data: 'nama'           , name: 'nama'           , class : 'text-center-td' },
            { data: 'ttl_listrik'    , name: 'ttl_listrik'    , class : 'text-center background-gray' },
            { data: 'ttl_airbersih'  , name: 'ttl_airbersih'  , class : 'text-center background-gray' },
            { data: 'ttl_keamananipk', name: 'ttl_keamananipk', class : 'text-center background-gray' },
            { data: 'ttl_kebersihan' , name: 'ttl_kebersihan' , class : 'text-center background-gray' },
            { data: 'ttl_airkotor'   , name: 'ttl_airkotor'   , class : 'text-center background-gray' },
            { data: 'ttl_lain'       , name: 'ttl_lain'       , class : 'text-center background-gray' },
            { data: 'ttl_tagihan'    , name: 'ttl_tagihan'    , class : 'text-center' },
            { data: 'action'         , name: 'action'         , class : 'text-center' },
            { data: 'show'           , name: 'show'           , class : 'text-center' },
        ],
        order: [[ 1, "asc" ]],
        stateSave: true,
        deferRender: true,
        aLengthMenu: [[5,10,25,50,100,-1], [5,10,25,50,100,"All"]],
        language: {
            paginate: {
                previous: "<i class='fas fa-angle-left'>",
                next: "<i class='fas fa-angle-right'>"
            }
        },
        aoColumnDefs: [
            { "bSortable": false, "aTargets": [10,11] }, 
            { "bSearchable": false, "aTargets": [10,11] }
        ],
        scrollX: true,
        scrollY: "50vh",
        pageLength: 5,
        fixedColumns:   {
            "leftColumns": 3,
            "rightColumns": 3,
        },
        preDrawCallback: function( settings ) {
            scrollPosition = $(".dataTables_scrollBody").scrollTop();
        },
        drawCallback: function( settings ) {
            $(".dataTables_scrollBody").scrollTop(scrollPosition);
            if(typeof rowIndex != 'undefined') {
                dtable.row(rowIndex).nodes().to$().addClass('row_selected');                       
            }
            setTimeout( function () {
                $("[data-toggle='tooltip']").tooltip();
            }, 10)
        },
    }).columns.adjust().draw();
    <?php } ?>
    
    <?php if($agent->isDesktop() == false){ ?>
    var dtable = $('#tabelPenghapusan').DataTable({
		serverSide: true,
		ajax: {
			url: "/tagihan/penghapusan",
            cache:false,
		},
		columns: [
            { data: 'tgl_hapus'      , name: 'tgl_hapus'      , class : 'text-center' },
            { data: 'kd_kontrol'     , name: 'kd_kontrol'     , class : 'text-center' },
            { data: 'nama'           , name: 'nama'           , class : 'text-center-td' },
            { data: 'action'         , name: 'action'         , class : 'text-center' },
            { data: 'show'           , name: 'show'           , class : 'text-center' },
        ],
        order: [[ 1, "asc" ]],
        stateSave: true,
        deferRender: true,
        aLengthMenu: [[5,10,25,50,100,-1], [5,10,25,50,100,"All"]],
        language: {
            paginate: {
                previous: "<i class='fas fa-angle-left'>",
                next: "<i class='fas fa-angle-right'>"
            }
        },
        aoColumnDefs: [
            { "bSortable": false, "aTargets": [3,4] }, 
            { "bSearchable": false, "aTargets": [3,4] }
        ],
        responsive: true,
        scrollY: "50vh",
        pageLength: 5,
        preDrawCallback: function( settings ) {
            scrollPosition = $(".dataTables_scrollBody").scrollTop();
        },
        drawCallback: function( settings ) {
            $(".dataTables_scrollBody").scrollTop(scrollPosition);
            if(typeof rowIndex != 'undefined') {
                dtable.row(rowIndex).nodes().to$().addClass('row_selected');                       
            }
            setTimeout( function () {
                $("[data-toggle='tooltip']").tooltip();
            }, 10)
        },
    }).columns.adjust().draw();
    <?php } ?>

    var id_tagihan = '';
    $(document).on('click', '.restore', function(){
		id_tagihan = $(this).attr('id');
		username = $(this).attr('nama');
		$('.titles').text('Restore Tagihan ' + username);
        $("#confirmModal").modal("show");
	});
    
    $('#ok_button').click(function(){
        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
		$.ajax({
			url:"/tagihan/penghapusan/" + id_tagihan,
            cache:false,
			method:"POST",
			data:$(this).serialize(),
			dataType:"json",
			beforeSend:function(){
				$('#ok_button').text('Restorasi...');
			},
			success:function(data)
			{
                $('#tabelPenghapusan').DataTable().ajax.reload(function(){}, false);
                if(data.success)
                    html = '<div class="alert alert-success" id="success-alert"> <strong>Sukses! </strong>' + data.success + '</div>';
                if(data.errors)
                    html = '<div class="alert alert-danger" id="error-alert"> <strong>Oops! </strong>' + data.errors + '</div>';
                $('#form_result').html(html);     
                $("#success-alert,#error-alert,#info-alert,#warning-alert")
                    .fadeTo(1000, 500)
                    .slideUp(1000, function () {
                        $("#success-alert,#error-alert").slideUp(500);
                });
                $('#confirmModal').modal('hide');
            },
            complete:function(){
                $('#ok_button').text('Submit');
            }
        })
    });

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
});
</script>
<script src="{{asset('js/tagihan/penghapusan.min.js')}}"></script>
@endsection
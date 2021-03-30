<?php use Jenssegers\Agent\Agent; $agent = new Agent();?>
@extends('layout.master')

@section('title')
<title>Data Tagihan | BP3C</title>
@endsection

@section('judul')
<h6 class="h2 text-white d-inline-block mb-0">Tagihan {{$periode}}</h6>
@endsection

@section('button')
@if($agent->isDesktop())
<a 
    href="{{url('tagihan')}}"
    class="btn btn-sm btn-success">
    <i class="fas fa-home text-white"></i>
</a>
@if(Session::get('role') == 'master' || Session::get('role') == 'admin' && (Session::get('otoritas')->tagihan))
<button 
    name="add_listrik"
    id="add_listrik" 
    class="btn btn-sm btn-warning">
    <i class="fas fa-plus text-white-50"></i>Listrik<span class="badge badge-pill badge-light badge-listrik"></span>
</button>
<button 
    name="add_air"
    id="add_air" 
    class="btn btn-sm btn-info">
    <i class="fas fa-plus text-white-50"></i>Air Bersih<span class="badge badge-pill badge-light badge-air"></span>
</button>
@endif
@endif
<a class="dropdown-toggle btn btn-sm btn-danger" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Menu</a>
<div class="dropdown-menu dropdown-menu-right">
    @if($agent->isMobile())
    <a 
    href="{{url('tagihan')}}"
    class="dropdown-item">
    <i class="fas fa-home text-gray"></i><span>Home</span>
</a>
    @if(Session::get('role') == 'master' || Session::get('role') == 'admin' && (Session::get('otoritas')->tagihan))
    <button class="dropdown-item" name="add_listrik" id="add_listrik"><i class="fas fa-plus text-gray"></i><span>Tambah Listrik</span><span class="badge badge-pill badge-warning badge-listrik"></span></button>
    <button class="dropdown-item" name="add_air" id="add_air"><i class="fas fa-plus text-gray"></i><span>Tambah Air</span><span class="badge badge-pill badge-primary badge-air"></span></button>
    <div class="dropdown-divider"></div>
    @endif
    @endif

    @if(Session::get('role') == 'master' || Session::get('role') == 'admin' && (Session::get('otoritas')->tagihan))
    <button
        class="dropdown-item" 
        id="sinkronisasi">
        <i class="fas fa-sync text-gray"></i><span id="sinkronisasi-data"></span>
    </button>
    <a 
        class="dropdown-item" 
        id="tambah_manual"
        href="#" 
        data-toggle="modal" 
        data-target="#myManual"
        type="button">
        <i class="fas fa-plus text-gray"></i><span>Manual</span>
    </a>
    <div class="dropdown-divider"></div>
    @endif
</div>
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
                    <table class="table table-flush" width="100%" id="tabelTagihan">
                        <thead class="thead-light">
                            <tr>
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
                    <table class="table table-flush" width="100%" id="tabelTagihan">
                        <thead class="thead-light">
                            <tr>
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
@endsection

@section('modal')
<div id="syncModal" class="modal fade" role="dialog" tabIndex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title titles"></h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="form_sync" method="POST">
                @csrf
                <div class="modal-body"><span id="sync-notif"></span></div>
                <div class="modal-footer">
                    <input type="hidden" name="sync_status" id="sync_status"/>
                    <input type="submit" name="sync_button" id="sync_button" class="btn btn-primary" value="Submit" />
                    <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
$(document).ready(function(){
    <?php if($agent->isDesktop()){ ?>
    var dtable = $('#tabelTagihan').DataTable({
		serverSide: true,
		ajax: {
			url: "/tagihan/?periode=" + <?php echo Session::get('periodetagihan')?>,
            cache:false,
		},
		columns: [
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
        order: [[ 0, "asc" ]],
        stateSave: true,
        deferRender: true,
        aLengthMenu: [[10,25,50,100,-1], [10,25,50,100,"All"]],
        language: {
            paginate: {
                previous: "<i class='fas fa-angle-left'>",
                next: "<i class='fas fa-angle-right'>"
            }
        },
        aoColumnDefs: [
            { "bSortable": false, "aTargets": [9,10] }, 
            { "bSearchable": false, "aTargets": [9,10] }
        ],
        scrollX: true,
        scrollY: "50vh",
        fixedColumns:   {
            "leftColumns": 2,
            "rightColumns": 3,
        },
        "preDrawCallback": function( settings ) {
            scrollPosition = $(".dataTables_scrollBody").scrollTop();
        },
        "drawCallback": function( settings ) {
            $(".dataTables_scrollBody").scrollTop(scrollPosition);
            if(typeof rowIndex != 'undefined') {
                dtable.row(rowIndex).nodes().to$().addClass('row_selected');                       
            }
        },
    }).columns.adjust().draw();
    <?php } ?>
    
    <?php if($agent->isDesktop() == false){ ?>
    var dtable = $('#tabelTagihan').DataTable({
		serverSide: true,
		ajax: {
			url: "/tagihan/?periode=" + <?php echo Session::get('periodetagihan')?>,
            cache:false,
		},
		columns: [
            { data: 'kd_kontrol'     , name: 'kd_kontrol'     , class : 'text-center' },
            { data: 'nama'           , name: 'nama'           , class : 'text-center-td' },
            { data: 'action'         , name: 'action'         , class : 'text-center' },
            { data: 'show'           , name: 'show'           , class : 'text-center' },
        ],
        order: [[ 0, "asc" ]],
        stateSave: true,
        deferRender: true,
        aLengthMenu: [[10,25,50,100,-1], [10,25,50,100,"All"]],
        language: {
            paginate: {
                previous: "<i class='fas fa-angle-left'>",
                next: "<i class='fas fa-angle-right'>"
            }
        },
        aoColumnDefs: [
            { "bSortable": false, "aTargets": [2,3] }, 
            { "bSearchable": false, "aTargets": [2,3] }
        ],
        responsive: true,
        "preDrawCallback": function( settings ) {
            scrollPosition = $(".dataTables_scrollBody").scrollTop();
        },
        "drawCallback": function( settings ) {
            $(".dataTables_scrollBody").scrollTop(scrollPosition);
            if(typeof rowIndex != 'undefined') {
                dtable.row(rowIndex).nodes().to$().addClass('row_selected');                       
            }
        },
    }).columns.adjust().draw();
    <?php } ?>

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
<script src="{{asset('js/tagihan/tagihan.js')}}"></script>
@endsection
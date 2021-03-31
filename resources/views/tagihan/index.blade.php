<?php use App\Models\Tagihan; use Jenssegers\Agent\Agent; $agent = new Agent();?>
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
    <a type="button" href="{{url('tagihan')}}" class="dropdown-item"><i class="fas fa-fw fa-home text-gray"></i><span>Home</span></a>
    @if(Session::get('role') == 'master' || Session::get('role') == 'admin' && (Session::get('otoritas')->tagihan))
    <button class="dropdown-item" name="add_listrik" id="add_listrik"><i class="fas fa-fw fa-plus text-gray"></i><span>Tambah Listrik</span><span class="badge badge-pill badge-warning badge-listrik"></span></button>
    <button class="dropdown-item" name="add_air" id="add_air"><i class="fas fa-fw fa-plus text-gray"></i><span>Tambah Air</span><span class="badge badge-pill badge-primary badge-air"></span></button>
    <div class="dropdown-divider"></div>
    @endif
    @endif

    @if(Session::get('role') == 'master' || Session::get('role') == 'admin' && (Session::get('otoritas')->tagihan))
    <button class="dropdown-item" id="sinkronisasi"><i class="fas fa-fw fa-sync text-gray"></i><span id="sinkronisasi-data"></span></button>
    <a class="dropdown-item" id="tambah_manual" href="#" data-toggle="modal" data-target="#myManual" type="button"><i class="fas fa-fw fa-plus text-gray"></i><span>Manual</span></a>
    <a type="button" href="{{url('tagihan/penghapusan')}}" class="dropdown-item"><i class="fas fa-fw fa-eraser text-gray"></i><span>Penghapusan</span></a>
    <div class="dropdown-divider"></div>
    @endif
    
    @if(Session::get('role') == 'master' || Session::get('role') == 'admin' && (Session::get('otoritas')->publish))
    <a class="dropdown-item publish" id="publish" href="#" data-toggle="modal" data-target="#publishModal" type="button"><i class="fas fa-fw fa-paper-plane text-gray"></i><span>Publish Tagihan</span></a>
    <a class="dropdown-item cancel-publish" id="cancel-publish" href="#" data-toggle="modal" data-target="#publishModal" type="button"><i class="far fa-fw fa-paper-plane text-gray"></i><span>Cancel Publish</span></a>
    <div class="dropdown-divider"></div>
    @endif

    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#myModal" type="button"><i class="fas fa-fw fa-search text-gray"></i><span>Cari Periode</span></a>
    <a type="button" class="dropdown-item" href="#" data-toggle="modal" data-target="#myTempat"><i class="far fa-fw fa-file text-gray"></i><span>Form Tagihan</span></a>
    <a type="button" class="dropdown-item" href="{{url('tagihan/print')}}" target="_blank"><i class="far fa-fw fa-file text-gray"></i><span>Form Pendataan</span></a>
    <a type="button" class="dropdown-item" href="#" data-toggle="modal" data-target="#myPemberitahuan"><i class="fas fa-fw fa-exclamation text-gray"></i><span>Pemberitahuan</span></a>
    
    @if(Session::get('role') == 'master')
    <a type="button" class="dropdown-item" href="#" data-toggle="modal" data-target="#myPembayaran"><i class="fas fa-fw fa-dollar-sign text-gray"></i><span>Pembayaran</span></a>
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
                <div class="modal-body">
                    <div class="form-group text-center">
                        <span id="sync-notif"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="sync_status" id="sync_status"/>
                    <input type="submit" name="sync_button" id="sync_button" class="btn btn-primary" value="Submit" />
                    <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="confirmModal" class="modal fade" role="dialog" tabIndex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title titles">Apakah yakin hapus data tagihan?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="form_destroy">
                @csrf
                <div class="modal-body">Pilih Data Tagihan yang ingin dihapus, jika sudah yakin Klik "Hapus".<br><br>
                    <div class="col-lg-12 justify-content-between" style="display:flex;flex-wrap:wrap;">
                        <div>
                            <div>
                                <input
                                    type="checkbox"
                                    name="checkListrik"
                                    id="checkListrik"
                                    value="listrik">
                                <label class="form-control-label" for="checkListrik">
                                    Listrik
                                </label>
                            </div>
                            <div>
                                <input
                                    type="checkbox"
                                    name="checkAirBersih"
                                    id="checkAirBersih"
                                    value="airbersih">
                                <label class="form-control-label" for="checkAirBersih">
                                    Air Bersih
                                </label>
                            </div>
                        </div>
                        <div>
                            <div>
                                <input
                                    type="checkbox"
                                    name="checkKeamananIpk"
                                    id="checkKeamananIpk"
                                    value="keamananipk">
                                <label class="form-control-label" for="checkKeamananIpk">
                                    Keamanan IPK
                                </label>
                            </div>
                            <div>
                                <input
                                    type="checkbox"
                                    name="checkKebersihan"
                                    id="checkKebersihan"
                                    value="kebersihan">
                                <label class="form-control-label" for="checkKebersihan">
                                    Kebersihan
                                </label>
                            </div>
                        </div>
                        <div>
                            <div>
                                <input
                                    type="checkbox"
                                    name="checkAirKotor"
                                    id="checkAirKotor"
                                    value="airkotor">
                                <label class="form-control-label" for="checkAirKotor">
                                    Air Kotor
                                </label>
                            </div>
                            <div>
                                <input
                                    type="checkbox"
                                    name="checkLain"
                                    id="checkLain"
                                    value="lain">
                                <label class="form-control-label" for="checkLain">
                                    Lain Lain
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" name="ok_button" id="ok_button" class="btn btn-danger" value="Hapus" />
                    <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="myModal" class="modal fade" role="dialog" tabIndex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cari Periode Tagihan</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="{{url('tagihan/periode')}}" method="GET">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-control-label" for="bulan">Bulan</label>
                        <select class="form-control" name="bulan" id="bulan" required>
                            <option value="01">Januari</option>
                            <option value="02">Februari</option>
                            <option value="03">Maret</option>
                            <option value="04">April</option>
                            <option value="05">Mei</option>
                            <option value="06">Juni</option>
                            <option value="07">Juli</option>
                            <option value="08">Agustus</option>
                            <option value="09">September</option>
                            <option value="10">Oktober</option>
                            <option value="11">November</option>
                            <option value="12">Desember</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-control-label" for="tahun">Tahun</label>
                        <select class="form-control" name="tahun" id="tahun" required>
                            <?php $tahun = Tagihan::select('thn_tagihan')->groupBy('thn_tagihan')->orderBy('thn_tagihan','desc')->get();?>
                            @foreach($tahun as $t)
                            <option value="{{$t->thn_tagihan}}">{{$t->thn_tagihan}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" name="periode_button" id="periode_button" class="btn btn-primary" value="Cari" />
                    <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="notifModal" class="modal fade" role="dialog" tabIndex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title titles"></h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="form_notif">
                @csrf
                <div class="modal-body">Tandai Data Tagihan yang ingin diberikan notifikasi untuk dilakukan pengecekan<br><br>
                    <div class="col-lg-12 justify-content-between" style="display:flex;flex-wrap:wrap;">
                        <div>
                            <div>
                                <input
                                    type="checkbox"
                                    name="notifListrik"
                                    id="notifListrik"
                                    value="listrik">
                                <label class="form-control-label" for="notifListrik">
                                    Listrik
                                </label>
                            </div>
                            <div>
                                <input
                                    type="checkbox"
                                    name="notifAirBersih"
                                    id="notifAirBersih"
                                    value="airbersih">
                                <label class="form-control-label" for="notifAirBersih">
                                    Air Bersih
                                </label>
                            </div>
                        </div>
                        <div>
                            <div>
                                <input
                                    type="checkbox"
                                    name="notifKeamananIpk"
                                    id="notifKeamananIpk"
                                    value="keamananipk">
                                <label class="form-control-label" for="notifKeamananIpk">
                                    Keamanan IPK
                                </label>
                            </div>
                            <div>
                                <input
                                    type="checkbox"
                                    name="notifKebersihan"
                                    id="notifKebersihan"
                                    value="kebersihan">
                                <label class="form-control-label" for="notifKebersihan">
                                    Kebersihan
                                </label>
                            </div>
                        </div>
                        <div>
                            <div>
                                <input
                                    type="checkbox"
                                    name="notifAirKotor"
                                    id="notifAirKotor"
                                    value="airkotor">
                                <label class="form-control-label" for="notifAirKotor">
                                    Air Kotor
                                </label>
                            </div>
                            <div>
                                <input
                                    type="checkbox"
                                    name="notifLain"
                                    id="notifLain"
                                    value="lain">
                                <label class="form-control-label" for="notifLain">
                                    Lain Lain
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" name="notif_button" id="notif_button" class="btn btn-primary" value="Submit" />
                    <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="publishModal" class="modal fade" role="dialog" tabIndex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title titles"></h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="form_publish">
                @csrf
                <div class="modal-body">
                    <div class="form-group text-center">
                        <span id="publish_text"></span>
                    </div>
                    <div class="form-group">
                        <label class="form-control-label" for="publish_bulan">Bulan</label>
                        <select class="form-control" name="publish_bulan" id="publish_bulan" required>
                            <option value="01">Januari</option>
                            <option value="02">Februari</option>
                            <option value="03">Maret</option>
                            <option value="04">April</option>
                            <option value="05">Mei</option>
                            <option value="06">Juni</option>
                            <option value="07">Juli</option>
                            <option value="08">Agustus</option>
                            <option value="09">September</option>
                            <option value="10">Oktober</option>
                            <option value="11">November</option>
                            <option value="12">Desember</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-control-label" for="publish_tahun">Tahun</label>
                        <select class="form-control" name="publish_tahun" id="publish_tahun" required>
                            <?php $tahun = Tagihan::select('thn_tagihan')->groupBy('thn_tagihan')->orderBy('thn_tagihan','desc')->get();?>
                            @foreach($tahun as $t)
                            <option value="{{$t->thn_tagihan}}">{{$t->thn_tagihan}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="publish_action" id="publish_action" />
                    <input type="submit" name="publish_button" id="publish_button" class="btn btn-primary" value="Submit" />
                    <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="myTempat" class="modal fade" role="dialog" tabIndex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cetak Form Tagihan Tempat Usaha</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="{{url('tagihan/tempat')}}" method="GET" target="_blank">
                <div class="modal-body">
                    <div class="form-group text-center">
                        <span>Silahkan Pilih Fasilitas yang digunakan Tempat Usaha dibawah ini. Jika setuju klik <b>Submit</b>.</span>
                    </div>
                    <div class="form-group row col-lg-12">
                        <div class="col-sm-12">
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="tempat"
                                    value="listrik"
                                    id="listriktempat">
                                <label class="form-control-label" for="listriktempat">
                                    Pengguna Fasilitas Listrik
                                </label>
                            </div>
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="tempat"
                                    id="airbersihtempat"
                                    value="airbersih">
                                <label class="form-control-label" for="airbersihtempat">
                                    Pengguna Fasilitas Air Bersih
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-primary" value="Submit"/>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="myPemberitahuan" class="modal fade" role="dialog" tabIndex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cetak Pemberitahuan</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="cetakPemberitahuan" action="{{url('tagihan/pemberitahuan')}}" method="POST" target="_blank">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-control-label" for="blokPemberitahuan">Pilih Blok</label>
                        <select class="form-control" name="blokPemberitahuan" id="blokPemberitahuan" required>
                            @foreach($blok as $b)
                            <option value="{{$b->nama}}">{{$b->nama}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-primary" value="Cetak" />
                </div>
            </form>
        </div>
    </div>
</div>

<div id="myPembayaran" class="modal fade" role="dialog" tabIndex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cetak Pembayaran</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="cetakPembayaran" action="{{url('tagihan/pembayaran')}}" method="POST" target="_blank">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-control-label" for="blokPembayaran">Pilih Blok</label>
                        <select class="form-control" name="blokPembayaran" id="blokPembayaran" required>
                            @foreach($blok as $b)
                            <option value="{{$b->nama}}">{{$b->nama}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-primary" value="Cetak" />
                </div>
            </form>
        </div>
    </div>
</div>

<div id="tagihanku" class="modal fade" role="dialog" tabIndex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title titles"></h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="form_tagihanku" action="" method="GET">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-control-label" for="tagihan_blok">Pilih Blok</label>
                        <select class="form-control" name="tagihan_blok" id="tagihan_blok" required>
                            @foreach($blok as $b)
                            <option value="{{$b->nama}}">{{$b->nama}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-primary" value="Submit" />
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

    var id_tagihan;
    $(document).on('click', '.delete', function(){
		id_tagihan = $(this).attr('id');
		username = $(this).attr('nama');
		$('.titles').text('Hapus data ' + username + ' ?');
        $('#checkListrik').prop("disabled",false);
        $('#checkAirBersih').prop("disabled",false);
        $('#checkKeamananIpk').prop("disabled",false);
        $('#checkKebersihan').prop("disabled",false);
        $('#checkAirKotor').prop("disabled",false);
        $('#checkLain').prop("disabled",false);
        $('#checkListrik').prop("checked",false);
        $('#checkAirBersih').prop("checked",false);
        $('#checkKeamananIpk').prop("checked",false);
        $('#checkKebersihan').prop("checked",false);
        $('#checkAirKotor').prop("checked",false);
        $('#checkLain').prop("checked",false);
        $.ajax({
			url:"/tagihan/destroy/edit/"+id_tagihan,
            cache:false,
            method:"get",
			dataType:"json",
			success:function(data)
			{
                if(data.result.stt_listrik === null)
                    $('#checkListrik').prop("disabled",true);

                if(data.result.stt_airbersih === null)
                    $('#checkAirBersih').prop("disabled",true);
                
                if(data.result.stt_keamananipk === null)
                    $('#checkKeamananIpk').prop("disabled",true);
                    
                if(data.result.stt_kebersihan === null)
                    $('#checkKebersihan').prop("disabled",true);
                    
                if(data.result.stt_airkotor === null)
                    $('#checkAirKotor').prop("disabled",true);
                
                if(data.result.stt_lain === null)
                    $('#checkLain').prop("disabled",true);
            }
        })
		$('#confirmModal').modal('show');
	});

    $('#form_destroy').on('submit',function(e){
        e.preventDefault();
		$.ajax({
			url:"/tagihan/destroy/"+id_tagihan,
            cache:false,
            method:"POST",
			data:$(this).serialize(),
			dataType:"json",
			beforeSend:function(){
				$('#ok_button').text('Menghapus...');
			},
			success:function(data)
			{
                $("#tabelTagihan").DataTable().ajax.reload(function(){}, false);
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
                $('#ok_button').text('Hapus');
            }
        })
    });

    $(document).on('click', '.unpublish', function(){
        id = $(this).attr('id');
        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
		$.ajax({
			url :"/tagihan/unpublish/"+id,
            cache:false,
			method:"POST",
			dataType:"json",
			success:function(data)
			{
                if(data.errors){
                    alert(data.errors);
                }

                if(data.unsuccess){
                    alert(data.unsuccess);
                }

                if(data.success){
                    $('#tabelTagihan').DataTable().ajax.reload(function(){}, false);
                }
            }
        });
    });
});
</script>
<script src="{{asset('js/tagihan/tagihan.js')}}"></script>
@endsection
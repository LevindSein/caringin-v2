<?php use App\Models\Tagihan; ?>
@extends('layout.master')

@section('title')
<title>Data Tagihan | BP3C</title>
@endsection

@section('judul')
<h6 class="h2 text-white d-inline-block mb-0">Data Tagihan {{$bulan}}</h6>
@endsection

@section('button')
<a class="dropdown-toggle btn btn-sm btn-danger" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Menu</a>
<div class="dropdown-menu dropdown-menu-right">
    <a type="button" class="dropdown-item" href="#" data-toggle="modal" data-target="#myPenerimaan"><i class="fad fa-fw fa-hand-receiving text-success"></i><span>Penerimaan</span></a>
    <a type="button" class="dropdown-item" href="#" data-toggle="modal" data-target="#mySisa"><i class="fad fa-fw fa-file-times text-primary"></i><span>Rekap Sisa</span></a>
    <a type="button" class="dropdown-item" href="#" data-toggle="modal" data-target="#mySelesai"><i class="fad fa-fw fa-file-check text-info"></i><span>Rekap Akhir Bulan</span></a>
    @if(Session::get('opsional') && Session::get('otoritas')->kepala_kasir)
    <div class="dropdown-divider"></div>
    <a type="button" class="dropdown-item" href="#" data-toggle="modal" data-target="#myUtama"><i class="fas fa-fw fa-book text-pink"></i><span>Pendapatan Harian</span></a>
    <a type="button" class="dropdown-item" href="#" data-toggle="modal" data-target="#myUtamaBulan"><i class="fas fa-fw fa-books text-danger"></i><span>Pendapatan Bulanan</span></a>
    @endif
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="nav-wrapper">
                    <ul class="nav nav-pills flex-column flex-md-row" id="tabs-icons-text" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0 active" id="tab-c-0" data-toggle="tab" href="#tab-animated-0" role="tab"><i class="fas fa-dollar-sign"></i> <span>Tagihan&nbsp;Utama</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0" id="tab-c-1" data-toggle="tab" href="#tab-animated-1" role="tab"><i class="fas fa-undo"></i> <span>Restore&nbsp;Pembayaran</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0" id="tab-c-2" data-toggle="tab" href="#tab-animated-2" role="tab"><i class="fas fa-file"></i> <span>Struk&nbsp;Tagihan</span></a>
                        </li>
                    </ul>
                </div>
                <div class="form-group text-right">
                    <button id="workasir" class="btn btn-sm">Status</button>
                    <img src="{{asset('img/updating.gif')}}" style="display:none;" id="refresh-img"/><button class="btn btn-sm btn-primary" id="refresh"><i class="fas fa-sync-alt"></i> Refresh Data</button>
                </div>
                <span id="form_result"></span>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="tab-animated-0" role="tabpanel">
                        @include('kasir.bulanan.tagihan')
                    </div>
                    <div class="tab-pane fade" id="tab-animated-1" role="tabpanel">
                        @include('kasir.bulanan.restore')
                    </div>
                    <div class="tab-pane fade" id="tab-animated-2" role="tabpanel">
                        @include('kasir.bulanan.struk')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('home.footer')
@endsection

@section('modal')
<div id="confirmModal" class="modal fade" role="dialog" tabIndex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title titles"></h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Pilih <b>OK</b> di bawah ini jika anda yakin untuk melakukan restorasi.</div>
            <div class="modal-footer">
            	<button type="button" name="ok_button" id="ok_button" class="btn btn-primary">OK</button>
                <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>

<div
    class="modal fade"
    id="myPenerimaan"
    tabIndex="-1"
    role="dialog"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cetak Penerimaan</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form class="user" action="{{url('kasir/penerimaan')}}" target="_blank" method="GET">
                <div class="modal-body">
                    <div class="form-group col-lg-12">
                        <label class="form-control-label" for="tanggal">Pilih Tanggal Penerimaan</label>
                        <input
                            required
                            class="form-control" 
                            type="date"
                            name="tanggal"
                            id="tanggal">
                    </div>
                    <div class="form-group col-lg-12">
                        <label class="form-control-label" for="shift">Pilih Shift</label>
                        <select class="form-control" name="shift" id="shift">
                            <option selected value="2">Semua</option>
                            <option value="1">Shift 1</option>
                            <option value="0">Shift 2</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-sm">Cetak</button>
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
            <form class="user" action="{{url('kasir/sisa')}}" target="_blank" method="GET">
                <div class="modal-body">
                    <div class="form-group col-lg-12">
                        <label class="form-control-label" for="sisatagihan">Pilih Rekap Sisa Tagihan</label>
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

<div
    class="modal fade"
    id="mySelesai"
    role="dialog"
    tabIndex="-1"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Rekap Akhir Bulan</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form class="user" action="{{url('kasir/selesai')}}" target="_blank" method="GET">
                <div class="modal-body">
                    <div class="form-group col-lg-12">
                        <label class="form-control-label" for="bulanselesai">Bulan</label>
                        <select class="form-control" name="bulanselesai" id="bulanselesai" required>
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
                    <div class="form-group col-lg-12">
                        <label class="form-control-label" for="tahunselesai">Tahun</label>
                        <select class="form-control" name="tahunselesai" id="tahunselesai" required>
                            <?php $tahun = Tagihan::select('thn_tagihan')->groupBy('thn_tagihan')->orderBy('thn_tagihan','desc')->get();?>
                            @foreach($tahun as $t)
                            <option value="{{$t->thn_tagihan}}">{{$t->thn_tagihan}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-sm">Cetak</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div
    class="modal fade"
    id="myUtama"
    tabIndex="-1"
    role="dialog"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cetak Pendapatan Harian</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form class="user" action="{{url('kasir/utama')}}" target="_blank" method="GET">
                <div class="modal-body">
                    <label class="form-control-label" for="tgl_utama">Pilih Tanggal Pendapatan Harian</label>
                    <div class="form-group col-lg-12">
                        <input
                            required 
                            class="form-control" 
                            type="date"
                            name="tgl_utama"
                            id="tgl_utama">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-sm">Cetak</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div
    class="modal fade"
    id="myUtamaBulan"
    role="dialog"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cetak Pendapatan Bulanan</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form class="user" action="{{url('kasir/utama/bulan')}}" target="_blank" method="GET">
                <div class="modal-body">
                    <div class="form-group col-lg-12">
                        <label class="form-control-label" for="bulanpendapatan">Bulan</label>
                        <select class="form-control" name="bulanpendapatan" id="bulanpendapatan" required>
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
                    <div class="form-group col-lg-12">
                        <label class="form-control-label" for="tahunpendapatan">Tahun</label>
                            <select class="form-control" name="tahunpendapatan" id="tahunpendapatan" required>
                            <?php $tahun = Tagihan::select('thn_tagihan')->groupBy('thn_tagihan')->orderBy('thn_tagihan','desc')->get();?>
                            @foreach($tahun as $t)
                            <option value="{{$t->thn_tagihan}}">{{$t->thn_tagihan}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-sm">Cetak</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div
    class="modal fade"
    id="myRincian"
    tabIndex="-1"
    role="dialog"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form class="user" id="form_rincian" method="POST">
                @csrf
                <div class="modal-header">
                    <h3 class="modal-title" id="judulRincian">Rincian</h3>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body" style="height: 55vh;overflow-y: auto;">
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
                    <hr>
                    <div class="col-lg-12 justify-content-between" style="display: flex;flex-wrap: wrap;">
                        <div>
                            <span style="color:#3f6ad8;"><strong>Fasilitas</strong></span>
                        </div>
                        <div>
                            <span style="color:#3f6ad8;"><strong>Nominal</strong></span>
                        </div>
                    </div>
                    <hr>
                    <div id="fasListrik">
                        <div class="form-group col-lg-12" id="divListrik">
                            <div class="justify-content-between" id="testListrik" style="display:flex;flex-wrap:wrap;">
                                <div>
                                    <span id="listrik">Listrik</span>
                                </div>
                                <div>
                                    <span id="nominalListrik"></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-lg-12" id="tungdivListrik">
                            <div class="justify-content-between" id="tungtestListrik" style="display:flex;flex-wrap:wrap;">
                                <div>
                                    <span id="tunglistrik">Tunggakan Listrik</span>
                                </div>
                                <div>
                                    <span id="tungnominalListrik"></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-lg-12" id="dendivListrik">
                            <div class="justify-content-between" id="dentestListrik" style="display:flex;flex-wrap:wrap;">
                                <div>
                                    <span id="denlistrik">Denda Listrik</span>
                                </div>
                                <div>
                                    <span id="dennominalListrik"></span>
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>
                    <div id="fasAirBersih">
                        <div class="form-group col-lg-12" id="divAirBersih">
                            <div class="justify-content-between" id="testAirBersih" style="display:flex;flex-wrap:wrap;">
                                <div>
                                    <span id="airbersih">Air Bersih</span>
                                </div>
                                <div>
                                    <span id="nominalAirBersih"></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-lg-12" id="tungdivAirBersih">
                            <div class="justify-content-between" id="tungtestAirBersih" style="display:flex;flex-wrap:wrap;">
                                <div>
                                    <span id="tungairbersih">Tunggakan Air Bersih</span>
                                </div>
                                <div>
                                    <span id="tungnominalAirBersih"></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-lg-12" id="dendivAirBersih">
                            <div class="justify-content-between" id="dentestAirBersih" style="display:flex;flex-wrap:wrap;">
                                <div>
                                    <span id="denairbersih">Denda Air Bersih</span>
                                </div>
                                <div>
                                    <span id="dennominalAirBersih"></span>
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>
                    <div id="fasKeamananIpk">
                        <div class="form-group col-lg-12" id="divKeamananIpk">
                            <div class="justify-content-between" id="testKeamananIpk" style="display:flex;flex-wrap:wrap;">
                                <div>
                                    <span id="keamananipk">Keamanan IPK</span>
                                </div>
                                <div>
                                    <span id="nominalKeamananIpk"></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-lg-12" id="tungdivKeamananIpk">
                            <div class="justify-content-between" id="tungtestKeamananIpk" style="display:flex;flex-wrap:wrap;">
                                <div>
                                    <span id="tungkeamananipk">Tunggakan Keamanan IPK</span>
                                </div>
                                <div>
                                    <span id="tungnominalKeamananIpk"></span>
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>
                    <div id="fasKebersihan">
                        <div class="form-group col-lg-12" id="divKebersihan">
                            <div class="justify-content-between" id="testKebersihan" style="display:flex;flex-wrap:wrap;">
                                <div>
                                    <span id="kebersihan">Kebersihan</span>
                                </div>
                                <div>
                                    <span id="nominalKebersihan"></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-lg-12" id="tungdivKebersihan">
                            <div class="justify-content-between" id="tungtestKebersihan" style="display:flex;flex-wrap:wrap;">
                                <div>
                                    <span id="tungkebersihan">Tunggakan Kebersihan</span>
                                </div>
                                <div>
                                    <span id="tungnominalKebersihan"></span>
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>
                    <div id="fasAirKotor">
                        <div class="form-group col-lg-12" id="divAirKotor">
                            <div class="justify-content-between" id="testAirKotor" style="display:flex;flex-wrap:wrap;">
                                <div>
                                    <span id="airkotor">Air Kotor</span>
                                </div>
                                <div>
                                    <span id="nominalAirKotor"></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-lg-12" id="tungdivAirKotor">
                            <div class="justify-content-between" id="tungtestAirKotor" style="display:flex;flex-wrap:wrap;">
                                <div>
                                    <span id="tungairkotor">Tunggakan Air Kotor</span>
                                </div>
                                <div>
                                    <span id="tungnominalAirKotor"></span>
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>
                    <div id="fasLain">
                        <div class="form-group col-lg-12" id="divLain">
                            <div class="justify-content-between" id="testLain" style="display:flex;flex-wrap:wrap;">
                                <div>
                                    <span id="lain">Lain - Lain</span>
                                </div>
                                <div>
                                    <span id="nominalLain"></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-lg-12" id="tungdivLain">
                            <div class="justify-content-between" id="tungtestLain" style="display:flex;flex-wrap:wrap;">
                                <div>
                                    <span id="tunglain">Tunggakan Lain - Lain</span>
                                </div>
                                <div>
                                    <span id="tungnominalLain"></span>
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>
                </div>
                <div class="modal-footer" style="margin-bottom:-2rem;">
                    <div class="col-lg-12 justify-content-between" style="display:flex;flex-wrap: wrap;">
                        <div>
                            <span style="color:#3f6ad8;"><strong>Total</strong></span>
                        </div>
                        <div>
                            <h3><strong><span id="nominalTotal" style="color:#3f6ad8;"></span></strong></h3>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" id="printStruk" class="btn btn-primary btn-sm" value="Bayar Sekarang"/>
                    
                    <input hidden id="tempatId" name="tempatId"/>
                    <input hidden id="pedagang" name="pedagang"/>
                    <input hidden id="los" name="los"/>
                    <input hidden id="lokasi" name="lokasi"/>
                    <input hidden id="faktur" name="faktur"/>
                    <input hidden id="ref" name="ref"/>
                    
                    <input hidden id="taglistrik" name="taglistrik"/>
                    <input hidden id="tagtunglistrik" name="tagtunglistrik"/>
                    <input hidden id="tagdenlistrik" name="tagdenlistrik"/>
                    <input hidden id="tagdylistrik" name="tagdylistrik"/>
                    <input hidden id="tagawlistrik" name="tagawlistrik"/>
                    <input hidden id="tagaklistrik" name="tagaklistrik"/>
                    <input hidden id="tagpklistrik" name="tagpklistrik"/>
                    
                    <input hidden id="tagairbersih" name="tagairbersih"/>
                    <input hidden id="tagtungairbersih" name="tagtungairbersih"/>
                    <input hidden id="tagdenairbersih" name="tagdenairbersih"/>
                    <input hidden id="tagawairbersih" name="tagawairbersih"/>
                    <input hidden id="tagakairbersih" name="tagakairbersih"/>
                    <input hidden id="tagpkairbersih" name="tagpkairbersih"/>
                    
                    <input hidden id="tagkeamananipk" name="tagkeamananipk"/>
                    <input hidden id="tagtungkeamananipk" name="tagtungkeamananipk"/>
                    
                    <input hidden id="tagkebersihan" name="tagkebersihan"/>
                    <input hidden id="tagtungkebersihan" name="tagtungkebersihan"/>
                    
                    <input hidden id="tagairkotor" name="tagairkotor"/>
                    <input hidden id="tagtungairkotor" name="tagtungairkotor"/>

                    <input hidden id="taglain" name="taglain"/>
                    <input hidden id="tagtunglain" name="tagtunglain"/>

                    <input hidden id="totalTagihan" name="totalTagihan"/>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{asset('js/kasir/bulanan/index.js')}}"></script>
@endsection
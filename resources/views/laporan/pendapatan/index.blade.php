@extends('layout.master')

@section('title')
<title>Laporan Pendapatan | BP3C</title>
@endsection

@section('judul')
<h6 class="h2 text-white d-inline-block mb-0">Laporan Pendapatan</h6>
@endsection

@section('button')
<button id="generate" class="btn btn-sm btn-danger generate" value="harian" data-toggle="tooltip" data-original-title="Generate"><i class="fas fa-fw fa-download text-white"></i></button>
@endsection

@section('content')
<span id="form_result"></span>
<div class="row">
    <div class="col-xl-12">
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="nav-wrapper">
                    <ul class="nav nav-pills flex-column flex-md-row" id="tabs-icons-text" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0 active" id="tab-c-0" data-toggle="tab" href="#tab-animated-0" role="tab">Harian</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0" id="tab-c-1" data-toggle="tab" href="#tab-animated-1" role="tab">Bulanan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0" id="tab-c-2" data-toggle="tab" href="#tab-animated-2" role="tab">Tahunan</a>
                        </li>
                    </ul>
                </div>
                <div class="text-right">
                    <img src="{{asset('img/updating.gif')}}" style="display:none;" id="refresh-img"/><button class="btn btn-sm btn-primary" id="refresh"><i class="fas fa-sync-alt"></i> Refresh Data</button>
                </div>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="tab-animated-0" role="tabpanel">
                        @include('laporan.pendapatan.harian')
                    </div>
                    <div class="tab-pane fade" id="tab-animated-1" role="tabpanel">
                        @include('laporan.pendapatan.bulanan')
                    </div>
                    <div class="tab-pane fade" id="tab-animated-2" role="tabpanel">
                        @include('laporan.pendapatan.tahunan')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('home.footer')
@endsection

@section('modal')
<div class="modal fade" id="harian-details" tabindex="-1" role="dialog" aria-labelledby="harian-details" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="card bg-secondary border-0 mb-0">
                    <div class="card-header bg-gradient-vine">
                        <div class="text-white text-center titles"></div>
                        <div class="text-white text-center bayars"></div>
                    </div>
                    <div class="card-body px-lg-3 py-lg-3">
                        <div class="card-body">
                            <div class="card-profile-stats d-flex justify-content-center">
                                <div class="col">
                                    <div>
                                        <span class="description">Pengguna</span>
                                        <span class="heading pengguna">&mdash;</span>
                                    </div>
                                    <div>
                                        <span class="description">Kasir</span>
                                        <span class="heading kasir">&mdash;</span>
                                    </div>
                                    <div>
                                        <span class="description">Tagihan</span>
                                        <span class="heading tagihan">&mdash;</span>
                                    </div>
                                    <div>
                                        <span class="description">Realisasi</span>
                                        <span class="heading realisasi">&mdash;</span>
                                    </div>
                                    <div>
                                        <span class="description">Selisih</span>
                                        <span class="heading selisih">&mdash;</span>
                                    </div>
                                    <div>
                                        <span class="description">Disc</span>
                                        <span class="heading diskon" style="color:red;">&mdash;</span>
                                    </div>
                                    <hr>
                                    <span class="heading">Rincian</span>
                                    <div class="row">
                                        <div class="col">
                                            <div class="card-profile-stats d-flex justify-content-between">
                                                <div>
                                                    <span class="description">Bayar Listrik</span>
                                                    <span class="heading bayar-listrik">&mdash;</span>
                                                </div>
                                                <div>
                                                    <span class="description">Denda Listrik</span>
                                                    <span class="heading denda-listrik">&mdash;</span>
                                                </div>
                                                <div>
                                                    <span class="description">Disc</span>
                                                    <span class="heading diskon-listrik" style="color:red;">&mdash;</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="card-profile-stats d-flex justify-content-between">
                                                <div>
                                                    <span class="description">Bayar Air Brs</span>
                                                    <span class="heading bayar-airbersih">&mdash;</span>
                                                </div>
                                                <div>
                                                    <span class="description">Denda Air Brs</span>
                                                    <span class="heading denda-airbersih">&mdash;</span>
                                                </div>
                                                <div>
                                                    <span class="description">Disc</span>
                                                    <span class="heading diskon-airbersih" style="color:red;">&mdash;</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="card-profile-stats d-flex justify-content-between">
                                                <div>
                                                    <span class="description">Bayar Keamanan IPK</span>
                                                    <span class="heading bayar-keamananipk">&mdash;</span>
                                                </div>
                                                <div>
                                                    <span class="description">Disc</span>
                                                    <span class="heading diskon-keamananipk" style="color:red;">&mdash;</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="card-profile-stats d-flex justify-content-between">
                                                <div>
                                                    <span class="description">Bayar Kebersihan</span>
                                                    <span class="heading bayar-kebersihan">&mdash;</span>
                                                </div>
                                                <div>
                                                    <span class="description">Disc</span>
                                                    <span class="heading diskon-kebersihan" style="color:red;">&mdash;</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="card-profile-stats d-flex justify-content-center">
                                                <div>
                                                    <span class="description">Bayar Air Kotor</span>
                                                    <span class="heading bayar-airkotor">&mdash;</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="card-profile-stats d-flex justify-content-center">
                                                <div>
                                                    <span class="description">Bayar Lain Lain</span>
                                                    <span class="heading bayar-lain">&mdash;</span>
                                                </div>
                                            </div>
                                        </div>
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

<div class="modal fade" id="show-details" tabindex="-1" role="dialog" aria-labelledby="show-details" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="card bg-secondary border-0 mb-0">
                    <div class="card-header bg-gradient-vine">
                        <div class="text-white text-center titles"></div>
                    </div>
                    <div class="card-body px-lg-3 py-lg-3">
                        <div class="card-body">
                            <div class="card-profile-stats d-flex justify-content-center">
                                <div class="col">
                                    <div>
                                        <span class="description">Pendapatan</span>
                                        <span class="heading realisasi">&mdash;</span>
                                    </div>
                                    <div>
                                        <span class="description">Disc</span>
                                        <span class="heading diskon" style="color:red;">&mdash;</span>
                                    </div>
                                    <hr>
                                    <span class="heading">Rincian</span>
                                    <div class="row">
                                        <div class="col">
                                            <div class="card-profile-stats d-flex justify-content-between">
                                                <div>
                                                    <span class="description">Bayar Listrik</span>
                                                    <span class="heading bayar-listrik">&mdash;</span>
                                                </div>
                                                <div>
                                                    <span class="description">Denda Listrik</span>
                                                    <span class="heading denda-listrik">&mdash;</span>
                                                </div>
                                                <div>
                                                    <span class="description">Disc</span>
                                                    <span class="heading diskon-listrik" style="color:red;">&mdash;</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="card-profile-stats d-flex justify-content-between">
                                                <div>
                                                    <span class="description">Bayar Air Brs</span>
                                                    <span class="heading bayar-airbersih">&mdash;</span>
                                                </div>
                                                <div>
                                                    <span class="description">Denda Air Brs</span>
                                                    <span class="heading denda-airbersih">&mdash;</span>
                                                </div>
                                                <div>
                                                    <span class="description">Disc</span>
                                                    <span class="heading diskon-airbersih" style="color:red;">&mdash;</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="card-profile-stats d-flex justify-content-between">
                                                <div>
                                                    <span class="description">Bayar Keamanan IPK</span>
                                                    <span class="heading bayar-keamananipk">&mdash;</span>
                                                </div>
                                                <div>
                                                    <span class="description">Disc</span>
                                                    <span class="heading diskon-keamananipk" style="color:red;">&mdash;</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="card-profile-stats d-flex justify-content-between">
                                                <div>
                                                    <span class="description">Bayar Kebersihan</span>
                                                    <span class="heading bayar-kebersihan">&mdash;</span>
                                                </div>
                                                <div>
                                                    <span class="description">Disc</span>
                                                    <span class="heading diskon-kebersihan" style="color:red;">&mdash;</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="card-profile-stats d-flex justify-content-center">
                                                <div>
                                                    <span class="description">Bayar Air Kotor</span>
                                                    <span class="heading bayar-airkotor">&mdash;</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="card-profile-stats d-flex justify-content-center">
                                                <div>
                                                    <span class="description">Bayar Lain Lain</span>
                                                    <span class="heading bayar-lain">&mdash;</span>
                                                </div>
                                            </div>
                                        </div>
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

<div id="generateHarian" class="modal fade" role="dialog" tabIndex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Generate Harian</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="{{url('rekap/pendapatan/generate')}}" method="POST" target="_blank">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-control-label" for="fasilitasi">Pilih Fasilitas</label>
                        <select class="form-control" name="fasilitas" id="fasilitasi" required>
                            <option value="listrik">Listrik</option>
                            <option value="airbersih">Air Bersih</option>
                            <option value="keamananipk">Keamanan IPK</option>
                            <option value="kebersihan">Kebersihan</option>
                            <option value="airkotor">Air Kotor</option>
                            <option value="lain">Lainnya</option>
                            <option value="tagihan">Semua Fasilitas</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-control-label" for="tanggal_generate">Pilih Tanggal Penerimaan</label>
                        <input class="form-control" type="date" name="tanggal_generate" id="tanggal_generate" required/>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="hidden_data" value="harian"/>
                    <button type="submit" class="btn btn-primary">Cetak</button>
                    <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="generateBulanan" class="modal fade" role="dialog" tabIndex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Generate Bulanan</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="{{url('rekap/pendapatan/generate')}}" method="POST" target="_blank">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-control-label" for="fasilitasii">Pilih Fasilitas</label>
                        <select class="form-control" name="fasilitas" id="fasilitasii" required>
                            <option value="listrik">Listrik</option>
                            <option value="airbersih">Air Bersih</option>
                            <option value="keamananipk">Keamanan IPK</option>
                            <option value="kebersihan">Kebersihan</option>
                            <option value="airkotor">Air Kotor</option>
                            <option value="lain">Lainnya</option>
                            <option value="tagihan">Semua Fasilitas</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <span>Pilih periode pendapatan yang ingin di cetak.</span>
                    </div>
                    <div class="form-group">
                        <label class="form-control-label" for="bulan_generate">Bulan</label>
                        <select class="form-control" name="bulan_generate" id="bulan_generate" required>
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
                        <label class="form-control-label" for="tahun_generatei">Tahun</label>
                        <select class="form-control" name="tahun_generate" id="tahun_generatei" required>
                            <?php $tahun = \App\Models\Tagihan::select('thn_tagihan')->groupBy('thn_tagihan')->orderBy('thn_tagihan','desc')->get();?>
                            @foreach($tahun as $t)
                            <option value="{{$t->thn_tagihan}}">{{$t->thn_tagihan}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="hidden_data" value="bulanan"/>
                    <button type="submit" class="btn btn-primary">Cetak</button>
                    <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="generateTahunan" class="modal fade" role="dialog" tabIndex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Generate Tahunan</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="{{url('rekap/pendapatan/generate')}}" method="POST" target="_blank">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-control-label" for="fasilitasiii">Pilih Fasilitas</label>
                        <select class="form-control" name="fasilitas" id="fasilitasiii" required>
                            <option value="listrik">Listrik</option>
                            <option value="airbersih">Air Bersih</option>
                            <option value="keamananipk">Keamanan IPK</option>
                            <option value="kebersihan">Kebersihan</option>
                            <option value="airkotor">Air Kotor</option>
                            <option value="lain">Lainnya</option>
                            <option value="tagihan">Semua Fasilitas</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <span>Pilih tahun pendapatan yang ingin di cetak.</span>
                    </div>
                    <div class="form-group">
                        <label class="form-control-label" for="tahun_generateii">Tahun</label>
                        <select class="form-control" name="tahun_generate" id="tahun_generateii" required>
                            <?php $tahun = \App\Models\Tagihan::select('thn_tagihan')->groupBy('thn_tagihan')->orderBy('thn_tagihan','desc')->get();?>
                            @foreach($tahun as $t)
                            <option value="{{$t->thn_tagihan}}">{{$t->thn_tagihan}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="hidden_data" value="tahunan"/>
                    <button type="submit" class="btn btn-primary">Cetak</button>
                    <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{asset('js/laporan/pendapatan.js')}}"></script>
@endsection
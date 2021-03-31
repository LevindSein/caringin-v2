@extends('layout.master')

@section('title')
<title>Laporan Pendapatan | BP3C</title>
@endsection

@section('judul')
<h6 class="h2 text-white d-inline-block mb-0">Laporan Pendapatan</h6>
@endsection

@section('button')
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
<div class="modal fade" id="harian-details" tabindex="-1" role="dialog" aria-labelledby="show-details" aria-hidden="true">
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
                                    <div class="row" style="margin-top:-1rem">
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
                                    <div class="row" style="margin-top:-1rem">
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
                                    <div class="row" style="margin-top:-1rem">
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
                                    <div class="row" style="margin-top:-1rem">
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
                                    <div class="row" style="margin-top:-1rem">
                                        <div class="col">
                                            <div class="card-profile-stats d-flex justify-content-center">
                                                <div>
                                                    <span class="description">Bayar Air Kotor</span>
                                                    <span class="heading bayar-airkotor">&mdash;</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top:-1rem">
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
                                    <div class="row" style="margin-top:-1rem">
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
                                    <div class="row" style="margin-top:-1rem">
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
                                    <div class="row" style="margin-top:-1rem">
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
                                    <div class="row" style="margin-top:-1rem">
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
                                    <div class="row" style="margin-top:-1rem">
                                        <div class="col">
                                            <div class="card-profile-stats d-flex justify-content-center">
                                                <div>
                                                    <span class="description">Bayar Air Kotor</span>
                                                    <span class="heading bayar-airkotor">&mdash;</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top:-1rem">
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
@endsection

@section('js')
<script src="{{asset('js/laporan/pendapatan.js')}}"></script>
@endsection
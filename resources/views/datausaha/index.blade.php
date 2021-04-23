@extends('layout.master')

@section('title')
<title>Data Usaha | BP3C</title>
@endsection

@section('judul')
<h6 class="h2 text-white d-inline-block mb-0">Data Usaha</h6>
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
                            <a class="nav-link mb-sm-3 mb-md-0 active" id="tab-c-0" data-toggle="tab" href="#tab-animated-0" role="tab">Tagihan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0" id="tab-c-1" data-toggle="tab" href="#tab-animated-1" role="tab">Tunggakan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0" id="tab-c-2" data-toggle="tab" href="#tab-animated-2" role="tab">Pendapatan</a>
                        </li>
                    </ul>
                </div>
                <div class="text-right">
                    <img src="{{asset('img/updating.gif')}}" style="display:none;" id="refresh-img"/><button class="btn btn-sm btn-primary" id="refresh"><i class="fas fa-sync-alt"></i> Refresh Data</button>
                </div>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="tab-animated-0" role="tabpanel">
                        @include('datausaha.tagihan')
                    </div>
                    <div class="tab-pane fade" id="tab-animated-1" role="tabpanel">
                        @include('datausaha.tunggakan')
                    </div>
                    <div class="tab-pane fade" id="tab-animated-2" role="tabpanel">
                        @include('datausaha.pendapatan')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('home.footer')
@endsection

@section('modal')
<div class="modal fade" id="show-details" tabindex="-1" role="dialog" aria-labelledby="show-details" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="card bg-secondary border-0 mb-0">
                    <div class="card-header bg-gradient-vine">
                        <h2 class="text-white text-center jumlah" style="font-weight:700">Test</h2>
                        <h2 class="text-white text-center periode" style="font-weight:700">Test</h2>
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
<script src="{{asset('js/datausaha/datausaha.js')}}"></script>
@endsection
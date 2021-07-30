@extends('layout.master')

@section('title')
<title>Layanan Bongkaran | BP3C</title>
@endsection

@section('judul')
<h6 class="h2 text-white d-inline-block mb-0">Layanan Bongkaran</h6>
@endsection

@section('button')
<div>
    <button name="riwayat_bongkaran" id="riwayat_bongkaran" class="btn btn-sm btn-success"><i class="fas fa-fw fa-book fa-sm text-white-50"></i> Riwayat</button>
    <a href="{{url('layanan/bongkaran/generate')}}" type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-original-title="Generate" target="_blank"><i class="fas fa-fw fa-download text-white"></i></a>
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
                <div class="table-responsive py-4">
                    <table class="table table-flush table-hover table-striped" width="100%" id="tabelBongkaran">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center" style="min-width:100px;max-width:20%">Kontrol</th>
                                <th class="text-center" style="min-width:100px;max-width:20%">Periode</th>
                                <th class="text-center">Details</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                    </table>
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
            <div class="modal-body">Pilih "Ok" di bawah ini jika anda yakin bongkaran telah selesai.</div>
            <div class="modal-footer">
                <button type="button" name="ok_button" id="ok_button" class="btn btn-success">Ok</button>
                <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>

<div id="suratModal" class="modal fade" role="dialog" tabIndex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title titles"></h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="form_surat" class="user" method="POST" action="{{url('layanan/bongkaran/surat')}}">
            @csrf
                <div class="modal-body">
                    <span>Pilih surat yang ingin diunduh terkait pembongkaran di bawah ini.</span><br><br>
                    <select class="form-control" name="surat" id="surat">
                        <option value="peringatan">Peringatan</option>
                        <option value="pembongkaran">Perintah Bongkar</option>
                        <option value="berita_acara">Berita Acara</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="surat_id" name="surat_id" />
                    <button type="submit" name="unduh_btn" id="unduh_btn" class="btn btn-success">Unduh</button>
                    <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
                </div>
            </form>
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
                        <div class="card-body modal-body-short">
                            <div class="card-profile-stats d-flex justify-content-center">
                                <div class="col">
                                    <div class="row">
                                        <div class="col">
                                            <div class="card-profile-stats d-flex justify-content-between" style="margin-bottom:-2rem">
                                                <div>
                                                    <span class="description" style="font-size:.875rem;">Bulan</span>
                                                </div>
                                                <div>
                                                    <span class="description" style="font-size:.875rem;">Tagihan</span>
                                                </div>
                                            </div>
                                            <div id="rincianrow">
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
<script src="{{asset('js/layanan/bongkaran.js')}}"></script>
@endsection
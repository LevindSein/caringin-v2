@extends('layout.keuangan')

@section('breadcrumb')
<title>Pendapatan Harian | BP3C</title>
<div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
    <div>
        <nav aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-style1 mg-b-10">
            <li class="breadcrumb-item" aria-current="page">Laporan</li>
            <li class="breadcrumb-item" aria-current="page">Pendapatan</li>
            <li class="breadcrumb-item active" aria-current="page">Harian</li>
        </ol>
        </nav>
        <h4 class="mg-b-0 tx-spacing--1">Daftar Pendapatan Harian</h4>
    </div>
    <hr>
    <div class="text-center">
        <button type="button" data-toggle="modal" data-target="#myGenerate" title="Cetak Pendapatan Harian" class="btn btn-sm pd-x-15 btn-primary btn-uppercase mg-l-5"><i data-feather="printer"></i> Generate</button>
    </div>
</div>
@endsection

@section('content')
<input type="hidden" id="fasilitas" value="harian" />
<table 
    id="tabel" 
    class="table table-bordered"
    cellspacing="0"
    width="100%">
    <thead>
        <tr>
            <th class="wd-25p"><b>Kontrol</b></th>
            <th class="wd-20p"><b>Tanggal</b></th>
            <th class="wd-20p"><b>Bayar</b></th>
            <th class="wd-15p"><b>Kasir</b></th>
        </tr>
    </thead>
</table>

<div
    class="modal fade"
    id="myGenerate"
    tabIndex="-1"
    role="dialog"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cetak Pendapatan Tanggal ?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form class="user" action="{{url('keuangan/laporan/pendapatan/generate/harian')}}" method="GET" target="_blank">
                <div class="modal-body-short">
                    <div class="form-group col-lg-12">
                        <input
                            required
                            placeholder="Masukkan Tanggal" class="form-control" type="text" onfocus="(this.type='date')"
                            autocomplete="off"
                            type="date"
                            name="tgl_utama"
                            id="tgl_utama">
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-primary btn-sm" value="Submit" />
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{asset('js/keuangan/pendapatan-harian.js')}}"></script>
@endsection
@extends('layout.keuangan')

@section('breadcrumb')
<title>Rekap Sisa Tagihan | BP3C</title>
<div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
    <div>
        <nav aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-style1 mg-b-10">
            <li class="breadcrumb-item" aria-current="page">Laporan</li>
            <li class="breadcrumb-item" aria-current="page">Rekap</li>
            <li class="breadcrumb-item active" aria-current="page">Sisa Tagihan</li>
        </ol>
        </nav>
        <h4 class="mg-b-0 tx-spacing--1">Daftar Pendapatan Bulanan</h4>
    </div>
    <hr>
    <div class="text-center">
        <a href="{{url('keuangan/laporan/rekap/generate/sisa')}}" target="_blank" type="button" title="Cetak Daftar Sisa Tagihan" class="btn btn-sm pd-x-15 btn-primary btn-uppercase mg-l-5"><i data-feather="printer"></i> Generate</a>
    </div>
</div>
@endsection

@section('content')
<input type="hidden" id="fasilitas" value="sisa" />
<table 
    id="tabel" 
    class="table table-bordered" 
    cellspacing="0"
    width="100%">
    <thead>
        <tr>
            <th class="wd-25p"><b>Bulan</b></th>
            <th class="wd-20p"><b>Tagihan</b></th>
            <th class="wd-20p"><b>Realisasi</b></th>
            <th class="wd-20p"><b>Selisih</b></th>
            <th class="wd-20p"><b>Diskon</b></th>
        </tr>
    </thead>
</table>
@endsection

@section('js')
<script src="{{asset('js/keuangan/rekap-sisa.js')}}"></script>
@endsection
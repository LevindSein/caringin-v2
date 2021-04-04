@extends('layout.keuangan')

@section('breadcrumb')
<title>Pendapatan Tahunan | BP3C</title>
<div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
    <div>
        <nav aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-style1 mg-b-10">
            <li class="breadcrumb-item" aria-current="page">Laporan</li>
            <li class="breadcrumb-item" aria-current="page">Pendapatan</li>
            <li class="breadcrumb-item active" aria-current="page">Tahunan</li>
        </ol>
        </nav>
        <h4 class="mg-b-0 tx-spacing--1">Daftar Pendapatan Tahunan</h4>
    </div>
    <hr>
</div>
@endsection

@section('content')
<input type="hidden" id="fasilitas" value="tahunan" />
<table 
    id="tabel" 
    class="table table-bordered" 
    cellspacing="0"
    width="100%">
    <thead>
        <tr>
            <th class="wd-25p"><b>Tahun</b></th>
            <th class="wd-20p"><b>Realisasi</b></th>
            <th class="wd-20p"><b>Diskon</b></th>
        </tr>
    </thead>
</table>
@endsection

@section('js')
<script src="{{asset('js/keuangan/pendapatan-tahunan.js')}}"></script>
@endsection
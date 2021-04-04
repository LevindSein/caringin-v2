@extends('layout.keuangan')

@section('breadcrumb')
<title>Data Tunggakan | BP3C</title>
<div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
    <div>
        <nav aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-style1 mg-b-10">
            <li class="breadcrumb-item" aria-current="page">Data Usaha</li>
            <li class="breadcrumb-item active" aria-current="page">Tunggakan</li>
        </ol>
        </nav>
        <h4 class="mg-b-0 tx-spacing--1">Daftar Aktifitas Tunggakan</h4>
    </div>
    <hr>
</div>
@endsection

@section('content')
<table 
    id="tabel" 
    class="table table-bordered" 
    cellspacing="0"
    width="100%">
    <thead>
        <tr>
            <th class="wd-20p"><b>Bulan</b></th>
            <th class="wd-25p"><b>Tunggakan</b></th>
        </tr>
    </thead>
</table>
@endsection

@section('js')
<script src="{{asset('js/keuangan/data-tunggakan.js')}}"></script>
@endsection
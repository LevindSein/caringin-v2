@extends('layout.master')

@section('title')
<title>Layanan Alat Meter | BP3C</title>
@endsection

@section('judul')
<h6 class="h2 text-white d-inline-block mb-0">Riwayat Alat Meter</h6>
@endsection

@section('button')
<div>
    <a href="{{url('layanan/alatmeter')}}" type="button" name="pasang_alat" id="pasang_alat" class="btn btn-sm btn-warning">Pasang Alat</a>
    <a href="{{url('layanan/alatmeter/ganti')}}" type="button" name="ganti_alat" id="ganti_alat" class="btn btn-sm btn-success">Ganti Alat</a>
    <a href="{{url('layanan/alatmeter/bongkar')}}" type="button" name="bongkar_alat" id="bongkar_alat" class="btn btn-sm btn-danger">Bongkar Alat</a>
</div>
@endsection

@section('content')
<span id="form_result"></span>
<div class="row">
    <div class="col-xl-12">
        <div class="card shadow mb-4">
            <div class="card-body">
                
            </div>
        </div>
    </div>
</div>
@include('home.footer')
@endsection

@section('modal')
@endsection

@section('js')
@endsection
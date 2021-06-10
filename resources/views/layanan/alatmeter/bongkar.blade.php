@extends('layout.master')

@section('title')
<title>Layanan Alat Meter | BP3C</title>
@endsection

@section('judul')
<h6 class="h2 text-white d-inline-block mb-0">Bongkar Alat Meter</h6>
@endsection

@section('button')
<div>
    <a href="{{url('layanan/alatmeter')}}" type="button" name="pasang_alat" id="pasang_alat" class="btn btn-sm btn-warning">Pasang Alat</a>
    <a href="{{url('layanan/alatmeter/ganti')}}" type="button" name="ganti_alat" id="ganti_alat" class="btn btn-sm btn-success">Ganti Alat</a>
    <a href="{{url('layanan/alatmeter/riwayat')}}" type="button" name="riwayat" id="riwayat" class="btn btn-sm btn-primary">Data Riwayat</a>
</div>
@endsection

@section('content')
<span id="form_result"></span>
<div class="row">
    <div class="col-xl-12">
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-xl-6">
                        <form class="user" method="POST">
                            @csrf
                            <div class="form-group">
                                <label class="form-control-label" for="jenis_alat">Jenis Pembongkaran Alat Meter <span style="color:red;">*</span></label>
                                <select required name="jenis_alat" id="jenis_alat" class="form-control">
                                    <option value="listrik">Listrik</option>
                                    <option value="airbersih">Air Bersih</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <Input type="submit" id="btn_submit" value="Submit" class="btn btn-primary btn-user btn-block">
                            </div>
                        </form>
                    </div>
                </div>
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
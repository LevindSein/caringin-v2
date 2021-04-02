@extends('layout.master')

@section('title')
<title>Tambah Tagihan Air | BP3C</title>
@endsection

@section('content')
<span id="form_result"></span>
<div class="row justify-content-center">
    <div class="col-xl-6">
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="form-group text-center">
                    <h2 class="h2 mb-0">Tambah Tagihan Air</h2>
                    <h2 class="h2 mb-0"><b>{{$dataset->kd_kontrol}}</b></h2>
                </div>
                <form class="user" action="{{url('tagihan/airbersih')}}" method="POST">
                    @csrf
                    <div class="form-group col-lg-12">
                        <label class="form-control-label" for="nama">Pengguna</label>
                        <input
                            required
                            value="{{$dataset->nama}}"
                            name="nama"
                            class="form-control"
                            id="nama">
                    </div>
                    <div class="form-group col-lg-12">
                        <label class="form-control-label" for="lokasi">No.Los</label>
                        <input
                            readonly
                            value="{{$ket->no_alamat}}"
                            name="lokasi"
                            class="form-control"
                            id="lokasi">
                    </div>
                    @if($ket->lok_tempat != NULL)
                    <div class="form-group col-lg-12">
                        <label class="form-control-label" for="ket">Keterangan</label>
                        <input
                            readonly
                            value="{{$ket->lok_tempat}}"
                            name="ket"
                            class="form-control"
                            id="ket">
                    </div>
                    @endif
                    <div class="form-group col-lg-12">
                        <label class="form-control-label" for="awal">Stand Awal Air Bersih <span style="color:red;">*</span></label>
                        <input
                            required
                            <?php if(Session::get('role') == 'admin') { ?> readonly <?php } ?>
                            value="{{number_format($dataset->awal_airbersih)}}"
                            autocomplete="off"
                            type="text" 
                            pattern="^[\d,]+$"
                            name="awal"
                            class="form-control"
                            id="awal"
                            maxLength="10">
                    </div>
                    <div class="form-group col-lg-12">
                        <label class="form-control-label" for="akhir">Stand Akhir Air Bersih <span style="color:red;">*</span></label>
                        <div class="input-group">
                            <input
                                autofocus
                                required
                                value="{{number_format($suggest)}}"
                                autocomplete="off"
                                type="text" 
                                pattern="^[\d,]+$"
                                name="akhir"
                                class="form-control"
                                id="akhir"
                                maxLength="10">
                            <div class="input-group-prepend">
                                <div class="col">
                                    <input 
                                        class="input-group-text"
                                        type="checkbox"
                                        name="reset"
                                        id="reset">
                                    <label class="form-control-label" for="reset">Reset ?</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="hidden_blok" id="hidden_blok" value="{{$blok}}">
                    <input type="hidden" name="hidden_id" id="hidden_id" value="{{$dataset->id}}">
                    <input type="hidden" name="kd_kontrol" id="kd_kontrol" value="{{$dataset->kd_kontrol}}">
                    <div class="form-group col-lg-12">
                        <Input type="submit" id="tambah" value="Tambah Tagihan" class="btn btn-primary btn-user btn-block">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@include('home.footer')
@endsection

@section('js')
<script src="{{asset('js/tagihan/form-airbersih.js')}}"></script>
@endsection
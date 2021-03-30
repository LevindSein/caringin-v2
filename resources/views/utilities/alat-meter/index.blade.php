@extends('layout.master')

@section('title')
<title>Data Alat | BP3C</title>
@endsection

@section('judul')
<h6 class="h2 text-white d-inline-block mb-0">Data Alat Meter</h6>
@endsection

@section('button')
@if(Session::get('role') == 'master')
<div>
    <button 
        type="button"
        name="add_alat"
        id="add_alat" 
        class="btn btn-sm btn-success">
        <i class="fas fa-fw fa-plus fa-sm text-white-50"></i> Alat</button>
</div>
@endif
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
                            <a class="nav-link mb-sm-3 mb-md-0 active" id="tab-c-0" data-toggle="tab" href="#tab-animated-0" role="tab">Listrik</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0" id="tab-c-1" data-toggle="tab" href="#tab-animated-1" role="tab">Air&nbsp;Bersih</a>
                        </li>
                    </ul>
                </div>
                <div class="text-right">
                    <img src="{{asset('img/updating.gif')}}" style="display:none;" id="refresh-img"/><button class="btn btn-sm btn-primary" id="refresh"><i class="fas fa-sync-alt"></i> Refresh Data</button>
                </div>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="tab-animated-0" role="tabpanel">
                        @include('utilities.alat-meter.listrik')
                    </div>
                    <div class="tab-pane fade" id="tab-animated-1" role="tabpanel">
                        @include('utilities.alat-meter.air-bersih')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('modal')
<div id="confirmModal" class="modal fade" role="dialog" tabIndex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title titles">Apakah yakin hapus data alat?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Pilih "Hapus" di bawah ini jika anda yakin untuk menghapus data alat.</div>
            <div class="modal-footer">
            	<button type="button" name="ok_button" id="ok_button" class="btn btn-danger">Hapus</button>
                <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>

<div
    class="modal fade"
    id="myModal"
    tabIndex="-1"
    role="dialog"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title titles" id="exampleModalLabel"></h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="form_alat" class="user" method="POST">
                <div class="modal-body">
                    @csrf
                    <div class="form-group col-lg-12">
                        <label class="form-control-label" for="nomor">Nomor Alat  <span style="color:red;">*</span></label>
                        <input
                            type="text"
                            autocomplete="off"
                            maxLength="30"
                            style="text-transform:uppercase;"
                            name="nomor"
                            class="form-control"
                            id="nomor">
                    </div>
                    <div class="form-group row col-lg-12">
                        <div class="col-sm-12">
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="radioMeter"
                                    id="listrik"
                                    value="listrik"
                                    data-related-item="divListrik"
                                    checked>
                                <label class="form-control-label" for="listrik">
                                    Listrik
                                </label>
                            </div>
                            <div class="form-group" style="display:none" id="meteranListrik">
                                <div class="form-group" id="divListrik">
                                    <input 
                                        type="text" 
                                        autocomplete="off" 
                                        class="form-control"
                                        name="standListrik"
                                        id="standListrik"
                                        placeholder="Masukkan Stand Akhir Listrik">
                                    <br>
                                    <div class="input-group">
                                        <input 
                                            type="text" 
                                            autocomplete="off" 
                                            class="form-control input-group"
                                            name="dayaListrik"
                                            id="dayaListrik"
                                            placeholder="Masukkan Daya Listrik"
                                            aria-describedby="inputGroupPrepend">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputGroupPrepend">Watt</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="radioMeter"
                                    id="air"
                                    value="air"
                                    data-related-item="divAir">
                                <label class="form-control-label" for="air">
                                    Air Bersih
                                </label>
                            </div>
                            <div class="form-group" style="display:none" id="meteranAir">
                                <div class="form-group" id="divAir">
                                    <input 
                                        type="text" 
                                        autocomplete="off" 
                                        class="form-control"
                                        name="standAir"
                                        id="standAir"
                                        placeholder="Masukkan Stand Akhir Air">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="action" id="action" value="Add" />
                    <input type="hidden" name="hidden_id" id="hidden_id" />
                    <input type="submit" class="btn btn-primary btn-sm" name="action_btn" id="action_btn" value="Tambah" />
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{asset('js/utilities/alat-meter.js')}}"></script>
@endsection
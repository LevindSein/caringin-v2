@extends('layout.master')

@section('title')
<title>Data Hari Libur | BP3C</title>
@endsection

@section('judul')
<h6 class="h2 text-white d-inline-block mb-0">Data Hari Libur</h6>
@endsection

@section('button')
@if(Session::get('role') == 'master')
<div>
    <button 
        type="button"
        name="add_tanggal"
        id="add_tanggal" 
        class="btn btn-sm btn-success">
        <i class="fas fa-fw fa-plus fa-sm text-white-50"></i> Hari&nbsp;Libur</button>
</div>
@endif
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
                    <table class="table table-flush" width="100%" id="tabelHariLibur">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center" style="max-width:25%">Tanggal</th>
                                <th class="text-center" style="min-width:200px;max-width:60%">Ket</th>
                                <th class="text-center" style="max-width:15%">Action</th>
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
                <h5 class="modal-title titles">Apakah yakin hapus data hari libur?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Pilih "Hapus" di bawah ini jika anda yakin untuk menghapus data hari libur.</div>
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
            <form id="form_harilibur" class="user" method="POST">
                <div class="modal-body">
                    @csrf
                    <div class="form-group col-lg-12">
                        <label class="form-control-label" for="tanggal">Tanggal <span style="color:red;">*</span></label>
                        <input
                            required
                            autocomplete="off"
                            type="date"
                            name="tanggal"
                            class="form-control"
                            id="tanggal">
                    </div>
                    <div class="form-group col-lg-12">
                        <label class="form-control-label" for="ket">Keterangan <span style="color:red;">*</span></label>
                        <input
                            required
                            autocomplete="off"
                            type="text"
                            name="ket"
                            maxlength="30"
                            class="form-control"
                            id="ket"
                            placeholder="Keterangan Libur">
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
<script src="{{asset('js/utilities/hari-libur.min.js')}}"></script>
@endsection
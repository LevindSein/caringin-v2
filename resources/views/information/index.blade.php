@extends('layout.master')

@section('title')
<title>Patch Info | BP3C</title>
@endsection

@section('judul')
<h6 class="h2 text-white d-inline-block mb-0">Patch Info</h6>
@endsection

@section('button')
<!-- <a href="#" class="btn btn-sm btn-neutral">New</a> -->
@if(Session::get('role') == 'master')
<div>
    <button 
        type="button"
        name="add_information"
        id="add_information" 
        class="btn btn-sm btn-success">
        <i class="fas fa-fw fa-plus fa-sm text-white-50"></i> Info</button>
</div>
@endif
@endsection

@section('content')
<span id="form_result"></span>
<div class="row">
    <div class="col-xl-12">
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive py-4">
                    <table class="table table-flush" width="100%" id="tabelInformation">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center" style="max-width:15%">Tanggal</th>
                                <th class="text-center" style="max-width:45%">Ket</th>
                                <th class="text-center" style="max-width:25%">Pengaruh</th>
                                <th class="text-center" style="max-width:15%">Action</th>
                            </tr>
                        </thead>
                    </table>
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
                <h5 class="modal-title titles">Apakah yakin hapus data ?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Pilih "Hapus" di bawah ini jika anda yakin untuk menghapus data.</div>
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
            <form id="form_information" class="user" method="POST">
                <div class="modal-body">
                    @csrf
                    <div class="text-center">
                        <textarea required id="ket_info" name="ket_info" class="form-control" placeholder="Masukkan Keterangan" style="height:40vh;"></textarea>
                    </div>
                    <div class="text-center">
                        <span class="form-control-label" style="font-size:1.5rem;">EFEK</span>
                    </div>
                    <div class="form-group col-lg-12 justify-content-center" style="display:flex;flex-wrap:wrap;">
                        <div class="row">
                            <div class="col">
                                <div class="form-check">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        name="admin"
                                        id="admin">
                                    <label class="form-control-label" for="admin">
                                        Admin
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        name="manajer"
                                        id="manajer">
                                    <label class="form-control-label" for="manajer">
                                        Manajer
                                    </label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-check">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        name="keuangan"
                                        id="keuangan">
                                    <label class="form-control-label" for="keuangan">
                                        Keuangan
                                    </label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-check">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        name="kasir"
                                        id="kasir">
                                    <label class="form-control-label" for="kasir">
                                        Kasir
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        name="nasabah"
                                        id="nasabah">
                                    <label class="form-control-label" for="nasabah">
                                        Nasabah
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="action" id="action" value="Add" />
                    <input type="hidden" name="hidden_id" id="hidden_id" />
                    <input type="submit" class="btn btn-primary btn-sm" name="action_btn" id="action_btn"/>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{asset('js/information/information.js')}}"></script>
@endsection
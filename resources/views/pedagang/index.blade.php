@extends('layout.master')

@section('title')
<title>Data Pedagang | BP3C</title>
@endsection

@section('judul')
<h6 class="h2 text-white d-inline-block mb-0">Data Pedagang</h6>
@endsection

@section('button')
<!-- <a href="#" class="btn btn-sm btn-neutral">New</a> -->
@if(Session::get('role') == 'master')
<div>
    <button 
        type="button"
        name="add_pedagang"
        id="add_pedagang" 
        class="btn btn-sm btn-success">
        <i class="fas fa-fw fa-plus fa-sm text-white-50"></i> Pedagang</button>
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
                    <table class="table table-flush" width="100%" id="tabelPedagang">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center" style="max-width:10%">No</th>
                                <th class="text-center" style="max-width:70%">Nama</th>
                                <th class="text-center" width="10%">Action</th>
                                <th class="text-center" width="10%">Details</th>
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
                <h5 class="modal-title titles">Apakah yakin hapus data pedagang?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Pilih "Hapus" di bawah ini jika anda yakin untuk menghapus data pedagang.</div>
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
            <form id="form_pedagang" class="user" method="POST">
                <div class="modal-body">
                    @csrf
                    <div class="form-group col-lg-12">
                        <label class="form-control-label" for="ktp">Nomor KTP <span style="color:red;">*</span></label>
                        <input
                            required
                            autocomplete="off"
                            type="tel"
                            name="ktp"
                            maxlength="17"
                            class="form-control"
                            id="ktp"
                            placeholder="321xxxxx">
                    </div>
                    <div class="form-group col-lg-12">
                        <label class="form-control-label" for="nama">Nama Pedagang <span style="color:red;">*</span></label>
                        <input
                            required
                            autocomplete="off"
                            type="text"
                            style="text-transform: capitalize;"
                            name="nama"
                            class="form-control"
                            id="nama"
                            minlength="2"
                            maxlength="30"
                            placeholder="Nama Sesuai KTP">
                    </div>
                    <div class="form-group col-lg-12">
                        <label class="form-control-label" for="username">Username</label>
                        <input
                            required
                            readonly
                            type="text"
                            style="text-transform: lowercase;"
                            class="form-control"
                            name="username"
                            id="username">
                    </div>
                    <div class="form-group col-lg-12">
                        <label class="form-control-label" for="anggota">Nomor Anggota</label>
                        <input
                            required
                            readonly
                            type="text"
                            style="text-transform: uppercase;"
                            class="form-control"
                            name="anggota"
                            id="anggota">
                    </div>
                    <div class="form-group row col-lg-12">
                        <div class="col-sm-2 form-control-label">Status</div>
                        <div class="col-sm-10">
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="pemilik"
                                    id="pemilik"
                                    value="pemilik"
                                    data-related-item="divPemilik">
                                <label class="form-control-label" for="pemilik">
                                    Pemilik
                                </label>
                            </div>
                            <div class="form-group" style="display:none" id="displayPemilik">
                                <label class="form-control-label" for="alamatPemilik" id="divPemilik">Alamat <span style="color:red;">*</span></label>
                                <div class="form-group">
                                    <select style="width:100%" class="alamatPemilik" name="alamatPemilik[]" id="alamatPemilik" multiple></select>
                                </div>
                            </div>
                            
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="pengguna"
                                    id="pengguna"
                                    value="pengguna"
                                    data-related-item="divPengguna">
                                <label class="form-control-label" for="pengguna">
                                    Pengguna
                                </label>
                            </div>
                            <div class="form-group" style="display:none" id="displayPengguna">
                                <label class="form-control-label" for="alamatPengguna"  id="divPengguna">Alamat <span style="color:red;">*</span></label>
                                <div class="form-group">
                                    <select style="width:100%" class="alamatPengguna" name="alamatPengguna[]" id="alamatPengguna" multiple required></select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label class="form-control-label" for="email">Email</label>
                            <div class="input-group">
                                <input type="text" autocomplete="off" class="form-control" maxlength="20" name="email" id="email" placeholder="youremail" aria-describedby="inputGroupPrepend">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">@gmail.com</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-lg-12">
                        <label class="form-control-label" for="hp">No. Handphone <span style="color:red;">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroupPrepend">+62</span>
                            </div>
                            <input required type="tel" autocomplete="off" class="form-control" maxlength="12" name="hp" id="hp" placeholder="8783847xxx" aria-describedby="inputGroupPrepend">
                        </div>
                    </div>
                    <div class="form-group col-lg-12">
                        <label class="form-control-label" for="alamat">Alamat KTP <span style="color:red;">*</span></label>
                        <textarea autocomplete="off" name="alamat" class="form-control" id="alamat" required></textarea>
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

<div class="modal fade" id="show-details" tabindex="-1" role="dialog" aria-labelledby="show-details" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="card bg-secondary border-0 mb-0">
                    <div class="card-header bg-gradient-vine">
                        <div class="text-white text-center titles">Sign in with</div>
                    </div>
                    <div class="card-body px-lg-3 py-lg-3">
                        <div class="card-header text-center">
                            <div class="d-flex justify-content-between">
                                <a href="#" class="btn btn-sm btn-success" id="whatsapp-number"> <i class="fas fa-fw fa-phone-alt fa-sm text-white-50"></i> Whatsapp</a>
                                <a href="#" class="btn btn-sm btn-danger" id="email-number"><i class="fas fa-fw fa-envelope fa-sm text-white-50"></i> Email</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="card-profile-stats d-flex justify-content-center">
                                <div class="col">
                                    <div>
                                        <span class="description">KTP</span>
                                        <span class="heading ktp"></span>
                                    </div>
                                    <div>
                                        <span class="description">No.Anggota</span>
                                        <span class="heading anggota"></span>
                                    </div>
                                    <div>
                                        <span class="description">Alamat</span>
                                        <span class="heading alamat" style="font-size:.875rem;"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                            <div class="text-center">
                                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{asset('js/pedagang/pedagang.js')}}"></script>
@endsection
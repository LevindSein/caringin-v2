@extends('layout.master')

@section('title')
<title>Data User | BP3C</title>
@endsection

@section('judul')
<h6 class="h2 text-white d-inline-block mb-0">Data User</h6>
@endsection

@section('button')
@if(Session::get('role') == 'master')
<div>
    <button 
        type="button"
        name="add_user"
        id="add_user" 
        class="btn btn-sm btn-success">
        <i class="fas fa-fw fa-plus fa-sm text-white-50"></i> User</button>
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
                            <a class="nav-link mb-sm-3 mb-md-0 active" id="tab-c-0" data-toggle="tab" href="#tab-animated-0" role="tab">Admin</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0" id="tab-c-1" data-toggle="tab" href="#tab-animated-1" role="tab">Manajer</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0" id="tab-c-2" data-toggle="tab" href="#tab-animated-2" role="tab">Keuangan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0" id="tab-c-3" data-toggle="tab" href="#tab-animated-3" role="tab">Kasir</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0" id="tab-c-4" data-toggle="tab" href="#tab-animated-4" role="tab">Nasabah</a>
                        </li>
                    </ul>
                </div>
                <div class="text-right">
                    <img src="{{asset('img/updating.gif')}}" style="display:none;" id="refresh-img"/><button class="btn btn-sm btn-primary" id="refresh"><i class="fas fa-sync-alt"></i> Refresh Data</button>
                </div>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="tab-animated-0" role="tabpanel">
                        @include('user.admin')
                    </div>
                    <div class="tab-pane fade" id="tab-animated-1" role="tabpanel">
                        @include('user.manajer')
                    </div>
                    <div class="tab-pane fade" id="tab-animated-2" role="tabpanel">
                        @include('user.keuangan')
                    </div>
                    <div class="tab-pane fade" id="tab-animated-3" role="tabpanel">
                        @include('user.kasir')
                    </div>
                    <div class="tab-pane fade" id="tab-animated-4" role="tabpanel">
                        @include('user.nasabah')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<footer class="footer pt-0">
    <div class="row align-items-center justify-content-lg-between">
        <div class="col-lg-6">
            <div class="copyright text-center text-lg-left text-muted">
                &copy; 2020 <a href="#" class="font-weight-bold ml-1">PT Pengelola Pusat Perdagangan Caringin</a>
            </div>
        </div>
    </div>
</footer>
@endsection

@section('modal')
<div id="confirmModal" class="modal fade" role="dialog" tabIndex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title titles">Apakah yakin hapus data user?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Pilih "Hapus" di bawah ini jika anda yakin untuk menghapus data user.</div>
            <div class="modal-footer">
            	<button type="button" name="ok_button" id="ok_button" class="btn btn-danger">Hapus</button>
                <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>

<div id="confirmReset" class="modal fade" role="dialog" tabIndex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title titles">Apakah yakin reset password user?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Pilih "Ya" di bawah ini jika anda yakin untuk mereset password user.</div>
            <div class="modal-footer">
            	<button type="button" name="ya_button" id="ya_button" class="btn btn-danger">Ya</button>
                <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>

<div id="resetModal" class="modal fade" role="dialog" tabIndex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title titles">Password Baru</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <h1><b><span id="password_baru">Password Baru</span></b></h1>
                </div>
            </div>
            <div class="modal-footer">
            	<button type="button" data-dismiss="modal" class="btn btn-success">OK</button>
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
            <form id="form_user" class="user" method="POST">
                <div class="modal-body">
                    @csrf
                    <div class="form-group col-lg-12">
                        <label class="form-control-label" for="ktp">Nomor KTP <span style="color:red;">*</span></label>
                        <input
                            required
                            autocomplete="off"
                            type="tel"
                            min="0"
                            name="ktp"
                            maxlength="17"
                            class="form-control"
                            id="ktp"
                            placeholder="321xxxxx">
                    </div>
                    <div class="form-group col-lg-12">
                        <label class="form-control-label" for="nomor">Nama  <span style="color:red;">*</span></label>
                        <input
                            required
                            autocomplete="off"
                            name="nama"
                            class="form-control"
                            style="text-transform: capitalize;"
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
                        <label class="form-control-label" for="password">Password</label>
                        <div>
                            <input
                                readonly
                                type="password"
                                class="form-control"
                                name="password"
                                id="password">
                            <span 
                                toggle="#password" 
                                style="float:right;margin-right:10px;margin-top:-30px;position:relative;z-index:2;"
                                class="fa fa-fw fa-eye toggle-password"></span>
                        </div>
                    </div>
                    <div class="form-group row col-lg-12">
                        <div class="col-sm-12">
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="role"
                                    id="roleAdmin"
                                    value="admin"
                                    checked>
                                <label class="form-control-label" for="roleAdmin">
                                    Admin
                                </label>
                            </div>
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="role"
                                    id="roleManajer"
                                    value="manajer">
                                <label class="form-control-label" for="roleManajer">
                                    Manajer
                                </label>
                            </div>
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="role"
                                    id="roleKeuangan"
                                    value="keuangan">
                                <label class="form-control-label" for="roleKeuangan">
                                    Keuangan
                                </label>
                            </div>
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="role"
                                    id="roleKasir"
                                    value="kasir">
                                <label class="form-control-label" for="roleKasir">
                                    Kasir
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-lg-12">
                        <label class="form-control-label" for="email">Email</label>
                        <div class="input-group">
                            <input
                                autocomplete="off" 
                                type="text" 
                                class="form-control" 
                                maxlength="20" 
                                name="email" 
                                id="email" 
                                placeholder="youremail" 
                                aria-describedby="inputGroupPrepend">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroupPrepend">@gmail.com</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-lg-12">
                        <label class="form-control-label" for="hp">No. Handphone <span style="color:red;">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroupPrepend">+62</span>
                            </div>
                            <input 
                                required 
                                autocomplete="off"
                                type="tel" 
                                class="form-control" 
                                maxlength="12" 
                                name="hp" 
                                id="hp" 
                                placeholder="8783847xxx" 
                                aria-describedby="inputGroupPrepend">
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

<div
    class="modal fade"
    id="myOtoritas"
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
            <form id="form_otoritas" class="user" method="POST">
                <div class="modal-body">
                    @csrf
                    <div class="form-group col-lg-12">
                        <label class="form-control-label" for="username">Username</label>
                        <input
                            readonly
                            name="username_otoritas"
                            class="form-control"
                            id="username_otoritas">
                    </div>
                    <div class="form-group col-lg-12">
                        <label class="form-control-label" for="nama">Nama</label>
                        <input
                            readonly
                            name="nama_otoritas"
                            class="form-control"
                            id="nama_otoritas">
                    </div>
                    <hr class="sidebar-divider d-none d-md-block col-lg-10 text-center">
                    <div class="form-group col-lg-12">
                        <div class="form-group">
                            <label class="form-control-label" for="blokOtoritas">Blok <span style="color:red;">*</span></label>
                            <div class="form-group">
                                <select style="width:100%" class="blokOtoritas" name="blokOtoritas[]" id="blokOtoritas" multiple></select>
                            </div>
                        </div>
                    </div>
                    <div class="text-center form-group">
                        <strong>Pilih Pengelolaan :</strong>
                    </div>
                    <div class="form-group col-lg-12 justify-content-between" style="display: flex;flex-wrap: wrap;">
                        <div>
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="kelola[]"
                                    id="pedagang"
                                    value="pedagang">
                                <label class="form-control-label" for="pedagang">
                                    Pedagang
                                </label>
                            </div>
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="kelola[]"
                                    id="tempatusaha"
                                    value="tempatusaha">
                                <label class="form-control-label" for="tempatusaha">
                                    Tempat Usaha
                                </label>
                            </div>
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="kelola[]"
                                    id="tagihan"
                                    value="tagihan">
                                <label class="form-control-label" for="tagihan">
                                    Tagihan
                                </label>
                            </div>
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="kelola[]"
                                    id="blok"
                                    value="blok">
                                <label class="form-control-label" for="blok">
                                    Blok
                                </label>
                            </div>
                        </div>
                        <div>
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="kelola[]"
                                    id="pemakaian"
                                    value="pemakaian">
                                <label class="form-control-label" for="pemakaian">
                                    Pemakaian
                                </label>
                            </div>
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="kelola[]"
                                    id="pendapatan"
                                    value="pendapatan">
                                <label class="form-control-label" for="pendapatan">
                                    Pendapatan
                                </label>
                            </div>
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="kelola[]"
                                    id="datausaha"
                                    value="datausaha">
                                <label class="form-control-label" for="datausaha">
                                    Data Usaha
                                </label>
                            </div>
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="kelola[]"
                                    id="publish"
                                    value="publish">
                                <label class="form-control-label" for="publish">
                                    Publishing
                                </label>
                            </div>
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="kelola[]"
                                    id="simulasi"
                                    value="simulasi">
                                <label class="form-control-label" for="simulasi">
                                    Simulasi
                                </label>
                            </div>
                        </div>
                        <div>
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="kelola[]"
                                    id="alatmeter"
                                    value="alatmeter">
                                <label class="form-control-label" for="alatmeter">
                                    Alat Meter
                                </label>
                            </div>
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="kelola[]"
                                    id="tarif"
                                    value="tarif">
                                <label class="form-control-label" for="tarif">
                                    Tarif Fasilitas
                                </label>
                            </div>
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="kelola[]"
                                    id="harilibur"
                                    value="harilibur">
                                <label class="form-control-label" for="harilibur">
                                    Hari Libur
                                </label>
                            </div>
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="kelola[]"
                                    id="layanan"
                                    value="layanan">
                                <label class="form-control-label" for="layanan">
                                    Layanan
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="hidden_id_otoritas" id="hidden_id_otoritas" />
                    <input type="submit" class="btn btn-primary btn-sm" name="action_btn_otoritas" id="action_btn_otoritas" value="Update" />
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
                        <div class="text-white text-center titles"></div>
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
                        <div class="text-right">
                            <button type="button" class="btn btn-white" data-dismiss="modal">&times; Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{asset('js/user/user.js')}}"></script>
@endsection
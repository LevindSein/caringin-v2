@extends('layout.master')

@section('title')
<title>Settings | BP3C</title>
@endsection

@section('judul')
@endsection

@section('button')
@endsection

@section('content')
<span class="form_result"></span>
<div class="row justify-content-center">
    <div class="col-xl-6">
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="form-group text-center">
                    <h2 class="h2 mb-0">Profile Settings</h2>
                </div>
                <form id="form_settings">
                    @csrf
                    @if(Session::get('role') != 'master')
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
                            placeholder="321xxxxx"
                            value="{{Session::get('ktp')}}">
                    </div>
                    @endif
                    <div class="form-group col-lg-12">
                        <label class="form-control-label" for="nama">Nama<span style="color:red;">*</span></label>
                        <input
                            <?php if(Session::get('role') != 'master'){ ?>
                            readonly="readonly"
                            <?php } ?>
                            required
                            autocomplete="off"
                            type="text"
                            style="text-transform: capitalize;"
                            name="nama"
                            class="form-control"
                            id="nama"
                            minlength="2"
                            maxlength="30"
                            placeholder="Masukkan Nama Sesuai KTP"
                            value="{{Session::get('username')}}">
                    </div>
                    <div class="form-group col-lg-12">
                        <label class="form-control-label" for="username">Username<span style="color:red;">*</span></label>
                        <input
                            required
                            autocomplete="off"
                            type="text"
                            style="text-transform: lowercase;"
                            class="form-control"
                            name="username"
                            id="username"
                            minlength="2"
                            maxlength="30"
                            placeholder="Masukkan Username untuk Login"
                            value="{{Session::get('nama')}}">
                    </div>
                    <div class="form-group col-lg-12">
                        <label class="form-control-label" for="password">Password<span style="color:red;">*</span></label>
                        <div>
                            <Input
                                required
                                autocomplete="off"
                                type="password"
                                class="form-control"
                                name="password"
                                id="password"
                                minlength="6"
                                maxlength="30"
                                placeholder="Masukkan Password">
                            <span 
                                toggle="#password" 
                                style="float:right;margin-right:10px;margin-top:-30px;position:relative;z-index:2;"
                                class="fa fa-fw fa-eye toggle-password"></span>
                        </div>
                    </div>
                    <div class="form-group col-lg-12">
                        <label class="form-control-label" for="password">Konfirmasi Password<span style="color:red;">*</span></label>
                        <div>
                            <Input
                                required
                                autocomplete="off"
                                type="password"
                                class="form-control"
                                name="konfirmasi_password"
                                id="konfirmasi_password"
                                minlength="6"
                                maxlength="30"
                                placeholder="Konfirmasi Password">
                            <span 
                                toggle="#konfirmasi_password" 
                                style="float:right;margin-right:10px;margin-top:-30px;position:relative;z-index:2;"
                                class="fa fa-fw fa-eye toggle-password"></span>
                        </div>
                    </div>
                    @if(Session::get('role') != 'master')
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label class="form-control-label" for="email">Email</label>
                            <div class="input-group">
                                <input type="text" autocomplete="off" class="form-control" maxlength="20" name="email" id="email" placeholder="youremail" aria-describedby="inputGroupPrepend" value="{{Session::get('email')}}">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">@gmail.com</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-lg-12">
                        <label class="form-control-label" for="hp">No. Handphone<span style="color:red;">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroupPrepend">+62</span>
                            </div>
                            <input required type="tel" autocomplete="off" class="form-control" maxlength="12" name="hp" id="hp" placeholder="8783847xxx" aria-describedby="inputGroupPrepend" value="{{Session::get('hp')}}">
                        </div>
                    </div>
                    <div class="form-group col-lg-12">
                        <label class="form-control-label" for="alamat">Alamat KTP <span style="color:red;">*</span></label>
                        <textarea autocomplete="off" name="alamat" class="form-control" id="alamat" placeholder="Masukkan Alamat Sesuai KTP" required>{{Session::get('alamatktp')}}</textarea>
                    </div>
                    @endif
                    <div class="form-group col-lg-12">
                        <Input type="submit" id="submit" value="Submit" class="btn btn-primary btn-user btn-block">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<span class="form_result"></span>
@include('home.footer')
@endsection

@section('modal')
@endsection

@section('js')
<script src="{{asset('js/settings/settings.js')}}"></script>
@endsection
@extends('tempatusaha.index')

@section('titles')
<title>Data Tempat Usaha | BP3C</title>
@endsection

@section('juduls')
<h6 class="h2 text-white d-inline-block mb-0">Data Tempat Usaha</h6>
@endsection

@section('contents')
@if(Session::get('role') == 'master' || Session::get('role') == 'admin')
<div class="table-responsive py-4">
    <table class="table table-flush" width="100%" id="tabelTempat">
        <thead class="thead-light">
            <tr>
                <th class="text-center" style="max-width:15%">Kontrol</th>
                <th class="text-center" style="max-width:20%">Pengguna</th>
                <th class="text-center" style="max-width:20%">Lokasi</th>
                <th class="text-center" style="max-width:5%">Jml.Los</th>
                <th class="text-center" style="max-width:20%">Usaha</th>
                <th class="text-center" style="max-width:10%">Action</th>
                <th class="text-center" style="max-width:10%">Details</th>
            </tr>
        </thead>
    </table>
</div>
@else
<div class="table-responsive py-4">
    <table class="table table-flush" width="100%" id="tabelTempat1">
        <thead class="thead-light">
            <tr>
                <th class="text-center" style="max-width:20%">Kontrol</th>
                <th class="text-center" style="max-width:20%">Pengguna</th>
                <th class="text-center" style="max-width:20%">Lokasi</th>
                <th class="text-center" style="max-width:10%">Jml.Los</th>
                <th class="text-center" style="max-width:20%">Usaha</th>
                <th class="text-center" style="max-width:10%">Details</th>
            </tr>
        </thead>
    </table>
</div>
@endif
@endsection

@section('modals')
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
                                        <span class="description">Pengguna</span>
                                        <span class="heading pengguna"></span>
                                    </div>
                                    <div>
                                        <span class="description">Pemilik</span>
                                        <span class="heading pemilik"></span>
                                    </div>
                                    <div>
                                        <span class="description">Nomor Los</span>
                                        <span class="heading los"></span>
                                    </div>
                                    <div>
                                        <span class="description">Jumlah Los</span>
                                        <span class="heading jumlah"></span>
                                    </div>
                                    <div>
                                        <span class="description">Lokasi</span>
                                        <span class="heading lokasi"></span>
                                    </div>
                                    <div>
                                        <span class="description">Usaha</span>
                                        <span class="heading usaha"></span>
                                    </div>
                                    <div>
                                        <span class="description">Status</span>
                                        <span class="heading status"></span>
                                    </div>
                                    <div>
                                        <span class="description">Keterangan</span>
                                        <span class="heading keterangan"></span>
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

@section('jss')
@endsection
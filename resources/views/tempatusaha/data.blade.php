@extends('tempatusaha.index')

@section('titles')
<title>Data Tempat Usaha | BP3C</title>
@endsection

@section('juduls')
<h6 class="h2 text-white d-inline-block mb-0">Data Tempat Usaha</h6>
@endsection

@section('contents')
<div class="text-right">
    <img src="{{asset('img/updating.gif')}}" style="display:none;" id="refresh-img"/><button class="btn btn-sm btn-primary" id="refresh"><i class="fas fa-sync-alt"></i> Refresh Data</button>
</div>
<div class="table-responsive py-4">
    <table class="table table-flush" width="100%" id="tabelTempat">
        <thead class="thead-light">
            <tr>
                <th class="text-center" style="max-width:15%">Kontrol</th>
                <th class="text-center" style="max-width:20%">Pengguna</th>
                <th class="text-center" style="max-width:20%">Lok</th>
                <th class="text-center" style="max-width:5%">Jml.Los</th>
                <th class="text-center" style="max-width:20%">Usaha</th>
                <th class="text-center" style="max-width:10%">Action</th>
                <th class="text-center" style="max-width:10%">Details</th>
            </tr>
        </thead>
    </table>
</div>
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
                        <div class="card-body modal-body-short">
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
                                    <div class="divListrik">
                                        <hr>
                                        <span class="heading">Listrik</span>
                                        <div class="row" style="margin-top:-1rem">
                                            <div class="col">
                                                <div class="card-profile-stats d-flex justify-content-between">
                                                    <div>
                                                        <span class="description">Diskon</span>
                                                        <span class="heading diskon-listrik" style="font-size:.875rem;">&mdash;</span>
                                                    </div>
                                                    <div>
                                                        <span class="description">Kode</span>
                                                        <span class="heading alat-listrik" style="font-size:.875rem;">&mdash;</span>
                                                    </div>
                                                    <div>
                                                        <span class="description">Daya</span>
                                                        <span class="heading daya-listrik" style="font-size:.875rem;">&mdash;</span>
                                                    </div>
                                                    <div>
                                                        <span class="description">Stand</span>
                                                        <span class="heading stand-listrik" style="font-size:.875rem;">&mdash;</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="divAirBersih">
                                        <hr>
                                        <span class="heading">Air Bersih</span>
                                        <div class="row" style="margin-top:-1rem">
                                            <div class="col">
                                                <div class="card-profile-stats d-flex justify-content-between">
                                                    <div>
                                                        <span class="description">Diskon</span>
                                                        <span class="heading diskon-airbersih" style="font-size:.875rem;">&mdash;</span>
                                                    </div>
                                                    <div>
                                                        <span class="description">Kode</span>
                                                        <span class="heading alat-airbersih" style="font-size:.875rem;">&mdash;</span>
                                                    </div>
                                                    <div>
                                                        <span class="description">Stand</span>
                                                        <span class="heading stand-airbersih" style="font-size:.875rem;">&mdash;</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="divKeamananIpk">
                                        <hr>
                                        <span class="heading">Keamanan IPK</span>
                                        <div class="row" style="margin-top:-1rem">
                                            <div class="col">
                                                <div class="card-profile-stats d-flex justify-content-between">
                                                    <div>
                                                        <span class="description">Diskon</span>
                                                        <span class="heading diskon-keamananipk" style="font-size:.875rem;">&mdash;</span>
                                                    </div>
                                                    <div>
                                                        <span class="description">Tarif</span>
                                                        <span class="heading subtotal-keamananipk" style="font-size:.875rem;">&mdash;</span>
                                                        <span class="heading per-unit-keamananipk" style="font-size:.875rem;">&mdash;</span>
                                                    </div>
                                                    <div>
                                                        <span class="description">Total</span>
                                                        <span class="heading total-keamananipk" style="font-size:.875rem;">&mdash;</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="divKebersihan">
                                        <hr>
                                        <span class="heading">Kebersihan</span>
                                        <div class="row" style="margin-top:-1rem">
                                            <div class="col">
                                                <div class="card-profile-stats d-flex justify-content-between">
                                                    <div>
                                                        <span class="description">Diskon</span>
                                                        <span class="heading diskon-kebersihan" style="font-size:.875rem;">&mdash;</span>
                                                    </div>
                                                    <div>
                                                        <span class="description">Tarif</span>
                                                        <span class="heading subtotal-kebersihan" style="font-size:.875rem;">&mdash;</span>
                                                        <span class="heading per-unit-kebersihan" style="font-size:.875rem;">&mdash;</span>
                                                    </div>
                                                    <div>
                                                        <span class="description">Total</span>
                                                        <span class="heading total-kebersihan" style="font-size:.875rem;">&mdash;</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="divAirKotor">
                                        <hr>
                                        <span class="heading">Air Kotor</span>
                                        <div class="row" style="margin-top:-1rem">
                                            <div class="col">
                                                <div class="card-profile-stats d-flex justify-content-center">
                                                    <div>
                                                        <span class="description">Total</span>
                                                        <span class="heading total-airkotor" style="font-size:.875rem;">&mdash;</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="divLain">
                                        <hr>
                                        <span class="heading">Lain - Lain</span>
                                        <div class="row" style="margin-top:-1rem">
                                            <div class="col">
                                                <div class="card-profile-stats d-flex justify-content-center">
                                                    <div>
                                                        <span class="description">Total</span>
                                                        <span class="heading total-lain" style="font-size:.875rem;">&mdash;</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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

@section('jss')
@endsection
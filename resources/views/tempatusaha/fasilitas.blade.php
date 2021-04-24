<?php
use App\Models\Tagihan;
?>

@extends('tempatusaha.index')

@section('titles')
<title>Pengguna Fasilitas {{$fasilitas}} | BP3C</title>
@endsection

@section('juduls')
<h6 class="h2 text-white d-inline-block mb-0">Pengguna {{$fasilitas}}</h6>
@endsection

@section('contents')
<div class="table-responsive py-4">
    <table class="table table-flush table-hover table-striped" width="100%" id="tabelFasilitas">
        <thead class="thead-light">
            <tr>
                <th class="text-center" style="max-width:30%">Kontrol</th>
                <th class="text-center" style="min-width:100px;max-width:20%">Pengguna</th>
                <th class="text-center" style="max-width:20%">Jml.Los</th>
                <th class="text-center" style="max-width:20%">Tagihan</th>
                <th class="text-center" style="max-width:10%">Details</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dataset as $data)
            <?php 
            $tagihan = Tagihan::fasilitas($data->kd_kontrol,$fas);
            ?>
            <tr>
                <td class="text-center">{{$data->kd_kontrol}}</td>
                <td class="text-center-td">{{$data->pengguna}}</td>
                <td class="text-center">{{$data->jml_alamat}}</td>
                <td class="text-center">{{number_format($tagihan)}}</td>
                <td class="text-center">
                    <button title="Show Details" name="show" id="{{$data->id}}" nama="{{$data->kd_kontrol}}" class="details btn btn-sm btn-primary">Show</button>
                </td>
            </tr>
            @endforeach
        </tbody>
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
                                        <div class="row">
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
                                        <div class="row">
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
                                        <div class="row">
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
                                        <div class="row">
                                            <div class="col">
                                                <div class="card-profile-stats d-flex justify-content-between">
                                                    <div>
                                                        <span class="description">Diskon</span>
                                                        <span class="heading diskon-kebersihan" style="font-size:.875rem;">&mdash;</span>
                                                    </div>
                                                    <div>
                                                        <span class="description">Per-Unit</span>
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
                                        <div class="row">
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
                                        <div class="row">
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
<script>
$(document).ready(function(){
    $('#tabelFasilitas').DataTable({
		deferRender: true,
        language: {
            paginate: {
                previous: "<i class='fas fa-angle-left'>",
                next: "<i class='fas fa-angle-right'>"
            }
        },
        pageLength: 10,
        aLengthMenu: [[5,10,25,50,100], [5,10,25,50,100]],
        order: [ 0, "asc" ],
        responsive:true,
    }).columns.adjust().draw();
});
</script>
@endsection
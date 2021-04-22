<?php 
use App\Models\IndoDate;

function indoDate($tanggal){
    return IndoDate::bulan($tanggal,' ');
}
?>

@extends('layout.master')

@section('title')
<title>Laporan Tunggakan | BP3C</title>
@endsection

@section('judul')
<h6 class="h2 text-white d-inline-block mb-0">Laporan Tunggakan</h6>
@endsection

@section('button')
@endsection

@section('content')
<span id="form_result"></span>
<div class="row">
    <div class="col-xl-12">
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive py-4">
                    <table class="table table-flush table-hover table-striped" width="100%" id="tunggakan">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center" style="max-width:70%">Tagihan</th>
                                <th class="text-center" style="max-width:30%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dataset as $d)
                            <tr>
                                <td class="text-center-td" <?php $bulan = indoDate($d->bln_tagihan); ?>>{{$bulan}}</td>
                                <td class="text-center">
                                    <button title="Cetak Tunggakan" name="cetak" id="{{$d->bln_tagihan}}" nama="{{$bulan}}" class="cetak btn btn-sm btn-primary">Cetak</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@include('home.footer')
@endsection

@section('modal')
<div id="myModal" class="modal fade" role="dialog" tabIndex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title titles"></h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="form_tunggakan" target="_blank" method="POST" action="{{url('rekap/tunggakan')}}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-control-label" for="fasilitas">Pilih Fasilitas</label>
                        <select class="form-control" id="fasilitas" name="fasilitas">
                            <option value="listrik">Listrik</option>
                            <option value="airbersih">Air Bersih</option>
                            <option value="keamananipk">Keamanan & IPK</option>
                            <option value="kebersihan">Kebersihan</option>
                            <option value="airkotor">Air Kotor</option>
                            <option value="lain">Lain - Lain</option>
                            <option value="total">Total Tunggakan</option>
                            <option value="diskon">Pengguna Diskon</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-light" data-dismiss="modal">Batal</button>
                    <input type="hidden" id="hidden_value" name="hidden_value" />
                    <input type="submit" class="btn btn-sm btn-primary" value="Cetak" />
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{asset('js/laporan/tunggakan.js')}}"></script>
@endsection
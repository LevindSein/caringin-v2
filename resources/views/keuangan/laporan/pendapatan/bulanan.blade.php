@extends('layout.keuangan')

@section('breadcrumb')
<title>Pendapatan Bulanan | BP3C</title>
<div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
    <div>
        <nav aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-style1 mg-b-10">
            <li class="breadcrumb-item" aria-current="page">Laporan</li>
            <li class="breadcrumb-item" aria-current="page">Pendapatan</li>
            <li class="breadcrumb-item active" aria-current="page">Bulanan</li>
        </ol>
        </nav>
        <h4 class="mg-b-0 tx-spacing--1">Daftar Pendapatan Bulanan</h4>
    </div>
    <hr>
    <div class="text-center">
        <button type="button" data-toggle="modal" data-target="#myGenerate" title="Cetak Pendapatan Bulanan" class="btn btn-sm pd-x-15 btn-primary btn-uppercase mg-l-5"><i data-feather="printer"></i> Generate</button>
    </div>
</div>
@endsection

@section('content')
<input type="hidden" id="fasilitas" value="bulanan" />
<table 
    id="tabel" 
    class="table table-bordered" 
    cellspacing="0"
    width="100%">
    <thead>
        <tr>
            <th class="wd-25p"><b>Bulan</b></th>
            <th class="wd-20p"><b>Realisasi</b></th>
            <th class="wd-20p"><b>Diskon</b></th>
        </tr>
    </thead>
</table>

<div
    class="modal fade"
    id="myGenerate"
    tabIndex="-1"
    role="dialog"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cetak Pendapatan Bulan ?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form class="user" action="{{url('keuangan/laporan/pendapatan/generate/bulanan')}}" method="GET" target="_blank">
                <div class="modal-body-short">
                    <div class="form-group col-lg-12">
                        <label for="bulanpendapatan">Bulan</label>
                        <select class="form-control" name="bulanpendapatan" id="bulanpendapatan" required>
                            <option value="01">Januari</option>
                            <option value="02">Februari</option>
                            <option value="03">Maret</option>
                            <option value="04">April</option>
                            <option value="05">Mei</option>
                            <option value="06">Juni</option>
                            <option value="07">Juli</option>
                            <option value="08">Agustus</option>
                            <option value="09">September</option>
                            <option value="10">Oktober</option>
                            <option value="11">November</option>
                            <option value="12">Desember</option>
                        </select>
                    </div>
                    <div class="form-group col-lg-12">
                        <label for="tahunpendapatan">Tahun</label>
                        <select class="form-control" name="tahunpendapatan" id="tahunpendapatan" required>
                            @foreach($dataTahun as $d)
                            <option value="{{$d->thn_tagihan}}">{{$d->thn_tagihan}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-primary btn-sm" value="Submit" />
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{asset('js/keuangan/pendapatan-bulanan.js')}}"></script>
@endsection
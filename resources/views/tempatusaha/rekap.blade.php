<?php use Jenssegers\Agent\Agent; $agent = new Agent();?>
@extends('tempatusaha.index')

@section('titles')
<title>Rekap Tempat Usaha | BP3C</title>
@endsection

@section('juduls')
<h6 class="h2 text-white d-inline-block mb-0">Rekap Tempat Usaha</h6>
@endsection

@section('contents')
<div class="table-responsive py-4">
    <table class="table table-flush" width="100%" id="tabelRekap">
        <thead class="thead-light">
            <tr>
                <th class="text-center">Blok</th>
                <th class="text-center">Kontrol</th>
                <th class="text-center">Aktif</th>
                <th class="text-center">Pasif</th>
                @if($agent->isDesktop())
                <th class="text-center">Action</th>
                @endif
            </tr>
        </thead>
        <tbody>
            <?php
            $unit = 0;
            $aktif = 0;
            ?>
            @foreach($dataset as $d)
            <?php 
            $unit = $unit + $d->total;
            $aktif = $aktif + $d->aktif;
            ?>
            <tr>
                <td class="text-center">{{$d->blok}}</td>
                <td class="text-center">{{number_format($d->total)}}</td>
                <td class="text-center">{{number_format($d->aktif)}}</td>
                <td class="text-center">{{number_format($d->total - $d->aktif)}}</td>
                @if($agent->isDesktop())
                <td class="text-center">
                    <button title="Show Details" name="show" id="{{$d->blok}}" nama="{{$d->blok}}" class="details-rekap btn btn-sm btn-primary">Show</button>
                </td>
                @endif
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
            <td style="font-weight: bold;" class="text-center">Total</td>
            <td style="font-weight: bold;" class="text-center">{{number_format($unit)}}</td>
            <td style="font-weight: bold;" class="text-center">{{number_format($aktif)}}</td>
            <td style="font-weight: bold;" class="text-center">{{number_format($unit - $aktif)}}</td>
            @if($agent->isDesktop())
            <td></td>
            @endif
            </tr>
        </tfoot>
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
                        <div class="card-body modal-body-short">
                            <div class="card-profile-stats d-flex justify-content-center">
                                <div class="col">
                                    <span class="heading">Pengguna</span>
                                    <div class="row" style="margin-top:-1rem">
                                        <div class="col">
                                            <div class="card-profile-stats d-flex justify-content-between">
                                                <div>
                                                    <span class="heading pengguna-listrik" style="font-size:.875rem;">&mdash;</span>
                                                    <span class="description">Listrik</span>
                                                </div>
                                                <div>
                                                    <span class="heading pengguna-airbersih" style="font-size:.875rem;">&mdash;</span>
                                                    <span class="description">Air Bersih</span>
                                                </div>
                                                <div>
                                                    <span class="heading pengguna-keamananipk" style="font-size:.875rem;">&mdash;</span>
                                                    <span class="description">Keamanan IPK</span>
                                                </div>
                                                <div>
                                                    <span class="heading pengguna-kebersihan" style="font-size:.875rem;">&mdash;</span>
                                                    <span class="description">Kebersihan</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="heading">Potensi</span>
                                    <div class="row" style="margin-top:-1rem">
                                        <div class="col">
                                            <div class="card-profile-stats d-flex justify-content-between">
                                                <div>
                                                    <span class="heading potensi-listrik" style="font-size:.875rem;">&mdash;</span>
                                                    <span class="description">Listrik</span>
                                                </div>
                                                <div>
                                                    <span class="heading potensi-airbersih" style="font-size:.875rem;">&mdash;</span>
                                                    <span class="description">Air Bersih</span>
                                                </div>
                                                <div>
                                                    <span class="heading potensi-keamananipk" style="font-size:.875rem;">&mdash;</span>
                                                    <span class="description">Keamanan IPK</span>
                                                </div>
                                                <div>
                                                    <span class="heading potensi-kebersihan" style="font-size:.875rem;">&mdash;</span>
                                                    <span class="description">Kebersihan</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="heading">Rincian</span>
                                    <div class="row" style="margin-top:-1rem">
                                        <div class="col">
                                            <div class="card-profile-stats d-flex justify-content-between" style="margin-bottom:-2rem">
                                                <div>
                                                    <span class="description" style="font-size:.875rem;">Kontrol</span>
                                                </div>
                                                <div>
                                                    <span class="description" style="font-size:.875rem;">Status</span>
                                                </div>
                                                <div>
                                                    <span class="description" style="font-size:.875rem;">Fasilitas</span>
                                                </div>
                                            </div>
                                            <div id="rincianrow">
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
    var DatatableBasic = (function() {
        // Variables
        var $dtBasic = $('#tabelRekap');

        // Methods
        function init($this) {

            // Basic options. For more options check out the Datatables Docs:
            // https://datatables.net/manual/options

            var options = {
                deferRender: true,
                keys: !0,
                select: {
                    style: "multi"
                },
                language: {
                    paginate: {
                        previous: "<i class='fas fa-angle-left'>",
                        next: "<i class='fas fa-angle-right'>"
                    }
                },
                pageLength: 8,
                order: [ 0, "asc" ],
                responsive:true
            };

            // Init the datatable

            var table = $this.on( 'init.dt', function () {
                $('div.dataTables_length select').removeClass('custom-select custom-select-sm');

            }).DataTable(options);
        }
        // Events
        if ($dtBasic.length) {
            init($dtBasic);
        }
    })();
});
</script>
@endsection
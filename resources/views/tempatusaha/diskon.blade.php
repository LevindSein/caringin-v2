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
    <table class="table table-flush" width="100%" id="tabelDiskon">
        <thead class="thead-light">
            <tr>
                <th class="text-center" style="max-width:30%">Kontrol</th>
                <th class="text-center" style="max-width:20%">Pengguna</th>
                <th class="text-center" style="max-width:20%">Jml.Los</th>
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
                <td class="text-center">{{$data->pengguna}}</td>
                <td class="text-center">{{$data->jml_alamat}}</td>
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
@endsection

@section('jss')
<script>
$(document).ready(function(){
    var DatatableBasic = (function() {
        // Variables
        var $dtBasic = $('#tabelDiskon');

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
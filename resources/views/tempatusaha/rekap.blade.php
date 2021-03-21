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
                <th class="text-center">Action</th>
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
                <td class="text-center">
                    <a
                        href="javascript:void(0)" 
                        onclick="location.href='{{url('tempatusaha/rekap',[$d->blok])}}'" 
                        type="submit" 
                        class="btn btn-sm btn-primary">Details</a>
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
            <td style="font-weight: bold;" class="text-center">Total</td>
            <td style="font-weight: bold;" class="text-center">{{number_format($unit)}}</td>
            <td style="font-weight: bold;" class="text-center">{{number_format($aktif)}}</td>
            <td style="font-weight: bold;" class="text-center">{{number_format($unit - $aktif)}}</td>
            <td></td>
            </tr>
        </tfoot>
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
                scrollX: true,
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
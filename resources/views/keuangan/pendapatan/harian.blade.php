@extends('layout.master')

@section('title')
<title>Pendapatan Harian | BP3C</title>
@endsection

@section('judul')
<h6 class="h2 text-white d-inline-block mb-0">Pendapatan Harian</h6>
@endsection

@section('button')
<button class="btn btn-sm btn-danger generate" data-toggle="tooltip" data-original-title="Generate"><i class="fas fa-fw fa-download text-white"></i></button>
@endsection

@section('content')
<span id="form_result"></span>
<div class="row">
    <div class="col-xl-12">
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="text-right">
                    <img src="{{asset('img/updating.gif')}}" style="display:none;" id="refresh-img"/><button class="btn btn-sm btn-primary" id="refresh"><i class="fas fa-sync-alt"></i> Refresh Data</button>
                </div>
                <div class="table-responsive py-4">
                    <table class="table table-flush table-hover table-striped" width="100%" id="tabel">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center" style="max-width:15%">Tanggal</th>
                                <th class="text-center" style="max-width:15%">Kontrol</th>
                                <th class="text-center" style="min-width:80px;max-width:20%">Pengguna</th>
                                <th class="text-center" style="min-width:80px;max-width:20%">Kasir</th>
                                <th class="text-center" style="max-width:15%">Realisasi</th>
                                <th class="text-center" style="max-width:15%">Details</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@include('home.footer')
@endsection

@section('modal')
<div id="myGenerate" class="modal fade" role="dialog" tabIndex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Generate</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="{{url('keuangan/pendapatan/generate')}}" method="POST" target="_blank">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-control-label" for="fasilitas">Pilih Fasilitas</label>
                    <select class="form-control" name="fasilitas" id="fasilitas" required>
                        <option value="listrik">Listrik</option>
                        <option value="airbersih">Air Bersih</option>
                        <option value="keamananipk">Keamanan IPK</option>
                        <option value="kebersihan">Kebersihan</option>
                        <option value="airkotor">Air Kotor</option>
                        <option value="lain">Lainnya</option>
                        <!-- <option value="tagihan">Semua Fasilitas</option> -->
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-control-label" for="tanggal_generate">Pilih Tanggal Penerimaan</label>
                    <input class="form-control" type="date" name="tanggal_generate" id="tanggal_generate" required/>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="hidden_data" value="harian"/>
                <button type="submit" class="btn btn-primary">Cetak</button>
                <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
$(document).ready(function () {
    var dtable = $('#tabel').DataTable({
        serverSide: true,
		ajax: {
			url: "/keuangan/pendapatan/harian",
            cache:false,
		},
		columns: [
			{ data: 'tgl_bayar', name: 'tgl_bayar', class : 'text-center' },
			{ data: 'kd_kontrol', name: 'kd_kontrol', class : 'text-center' },
			{ data: 'pengguna', name: 'pengguna', class : 'text-center-td' },
			{ data: 'nama', name: 'nama', class : 'text-center-td' },
			{ data: 'realisasi', name: 'realisasi', class : 'text-center' },
			{ data: 'show', name: 'show', class : 'text-center' },
        ],
        pageLength: 5,
        stateSave: true,
        lengthMenu: [[5,10,25,50,100,-1], [5,10,25,50,100,"All"]],
        deferRender: true,
        language: {
            paginate: {
                previous: "<i class='fas fa-angle-left'>",
                next: "<i class='fas fa-angle-right'>"
            }
        },
        aoColumnDefs: [
            { "bSortable": false, "aTargets": [5] }, 
            { "bSearchable": false, "aTargets": [5] }
        ],
        order:[[0, 'asc']],
        responsive:true,
        scrollY: "50vh",
        "preDrawCallback": function( settings ) {
            scrollPosition = $(".dataTables_scrollBody").scrollTop();
        },
        "drawCallback": function( settings ) {
            $(".dataTables_scrollBody").scrollTop(scrollPosition);
            if(typeof rowIndex != 'undefined') {
                dtable.row(rowIndex).nodes().to$().addClass('row_selected');                       
            }
            setTimeout( function () {
                $("[data-toggle='tooltip']").tooltip();
            }, 10)
        },
    }).columns.adjust().draw();

    setInterval(function(){ dtable.ajax.reload(function(){console.log("Refresh Automatic"); $(".tooltip").tooltip("hide");}, false); }, 60000);
    $('#refresh').click(function(){
        $('#refresh-img').show();
        $('#refresh').removeClass("btn-primary").addClass("btn-success").html('Refreshing');
        dtable.ajax.reload(function(){console.log("Refresh Manual")}, false);
        setTimeout(function(){
            $('#refresh').removeClass("btn-success").addClass("btn-primary").html('<i class="fas fa-sync-alt"></i> Refresh Data');
            $('#refresh-data').text("Refresh Data");
            $('#refresh-img').hide();
        }, 2000);
    });

    $(".generate").click(function() {
        $("#myGenerate").modal("show");
    });
});
</script>
@endsection
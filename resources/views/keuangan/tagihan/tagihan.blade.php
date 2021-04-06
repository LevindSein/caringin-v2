@extends('layout.master')

@section('title')
<title>Laporan Tagihan Semua Faslitas | BP3C</title>
@endsection

@section('judul')
<h6 class="h2 text-white d-inline-block mb-0">Tagihan Semua Faslitas {{$periode}}</h6>
@endsection

@section('button')
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
                    <table class="table table-flush" width="100%" id="tabel">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center" style="max-width:20%">Kontrol</th>
                                <th class="text-center" style="min-width:80px;max-width:40%">Nama</th>
                                <th class="text-center" style="max-width:25%">Tagihan</th>
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
@endsection

@section('js')
<script>
$(document).ready(function () {
    $('#tabel').DataTable({
        serverSide: true,
		ajax: {
			url: "/keuangan/laporan/tagihan/tagihan/?periode=" + <?php echo Session::get('periodetagihan')?>,
            cache:false,
		},
		columns: [
			{ data: 'kd_kontrol', name: 'kd_kontrol', class : 'text-center' },
			{ data: 'nama', name: 'nama', class : 'text-center-td' },
			{ data: 'ttl_tagihan', name: 'ttl_tagihan', class : 'text-center' },
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
            { "bSortable": false, "aTargets": [3] }, 
            { "bSearchable": false, "aTargets": [3] }
        ],
        order:[[0, 'asc']],
        responsive:true
    }).columns.adjust().draw();
});
</script>
@endsection
@extends('layout.master')

@section('title')
<title>Laporan Tunggakan Air Bersih | BP3C</title>
@endsection

@section('judul')
<h6 class="h2 text-white d-inline-block mb-0">Tunggakan Air Bersih {{$periode}}</h6>
@endsection

@section('button')
<a 
    href="{{url('keuangan/tunggakan/airbersih')}}"
    class="btn btn-sm btn-success"
    data-toggle="tooltip" data-original-title="Home">
    <i class="fas fa-home text-white"></i>
</a>
<button class="btn btn-sm btn-danger generate" data-toggle="tooltip" data-original-title="Generate"><i class="fas fa-fw fa-download text-white"></i></button>
<button class="btn btn-sm btn-info cari-periode" data-toggle="tooltip" data-original-title="Cari Periode"><i class="fas fa-fw fa-search text-white"></i></button>
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
                                <th class="text-center" style="max-width:25%">Tunggakan</th>
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
<div id="myModal" class="modal fade" role="dialog" tabIndex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cari Periode Tagihan</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-control-label" for="bulan">Bulan</label>
                    <select class="form-control" name="bulan" id="bulan" required>
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
                <div class="form-group">
                    <label class="form-control-label" for="tahun">Tahun</label>
                    <select class="form-control" name="tahun" id="tahun" required>
                        <?php $tahun = \App\Models\Tagihan::select('thn_tagihan')->groupBy('thn_tagihan')->orderBy('thn_tagihan','desc')->get();?>
                        @foreach($tahun as $t)
                        <option value="{{$t->thn_tagihan}}">{{$t->thn_tagihan}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button name="periode_button" id="periode_button" class="btn btn-primary">Cari</button>
                <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>

<div id="myGenerate" class="modal fade" role="dialog" tabIndex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Generate</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="{{url('keuangan/tunggakan/generate')}}" method="POST" target="_blank">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <span>Pilih Periode Tagihan yang ingin di cetak.</span>
                    </div>
                    <div class="form-group">
                        <label class="form-control-label" for="bulan_generate">Bulan</label>
                        <select class="form-control" name="bulan_generate" id="bulan_generate" required>
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
                    <div class="form-group">
                        <label class="form-control-label" for="tahun_generate">Tahun</label>
                        <select class="form-control" name="tahun_generate" id="tahun_generate" required>
                            <?php $tahun = \App\Models\Tagihan::select('thn_tagihan')->groupBy('thn_tagihan')->orderBy('thn_tagihan','desc')->get();?>
                            @foreach($tahun as $t)
                            <option value="{{$t->thn_tagihan}}">{{$t->thn_tagihan}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="hidden_data" value="airbersih"/>
                    <button type="submit" class="btn btn-primary">Cetak</button>
                    <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

@include("keuangan.tunggakan.details")
@endsection

@section('js')
<script>
$(document).ready(function () {
    var dtable = $('#tabel').DataTable({
        serverSide: true,
		ajax: {
			url: "/keuangan/tunggakan/airbersih/?periode=" + <?php echo Session::get('periodetagihan')?>,
            cache:false,
		},
		columns: [
			{ data: 'kd_kontrol', name: 'kd_kontrol', class : 'text-center' },
			{ data: 'nama', name: 'nama', class : 'text-center-td' },
			{ data: 'sel_airbersih', name: 'sel_airbersih', class : 'text-center' },
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

    $(".cari-periode").click(function() {
        $("#myModal").modal("show");
    });

    $(".generate").click(function() {
        $("#myGenerate").modal("show");
    });

    $('#periode_button').click(function(){
        var bulan = $("#bulan").val();
        var tahun = $("#tahun").val();
        var periode = tahun + "-" + bulan;

        window.location.href = "/keuangan/tunggakan/airbersih?periode=" + periode;
    });
    
    $(document).on('click', '.details', function(){
		id = $(this).attr('id');
        $(".divListrik").hide();
        $(".divAirBersih").show();
        $(".divKeamananIpk").hide();
        $(".divKebersihan").hide();
        $(".divAirKotor").hide();
        $(".divLain").hide();
        $(".divTagihan").hide();
        $(".fasilitas").text("DETAILS AIR BERSIH");
        $.ajax({
			url :"/keuangan/tagihan/show/" + id,
            cache:false,
			dataType:"json",
			success:function(data)
			{
                $(".kontrol").text(data.result.kd_kontrol);
                $(".periode").text(data.result.periode);
                $(".pengguna").text(data.result.nama);

                if(data.result.awal_airbersih !== null)
                    $(".awal-airbersih").text(data.result.awal_airbersih.toLocaleString("en-US"));
                else 
                    $(".awal-airbersih").html("&mdash;");
                
                if(data.result.akhir_airbersih !== null)
                    $(".akhir-airbersih").text(data.result.akhir_airbersih.toLocaleString("en-US"));
                else
                    $(".akhir-airbersih").html("&mdash;")
                
                if(data.result.pakai_airbersih !== null)
                    $(".pakai-airbersih").html("<span style='color:green;'>" + data.result.pakai_airbersih.toLocaleString("en-US") + "</span");
                else
                    $(".pakai-airbersih").html("&mdash;")

                $(".diskon-airbersih").html("<span style='color:red;'>" + data.result.dis_airbersih.toLocaleString("en-US") + "</span");
                $(".tagihan-airbersih").html("<span style='color:green;'>" + data.result.ttl_airbersih.toLocaleString("en-US") + "</span");
                $(".denda-airbersih").html("<span style='color:blue;'>" + data.result.den_airbersih.toLocaleString("en-US") + "</span");

                $(".realisasi-airbersih").html("<span style='color:green;'>" + data.result.rea_airbersih.toLocaleString("en-US") + "</span");
                $(".selisih-airbersih").html("<span style='color:red;'>" + data.result.sel_airbersih.toLocaleString("en-US") + "</span");

                if(data.result.no_alamat !== null)
                    $(".los").text(data.result.no_alamat);
                else
                    $(".los").html("&mdash;");

                if(data.result.jml_alamat !== null)
                    $(".jumlah").text(data.result.jml_alamat);
                else
                    $(".jumlah").html("&mdash;");

                if(data.result.lok_tempat !== null)
                    $(".lokasi").text(data.result.lok_tempat);
                else
                    $(".lokasi").html("&mdash;");
                
                if(data.result.sel_tagihan > 0 && data.result.stt_lunas == 0)
                    $(".pembayaran").html("<span style='color:red;'>Belum Lunas</span");
                else if(data.result.sel_tagihan == 0 && data.result.stt_lunas == 1)
                    $(".pembayaran").html("<span style='color:green;'>Lunas</span");
                else
                    $(".pembayaran").html("&times;");

                if(data.result.stt_publish == 1)
                    $(".status").html("<span style='color:green;'>Publish</span");
                else if(data.result.review == 0)
                    $(".status").html("<span style='color:red;'>Checking</span");
                else if(data.result.review == 2)
                    $(".status").html("<span style='color:blue;'>Edited</span");
                else
                    $(".status").html("<span style='color:red;'>Unpublish</span");

                if(data.result.via_publish !== null)
                    $(".publisher").text(data.result.via_publish);
                else
                    $(".publisher").html("&mdash;");
                    
                $(".pengelola").text(data.result.via_tambah);

                $("#show-details").modal("show");
            }
        });
    });
});
</script>
@endsection
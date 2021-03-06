@extends('layout.master')

@section('title')
<title>Pendapatan Bulanan | BP3C</title>
@endsection

@section('judul')
<h6 class="h2 text-white d-inline-block mb-0">Pendapatan Bulanan</h6>
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
                                <th class="text-center" style="max-width:20%">Bln.Bayar</th>
                                <th class="text-center" style="max-width:65%">Realisasi</th>
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
                            <option value="tagihan">Semua Fasilitas</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <span>Pilih periode pendapatan yang ingin di cetak.</span>
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
                    <input type="hidden" name="hidden_data" value="bulanan"/>
                    <button type="submit" class="btn btn-primary">Cetak</button>
                    <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="show-details" tabindex="-1" role="dialog" aria-labelledby="show-details" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="card bg-secondary border-0 mb-0">
                    <div class="card-header bg-gradient-vine">
                        <div class="text-white text-center titles"></div>
                    </div>
                    <div class="card-body px-lg-3 py-lg-3">
                        <div class="card-body">
                            <div class="card-profile-stats d-flex justify-content-center">
                                <div class="col">
                                    <div>
                                        <span class="description">Pendapatan</span>
                                        <span class="heading realisasi">&mdash;</span>
                                    </div>
                                    <div>
                                        <span class="description">Disc</span>
                                        <span class="heading diskon" style="color:red;">&mdash;</span>
                                    </div>
                                    <hr>
                                    <span class="heading">Rincian</span>
                                    <div class="row">
                                        <div class="col">
                                            <div class="card-profile-stats d-flex justify-content-between">
                                                <div>
                                                    <span class="description">Bayar Listrik</span>
                                                    <span class="heading bayar-listrik">&mdash;</span>
                                                </div>
                                                <div>
                                                    <span class="description">Denda Listrik</span>
                                                    <span class="heading denda-listrik">&mdash;</span>
                                                </div>
                                                <div>
                                                    <span class="description">Disc</span>
                                                    <span class="heading diskon-listrik" style="color:red;">&mdash;</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="card-profile-stats d-flex justify-content-between">
                                                <div>
                                                    <span class="description">Bayar Air Brs</span>
                                                    <span class="heading bayar-airbersih">&mdash;</span>
                                                </div>
                                                <div>
                                                    <span class="description">Denda Air Brs</span>
                                                    <span class="heading denda-airbersih">&mdash;</span>
                                                </div>
                                                <div>
                                                    <span class="description">Disc</span>
                                                    <span class="heading diskon-airbersih" style="color:red;">&mdash;</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="card-profile-stats d-flex justify-content-between">
                                                <div>
                                                    <span class="description">Bayar Keamanan IPK</span>
                                                    <span class="heading bayar-keamananipk">&mdash;</span>
                                                </div>
                                                <div>
                                                    <span class="description">Disc</span>
                                                    <span class="heading diskon-keamananipk" style="color:red;">&mdash;</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="card-profile-stats d-flex justify-content-between">
                                                <div>
                                                    <span class="description">Bayar Kebersihan</span>
                                                    <span class="heading bayar-kebersihan">&mdash;</span>
                                                </div>
                                                <div>
                                                    <span class="description">Disc</span>
                                                    <span class="heading diskon-kebersihan" style="color:red;">&mdash;</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="card-profile-stats d-flex justify-content-center">
                                                <div>
                                                    <span class="description">Bayar Air Kotor</span>
                                                    <span class="heading bayar-airkotor">&mdash;</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="card-profile-stats d-flex justify-content-center">
                                                <div>
                                                    <span class="description">Bayar Lain Lain</span>
                                                    <span class="heading bayar-lain">&mdash;</span>
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

@section('js')
<script>
$(document).ready(function () {
    var dtable = $('#tabel').DataTable({
        serverSide: true,
		ajax: {
			url: "/keuangan/pendapatan/bulanan",
            cache:false,
		},
		columns: [
			{ data: 'bln_bayar', name: 'bln_bayar', class : 'text-center' },
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
            { "bSortable": false, "aTargets": [2] }, 
            { "bSearchable": false, "aTargets": [2] }
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

    $(document).on('click', '.details', function(){
		id = $(this).attr('id');
        $.ajax({
			url :"/keuangan/pendapatan/show/bulanan/" + id,
            cache:false,
			dataType:"json",
			success:function(data)
			{
                $('.titles').html("<h1 style='color:white;font-weight:700;'>" + data.result.bulan + "</h1>");
                if(data.result.realisasi !== null)
                    $('.realisasi').text(Number(data.result.realisasi).toLocaleString('en-US'));
                else
                    $('.realisasi').html("&mdash;");
                    
                if(data.result.diskon !== null)
                    $('.diskon').text(Number(data.result.diskon).toLocaleString('en-US'));
                else
                    $('.diskon').html("&mdash;");

                if(data.result.byr_listrik !== null){
                    $('.bayar-listrik').text((data.result.byr_listrik - data.result.byr_denlistrik).toLocaleString('en-US'));
                    $('.denda-listrik').text(Number(data.result.byr_denlistrik).toLocaleString('en-US'));
                    $('.diskon-listrik').text(Number(data.result.dis_listrik).toLocaleString('en-US'));
                }
                else{
                    $('.bayar-listrik').html("&mdash;");
                    $('.denda-listrik').html("&mdash;");
                    $('.diskon-listrik').html("&mdash;");
                }

                if(data.result.byr_airbersih !== null){
                    $('.bayar-airbersih').text((data.result.byr_airbersih - data.result.byr_denairbersih).toLocaleString('en-US'));
                    $('.denda-airbersih').text(Number(data.result.byr_denairbersih).toLocaleString('en-US'));
                    $('.diskon-airbersih').text(Number(data.result.dis_airbersih).toLocaleString('en-US'));
                }
                else{
                    $('.bayar-airbersih').html("&mdash;");
                    $('.denda-airbersih').html("&mdash;");
                    $('.diskon-airbersih').html("&mdash;");
                }
                
                if(data.result.byr_keamananipk !== null){
                    $('.bayar-keamananipk').text(Number(data.result.byr_keamananipk).toLocaleString('en-US'));
                    $('.diskon-keamananipk').text(Number(data.result.dis_keamananipk).toLocaleString('en-US'));
                }
                else{
                    $('.bayar-keamananipk').html("&mdash;");
                    $('.diskon-keamananipk').html("&mdash;");
                }
                
                if(data.result.byr_kebersihan !== null){
                    $('.bayar-kebersihan').text(Number(data.result.byr_kebersihan).toLocaleString('en-US'));
                    $('.diskon-kebersihan').text(Number(data.result.dis_kebersihan).toLocaleString('en-US'));
                }
                else{
                    $('.bayar-kebersihan').html("&mdash;");
                    $('.diskon-kebersihan').html("&mdash;");
                }
                
                if(data.result.byr_airkotor !== null)
                    $('.bayar-airkotor').text(Number(data.result.byr_airkotor).toLocaleString('en-US'));
                else
                    $('.bayar-airkotor').html("&mdash;");

                if(data.result.byr_lain !== null)
                    $('.bayar-lain').text(Number(data.result.byr_lain).toLocaleString('en-US'));
                else
                    $('.bayar-lain').html("&mdash;");
			}
		});
        
        $('#show-details').modal('show');
	});
});
</script>
@endsection
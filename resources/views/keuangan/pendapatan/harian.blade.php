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
                                <th class="text-center" style="max-width:15%">Tgl.Bayar</th>
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
                            <option value="tagihan">Semua Fasilitas</option>
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
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="harian-details" tabindex="-1" role="dialog" aria-labelledby="show-details" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="card bg-secondary border-0 mb-0">
                    <div class="card-header bg-gradient-vine">
                        <div class="text-white text-center titles"></div>
                        <div class="text-white text-center bayars"></div>
                    </div>
                    <div class="card-body px-lg-3 py-lg-3">
                        <div class="card-body">
                            <div class="card-profile-stats d-flex justify-content-center">
                                <div class="col">
                                    <div>
                                        <span class="description">Pengguna</span>
                                        <span class="heading pengguna">&mdash;</span>
                                    </div>
                                    <div>
                                        <span class="description">Kasir</span>
                                        <span class="heading kasir">&mdash;</span>
                                    </div>
                                    <div>
                                        <span class="description">Tagihan</span>
                                        <span class="heading tagihan">&mdash;</span>
                                    </div>
                                    <div>
                                        <span class="description">Realisasi</span>
                                        <span class="heading realisasi">&mdash;</span>
                                    </div>
                                    <div>
                                        <span class="description">Selisih</span>
                                        <span class="heading selisih">&mdash;</span>
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

    $(document).on('click', '.details', function(){
		id = $(this).attr('id');
		nama = $(this).attr('nama');
		bayar = $(this).attr('bayar');
		$('.titles').html("<h1 style='color:white;font-weight:700;'>" + nama + "</h1>");
		$('.bayars').html("<h3 style='color:white;font-weight:500;'>" + bayar + "</h3>");
        $.ajax({
			url :"/keuangan/pendapatan/show/harian/" + id,
            cache:false,
			dataType:"json",
			success:function(data)
			{
                if(data.result.pengguna !== null)
                    $('.pengguna').text(data.result.pengguna);
                else
                    $('.pengguna').html("&mdash;");

                if(data.result.kasir !== null)
                    $('.kasir').text(data.result.nama);
                else
                    $('.kasir').html("&mdash;");
                    
                if(data.result.ttl_tagihan !== null)
                    $('.tagihan').text(data.result.ttl_tagihan.toLocaleString('en-US'));
                else
                    $('.tagihan').html("&mdash;");

                if(data.result.realisasi !== null)
                    $('.realisasi').text(data.result.realisasi.toLocaleString('en-US'));
                else
                    $('.realisasi').html("&mdash;");
                    
                if(data.result.sel_tagihan !== null)
                    $('.selisih').text(data.result.sel_tagihan.toLocaleString('en-US'));
                else
                    $('.selisih').html("&mdash;");
                    
                if(data.result.diskon !== null)
                    $('.diskon').text(data.result.diskon.toLocaleString('en-US'));
                else
                    $('.diskon').html("&mdash;");

                if(data.result.byr_listrik !== null){
                    $('.bayar-listrik').text((data.result.byr_listrik - data.result.byr_denlistrik).toLocaleString('en-US'));
                    $('.denda-listrik').text(data.result.byr_denlistrik.toLocaleString('en-US'));
                    $('.diskon-listrik').text(data.result.dis_listrik.toLocaleString('en-US'));
                }
                else{
                    $('.bayar-listrik').html("&mdash;");
                    $('.denda-listrik').html("&mdash;");
                    $('.diskon-listrik').html("&mdash;");
                }

                if(data.result.byr_airbersih !== null){
                    $('.bayar-airbersih').text((data.result.byr_airbersih - data.result.byr_denairbersih).toLocaleString('en-US'));
                    $('.denda-airbersih').text(data.result.byr_denairbersih.toLocaleString('en-US'));
                    $('.diskon-airbersih').text(data.result.dis_airbersih.toLocaleString('en-US'));
                }
                else{
                    $('.bayar-airbersih').html("&mdash;");
                    $('.denda-airbersih').html("&mdash;");
                    $('.diskon-airbersih').html("&mdash;");
                }
                
                if(data.result.byr_keamananipk !== null){
                    $('.bayar-keamananipk').text(data.result.byr_keamananipk.toLocaleString('en-US'));
                    $('.diskon-keamananipk').text(data.result.dis_keamananipk.toLocaleString('en-US'));
                }
                else{
                    $('.bayar-keamananipk').html("&mdash;");
                    $('.diskon-keamananipk').html("&mdash;");
                }
                
                if(data.result.byr_kebersihan !== null){
                    $('.bayar-kebersihan').text(data.result.byr_kebersihan.toLocaleString('en-US'));
                    $('.diskon-kebersihan').text(data.result.dis_kebersihan.toLocaleString('en-US'));
                }
                else{
                    $('.bayar-kebersihan').html("&mdash;");
                    $('.diskon-kebersihan').html("&mdash;");
                }
                
                if(data.result.byr_airkotor !== null)
                    $('.bayar-airkotor').text(data.result.byr_airkotor.toLocaleString('en-US'));
                else
                    $('.bayar-airkotor').html("&mdash;");

                if(data.result.byr_lain !== null)
                    $('.bayar-lain').text(data.result.byr_lain.toLocaleString('en-US'));
                else
                    $('.bayar-lain').html("&mdash;");
			}
		});
        
        $('#harian-details').modal('show');
	});
});
</script>
@endsection
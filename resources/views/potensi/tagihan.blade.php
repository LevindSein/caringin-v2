@extends('layout.master')

@section('title')
<title>Potensi | BP3C</title>
@endsection

@section('judul')
<h6 class="h2 text-white d-inline-block mb-0">Sisa tagihan</h6>
@endsection

@section('button')
<button class="btn btn-sm btn-success generate" data-toggle="tooltip" data-original-title="Generate"><i class="fas fa-fw fa-download text-white"></i></button>
@endsection

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive py-4">
                    <table class="table table-flush" width="100%" id="dtable">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center" style="min-width:60px;max-width:13%;">Kontrol</th>
                                <th class="text-center" style="min-width:90px;max-width:15%;">Nama</th>
                                <th class="text-center" style="min-width:70px;max-width:9%">Listrik</th>
                                <th class="text-center" style="min-width:70px;max-width:9%">Air&nbsp;Bersih</th>
                                <th class="text-center" style="min-width:70px;max-width:9%">K.aman&nbsp;IPK</th>
                                <th class="text-center" style="min-width:70px;max-width:9%">Kebersihan</th>
                                <th class="text-center" style="min-width:70px;max-width:9%">Air&nbsp;Kotor</th>
                                <th class="text-center" style="min-width:70px;max-width:9%">Lainnya</th>
                                <th class="text-center" style="min-width:70px;max-width:9%">Jumlah</th>
                                <th class="text-center" style="min-width:60px;max-width:9%">Details</th>
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
<div class="modal fade" id="total-details" tabindex="-1" role="dialog" aria-labelledby="show-details" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="card bg-secondary border-0 mb-0">
                    <div class="card-header bg-gradient-vine">
                        <h1 class="text-white text-center kontrol" style="font-weight:700"></h1>
                        <h2 class="text-white text-center fasilitas" style="font-weight:700"></h2>
                        <h2 class="text-white text-center periode" style="font-weight:700"></h2>
                    </div>
                    <div class="card-body px-lg-3 py-lg-3">
                        <div class="card-body modal-body-short">
                            <div class="card-profile-stats d-flex justify-content-center">
                                <div class="col">
                                    <div class="divListrik">
                                        <span class="heading">Listrik</span>
                                        <div class="row">
                                            <div class="col">
                                                <div class="card-profile-stats d-flex justify-content-between">
                                                    <div>
                                                        <span class="description">Daya</span>
                                                        <span class="heading daya-listrik">&mdash;</span>
                                                    </div>
                                                    <div>
                                                        <span class="description">Awal</span>
                                                        <span class="heading awal-listrik">&mdash;</span>
                                                    </div>
                                                    <div>
                                                        <span class="description">Akhir</span>
                                                        <span class="heading akhir-listrik">&mdash;</span>
                                                    </div>
                                                    <div>
                                                        <span class="description">Pakai</span>
                                                        <span class="heading pakai-listrik">&mdash;</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="card-profile-stats d-flex justify-content-between">
                                                    <div>
                                                        <span class="description">Disc</span>
                                                        <span class="heading diskon-listrik">&mdash;</span>
                                                    </div>
                                                    <div>
                                                        <span class="description">Tagihan</span>
                                                        <span class="heading tagihan-listrik">&mdash;</span>
                                                    </div>
                                                    <div>
                                                        <span class="description">Denda</span>
                                                        <span class="heading denda-listrik">&mdash;</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                    </div>
                                    <div class="divAirBersih">
                                        <span class="heading">Air Bersih</span>
                                        <div class="row">
                                            <div class="col">
                                                <div class="card-profile-stats d-flex justify-content-between">
                                                    <div>
                                                        <span class="description">Awal</span>
                                                        <span class="heading awal-airbersih">&mdash;</span>
                                                    </div>
                                                    <div>
                                                        <span class="description">Akhir</span>
                                                        <span class="heading akhir-airbersih">&mdash;</span>
                                                    </div>
                                                    <div>
                                                        <span class="description">Pakai</span>
                                                        <span class="heading pakai-airbersih">&mdash;</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="card-profile-stats d-flex justify-content-between">
                                                    <div>
                                                        <span class="description">Disc</span>
                                                        <span class="heading diskon-airbersih">&mdash;</span>
                                                    </div>
                                                    <div>
                                                        <span class="description">Tagihan</span>
                                                        <span class="heading tagihan-airbersih">&mdash;</span>
                                                    </div>
                                                    <div>
                                                        <span class="description">Denda</span>
                                                        <span class="heading denda-airbersih">&mdash;</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                    </div>
                                    <div class="divKeamananIpk">
                                        <span class="heading">Keamanan IPK</span>
                                        <div class="row">
                                            <div class="col">
                                                <div class="card-profile-stats d-flex justify-content-between">
                                                    <div>
                                                        <span class="description">Disc</span>
                                                        <span class="heading diskon-keamananipk">&mdash;</span>
                                                    </div>
                                                    <div>
                                                        <span class="description">Tagihan</span>
                                                        <span class="heading tagihan-keamananipk">&mdash;</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                    </div>
                                    <div class="divKebersihan">
                                        <span class="heading">Kebersihan</span>
                                        <div class="row">
                                            <div class="col">
                                                <div class="card-profile-stats d-flex justify-content-between">
                                                    <div>
                                                        <span class="description">Disc</span>
                                                        <span class="heading diskon-kebersihan">&mdash;</span>
                                                    </div>
                                                    <div>
                                                        <span class="description">Tagihan</span>
                                                        <span class="heading tagihan-kebersihan">&mdash;</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                    </div>
                                    <div class="divAirKotor">
                                        <span class="heading">Air Kotor</span>
                                        <div>
                                            <span class="description">Tagihan</span>
                                            <span class="heading tagihan-airkotor">&mdash;</span>
                                        </div>
                                        <hr>
                                    </div>
                                    <div class="divLain">
                                        <span class="heading">Lain - Lain</span>
                                        <div>
                                            <span class="description">Tagihan</span>
                                            <span class="heading tagihan-lain">&mdash;</span>
                                        </div>
                                        <hr>
                                    </div>
                                    <div class="divTagihan">
                                        <span class="heading">Jumlah Tagihan</span>
                                        <div class="row">
                                            <div class="col">
                                                <div class="card-profile-stats d-flex justify-content-between">
                                                    <div>
                                                        <span class="description">Listrik</span>
                                                        <span class="heading tagihan-listrik">&mdash;</span>
                                                    </div>
                                                    <div>
                                                        <span class="description">Keamanan IPK</span>
                                                        <span class="heading tagihan-keamananipk">&mdash;</span>
                                                    </div>
                                                    <div>
                                                        <span class="description">Air Kotor</span>
                                                        <span class="heading tagihan-airkotor">&mdash;</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="card-profile-stats d-flex justify-content-between">
                                                    <div>
                                                        <span class="description">Air Bersih</span>
                                                        <span class="heading tagihan-airbersih">&mdash;</span>
                                                    </div>
                                                    <div>
                                                        <span class="description">Kebersihan</span>
                                                        <span class="heading tagihan-kebersihan">&mdash;</span>
                                                    </div>
                                                    <div>
                                                        <span class="description">Lainnya</span>
                                                        <span class="heading tagihan-lain">&mdash;</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="card-profile-stats d-flex justify-content-between">
                                                    <div>
                                                        <span class="description">Diskon</span>
                                                        <span class="heading tagihan-diskon">&mdash;</span>
                                                    </div>
                                                    <div>
                                                        <span class="description">Denda</span>
                                                        <span class="heading tagihan-denda">&mdash;</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                    </div>
                                    <div>
                                        <span class="description">Pengguna</span>
                                        <span class="heading pengguna"></span>
                                    </div>
                                    <div>
                                        <span class="description">Nomor Los</span>
                                        <span class="heading los"></span>
                                    </div>
                                    <div>
                                        <span class="description">Jumlah Los</span>
                                        <span class="heading jumlah"></span>
                                    </div>
                                    <div>
                                        <span class="description">Lokasi</span>
                                        <span class="heading lokasi" style="font-size:.875rem"></span>
                                    </div>
                                    <div>
                                        <span class="description pembayaran-heading">Pembayaran</span>
                                        <span class="heading pembayaran"></span>
                                    </div>
                                    <div>
                                        <span class="description">Status</span>
                                        <span class="heading status"></span>
                                    </div>
                                    <div>
                                        <span class="description">Terakhir Dipublish Oleh</span>
                                        <span class="heading publisher"></span>
                                    </div>
                                    <div>
                                        <span class="description">Terakhir Dikelola Oleh</span>
                                        <span class="heading pengelola"></span>
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

<div
    class="modal fade"
    id="generate"
    role="dialog"
    tabIndex="-1"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Rekap Sisa Tagihan</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form class="user" action="{{url('master/kasir/sisa')}}" target="_blank" method="GET">
                <div class="modal-body">
                    <div class="form-group col-lg-12">
                        <select class="form-control" name="sisatagihan" id="sisatagihan" required>
                            <option selected value="all">Semua</option>
                            <option value="sebagian">Sebagian</option>
                        </select>
                        <div class="form-group col-lg-12" style="display:none;" id="divrekapsisa">
                            <label class="form-control-label" for="sebagian">Blok <span style="color:red;">*</span></label>
                            <div class="form-group">
                                <select class="sebagian" name="sebagian[]" id="sebagian" required multiple></select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-sm">Cetak</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $(document).ready(function(){
        var dtable = $('#dtable').DataTable({
            serverSide: true,
            ajax: {
                url: "/potensi/tagihan",
                cache:false,
            },
            columns: [
                { data: 'kd_kontrol'     , name: 'kd_kontrol'     , class : 'text-center' },
                { data: 'nama'           , name: 'nama'           , class : 'text-center-td' },
                { data: 'ttl_listrik'    , name: 'ttl_listrik'    , class : 'text-center background-gray' },
                { data: 'ttl_airbersih'  , name: 'ttl_airbersih'  , class : 'text-center background-gray' },
                { data: 'ttl_keamananipk', name: 'ttl_keamananipk', class : 'text-center background-gray' },
                { data: 'ttl_kebersihan' , name: 'ttl_kebersihan' , class : 'text-center background-gray' },
                { data: 'ttl_airkotor'   , name: 'ttl_airkotor'   , class : 'text-center background-gray' },
                { data: 'ttl_lain'       , name: 'ttl_lain'       , class : 'text-center background-gray' },
                { data: 'ttl_tagihan'    , name: 'ttl_tagihan'    , class : 'text-center' },
                { data: 'show'           , name: 'show'           , class : 'text-center' },
            ],
            order: [[ 0, "asc" ]],
            stateSave: true,
            deferRender: true,
            aLengthMenu: [[5,10,25,50,100,-1], [5,10,25,50,100,"All"]],
            language: {
                paginate: {
                    previous: "<i class='fas fa-angle-left'>",
                    next: "<i class='fas fa-angle-right'>"
                }
            },
            aoColumnDefs: [
                { "bSortable": false, "aTargets": [9] },
                { "bSearchable": false, "aTargets": [9] }
            ],
            pageLength: 5,
            scrollX: true,
            scrollY: "60vh",
            fixedColumns:   {
                "rightColumns": 2,
                "leftColumns": 2,
            },
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
        });

        $(document).on('click', '.details', function(){
            id = $(this).attr('id');
            $(".divListrik").show();
            $(".divAirBersih").show();
            $(".divKeamananIpk").show();
            $(".divKebersihan").show();
            $(".divAirKotor").show();
            $(".divLain").show();
            $(".divTagihan").hide();
            $(".divHistory").show();
            $(".fasilitas").text("DETAILS TAGIHAN");
            $.ajax({
                url :"/tagihan/show/details/" + id,
                cache:false,
                dataType:"json",
                success:function(data)
                {
                    $(".kontrol").text(data.result.kd_kontrol);
                    $(".periode").text(data.result.periode);
                    $(".pembayaran-heading").text("Pembayaran Tagihan");
                    $(".history-heading").text("Riwayat Details Tagihan");

                    $(".pengguna").text(data.result.nama);

                    if(data.result.daya_listrik !== null)
                        $(".daya-listrik").text(data.result.daya_listrik.toLocaleString("en-US"));
                    else
                        $(".daya-listrik").html("&mdash;");

                    if(data.result.awal_listrik !== null)
                        $(".awal-listrik").text(data.result.awal_listrik.toLocaleString("en-US"));
                    else
                        $(".awal-listrik").html("&mdash;")

                    if(data.result.akhir_listrik !== null)
                        $(".akhir-listrik").text(data.result.akhir_listrik.toLocaleString("en-US"));
                    else
                        $(".akhir-listrik").html("&mdash;")

                    if(data.result.pakai_listrik !== null)
                        $(".pakai-listrik").html("<span style='color:green;'>" + data.result.pakai_listrik.toLocaleString("en-US") + "</span");
                    else
                        $(".pakai-listrik").html("&mdash;")

                    $(".diskon-listrik").html("<span style='color:red;'>" + data.result.dis_listrik.toLocaleString("en-US") + "</span");
                    $(".tagihan-listrik").html("<span style='color:green;'>" + data.result.ttl_listrik.toLocaleString("en-US") + "</span");
                    $(".denda-listrik").html("<span style='color:blue;'>" + data.result.den_listrik.toLocaleString("en-US") + "</span");

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

                    $(".diskon-keamananipk").html("<span style='color:red;'>" + data.result.dis_keamananipk.toLocaleString("en-US") + "</span");
                    $(".tagihan-keamananipk").html("<span style='color:green;'>" + data.result.ttl_keamananipk.toLocaleString("en-US") + "</span");

                    $(".diskon-kebersihan").html("<span style='color:red;'>" + data.result.dis_kebersihan.toLocaleString("en-US") + "</span");
                    $(".tagihan-kebersihan").html("<span style='color:green;'>" + data.result.ttl_kebersihan.toLocaleString("en-US") + "</span");

                    $(".tagihan-airkotor").html("<span style='color:green;'>" + data.result.ttl_airkotor.toLocaleString("en-US") + "</span");

                    $(".tagihan-lain").html("<span style='color:green;'>" + data.result.ttl_lain.toLocaleString("en-US") + "</span");

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

                    $("#total-details").modal("show");
                }
            });
        });

        $(".generate").click(function(){
            $("#generate").modal('show');
        });

        $("#sebagian").prop('required',false);

        $('#sebagian').select2({
            placeholder: '--- Pilih Blok ---',
            ajax: {
                url: "/cari/blok",
                dataType: 'json',
                delay: 250,
                processResults: function (blok) {
                    return {
                    results:  $.map(blok, function (bl) {
                        return {
                        text: bl.nama,
                        id: bl.nama
                        }
                    })
                    };
                },
                cache: true
            }
        });

        $("#sisatagihan").change(function() {
            var val = $(this).val();
            if(val === "all") {
                $("#divrekapsisa").hide();
                $("#sebagian").prop('required',false);
                $('#sebagian').val('');
                $('#sebagian').html('');
            }
            else if(val === "sebagian") {
                $("#divrekapsisa").show();
                $("#sebagian").prop('required',true);
            }
        });
    });
</script>
@endsection

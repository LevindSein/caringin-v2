@extends('layout.master')

@section('title')
<title>Potensi | BP3C</title>
@endsection

@section('judul')
<h6 class="h2 text-white d-inline-block mb-0 page-title">Tempat Usaha Pasif</h6>
@endsection

@section('button')
<button class="btn btn-sm btn-success generate" data-toggle="tooltip" data-original-title="Generate"><i class="fas fa-fw fa-download text-white"></i></button>
<a class="dropdown-toggle btn btn-sm btn-danger" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Menu</a>
<div class="dropdown-menu dropdown-menu-right">
    <div class="dropdown-header">Tempat Usaha:</div>
    <a class="dropdown-item {{ (request()->is('potensi/tempatusaha?data=1')) ? 'active' : '' }}" href="{{url('potensi/tempatusaha?data=1')}}">Aktif</a>
    <a class="dropdown-item {{ (request()->is('potensi/tempatusaha?data=2')) ? 'active' : '' }}" href="{{url('potensi/tempatusaha?data=2')}}">Pasif</a>
    <a class="dropdown-item {{ (request()->is('potensi/tempatusaha?data=3')) ? 'active' : '' }}" href="{{url('potensi/tempatusaha?data=3')}}">Bebas Bayar</a>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive py-4">
                    <table class="table table-flush table-hover table-striped" width="100%" id="dtable">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center" style="max-width:15%">Kontrol</th>
                                <th class="text-center" style="min-width:100px;max-width:25%">Pengguna</th>
                                <th class="text-center" style="min-width:100px;max-width:25%">Lok</th>
                                <th class="text-center" style="max-width:5%">Jml.Los</th>
                                <th class="text-center" style="max-width:20%">Usaha</th>
                                <th class="text-center" style="max-width:10%">Details</th>
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
<div class="modal fade" id="show-details" tabindex="-1" role="dialog" aria-labelledby="show-details" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="card bg-secondary border-0 mb-0">
                    <div class="card-header bg-gradient-vine">
                        <div class="text-white text-center titles"></div>
                    </div>
                    <div class="card-body px-lg-3 py-lg-3">
                        <div class="card-header text-center">
                            <div class="d-flex justify-content-between">
                                <a href="#" class="btn btn-sm btn-success" id="whatsapp-number"> <i class="fas fa-fw fa-phone-alt fa-sm text-white-50"></i> Whatsapp</a>
                                <a href="#" class="btn btn-sm btn-danger" id="email-number"><i class="fas fa-fw fa-envelope fa-sm text-white-50"></i> Email</a>
                            </div>
                        </div>
                        <div class="card-body modal-body-short">
                            <div class="card-profile-stats d-flex justify-content-center">
                                <div class="col">
                                    <div>
                                        <span class="description">Pengguna</span>
                                        <span class="heading pengguna"></span>
                                    </div>
                                    <div>
                                        <span class="description">Pemilik</span>
                                        <span class="heading pemilik"></span>
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
                                        <span class="heading lokasi"></span>
                                    </div>
                                    <div>
                                        <span class="description">Usaha</span>
                                        <span class="heading usaha"></span>
                                    </div>
                                    <div>
                                        <span class="description">Status</span>
                                        <span class="heading status"></span>
                                    </div>
                                    <div>
                                        <span class="description">Keterangan</span>
                                        <span class="heading keterangan"></span>
                                    </div>
                                    <div class="divListrik">
                                        <hr>
                                        <span class="heading">Listrik</span>
                                        <div class="row">
                                            <div class="col">
                                                <div class="card-profile-stats d-flex justify-content-between">
                                                    <div>
                                                        <span class="description">Diskon</span>
                                                        <span class="heading diskon-listrik" style="font-size:.875rem;">&mdash;</span>
                                                    </div>
                                                    <div>
                                                        <span class="description">Kode</span>
                                                        <span class="heading alat-listrik" style="font-size:.875rem;">&mdash;</span>
                                                    </div>
                                                    <div>
                                                        <span class="description">Daya</span>
                                                        <span class="heading daya-listrik" style="font-size:.875rem;">&mdash;</span>
                                                    </div>
                                                    <div>
                                                        <span class="description">Stand</span>
                                                        <span class="heading stand-listrik" style="font-size:.875rem;">&mdash;</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="divAirBersih">
                                        <hr>
                                        <span class="heading">Air Bersih</span>
                                        <div class="row">
                                            <div class="col">
                                                <div class="card-profile-stats d-flex justify-content-between">
                                                    <div>
                                                        <span class="description">Diskon</span>
                                                        <span class="heading diskon-airbersih" style="font-size:.875rem;">&mdash;</span>
                                                    </div>
                                                    <div>
                                                        <span class="description">Kode</span>
                                                        <span class="heading alat-airbersih" style="font-size:.875rem;">&mdash;</span>
                                                    </div>
                                                    <div>
                                                        <span class="description">Stand</span>
                                                        <span class="heading stand-airbersih" style="font-size:.875rem;">&mdash;</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="divKeamananIpk">
                                        <hr>
                                        <span class="heading">Keamanan IPK</span>
                                        <div class="row">
                                            <div class="col">
                                                <div class="card-profile-stats d-flex justify-content-between">
                                                    <div>
                                                        <span class="description">Diskon</span>
                                                        <span class="heading diskon-keamananipk" style="font-size:.875rem;">&mdash;</span>
                                                    </div>
                                                    <div>
                                                        <span class="description">Tarif</span>
                                                        <span class="heading subtotal-keamananipk" style="font-size:.875rem;">&mdash;</span>
                                                        <span class="heading per-unit-keamananipk" style="font-size:.875rem;">&mdash;</span>
                                                    </div>
                                                    <div>
                                                        <span class="description">Total</span>
                                                        <span class="heading total-keamananipk" style="font-size:.875rem;">&mdash;</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="divKebersihan">
                                        <hr>
                                        <span class="heading">Kebersihan</span>
                                        <div class="row">
                                            <div class="col">
                                                <div class="card-profile-stats d-flex justify-content-between">
                                                    <div>
                                                        <span class="description">Diskon</span>
                                                        <span class="heading diskon-kebersihan" style="font-size:.875rem;">&mdash;</span>
                                                    </div>
                                                    <div>
                                                        <span class="description">Tarif</span>
                                                        <span class="heading subtotal-kebersihan" style="font-size:.875rem;">&mdash;</span>
                                                        <span class="heading per-unit-kebersihan" style="font-size:.875rem;">&mdash;</span>
                                                    </div>
                                                    <div>
                                                        <span class="description">Total</span>
                                                        <span class="heading total-kebersihan" style="font-size:.875rem;">&mdash;</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="divAirKotor">
                                        <hr>
                                        <span class="heading">Air Kotor</span>
                                        <div class="row">
                                            <div class="col">
                                                <div class="card-profile-stats d-flex justify-content-center">
                                                    <div>
                                                        <span class="description">Total</span>
                                                        <span class="heading total-airkotor" style="font-size:.875rem;">&mdash;</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="divLain">
                                        <hr>
                                        <span class="heading">Lain - Lain</span>
                                        <div class="row">
                                            <div class="col">
                                                <div class="card-profile-stats d-flex justify-content-center">
                                                    <div>
                                                        <span class="description">Total</span>
                                                        <span class="heading total-lain" style="font-size:.875rem;">&mdash;</span>
                                                    </div>
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

<div id="generate" class="modal fade" role="dialog" tabIndex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Download Excel</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="{{url('potensi/tempatusaha/download')}}" method="POST" target="_blank">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-control-label" for="stt_tempat">Pilih Status Tempat Usaha</label>
                        <select class="form-control" name="stt_tempat" id="stt_tempat" required>
                            <option value="1">Aktif</option>
                            <option value="2">Pasif</option>
                            <option value="3">Bebas Bayar</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Download</button>
                    <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $(document).ready(function(){
        var id;
        var params = getUrlParameter('data');

        if(params == 1){
            aktif();
        }
        else if(params == 3){
            bebas();
        }
        else{
            pasif();
        }

        $(".aktif").click(function(){
            aktif();
        });

        $(".pasif").click(function(){
            pasif();
        });

        $(".bebas").click(function(){
            bebas();
        });

        function dtableInit(val){
            $('#dtable').DataTable().clear().destroy();
            return $('#dtable').DataTable({
                serverSide: true,
                ajax: {
                    url: "/potensi/tempatusaha?data=" + val,
                    cache:false,
                },
                columns: [
                    { data: 'kd_kontrol', name: 'kd_kontrol', class : 'text-center' },
                    { data: 'id_pengguna', name: 'user.nama', class : 'text-center-td' },
                    { data: 'lok_tempat', name: 'lok_tempat', class : 'text-center-td' },
                    { data: 'jml_alamat', name: 'jml_alamat', class : 'text-center' },
                    { data: 'bentuk_usaha', name: 'bentuk_usaha', class : 'text-center-td' },
                    { data: 'show', name: 'show', class : 'text-center' },
                ],
                stateSave: true,
                deferRender: true,
                pageLength: 10,
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
                responsive: true,
                scrollY: "50vh",
                scrollX: true,
                aLengthMenu: [[5,10,25,50,100], [5,10,25,50,100]],
                preDrawCallback: function( settings ) {
                    scrollPosition = $(".dataTables_scrollBody").scrollTop();
                },
                drawCallback: function( settings ) {
                    $(".dataTables_scrollBody").scrollTop(scrollPosition);
                    if(typeof rowIndex != 'undefined') {
                        dtable.row(rowIndex).nodes().to$().addClass('row_selected');
                    }
                    setTimeout( function () {
                        $("[data-toggle='tooltip']").tooltip();
                    }, 10)
                },
            });
        }

        setInterval(function(){
            dtableReload();
        }, 5000);

        function dtableReload(){
            dtable.ajax.reload(function(){
                console.log("Refresh Automatic")
            }, false);
        }

        function getUrlParameter(sParam) {
            var sPageURL = window.location.search.substring(1),
                sURLVariables = sPageURL.split('&'),
                sParameterName,
                i;

            for (i = 0; i < sURLVariables.length; i++) {
                sParameterName = sURLVariables[i].split('=');

                if (sParameterName[0] === sParam) {
                    return typeof sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
                }
            }
            return false;
        }

        function aktif(){
            $(".page-title").text("Tempat Usaha Aktif");
            window.history.replaceState(null, null, "?data=1");
            dtable = dtableInit(1);
        }

        function pasif(){
            $(".page-title").text("Tempat Usaha Pasif");
            window.history.replaceState(null, null, "?data=2");
            dtable = dtableInit(2);
        }

        function bebas(){
            $(".page-title").text("Tempat Usaha Bebas Bayar");
            window.history.replaceState(null, null, "?data=3");
            dtable = dtableInit(3);
        }

        $(document).on('click', '.details', function(){
            $('.divListrik').hide();
            $('.divAirBersih').hide();
            $('.divKeamananIpk').hide();
            $('.divKebersihan').hide();
            $('.divAirKotor').hide();
            $('.divLain').hide();
            id = $(this).attr('id');
            kontrol = $(this).attr('nama');
            $('.titles').html("<h1 style='color:white;font-weight:700;'>" + kontrol + "</h1>");
            $.ajax({
                url :"/tempatusaha/show/"+id,
                cache:false,
                dataType:"json",
                success:function(data)
                {
                    if(data.result.pengguna !== null)
                        $('.pengguna').text(data.result.pengguna);
                    else
                        $('.pengguna').html("&mdash;");

                    if(data.result.pemilik !== null)
                        $('.pemilik').text(data.result.pemilik);
                    else
                        $('.pemilik').html("&mdash;");

                    $('.los').text(data.result.no_alamat);
                    $('.jumlah').text(data.result.jml_alamat);

                    if(data.result.lok_tempat !== null)
                        $('.lokasi').text(data.result.lok_tempat);
                    else
                        $('.lokasi').html("&mdash;");

                    if(data.result.bentuk_usaha !== null)
                        $('.usaha').text(data.result.bentuk_usaha);
                    else
                        $('.usaha').html("&mdash;");

                    if(data.result.stt_tempat == 1){
                        $('.status').html("<span style='color:green;'>Aktif</span");
                        $('.keterangan').html("&mdash;");
                    }
                    else if(data.result.stt_tempat == 2){
                        $('.status').html("<span style='color:red;'>Pasif</span");
                        $('.keterangan').text(data.result.ket_tempat);
                    }
                    else if(data.result.stt_tempat == 3){
                        $('.status').html("<span style='color:blue;'>Bebas Bayar</span");
                        $('.keterangan').html("&mdash;");
                    }

                    //Fasilitas
                    if(data.result.faslistrik != null){
                        $('.divListrik').show();
                        if(data.result.diskonlistrik != null)
                            $('.diskon-listrik').html("<span style='color:red;'>&check;</span>");
                        else
                            $('.diskon-listrik').html("&times;");

                        $('.alat-listrik').text(data.result.alatlistrik);
                        $('.daya-listrik').text(data.result.dayalistrik.toLocaleString("en-US"));
                        $('.stand-listrik').text(data.result.standlistrik.toLocaleString("en-US"));
                    }
                    if(data.result.fasairbersih != null){
                        $('.divAirBersih').show();
                        if(data.result.diskonairbersih != null)
                            $('.diskon-airbersih').html("<span style='color:red;'>&check;</span>");
                        else
                            $('.diskon-airbersih').html("&times;");

                        $('.alat-airbersih').text(data.result.alatairbersih);
                        $('.stand-airbersih').text(data.result.standairbersih.toLocaleString("en-US"));
                    }
                    if(data.result.faskeamananipk != null){
                        $('.divKeamananIpk').show();
                        if(data.result.diskonkeamananipk != null)
                            $('.diskon-keamananipk').html("<span style='color:red;'>" + data.result.diskonkeamananipk.toLocaleString("en-US") + "</span>");
                        else
                            $('.diskon-keamananipk').html("&times;");

                        $('.per-unit-keamananipk').html("<span style='color:blue;text-transform:lowercase;'>(" + data.result.perunitkeamananipk.toLocaleString("en-US") + " / unit)</span>");
                        $('.subtotal-keamananipk').html("<span style='color:blue;'>" + data.result.subtotalkeamananipk.toLocaleString("en-US") + "</span>");
                        $('.total-keamananipk').html("<span style='color:green;'>" + data.result.totalkeamananipk.toLocaleString("en-US") + "</span>");
                    }
                    if(data.result.faskebersihan != null){
                        $('.divKebersihan').show();
                        if(data.result.diskonkebersihan != null)
                            $('.diskon-kebersihan').html("<span style='color:red;'>" + data.result.diskonkebersihan.toLocaleString("en-US") + "</span>");
                        else
                            $('.diskon-kebersihan').html("&times;");

                        $('.per-unit-kebersihan').html("<span style='color:blue;text-transform:lowercase;'>(" + data.result.perunitkebersihan.toLocaleString("en-US") + " / unit)</span>");
                        $('.subtotal-kebersihan').html("<span style='color:blue;'>" + data.result.subtotalkebersihan.toLocaleString("en-US") + "</span>");
                        $('.total-kebersihan').html("<span style='color:green;'>" + data.result.totalkebersihan.toLocaleString("en-US") + "</span>");
                    }
                    if(data.result.fasairkotor != null){
                        $('.divAirKotor').show();
                        $('.total-airkotor').html("<span style='color:green;'>" + data.result.totalairkotor.toLocaleString("en-US") + "</span>");
                    }
                    if(data.result.faslain != null){
                        $('.divLain').show();
                        $('.total-lain').html("<span style='color:green;'>" + data.result.totallain.toLocaleString("en-US") + "</span>");
                    }

                    if(data.result.hp_pengguna != null){
                        $('#whatsapp-number').attr("href", "https://api.whatsapp.com/send?phone=" + data.result.hp_pengguna + "&text=Halo%20" + data.result.pengguna +
                        "%20Selaku%20Pengguna%20Tempat%20" + kontrol +"%0A*Selamat%20Berniaga%20Mitra%20BP3C*").attr("target", "_blank").css("pointer-events","").removeClass("btn-dark").addClass("btn-success");
                    }
                    else{
                        $('#whatsapp-number').attr("href","#").removeAttr("target").css("pointer-events", "none").removeClass("btn-success").addClass("btn-dark");
                    }

                    if(data.result.email_pengguna != null){
                        $('#email-number').attr("href", "mailto:" + data.result.email_pengguna).attr("target", "_blank").css("pointer-events","").removeClass("btn-dark").addClass("btn-danger");
                    }
                    else{
                        $('#email-number').attr("href","#").removeAttr("target").css("pointer-events", "none").removeClass("btn-danger").addClass("btn-dark");
                    }

                    $('#show-details').modal('show');
                }
            });
        });

        $(".generate").click(function(){
            $("#generate").modal('show');
        });
    });
</script>
@endsection

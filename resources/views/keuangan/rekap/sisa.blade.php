@extends('layout.master')

@section('title')
<title>Rekap Sisa Tagihan | BP3C</title>
@endsection

@section('judul')
<h6 class="h2 text-white d-inline-block mb-0">Rekap Sisa Tagihan</h6>
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
                                <th class="text-center" style="max-width:20%">Blok</th>
                                <th class="text-center" style="max-width:65%">Tagihan</th>
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
<div
    class="modal fade"
    id="myGenerate"
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
            <form class="user" action="{{url('keuangan/rekap/generate')}}" target="_blank" method="POST">
                @csrf
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
                    <input type="hidden" name="hidden_data" value="sisa"/>
                    <button type="submit" class="btn btn-primary">Cetak</button>
                    <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
                </div>
            </form>
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
			url: "/keuangan/rekap/sisa",
            cache:false,
		},
		columns: [
			{ data: 'blok', name: 'blok', class : 'text-center' },
			{ data: 'tagihan', name: 'tagihan', class : 'text-center' },
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
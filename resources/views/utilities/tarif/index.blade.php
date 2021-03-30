@extends('layout.master')

@section('title')
<title>Data Tarif | BP3C</title>
@endsection

@section('judul')
<h6 class="h2 text-white d-inline-block mb-0">Data Tarif</h6>
@endsection

@section('button')
@if(Session::get('role') == 'master')
<div>
    <button 
        type="button"
        name="add_tarif"
        id="add_tarif" 
        class="btn btn-sm btn-success">
        <i class="fas fa-fw fa-plus fa-sm text-white-50"></i> Tarif</button>
</div>
@endif
@endsection

@section('content')
<span id="form_result"></span>
<div class="row">
    <div class="col-xl-12">
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="nav-wrapper">
                    <ul class="nav nav-pills flex-column flex-md-row" id="tabs-icons-text" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0 active" id="tab-c-0" data-toggle="tab" href="#tab-animated-0" role="tab">Listrik</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0" id="tab-c-1" data-toggle="tab" href="#tab-animated-1" role="tab">Air Bersih</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0" id="tab-c-2" data-toggle="tab" href="#tab-animated-2" role="tab">Keamanan IPK</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0" id="tab-c-3" data-toggle="tab" href="#tab-animated-3" role="tab">Kebersihan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0" id="tab-c-4" data-toggle="tab" href="#tab-animated-4" role="tab">Air Kotor</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0" id="tab-c-6" data-toggle="tab" href="#tab-animated-6" role="tab">Lain Lain</a>
                        </li>
                    </ul>
                </div>
                <div class="text-right" id="div-refresh" style="display:none;">
                    <img src="{{asset('img/updating.gif')}}" style="display:none;" id="refresh-img"/><button class="btn btn-sm btn-primary" id="refresh"><i class="fas fa-sync-alt"></i> Refresh Data</button>
                </div>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="tab-animated-0" role="tabpanel">
                        @include('utilities.tarif.listrik')
                    </div>
                    <div class="tab-pane fade" id="tab-animated-1" role="tabpanel">
                        @include('utilities.tarif.airbersih')
                    </div>
                    <div class="tab-pane fade" id="tab-animated-2" role="tabpanel">
                        @include('utilities.tarif.keamananipk')
                    </div>
                    <div class="tab-pane fade" id="tab-animated-3" role="tabpanel">
                        @include('utilities.tarif.kebersihan')
                    </div>
                    <div class="tab-pane fade" id="tab-animated-4" role="tabpanel">
                        @include('utilities.tarif.airkotor')
                    </div>
                    <div class="tab-pane fade" id="tab-animated-6" role="tabpanel">
                        @include('utilities.tarif.lain')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('modal')
<div id="confirmModal" class="modal fade" role="dialog" tabIndex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title titles">Apakah yakin hapus data tarif?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Pilih "Hapus" di bawah ini jika anda yakin untuk menghapus data tarif.</div>
            <div class="modal-footer">
            	<button type="button" name="ok_button" id="ok_button" class="btn btn-danger">Hapus</button>
                <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>

<div
    class="modal fade"
    id="myModal"
    tabIndex="-1"
    role="dialog"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title titles" id="exampleModalLabel"></h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="form_tarif" class="user" method="POST">
                <div class="modal-body">
                    @csrf
                    <div class="row col-lg-12">
                        <div class="col-sm-12">
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="checkKeamananIpk"
                                    id="myCheck1"
                                    data-related-item="myDiv1">
                                <label class="form-control-label" for="myCheck1">
                                    Keamanan IPK
                                </label>
                            </div>
                            <div class="form-group" style="display:none" id="displayKeamananIpk">
                                <div class="input-group" id="myDiv1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroupPrepend">Rp.</span>
                                    </div>
                                    <input 
                                        type="text" 
                                        autocomplete="off" 
                                        class="form-control"
                                        name="keamananIpk"
                                        id="keamananIpk"
                                        placeholder="Masukkan Tarif Baru"
                                        aria-describedby="inputGroupPrepend">
                                </div>            
                                <div class="keamananipk-persen">
                                    <div class="input-group">
                                        <input 
                                            type="number"
                                            max="100"
                                            min="0"
                                            autocomplete="off" 
                                            class="form-control prs_keamanan"
                                            name="prs_keamanan"
                                            id="prs_keamanan"
                                            oninput="functionKeamanan()"
                                            placeholder="% Keamanan"
                                            aria-describedby="inputGroupPrepend">
                                        <input 
                                            type="number"
                                            max="100"
                                            min="0"
                                            autocomplete="off" 
                                            class="form-control prs_ipk"
                                            name="prs_ipk"
                                            id="prs_ipk"
                                            oninput="functionIpk()"
                                            placeholder="% IPK"
                                            aria-describedby="inputGroupPrepend">
                                    </div>
                                </div>
                            </div>

                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="checkKebersihan"
                                    id="myCheck2"
                                    data-related-item="myDiv2">
                                <label class="form-control-label" for="myCheck2">
                                    Kebersihan
                                </label>
                            </div>
                            <div class="form-group" style="display:none" id="displayKebersihan">
                                <div class="input-group" id="myDiv2">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroupPrepend">Rp.</span>
                                    </div>
                                    <input 
                                        type="text" 
                                        autocomplete="off" 
                                        class="form-control"
                                        name="kebersihan"
                                        id="kebersihan"
                                        placeholder="Masukkan Tarif Baru"
                                        aria-describedby="inputGroupPrepend">
                                </div>
                            </div>

                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="checkAirKotor"
                                    id="myCheck3"
                                    data-related-item="myDiv3">
                                <label class="form-control-label" for="myCheck3">
                                    Air Kotor
                                </label>
                            </div>
                            <div class="form-group" style="display:none" id="displayAirKotor">
                                <div class="input-group" id="myDiv3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroupPrepend">Rp.</span>
                                    </div>
                                    <input 
                                        type="text" 
                                        autocomplete="off" 
                                        class="form-control"
                                        name="airkotor"
                                        id="airkotor"
                                        placeholder="Masukkan Tarif Baru"
                                        aria-describedby="inputGroupPrepend">
                                </div>
                            </div>
                            
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="checkLain"
                                    id="myCheck5"
                                    data-related-item="myDiv5">
                                <label class="form-control-label" for="myCheck5">
                                    Lain - Lain
                                </label>
                            </div>
                            <div class="form-group" style="display:none" id="displayLain">
                                <div class="input-group" id="myDiv5">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroupPrepend">Rp.</span>
                                    </div>
                                    <input 
                                        type="text" 
                                        autocomplete="off" 
                                        class="form-control"
                                        name="lain"
                                        id="lain"
                                        placeholder="Masukkan Tarif Baru"
                                        aria-describedby="inputGroupPrepend">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="hidden_tarif" name="fasilitas" value="tarif" />
                    <input type="hidden" name="action" id="action" value="Add" />
                    <input type="hidden" name="hidden_id" id="hidden_id" />
                    <input type="submit" class="btn btn-primary btn-sm" name="action_btn" id="action_btn" value="Tambah" />
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{asset('js/utilities/tarif.js')}}"></script>
<script>
function functionKeamanan() {
    $(".keamananipk-persen").each(function() { 
        var keamanan = document.getElementById("prs_keamanan").value;

        var ipk = 100 - keamanan;
        $(this).find('.prs_ipk').val(ipk);
    });
}
function functionIpk() {
    $(".keamananipk-persen").each(function() { 
        var ipk = document.getElementById("prs_ipk").value;

        var keamanan = 100 - ipk;
        $(this).find('.prs_keamanan').val(keamanan);
    });
}
</script>
@endsection
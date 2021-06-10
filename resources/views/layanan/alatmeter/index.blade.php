@extends('layout.master')

@section('title')
<title>Layanan Alat Meter | BP3C</title>
@endsection

@section('judul')
<h6 class="h2 text-white d-inline-block mb-0">Pasang Alat Meter</h6>
@endsection

@section('button')
<div>
    <a href="{{url('layanan/alatmeter/ganti')}}" type="button" name="ganti_alat" id="ganti_alat" class="btn btn-sm btn-success">Ganti Alat</a>
    <a href="{{url('layanan/alatmeter/bongkar')}}" name="bongkar_alat" id="bongkar_alat" class="btn btn-sm btn-danger">Bongkar Alat</a>
    <a href="{{url('layanan/alatmeter/riwayat')}}" name="riwayat" id="riwayat" class="btn btn-sm btn-primary">Data Riwayat</a>
</div>
@endsection

@section('content')
<span id="form_result"></span>
<div class="row">
    <div class="col-xl-12">
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-xl-6">
                        <form id="form_pasang_alat" class="user">
                            @csrf
                            <div class="form-group text-center">
                                <h2 class="h2 mb-0 langkah">Langkah 1 : Pilih Jenis Pemasangan</h2>
                            </div>
                            <div class="form-group">
                                <div class="progress mb-4">
                                    <div
                                        class="progress-bar bg-success"
                                        role="progressbar"
                                        id="progress_pasang">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group tab" style="display:none;">
                                <label class="form-control-label" for="jenis_alat">Jenis Pemasangan Alat Meter</label><span style="color:red;">*</span>
                                <select required name="jenis_alat" id="jenis_alat" class="form-control">
                                    <option value="listrik">Listrik</option>
                                    <option value="airbersih">Air Bersih</option>
                                </select>
                            </div>
                            <div class="form-group tab" style="display:none;">
                                <div>
                                    <label class="form-control-label" for="kontrol">Pilih Tempat</label><span style="color:red;">*</span>
                                    <select class="kontrol form-control" name="kontrol" id="kontrol" keterangan="Pilih Tempat" required></select>
                                </div>
                            </div>
                            <div class="form-group tab" style="display:none;">
                                <div>
                                    <label class="form-control-label pilih-alat" for="alat">Pilih Alat</label><span style="color:red;">*</span>
                                    <select class="alat form-control" name="alat" id="alat" required></select>
                                </div>
                            </div>
                            <div class="form-group text-right">
                                <button type="button" id="prevBtn" class="btn btn-light">Kembali</button>
                                <button type="button" id="nextBtn" class="btn btn-success">Lanjut</button>
                            </div>
                        </form>
                    </div>
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
$(document).ready(function(){
    $("#prevBtn").click(function() {
        nextPrev(-1);
    });

    $("#nextBtn").click(function() {
        nextPrev(1);
    });

    var currentTab = 0; // Current tab is set to be the first tab (0)
    showTab(currentTab); // Display the current tab

    function showTab(n) {
        // This function will display the specified tab of the form...
        var x = $(".tab");
        x[n].style.display = "block";
        //... and fix the Previous/Next buttons:
        if (n == 0) {
            $("#prevBtn").css("display","none");
        } else {
            $("#prevBtn").css("display","inline");
        }
        if (n == (x.length - 1)) {
            $("#nextBtn").html("Submit").addClass("btn-primary").removeClass("btn-success");
        } else {
            $("#nextBtn").html("Lanjut").addClass("btn-success").removeClass("btn-primary");
        }

        var langkah = n + 1;

        //... and run a function that will display the correct step indicator:
        fixStepIndicator(langkah);
    }

    function nextPrev(n) {
        // This function will figure out which tab to display
        var x = $(".tab");
        // Exit the function if any field in the current tab is invalid:
        if (n == 1 && !validateForm()) return false;
        // Hide the current tab:
        x[currentTab].style.display = "none";
        // Increase or decrease the current tab by 1:
        currentTab = currentTab + n;
        // if you have reached the end of the form...
        if (currentTab >= x.length) {
            // ... the form gets submitted:
            $("#form_pasang_alat").submit();
            return false;
        }
        // Otherwise, display the correct tab:
        showTab(currentTab);
    }
 
    function validateForm() {
        // This function deals with validation of the form fields
        var x, y, i, valid = true;
        x = $(".tab");
        y = x[currentTab].getElementsByTagName("select");
        // A loop that checks every input field in the current tab:
        for (i = 0; i < y.length; i++) {
            // If a field is empty...
            if (y[i].value == "") {
                var msg = y[i].getAttribute("keterangan");
                $.notify({
                    icon: 'fas fa-exclamation',
                    title: 'Lengkapi Data',
                    message: msg,
                },{
                    type: "danger",
                    animate: { enter: "animated fadeInRight", exit: "animated fadeOutRight" },
                    delay: 1000,
                    timer: 1000,
                })
                valid = false;
            }
        }

        return valid; // return the valid status
    }

    function fixStepIndicator(n) {
        var percent = (n / 5) * 100;
        $("#progress_pasang").css("width", percent + "%");

        if (n == 1)
            $(".langkah").text("Langkah " + n + " : Pilih Jenis Pemasangan");
        else if (n == 2)
            $(".langkah").text("Langkah " + n + " : Pilih Tempat Usaha");
        else if (n == 3){
            $(".langkah").text("Langkah " + n + " : Pendaftaran");
            if($("#jenis_alat").val() == "listrik"){
                $(".pilih-alat").text("Pilih Alat Listrik");
                $('#alat').val('').select2({
                    placeholder: '--- Pilih Alat ---',
                    ajax: {
                        url: "/cari/alatlistrik",
                        dataType: 'json',
                        delay: 250,
                        processResults: function (alats) {
                            return {
                            results:  $.map(alats, function (alat) {
                                return {
                                text: alat.kode + ' - ' + alat.nomor + ' (' + alat.akhir +  ' - ' + alat.daya + ' W)',
                                id: alat.id
                                }
                            })
                            };
                        },
                        cache: true
                    }
                });
            }
            else{
                $(".pilih-alat").text("Pilih Alat Air Bersih");
                $('#alat').val('').select2({
                    placeholder: '--- Pilih Alat ---',
                    ajax: {
                        url: "/cari/alatair",
                        dataType: 'json',
                        delay: 250,
                        processResults: function (alats) {
                            return {
                            results:  $.map(alats, function (alat) {
                                return {
                                text: alat.kode + ' - ' + alat.nomor + ' (' + alat.akhir + ')',
                                id: alat.id
                                }
                            })
                            };
                        },
                        cache: true
                    }
                });
            }
        }
    }

    $('#kontrol').select2({
        placeholder: '--- Pilih Tempat ---',
        ajax: {
            url: "/cari/alamat",
            dataType: 'json',
            delay: 250,
            processResults: function (alamat) {
                return {
                results:  $.map(alamat, function (al) {
                    return {
                    text: al.kd_kontrol,
                    id: al.id
                    }
                })
                };
            },
            cache: true
        }
    });
});
</script>
@endsection
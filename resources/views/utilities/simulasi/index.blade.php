@extends('layout.master')

@section('title')
<title>Simulasi Tarif | BP3C</title>
@endsection

@section('judul')
<h6 class="h2 text-white d-inline-block mb-0">Simulasi Tarif</h6>
@endsection

@section('content')
<span id="form_result"></span>
<div class="row">
    <div class="col-xl-12">
        <div class="card shadow mb-4">
            <form id="form_simulasi" action="{{url('utilities/simulasi')}}" method="POST" target="_blank">
                @csrf
                <div class="card-body">
                    <div class="row justify-content-center">
                        <div class="col-lg-12">
                            <div class="p-4 row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="simulasi">Simulasi Tarif</label>
                                        <select class="form-control" name="simulasi" id="simulasi" required>
                                            <option selected hidden value="">Pilih Tarif</option>
                                            <option value="listrik">Listrik</option>
                                            <option value="airbersih">Air Bersih</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label">Gunakan Data Tagihan</label>
                                        <div class="input-group">
                                            <select class="form-control" name="bulan" id="bulan" required>
                                                <option selected hidden value="">Pilih Bulan</option>
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
                                            <select class="form-control" name="tahun" id="tahun" required>
                                                <option selected hidden value="">Pilih Tahun</option>
                                                @foreach($tahun as $t)
                                                <option value="{{$t->thn_tagihan}}">{{$t->thn_tagihan}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <input type="hidden" id="hidden_id" name="hidden_id"/>
                                    <input type="submit" class="btn btn-primary btn-user btn-block" value="Simulasikan" />
                                </div>
                                <div class="col-lg-6" style="display:none;" id="divTarifListrik">
                                    <div class="form-group col-lg-12">
                                        <label class="form-control-label" for="rekmin">Tarif Rekmin <span style="color:red;">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputGroupPrepend">Rp.</span>
                                            </div>
                                            <input 
                                                required
                                                <?php if($listrik != NULL) { ?>
                                                value="{{$listrik->trf_rekmin}}"
                                                <?php } ?>
                                                type="text" 
                                                autocomplete="off" 
                                                class="form-control shadow"
                                                name="rekmin" 
                                                id="rekmin" 
                                                placeholder="Tarif Rekmin"
                                                aria-describedby="inputGroupPrepend">
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <label class="form-control-label" for="blok1">Tarif Blok 1 <span style="color:red;">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputGroupPrepend">Rp.</span>
                                            </div>
                                            <input 
                                                required
                                                <?php if($listrik != NULL) { ?>
                                                value="{{number_format($listrik->trf_blok1)}}"
                                                <?php } ?>
                                                type="text" 
                                                autocomplete="off" 
                                                class="form-control shadow"
                                                name="blok1" 
                                                id="blok1" 
                                                placeholder="Tarif Blok 1" 
                                                aria-describedby="inputGroupPrepend">
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <label class="form-control-label" for="blok2">Tarif Blok 2 <span style="color:red;">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputGroupPrepend">Rp.</span>
                                            </div>
                                            <input 
                                                required
                                                <?php if($listrik != NULL) { ?>
                                                value="{{number_format($listrik->trf_blok2)}}"
                                                <?php } ?>
                                                type="text" 
                                                autocomplete="off" 
                                                class="form-control shadow"
                                                name="blok2" 
                                                id="blok2"
                                                placeholder="Tarif Blok 2" 
                                                aria-describedby="inputGroupPrepend">
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <label class="form-control-label" for="waktu">Waktu Kerja <span style="color:red;">*</span></label>
                                        <div class="input-group">
                                            <input 
                                                required
                                                <?php if($listrik != NULL) { ?>
                                                value="{{$listrik->trf_standar}}"
                                                <?php } ?>
                                                type="number" 
                                                autocomplete="off"
                                                min="0"
                                                max="24"
                                                class="form-control shadow"
                                                name="waktu" 
                                                id="waktu" 
                                                placeholder="Waktu Kerja" 
                                                aria-describedby="inputGroupPrepend">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputGroupPrepend">Jam</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <label class="form-control-label" for="bebanListrik">Beban Daya <span style="color:red;">*</span></label>
                                        <div class="input-group">
                                            <input 
                                                required
                                                <?php if($listrik != NULL) { ?>
                                                value="{{number_format($listrik->trf_beban)}}"
                                                <?php } ?>
                                                type="text" 
                                                autocomplete="off" 
                                                class="form-control shadow"
                                                name="bebanListrik" 
                                                id="bebanListrik"
                                                placeholder="Beban Daya" 
                                                aria-describedby="inputGroupPrepend">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputGroupPrepend">Watt</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <label class="form-control-label" for="bpju">Tarif BPJU <span style="color:red;">*</span></label>
                                        <div class="input-group">
                                            <input 
                                                required
                                                <?php if($listrik != NULL) { ?>
                                                value="{{$listrik->trf_bpju}}"
                                                <?php } ?>
                                                type="number"
                                                min="0"
                                                max="100" 
                                                autocomplete="off" 
                                                class="form-control shadow"
                                                name="bpju" 
                                                id="bpju"
                                                placeholder="Tarif BPJU" 
                                                aria-describedby="inputGroupPrepend">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputGroupPrepend">%</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <label class="form-control-label" for="denda1">Tarif Denda 1 <span style="color:red;">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputGroupPrepend">Rp.</span>
                                            </div>
                                            <input 
                                                required
                                                <?php if($listrik != NULL) { ?>
                                                value="{{number_format($listrik->trf_denda)}}"
                                                <?php } ?>
                                                type="text" 
                                                autocomplete="off" 
                                                class="form-control shadow"
                                                name="denda1" 
                                                id="denda1"
                                                placeholder="Tarif Denda &#8804; 4400" 
                                                aria-describedby="inputGroupPrepend">
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <label class="form-control-label" for="denda2">Tarif Denda 2 <span style="color:red;">*</span></label>
                                        <div class="input-group">
                                            <input 
                                                required
                                                <?php if($listrik != NULL) { ?>
                                                value="{{$listrik->trf_denda_lebih}}"
                                                <?php } ?>
                                                type="number"
                                                min="0"
                                                max="100" 
                                                autocomplete="off" 
                                                class="form-control shadow"
                                                name="denda2" 
                                                id="denda2"
                                                placeholder="Tarif Denda > 4400" 
                                                aria-describedby="inputGroupPrepend">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputGroupPrepend">%</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <label class="form-control-label" for="ppnListrik">PPN <span style="color:red;">*</span></label>
                                        <div class="input-group">
                                            <input 
                                                required
                                                <?php if($listrik != NULL) { ?>
                                                value="{{$listrik->trf_ppn}}"
                                                <?php } ?>
                                                type="number"
                                                min="0"
                                                max="100" 
                                                autocomplete="off" 
                                                class="form-control shadow"
                                                name="ppnListrik" 
                                                id="ppnListrik"
                                                placeholder="PPN" 
                                                aria-describedby="inputGroupPrepend">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputGroupPrepend">%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6" style="display:none;" id="divTarifAirBersih">
                                    <div class="form-group col-lg-12">
                                        <label class="form-control-label" for="tarif1">Tarif 1 <span style="color:red;">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputGroupPrepend">Rp.</span>
                                            </div>
                                            <input 
                                                required
                                                <?php if($airbersih != NULL) { ?>
                                                value="{{number_format($airbersih->trf_1)}}"
                                                <?php } ?>
                                                type="text" 
                                                autocomplete="off" 
                                                class="form-control shadow"
                                                name="tarif1" 
                                                id="tarif1" 
                                                placeholder="Pemakaian &#8804; 10 M&#179;"
                                                aria-describedby="inputGroupPrepend">
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <label class="form-control-label" for="tarif2">Tarif 2 <span style="color:red;">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputGroupPrepend">Rp.</span>
                                            </div>
                                            <input 
                                                required
                                                <?php if($airbersih != NULL) { ?>
                                                value="{{number_format($airbersih->trf_2)}}"
                                                <?php } ?>
                                                type="text" 
                                                autocomplete="off" 
                                                class="form-control shadow"
                                                name="tarif2" 
                                                id="tarif2"
                                                placeholder="Pemakaian > 10 M&#179;" 
                                                aria-describedby="inputGroupPrepend">
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <label class="form-control-label" for="pemeliharaan">Tarif Pemeliharaan <span style="color:red;">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputGroupPrepend">RP.</span>
                                            </div>
                                            <input 
                                                required
                                                <?php if($airbersih != NULL) { ?>
                                                value="{{number_format($airbersih->trf_pemeliharaan)}}"
                                                <?php } ?>
                                                type="text" 
                                                autocomplete="off" 
                                                class="form-control shadow"
                                                name="pemeliharaan" 
                                                id="pemeliharaan" 
                                                placeholder="Waktu Kerja" 
                                                aria-describedby="inputGroupPrepend">
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <label class="form-control-label" for="bebanAir">Tarif Beban <span style="color:red;">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputGroupPrepend">Rp.</span>
                                            </div>
                                            <input 
                                                required
                                                <?php if($airbersih != NULL) { ?>
                                                value="{{number_format($airbersih->trf_beban)}}"
                                                <?php } ?>
                                                type="text" 
                                                autocomplete="off" 
                                                class="form-control shadow"
                                                name="bebanAir" 
                                                id="bebanAir"
                                                placeholder="Beban Air" 
                                                aria-describedby="inputGroupPrepend">
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <label class="form-control-label" for="arkot">Tarif Air Kotor <span style="color:red;">*</span></label>
                                        <div class="input-group">
                                            <input 
                                                required
                                                <?php if($airbersih != NULL) { ?>
                                                value="{{$airbersih->trf_arkot}}"
                                                <?php } ?>
                                                type="number"
                                                min="0"
                                                max="100"
                                                autocomplete="off" 
                                                class="form-control shadow"
                                                name="arkot" 
                                                id="arkot"
                                                placeholder="Tarif Air Kotor" 
                                                aria-describedby="inputGroupPrepend">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputGroupPrepend">%</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <label class="form-control-label" for="dendaAir">Tarif Denda <span style="color:red;">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputGroupPrepend">Rp.</span>
                                            </div>
                                            <input 
                                                required
                                                <?php if($airbersih != NULL) { ?>
                                                value="{{number_format($airbersih->trf_denda)}}"
                                                <?php } ?>
                                                type="text" 
                                                autocomplete="off" 
                                                class="form-control shadow"
                                                name="dendaAir" 
                                                id="dendaAir"
                                                placeholder="Tarif Denda Air" 
                                                aria-describedby="inputGroupPrepend">
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <label class="form-control-label" for="ppnAir">PPN <span style="color:red;">*</span></label>
                                        <div class="input-group">
                                            <input 
                                                required
                                                <?php if($airbersih != NULL) { ?>
                                                value="{{$airbersih->trf_ppn}}"
                                                <?php } ?>
                                                type="number"
                                                min="0"
                                                max="100"
                                                autocomplete="off" 
                                                class="form-control shadow"
                                                name="ppnAir" 
                                                id="ppnAir"
                                                placeholder="PPN" 
                                                aria-describedby="inputGroupPrepend">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputGroupPrepend">%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@include('home.footer')
@endsection

@section('js')
<script src="{{asset('js/utilities/simulasi.min.js')}}"></script>
@endsection
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="p-4">
            <form class="user" id="form_air" method="POST">
                @csrf
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
                <div class="form-group col-lg-12">
                    <label class="form-control-label" for="pasangAir">Pasang Alat <span style="color:red;">*</span></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroupPrepend">Rp.</span>
                        </div>
                        <input 
                            required
                            <?php if($airbersih != NULL) { ?>
                            value="{{number_format($airbersih->trf_pasang)}}"
                            <?php } ?>
                            type="text" 
                            autocomplete="off" 
                            class="form-control shadow"
                            name="pasangAir" 
                            id="pasangAir"
                            placeholder="Tarif Pemasangan Baru atau Ganti" 
                            aria-describedby="inputGroupPrepend">
                    </div>
                </div>
                <div class="form-group col-lg-12">
                    <input type="hidden" id="hidden_air" name="fasilitas" value="air" />
                    @if(Session::get('role') == 'master')
                        <input type="submit" class="btn btn-primary btn-user btn-block" name="air" id="air" fas="air" value="Simpan" />
                    @endif
                </div>
            </form> 
        </div>
    </div>
</div>
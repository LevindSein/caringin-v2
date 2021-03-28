<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="p-4">
            <form class="user" id="form_listrik" method="POST">
                @csrf
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
                <div class="form-group col-lg-12">
                    <label class="form-control-label" for="pasang">Pasang Alat <span style="color:red;">*</span></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroupPrepend">Rp.</span>
                        </div>
                        <input 
                            required
                            <?php if($listrik != NULL) { ?>
                            value="{{number_format($listrik->trf_pasang)}}"
                            <?php } ?>
                            type="text" 
                            autocomplete="off" 
                            class="form-control shadow"
                            name="pasangListrik" 
                            id="pasangListrik"
                            placeholder="Tarif Pemasangan Baru atau Ganti" 
                            aria-describedby="inputGroupPrepend">
                    </div>
                </div>
                <div class="form-group col-lg-12">
                    <input type="hidden" id="hidden_listrik" name="fasilitas" value="listrik" />
                    @if(Session::get('role') == 'master')
                        <input type="submit" class="btn btn-primary btn-lg btn-block" name="listrik" id="listrik" value="Simpan" />
                    @endif
                </div>
            </form>  
        </div>
    </div>
</div>
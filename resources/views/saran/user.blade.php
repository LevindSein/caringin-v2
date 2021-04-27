<div class="row justify-content-center">
    <div class="col-xl-6">
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="form-group text-center">
                    <h2 class="h2 mb-0">Form Saran</h2>
                </div>
                <form id="form_saran">
                    @csrf
                    <div class="form-group col-lg-12">
                        <label class="form-control-label" for="nama">Nama<span style="color:red;">*</span></label>
                        <input
                            required
                            autocomplete="off"
                            type="text"
                            style="text-transform: capitalize;"
                            name="nama"
                            class="form-control"
                            id="nama"
                            minlength="2"
                            maxlength="30"
                            placeholder="Masukkan Nama">
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label class="form-control-label" for="email">Email</label>
                            <div class="input-group">
                                <input type="text" autocomplete="off" class="form-control" maxlength="20" name="email" id="email" placeholder="youremail" aria-describedby="inputGroupPrepend">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">@gmail.com</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-lg-12">
                        <label class="form-control-label" for="hp">No. Handphone<span style="color:red;">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroupPrepend">+62</span>
                            </div>
                            <input required type="tel" autocomplete="off" class="form-control" maxlength="12" name="hp" id="hp" placeholder="8783847xxx" aria-describedby="inputGroupPrepend">
                        </div>
                    </div>
                    <div class="form-group col-lg-12">
                        <label class="form-control-label" for="keterangan">Saran<span style="color:red;">*</span></label>
                        <textarea required id="keterangan" name="keterangan" class="form-control" placeholder="Masukkan Saran . . ." style="height:40vh;"></textarea>
                    </div>
                    <div class="form-group col-lg-12">
                        <Input type="submit" id="kirim" value="Kirim" class="btn btn-primary btn-user btn-block">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<span class="form_result"></span>
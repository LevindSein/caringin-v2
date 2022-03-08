<div id="tunggakan-modal" class="modal fade" role="dialog" tabIndex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cari Data Tunggakan</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="{{url('data/tunggakan')}}" target="_blank" method="GET">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-control-label">Blok Tempat</label>
                        <select class="form-control" name="blok_tunggakan" id="blok-tunggakan" required>
                            <?php $blok_tunggakan = \App\Models\Blok::select('nama')->orderBy('nama','asc')->get();?>
                            <option value="semua">Semua</option>
                            @foreach($blok_tunggakan as $t)
                            <option value="{{$t->nama}}">{{$t->nama}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-control-label">Dari</label>
                        <div class="input-group">
                            <select class="form-control" name="bulan_dari_tunggakan" id="bulan-dari-tunggakan" required>
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

                            <select class="form-control" name="tahun_dari_tunggakan" id="tahun-dari-tunggakan" required>
                                <?php $tahun_tunggakan = \App\Models\Tagihan::select('thn_tagihan')->groupBy('thn_tagihan')->orderBy('thn_tagihan','desc')->get();?>
                                @foreach($tahun_tunggakan as $t)
                                <option value="{{$t->thn_tagihan}}">{{$t->thn_tagihan}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-control-label">Ke</label>
                        <div class="input-group">
                            <select class="form-control" name="bulan_ke_tunggakan" id="bulan-ke-tunggakan" required>
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

                            <select class="form-control" name="tahun_ke_tunggakan" id="tahun-ke-tunggakan" required>
                                @foreach($tahun as $t)
                                <option value="{{$t->thn_tagihan}}">{{$t->thn_tagihan}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Cari</button>
                    <button class="btn btn-light" data-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $("#data-tunggakan").click(function(){
        $("#tunggakan-modal").modal('show');
    });
</script>

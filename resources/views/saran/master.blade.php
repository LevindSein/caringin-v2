<div class="row">
    <div class="col-xl-12">
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="text-right">
                    <img src="{{asset('img/updating.gif')}}" style="display:none;" id="refresh-img"/><button class="btn btn-sm btn-primary" id="refresh"><i class="fas fa-sync-alt"></i> Refresh Data</button>
                </div>
                <div class="table-responsive py-4">
                    <table class="table table-flush table-hover table-striped" width="100%" id="tabelSaran">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center" style="max-width:15%">Tanggal</th>
                                <th class="text-center" style="min-width:100px;max-width:20%">Nama</th>
                                <th class="text-center" style="min-width:250px;max-width:35%">Saran</th>
                                <th class="text-center" style="max-width:15%">Status</th>
                                <th class="text-center" style="max-width:15%">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
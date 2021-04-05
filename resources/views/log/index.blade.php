@extends('layout.master')

@section('title')
<title>Riwayat Login | BP3C</title>
@endsection

@section('judul')
<h6 class="h2 text-white d-inline-block mb-0">Riwayat Login</h6>
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
                    <table class="table table-flush" width="100%" id="tabelLog">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center" style="min-width:100px;max-width:20%">Username</th>
                                <th class="text-center" style="min-width:100px;max-width:20%">Nama</th>
                                <th class="text-center" style="min-width:70px;max-width:15%">KTP</th>
                                <th class="text-center" style="max-width:15%">Role</th>
                                <th class="text-center" style="min-width:90px;max-width:15%">Platform</th>
                                <th class="text-center" style="min-width:90px;max-width:15%">Login</th>
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

@section('js')
<script src="{{asset('js/log/log.min.js')}}"></script>
@endsection
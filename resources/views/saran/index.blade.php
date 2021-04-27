@extends('layout.master')

@section('title')
<title>Kotak Saran | BP3C</title>
@endsection

@section('judul')
@if(Session::get('role') == 'master')
<h6 class="h2 text-white d-inline-block mb-0">Kotak Saran</h6>
@endif
@endsection

@section('button')
@endsection

@section('content')
<span class="form_result"></span>
@if(Session::get('role') == 'master')
@include('saran.master')
@else
@include('saran.user')
@endif
@include('home.footer')
@endsection

@section('modal')
@endsection

@section('js')
@if(Session::get('role') == 'master')
<script src="{{asset('js/saran/master.js')}}"></script>
@else
<script src="{{asset('js/saran/user.js')}}"></script>
@endif
@endsection
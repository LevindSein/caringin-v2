@if ($message = Session::get('success'))
<div class="alert alert-success alert-block" id="success-alert">
    <strong>Sukses ! </strong>{{ $message }}
</div>
@endif


@if ($message = Session::get('error'))
<div class="alert alert-danger alert-block" id="error-alert"> 
    <strong>Maaf ! </strong>{{ $message }} 
</div>
@endif


@if ($message = Session::get('warning'))
<div class="alert alert-warning alert-block" id="warning-alert">
	<strong>Warning ! </strong> {{ $message }}
</div>
@endif


@if ($message = Session::get('info'))
<div class="alert alert-info alert-block" id="warning-alert">	
	<strong>Info ! </strong>{{ $message }}
</div>
@endif

<script>
    $(document).ready(function () {
        $("#success-alert,#warning-alert,#error-alert,#info-alert").fadeTo(1000, 500).slideUp(1200);
    });
</script>
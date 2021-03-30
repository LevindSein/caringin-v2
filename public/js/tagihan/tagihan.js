$(document).ready(function(){
    badge();
    var sync_text = '';
    var periode = '';
    var sync = '';
    $.ajax({
        url :"/tagihan/sync/initiate",
        cache:false,
        dataType:"json",
        success:function(data)
        {
            if(data.result.status){
                $("#sinkronisasi").show();
                $("#sinkronisasi-data").text(data.result.sync_text);
                if(data.result.sync_text == "Synchronize")
                    $("#sinkronisasi").addClass("synchronize").removeClass("unsynchronize");
                else
                    $("#sinkronisasi").addClass("unsynchronize").removeClass("synchronize");

                sync_text = data.result.sync_text;
                periode = data.result.periode;
                sync = data.result.sync;
            }
            else{
                $("#sinkronisasi").hide();
            }
        }
    })

    function badge(){
        $.ajax({
            url :"/tagihan/sync/badge",
            cache:false,
            dataType:"json",
            success:function(data)
            {
                $('.badge-listrik').html(data.result.listrik);
                $('.badge-air').html(data.result.air);
            }
        })
    }
    setInterval(function(){ 
        badge()
    }, 5000);

    $(document).on('click', '.synchronize', function(){
		$('.titles').text("Sinkronisasi Tagihan Periode " + periode + " ?");
		$('#sync-notif').html('Dengan melakukan sinkronisasi tagihan, maka data tagihan untuk periode ' + periode + ' akan <b>menggunakan Tarif yang sudah ditentukan</b>. Jika setuju silakan <b>Submit</b>.');
		$('#sync_status').val('synchronize');
        $('#sync_button').removeClass("btn-danger").addClass("btn-primary");
		$('#syncModal').modal('show');
	});
    $(document).on('click', '.unsynchronize', function(){
		$('.titles').text("Batalkan Tagihan Periode " + periode + " ?");
		$('#sync-notif').html('<b style="color:red;">WARNING!</b> Membatalkan tagihan, maka <b>semua data tagihan</b> untuk periode ' + periode + ' <b>akan dihapus</b>. <b>Kecuali, Tagihan yang sudah terbayar</b>. Jika setuju silakan <b>Submit</b>.');
		$('#sync_status').val('unsynchronize');
        $('#sync_button').removeClass("btn-primary").addClass("btn-danger");
		$('#syncModal').modal('show');
	});

    $('#form_sync').on('submit', function(event){
        event.preventDefault();
        $('#refresh').removeClass("btn-primary").addClass("btn-success").html('Refreshing');
        $('#refresh-img').show();
		var action_url = '';
        $('#syncModal').modal('hide');
        $('#form_result').html('<div class="alert alert-info" id="info-alert"> <strong>Penting ! </strong> Tunggu Hingga Status Konfirmasi Muncul disini.</div>')

        if($('#sync_status').val() == 'unsynchronize')
		{
			action_url = "/tagihan/sync/unsynchronize/" + sync;
		}
        
		if($('#sync_status').val() == 'synchronize')
		{
			action_url = "/tagihan/sync/synchronize/" + sync;
        }

		$.ajax({
			url: action_url,
            cache:false,
			method:"POST",
			data:$(this).serialize(),
			dataType:"json",
			success:function(data)
			{
                $('#form_result').show();
				var html = '';
				if(data.errors)
				{
                    html = '<div class="alert alert-danger" id="error-alert"> <strong>Maaf ! </strong>' + data.errors + '</div>';
				}
				if(data.success)
				{
					html = '<div class="alert alert-success" id="success-alert"> <strong>Sukses ! </strong>' + data.success + '</div>';
                }
				$('#form_result').html(html);
                $("#success-alert,#error-alert,#info-alert,#warning-alert")
                    .fadeTo(1000, 500)
                    .slideUp(1000, function () {
                        $("#success-alert,#error-alert").slideUp(500);
                });
                setTimeout(function(){
                    $('#refresh').removeClass("btn-success").addClass("btn-primary").html('<i class="fas fa-sync-alt"></i> Refresh Data');
                    $('#refresh-data').text("Refresh Data");
                    $('#refresh-img').hide();
                    location.reload();
                }, 2000);
            },
            error:function(){
                $('#refresh').removeClass("btn-success").addClass("btn-primary").html('<i class="fas fa-sync-alt"></i> Refresh Data');
                $('#refresh-data').text("Refresh Data");
                $('#refresh-img').hide();
                location.reload();
            }
        });
    });

    var id_tagihan;
    $(document).on('click', '.report', function(){
		id_tagihan = $(this).attr('id');
		username = $(this).attr('nama');
		$('.titles').text('Kirim Notifikasi ' + username);
        $('#notifListrik').prop("disabled",false);
        $('#notifAirBersih').prop("disabled",false);
        $('#notifKeamananIpk').prop("disabled",false);
        $('#notifKebersihan').prop("disabled",false);
        $('#notifAirKotor').prop("disabled",false);
        $('#notifLain').prop("disabled",false);
        $('#notifListrik').prop("checked",false);
        $('#notifAirBersih').prop("checked",false);
        $('#notifKeamananIpk').prop("checked",false);
        $('#notifKebersihan').prop("checked",false);
        $('#notifAirKotor').prop("checked",false);
        $('#notifLain').prop("checked",false);
        $.ajax({
			url:"/tagihan/notif/edit/" + id_tagihan,
            cache:false,
            method:"get",
			dataType:"json",
			success:function(data)
			{
                if(data.result.stt_listrik === null)
                    $('#notifListrik').prop("disabled",true);

                if(data.result.stt_airbersih === null)
                    $('#notifAirBersih').prop("disabled",true);
                
                if(data.result.stt_keamananipk === null)
                    $('#notifKeamananIpk').prop("disabled",true);
                    
                if(data.result.stt_kebersihan === null)
                    $('#notifKebersihan').prop("disabled",true);
                    
                if(data.result.stt_airkotor === null)
                    $('#notifAirKotor').prop("disabled",true);
                
                if(data.result.stt_lain === null)
                    $('#notifLain').prop("disabled",true);
            }
        })
		$('#notifModal').modal('show');
	});

    $('#form_notif').on('submit',function(e){
        e.preventDefault();
		$.ajax({
			url:"/tagihan/notif/"+id_tagihan,
            cache:false,
            method:"POST",
			data:$(this).serialize(),
			dataType:"json",
			beforeSend:function(){
				$('#ok_button').text('Menghapus...');
			},
			success:function(data)
			{
                if(data.success)
                    html = '<div class="alert alert-success" id="success-alert"> <strong>Sukses! </strong>' + data.success + '</div>';
                if(data.errors)
                    html = '<div class="alert alert-danger" id="error-alert"> <strong>Oops! </strong>' + data.errors + '</div>';
                $('#form_result').html(html);     
                $("#success-alert,#error-alert,#info-alert,#warning-alert")
                    .fadeTo(1000, 500)
                    .slideUp(1000, function () {
                        $("#success-alert,#error-alert").slideUp(500);
                });
                $('#notifModal').modal('hide');
            },
            complete:function(){
                $('#ok_button').text('Hapus');
            }
        })
    });
});
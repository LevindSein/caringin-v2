$(document).ready(function(){
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
});
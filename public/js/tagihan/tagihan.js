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
            if(data.errors){
                location.reload();
            }

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
                $('.badge-report').html(data.result.report);
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
				$('#notif_button').text('Mengirim...');
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
                $('#notif_button').text('Submit');
            }
        })
    });

    $(document).on('click', '.publish', function(){
		$('#publish_action').val('publish');
		$('.titles').text('Publish Tagihan');
        $('#publish_button').removeClass("btn-danger").addClass("btn-success").val("Publish");
        $("#publish_text").html('Dengan Melakukan <b>Publish</b>, maka tagihan akan berstatus <b style="color:green;">aktif</b> sesuai dengan periode yang dipilih. Jika setuju silakan <b>Submit</b>.');
        $("#publishModal").show();
    });
    
    $(document).on('click', '.cancel-publish', function(){
		$('#publish_action').val('unpublish');
		$('.titles').text('Cancel Publish Tagihan');
        $('#publish_button').removeClass("btn-success").addClass("btn-danger").val("Unpublish");
		$('#publish_text').html('<b style="color:red;">WARNING!</b> Membatalkan tagihan, maka <b>semua data tagihan</b> untuk periode yang anda pilih <b style="color:red;">akan dinon-aktifkan</b>. <b>Kecuali, Tagihan yang sudah terbayar</b>. Jika setuju silakan <b>Submit</b>.');
        $("#publishModal").show();
    });

    $('#form_publish').on('submit',function(e){
        e.preventDefault();
        $('#refresh').removeClass("btn-primary").addClass("btn-success").html('Refreshing');
        $('#refresh-img').show();
        $('#publishModal').modal('hide');
        $('#form_result').html('<div class="alert alert-info" id="info-alert"> <strong>Penting ! </strong> Tunggu Hingga Status Konfirmasi Muncul disini.</div>');
		$.ajax({
			url:"/tagihan/publish",
            cache:false,
            method:"POST",
			data:$(this).serialize(),
			dataType:"json",
			success:function(data)
			{
                if(data.result.status)
                    html = '<div class="alert alert-success" id="success-alert"> <strong>Sukses! </strong>' + data.result.message + '</div>';
                if(data.result.status == false)
                    html = '<div class="alert alert-danger" id="error-alert"> <strong>Oops! </strong>' + data.result.message + '</div>';
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
                    window.location = '/tagihan?periode=' + data.result.periode;
                }, 2000);
            },
            error:function(){
                alert("Somthing went wrong");
            }
        })
    });

    $('#add_listrik').click(function(){
        $('.titles').text('Tambah Tagihan Listrik');
        $('#form_tagihanku').attr('action', '/tagihan/listrik');
        $('#tagihanku').modal('show');
    });

    $('#add_air').click(function(){
        $('.titles').text('Tambah Tagihan Air Bersih');
        $('#form_tagihanku').attr('action', '/tagihan/airbersih');
        $('#tagihanku').modal('show');
    });

    $('#form_refresh').on('submit',function(e){
        e.preventDefault();
        $('#refresh').removeClass("btn-primary").addClass("btn-success").html('Refreshing');
        $('#refresh-img').show();
        $('#myRefresh').modal('hide');
        $('#form_result').html('<div class="alert alert-info" id="info-alert"> <strong>Penting ! </strong> Tunggu Hingga Status Konfirmasi Muncul disini.</div>');
		$.ajax({
			url:"/tagihan/refresh/tarif",
            cache:false,
            method:"POST",
			data:$(this).serialize(),
			dataType:"json",
			success:function(data)
			{
                if(data.result.status)
                    html = '<div class="alert alert-success" id="success-alert"> <strong>Sukses! </strong>' + data.result.message + '</div>';
                if(data.errors)
                    html = '<div class="alert alert-danger" id="error-alert"> <strong>Oops! </strong>' + data.result.message + '</div>';
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
                    window.location = '/tagihan?periode=' + data.result.periode;
                }, 2000);
            },
        })
    });

    $(document).on('click', '.totallistrik', function(){
		id = $(this).attr('id');
        $(".divListrik").show();
        $(".divAirBersih").hide();
        $(".divKeamananIpk").hide();
        $(".divKebersihan").hide();
        $(".divAirKotor").hide();
        $(".divLain").hide();
        $(".divTagihan").hide();
        $(".divHistory").show();
        $(".fasilitas").text("LISTRIK");
        $('#rincianrow').html('');
        $.ajax({
			url :"/tagihan/show/listrik/" + id,
            cache:false,
			dataType:"json",
			success:function(data)
			{
                $(".kontrol").text(data.result.kd_kontrol);
                $(".periode").text(data.result.periode);
                $(".pembayaran-heading").text("Pembayaran Listrik");
                $(".history-heading").text("Riwayat Tag.Listrik");

                if(data.result.daya_listrik !== null)
                    $(".daya-listrik").text(data.result.daya_listrik.toLocaleString("en-US"));
                else
                    $(".daya-listrik").html("&mdash;");
                
                if(data.result.awal_listrik !== null)
                    $(".awal-listrik").text(data.result.awal_listrik.toLocaleString("en-US"));
                else
                    $(".awal-listrik").html("&mdash;")

                if(data.result.akhir_listrik !== null)
                    $(".akhir-listrik").text(data.result.akhir_listrik.toLocaleString("en-US"));
                else
                    $(".akhir-listrik").html("&mdash;")

                if(data.result.pakai_listrik !== null)
                    $(".pakai-listrik").html("<span style='color:green;'>" + data.result.pakai_listrik.toLocaleString("en-US") + "</span");
                else
                    $(".pakai-listrik").html("&mdash;")

                $(".diskon-listrik").html("<span style='color:red;'>" + data.result.dis_listrik.toLocaleString("en-US") + "</span");
                $(".tagihan-listrik").html("<span style='color:green;'>" + data.result.ttl_listrik.toLocaleString("en-US") + "</span");
                $(".denda-listrik").html("<span style='color:blue;'>" + data.result.den_listrik.toLocaleString("en-US") + "</span");

                $(".pengguna").text(data.result.nama);

                if(data.result.no_alamat !== null)
                    $(".los").text(data.result.no_alamat);
                else
                    $(".los").html("&mdash;");

                if(data.result.jml_alamat !== null)
                    $(".jumlah").text(data.result.jml_alamat);
                else
                    $(".jumlah").html("&mdash;");

                if(data.result.lok_tempat !== null)
                    $(".lokasi").text(data.result.lok_tempat);
                else
                    $(".lokasi").html("&mdash;");
                
                if(data.result.sel_listrik > 0 && data.result.stt_listrik != null)
                    $(".pembayaran").html("<span style='color:red;'>Belum Lunas</span");
                else if(data.result.sel_listrik == 0 && data.result.stt_listrik != null)
                    $(".pembayaran").html("<span style='color:green;'>Lunas</span");
                else
                    $(".pembayaran").html("&times;");

                if(data.result.stt_publish == 1)
                    $(".status").html("<span style='color:green;'>Publish</span");
                else if(data.result.review == 0)
                    $(".status").html("<span style='color:red;'>Checking</span");
                else if(data.result.review == 2)
                    $(".status").html("<span style='color:blue;'>Edited</span");
                else
                    $(".status").html("<span style='color:red;'>Unpublish</span");

                if(data.result.via_publish !== null)
                    $(".publisher").text(data.result.via_publish);
                else
                    $(".publisher").html("&mdash;");
                    
                $(".pengelola").text(data.result.via_tambah);

                for(i = 0; i < data.result.rincian.length; i++){
                    html = '';
                    html += '<div class="card-profile-stats d-flex justify-content-between" style="margin-bottom:-2rem">';
                    html +=     '<div>';
                    if(data.result.data_periode == data.result.rincian[i].bln_tagihan)
                    html +=         '<span class="heading" style="font-size:.875rem;color:blue;">' + data.result.rincian[i].bln_tagihan + '</span>';
                    else
                    html +=         '<span class="heading" style="font-size:.875rem;">' + data.result.rincian[i].bln_tagihan + '</span>';
                    html +=     '</div>';
                    html +=     '<div>';
                    if(data.result.data_periode == data.result.rincian[i].bln_tagihan)
                    html +=         '<span class="heading" style="font-size:.875rem;color:blue;">' + data.result.rincian[i].ttl_listrik.toLocaleString("en-Us") + '</span>';
                    else
                    html +=         '<span class="heading" style="font-size:.875rem;">' + data.result.rincian[i].ttl_listrik.toLocaleString("en-Us") + '</span>';
                    html +=     '</div>';
                    html += '</div>';
                    
                    $('#rincianrow').append(html);
                }

                $("#total-details").modal("show");
            }
        });
    });

    $(document).on('click', '.totalairbersih', function(){
		id = $(this).attr('id');
        $(".divListrik").hide();
        $(".divAirBersih").show();
        $(".divKeamananIpk").hide();
        $(".divKebersihan").hide();
        $(".divAirKotor").hide();
        $(".divLain").hide();
        $(".divTagihan").hide();
        $(".divHistory").show();
        $(".fasilitas").text("AIR BERSIH");
        $('#rincianrow').html('');
        $.ajax({
			url :"/tagihan/show/airbersih/" + id,
            cache:false,
			dataType:"json",
			success:function(data)
			{
                $(".kontrol").text(data.result.kd_kontrol);
                $(".periode").text(data.result.periode);
                $(".pembayaran-heading").text("Pembayaran Air Bersih");
                $(".history-heading").text("Riwayat Tag.Air Bersih");

                if(data.result.awal_airbersih !== null)
                    $(".awal-airbersih").text(data.result.awal_airbersih.toLocaleString("en-US"));
                else 
                    $(".awal-airbersih").html("&mdash;");
                
                if(data.result.akhir_airbersih !== null)
                    $(".akhir-airbersih").text(data.result.akhir_airbersih.toLocaleString("en-US"));
                else
                    $(".akhir-airbersih").html("&mdash;")
                
                if(data.result.pakai_airbersih !== null)
                    $(".pakai-airbersih").html("<span style='color:green;'>" + data.result.pakai_airbersih.toLocaleString("en-US") + "</span");
                else
                    $(".pakai-airbersih").html("&mdash;")

                $(".diskon-airbersih").html("<span style='color:red;'>" + data.result.dis_airbersih.toLocaleString("en-US") + "</span");
                $(".tagihan-airbersih").html("<span style='color:green;'>" + data.result.ttl_airbersih.toLocaleString("en-US") + "</span");
                $(".denda-airbersih").html("<span style='color:blue;'>" + data.result.den_airbersih.toLocaleString("en-US") + "</span");

                $(".pengguna").text(data.result.nama);

                if(data.result.no_alamat !== null)
                    $(".los").text(data.result.no_alamat);
                else
                    $(".los").html("&mdash;");

                if(data.result.jml_alamat !== null)
                    $(".jumlah").text(data.result.jml_alamat);
                else
                    $(".jumlah").html("&mdash;");

                if(data.result.lok_tempat !== null)
                    $(".lokasi").text(data.result.lok_tempat);
                else
                    $(".lokasi").html("&mdash;");
                
                if(data.result.sel_airbersih > 0 && data.result.stt_airbersih != null)
                    $(".pembayaran").html("<span style='color:red;'>Belum Lunas</span");
                else if(data.result.sel_airbersih == 0 && data.result.stt_airbersih != null)
                    $(".pembayaran").html("<span style='color:green;'>Lunas</span");
                else
                    $(".pembayaran").html("&times;");

                if(data.result.stt_publish == 1)
                    $(".status").html("<span style='color:green;'>Publish</span");
                else if(data.result.review == 0)
                    $(".status").html("<span style='color:red;'>Checking</span");
                else if(data.result.review == 2)
                    $(".status").html("<span style='color:blue;'>Edited</span");
                else
                    $(".status").html("<span style='color:red;'>Unpublish</span");

                if(data.result.via_publish !== null)
                    $(".publisher").text(data.result.via_publish);
                else
                    $(".publisher").html("&mdash;");
                    
                $(".pengelola").text(data.result.via_tambah);

                for(i = 0; i < data.result.rincian.length; i++){
                    html = '';
                    html += '<div class="card-profile-stats d-flex justify-content-between" style="margin-bottom:-2rem">';
                    html +=     '<div>';
                    if(data.result.data_periode == data.result.rincian[i].bln_tagihan)
                    html +=         '<span class="heading" style="font-size:.875rem;color:blue;">' + data.result.rincian[i].bln_tagihan + '</span>';
                    else
                    html +=         '<span class="heading" style="font-size:.875rem;">' + data.result.rincian[i].bln_tagihan + '</span>';
                    html +=     '</div>';
                    html +=     '<div>';
                    if(data.result.data_periode == data.result.rincian[i].bln_tagihan)
                    html +=         '<span class="heading" style="font-size:.875rem;color:blue;">' + data.result.rincian[i].ttl_airbersih.toLocaleString("en-Us") + '</span>';
                    else
                    html +=         '<span class="heading" style="font-size:.875rem;">' + data.result.rincian[i].ttl_airbersih.toLocaleString("en-Us") + '</span>';
                    html +=     '</div>';
                    html += '</div>';
                    
                    $('#rincianrow').append(html);
                }

                $("#total-details").modal("show");
            }
        });
    });

    $(document).on('click', '.totalkeamananipk', function(){
		id = $(this).attr('id');
        $(".divListrik").hide();
        $(".divAirBersih").hide();
        $(".divKeamananIpk").show();
        $(".divKebersihan").hide();
        $(".divAirKotor").hide();
        $(".divLain").hide();
        $(".divTagihan").hide();
        $(".divHistory").show();
        $(".fasilitas").text("KEAMANAN IPK");
        $('#rincianrow').html('');
        $.ajax({
			url :"/tagihan/show/keamananipk/" + id,
            cache:false,
			dataType:"json",
			success:function(data)
			{
                $(".kontrol").text(data.result.kd_kontrol);
                $(".periode").text(data.result.periode);
                $(".pembayaran-heading").text("Pembayaran Keamanan IPK");
                $(".history-heading").text("Riwayat Tag.Keamanan IPK");

                $(".diskon-keamananipk").html("<span style='color:red;'>" + data.result.dis_keamananipk.toLocaleString("en-US") + "</span");
                $(".tagihan-keamananipk").html("<span style='color:green;'>" + data.result.ttl_keamananipk.toLocaleString("en-US") + "</span");

                $(".pengguna").text(data.result.nama);

                if(data.result.no_alamat !== null)
                    $(".los").text(data.result.no_alamat);
                else
                    $(".los").html("&mdash;");

                if(data.result.jml_alamat !== null)
                    $(".jumlah").text(data.result.jml_alamat);
                else
                    $(".jumlah").html("&mdash;");

                if(data.result.lok_tempat !== null)
                    $(".lokasi").text(data.result.lok_tempat);
                else
                    $(".lokasi").html("&mdash;");
                
                if(data.result.sel_keamananipk > 0 && data.result.stt_keamananipk != null)
                    $(".pembayaran").html("<span style='color:red;'>Belum Lunas</span");
                else if(data.result.sel_keamananipk == 0 && data.result.stt_keamananipk != null)
                    $(".pembayaran").html("<span style='color:green;'>Lunas</span");
                else
                    $(".pembayaran").html("&times;");

                if(data.result.stt_publish == 1)
                    $(".status").html("<span style='color:green;'>Publish</span");
                else if(data.result.review == 0)
                    $(".status").html("<span style='color:red;'>Checking</span");
                else if(data.result.review == 2)
                    $(".status").html("<span style='color:blue;'>Edited</span");
                else
                    $(".status").html("<span style='color:red;'>Unpublish</span");

                if(data.result.via_publish !== null)
                    $(".publisher").text(data.result.via_publish);
                else
                    $(".publisher").html("&mdash;");
                    
                $(".pengelola").text(data.result.via_tambah);

                for(i = 0; i < data.result.rincian.length; i++){
                    html = '';
                    html += '<div class="card-profile-stats d-flex justify-content-between" style="margin-bottom:-2rem">';
                    html +=     '<div>';
                    if(data.result.data_periode == data.result.rincian[i].bln_tagihan)
                    html +=         '<span class="heading" style="font-size:.875rem;color:blue;">' + data.result.rincian[i].bln_tagihan + '</span>';
                    else
                    html +=         '<span class="heading" style="font-size:.875rem;">' + data.result.rincian[i].bln_tagihan + '</span>';
                    html +=     '</div>';
                    html +=     '<div>';
                    if(data.result.data_periode == data.result.rincian[i].bln_tagihan)
                    html +=         '<span class="heading" style="font-size:.875rem;color:blue;">' + data.result.rincian[i].ttl_keamananipk.toLocaleString("en-Us") + '</span>';
                    else
                    html +=         '<span class="heading" style="font-size:.875rem;">' + data.result.rincian[i].ttl_keamananipk.toLocaleString("en-Us") + '</span>';
                    html +=     '</div>';
                    html += '</div>';
                    
                    $('#rincianrow').append(html);
                }

                $("#total-details").modal("show");
            }
        });
    });

    $(document).on('click', '.totalkebersihan', function(){
		id = $(this).attr('id');
        $(".divListrik").hide();
        $(".divAirBersih").hide();
        $(".divKeamananIpk").hide();
        $(".divKebersihan").show();
        $(".divAirKotor").hide();
        $(".divLain").hide();
        $(".divTagihan").hide();
        $(".divHistory").show();
        $(".fasilitas").text("KEBERSIHAN");
        $('#rincianrow').html('');
        $.ajax({
			url :"/tagihan/show/kebersihan/" + id,
            cache:false,
			dataType:"json",
			success:function(data)
			{
                $(".kontrol").text(data.result.kd_kontrol);
                $(".periode").text(data.result.periode);
                $(".pembayaran-heading").text("Pembayaran Kebersihan");
                $(".history-heading").text("Riwayat Tag.Kebersihan");

                $(".diskon-kebersihan").html("<span style='color:red;'>" + data.result.dis_kebersihan.toLocaleString("en-US") + "</span");
                $(".tagihan-kebersihan").html("<span style='color:green;'>" + data.result.ttl_kebersihan.toLocaleString("en-US") + "</span");

                $(".pengguna").text(data.result.nama);

                if(data.result.no_alamat !== null)
                    $(".los").text(data.result.no_alamat);
                else
                    $(".los").html("&mdash;");

                if(data.result.jml_alamat !== null)
                    $(".jumlah").text(data.result.jml_alamat);
                else
                    $(".jumlah").html("&mdash;");

                if(data.result.lok_tempat !== null)
                    $(".lokasi").text(data.result.lok_tempat);
                else
                    $(".lokasi").html("&mdash;");
                
                if(data.result.sel_kebersihan > 0 && data.result.stt_kebersihan != null)
                    $(".pembayaran").html("<span style='color:red;'>Belum Lunas</span");
                else if(data.result.sel_kebersihan == 0 && data.result.stt_kebersihan != null)
                    $(".pembayaran").html("<span style='color:green;'>Lunas</span");
                else
                    $(".pembayaran").html("&times;");

                if(data.result.stt_publish == 1)
                    $(".status").html("<span style='color:green;'>Publish</span");
                else if(data.result.review == 0)
                    $(".status").html("<span style='color:red;'>Checking</span");
                else if(data.result.review == 2)
                    $(".status").html("<span style='color:blue;'>Edited</span");
                else
                    $(".status").html("<span style='color:red;'>Unpublish</span");

                if(data.result.via_publish !== null)
                    $(".publisher").text(data.result.via_publish);
                else
                    $(".publisher").html("&mdash;");
                    
                $(".pengelola").text(data.result.via_tambah);

                for(i = 0; i < data.result.rincian.length; i++){
                    html = '';
                    html += '<div class="card-profile-stats d-flex justify-content-between" style="margin-bottom:-2rem">';
                    html +=     '<div>';
                    if(data.result.data_periode == data.result.rincian[i].bln_tagihan)
                    html +=         '<span class="heading" style="font-size:.875rem;color:blue;">' + data.result.rincian[i].bln_tagihan + '</span>';
                    else
                    html +=         '<span class="heading" style="font-size:.875rem;">' + data.result.rincian[i].bln_tagihan + '</span>';
                    html +=     '</div>';
                    html +=     '<div>';
                    if(data.result.data_periode == data.result.rincian[i].bln_tagihan)
                    html +=         '<span class="heading" style="font-size:.875rem;color:blue;">' + data.result.rincian[i].ttl_kebersihan.toLocaleString("en-Us") + '</span>';
                    else
                    html +=         '<span class="heading" style="font-size:.875rem;">' + data.result.rincian[i].ttl_kebersihan.toLocaleString("en-Us") + '</span>';
                    html +=     '</div>';
                    html += '</div>';
                    
                    $('#rincianrow').append(html);
                }

                $("#total-details").modal("show");
            }
        });
    });

    $(document).on('click', '.totalairkotor', function(){
		id = $(this).attr('id');
        $(".divListrik").hide();
        $(".divAirBersih").hide();
        $(".divKeamananIpk").hide();
        $(".divKebersihan").hide();
        $(".divAirKotor").show();
        $(".divLain").hide();
        $(".divTagihan").hide();
        $(".divHistory").show();
        $(".fasilitas").text("AIR KOTOR");
        $('#rincianrow').html('');
        $.ajax({
			url :"/tagihan/show/airkotor/" + id,
            cache:false,
			dataType:"json",
			success:function(data)
			{
                $(".kontrol").text(data.result.kd_kontrol);
                $(".periode").text(data.result.periode);
                $(".pembayaran-heading").text("Pembayaran Air Kotor");
                $(".history-heading").text("Riwayat Tag.Air Kotor");

                $(".tagihan-airkotor").html("<span style='color:green;'>" + data.result.ttl_airkotor.toLocaleString("en-US") + "</span");

                $(".pengguna").text(data.result.nama);

                if(data.result.no_alamat !== null)
                    $(".los").text(data.result.no_alamat);
                else
                    $(".los").html("&mdash;");

                if(data.result.jml_alamat !== null)
                    $(".jumlah").text(data.result.jml_alamat);
                else
                    $(".jumlah").html("&mdash;");

                if(data.result.lok_tempat !== null)
                    $(".lokasi").text(data.result.lok_tempat);
                else
                    $(".lokasi").html("&mdash;");
                
                if(data.result.sel_airkotor > 0 && data.result.stt_airkotor != null)
                    $(".pembayaran").html("<span style='color:red;'>Belum Lunas</span");
                else if(data.result.sel_airkotor == 0 && data.result.stt_airkotor != null)
                    $(".pembayaran").html("<span style='color:green;'>Lunas</span");
                else
                    $(".pembayaran").html("&times;");


                if(data.result.stt_publish == 1)
                    $(".status").html("<span style='color:green;'>Publish</span");
                else if(data.result.review == 0)
                    $(".status").html("<span style='color:red;'>Checking</span");
                else if(data.result.review == 2)
                    $(".status").html("<span style='color:blue;'>Edited</span");
                else
                    $(".status").html("<span style='color:red;'>Unpublish</span");

                if(data.result.via_publish !== null)
                    $(".publisher").text(data.result.via_publish);
                else
                    $(".publisher").html("&mdash;");
                    
                $(".pengelola").text(data.result.via_tambah);

                for(i = 0; i < data.result.rincian.length; i++){
                    html = '';
                    html += '<div class="card-profile-stats d-flex justify-content-between" style="margin-bottom:-2rem">';
                    html +=     '<div>';
                    if(data.result.data_periode == data.result.rincian[i].bln_tagihan)
                    html +=         '<span class="heading" style="font-size:.875rem;color:blue;">' + data.result.rincian[i].bln_tagihan + '</span>';
                    else
                    html +=         '<span class="heading" style="font-size:.875rem;">' + data.result.rincian[i].bln_tagihan + '</span>';
                    html +=     '</div>';
                    html +=     '<div>';
                    if(data.result.data_periode == data.result.rincian[i].bln_tagihan)
                    html +=         '<span class="heading" style="font-size:.875rem;color:blue;">' + data.result.rincian[i].ttl_airkotor.toLocaleString("en-Us") + '</span>';
                    else
                    html +=         '<span class="heading" style="font-size:.875rem;">' + data.result.rincian[i].ttl_airkotor.toLocaleString("en-Us") + '</span>';
                    html +=     '</div>';
                    html += '</div>';
                    
                    $('#rincianrow').append(html);
                }

                $("#total-details").modal("show");
            }
        });
    });

    $(document).on('click', '.totallain', function(){
		id = $(this).attr('id');
        $(".divListrik").hide();
        $(".divAirBersih").hide();
        $(".divKeamananIpk").hide();
        $(".divKebersihan").hide();
        $(".divAirKotor").hide();
        $(".divLain").show();
        $(".divTagihan").hide();
        $(".divHistory").show();
        $(".fasilitas").text("LAIN - LAIN");
        $('#rincianrow').html('');
        $.ajax({
			url :"/tagihan/show/lain/" + id,
            cache:false,
			dataType:"json",
			success:function(data)
			{
                $(".kontrol").text(data.result.kd_kontrol);
                $(".periode").text(data.result.periode);
                $(".pembayaran-heading").text("Pembayaran Lainnya");
                $(".history-heading").text("Riwayat Tag.Lainnya");

                $(".tagihan-lain").html("<span style='color:green;'>" + data.result.ttl_lain.toLocaleString("en-US") + "</span");

                $(".pengguna").text(data.result.nama);

                if(data.result.no_alamat !== null)
                    $(".los").text(data.result.no_alamat);
                else
                    $(".los").html("&mdash;");

                if(data.result.jml_alamat !== null)
                    $(".jumlah").text(data.result.jml_alamat);
                else
                    $(".jumlah").html("&mdash;");

                if(data.result.lok_tempat !== null)
                    $(".lokasi").text(data.result.lok_tempat);
                else
                    $(".lokasi").html("&mdash;");
                
                if(data.result.sel_lain > 0 && data.result.stt_lain != null)
                    $(".pembayaran").html("<span style='color:red;'>Belum Lunas</span");
                else if(data.result.sel_lain == 0 && data.result.stt_lain != null)
                    $(".pembayaran").html("<span style='color:green;'>Lunas</span");
                else
                    $(".pembayaran").html("&times;");

                if(data.result.stt_publish == 1)
                    $(".status").html("<span style='color:green;'>Publish</span");
                else if(data.result.review == 0)
                    $(".status").html("<span style='color:red;'>Checking</span");
                else if(data.result.review == 2)
                    $(".status").html("<span style='color:blue;'>Edited</span");
                else
                    $(".status").html("<span style='color:red;'>Unpublish</span");

                if(data.result.via_publish !== null)
                    $(".publisher").text(data.result.via_publish);
                else
                    $(".publisher").html("&mdash;");
                    
                $(".pengelola").text(data.result.via_tambah);

                for(i = 0; i < data.result.rincian.length; i++){
                    html = '';
                    html += '<div class="card-profile-stats d-flex justify-content-between" style="margin-bottom:-2rem">';
                    html +=     '<div>';
                    if(data.result.data_periode == data.result.rincian[i].bln_tagihan)
                    html +=         '<span class="heading" style="font-size:.875rem;color:blue;">' + data.result.rincian[i].bln_tagihan + '</span>';
                    else
                    html +=         '<span class="heading" style="font-size:.875rem;">' + data.result.rincian[i].bln_tagihan + '</span>';
                    html +=     '</div>';
                    html +=     '<div>';
                    if(data.result.data_periode == data.result.rincian[i].bln_tagihan)
                    html +=         '<span class="heading" style="font-size:.875rem;color:blue;">' + data.result.rincian[i].ttl_lain.toLocaleString("en-Us") + '</span>';
                    else
                    html +=         '<span class="heading" style="font-size:.875rem;">' + data.result.rincian[i].ttl_lain.toLocaleString("en-Us") + '</span>';
                    html +=     '</div>';
                    html += '</div>';
                    
                    $('#rincianrow').append(html);
                }

                $("#total-details").modal("show");
            }
        });
    });

    $(document).on('click', '.totaltagihan', function(){
		id = $(this).attr('id');
        $(".divListrik").hide();
        $(".divAirBersih").hide();
        $(".divKeamananIpk").hide();
        $(".divKebersihan").hide();
        $(".divAirKotor").hide();
        $(".divLain").hide();
        $(".divTagihan").show();
        $(".divHistory").show();
        $(".fasilitas").text("JUMLAH TAGIHAN");
        $('#rincianrow').html('');
        $.ajax({
			url :"/tagihan/show/tagihan/" + id,
            cache:false,
			dataType:"json",
			success:function(data)
			{
                $(".kontrol").text(data.result.kd_kontrol);
                $(".periode").text(data.result.periode);
                $(".pembayaran-heading").text("Pembayaran Jumlah Tagihan");
                $(".history-heading").text("Riwayat Jumlah Tagihan");

                $(".tagihan-listrik").text(data.result.ttl_listrik.toLocaleString("en-US"));
                $(".tagihan-airbersih").text(data.result.ttl_airbersih.toLocaleString("en-US"));
                $(".tagihan-keamananipk").text(data.result.ttl_keamananipk.toLocaleString("en-US"));
                $(".tagihan-kebersihan").text(data.result.ttl_kebersihan.toLocaleString("en-US"));
                $(".tagihan-airkotor").text(data.result.ttl_airkotor.toLocaleString("en-US"));
                $(".tagihan-lain").text(data.result.ttl_lain.toLocaleString("en-US"));
                $(".tagihan-diskon").html("<span style='color:red;'>" + data.result.dis_tagihan.toLocaleString("en-US") + "</span");
                $(".tagihan-denda").html("<span style='color:blue;'>" + data.result.den_tagihan.toLocaleString("en-US") + "</span");

                $(".pengguna").text(data.result.nama);

                if(data.result.no_alamat !== null)
                    $(".los").text(data.result.no_alamat);
                else
                    $(".los").html("&mdash;");

                if(data.result.jml_alamat !== null)
                    $(".jumlah").text(data.result.jml_alamat);
                else
                    $(".jumlah").html("&mdash;");

                if(data.result.lok_tempat !== null)
                    $(".lokasi").text(data.result.lok_tempat);
                else
                    $(".lokasi").html("&mdash;");
                
                if(data.result.sel_tagihan > 0 && data.result.stt_lunas == 0)
                    $(".pembayaran").html("<span style='color:red;'>Belum Lunas</span");
                else if(data.result.sel_tagihan == 0 && data.result.stt_lunas == 1)
                    $(".pembayaran").html("<span style='color:green;'>Lunas</span");
                else
                    $(".pembayaran").html("&times;");

                if(data.result.stt_publish == 1)
                    $(".status").html("<span style='color:green;'>Publish</span");
                else if(data.result.review == 0)
                    $(".status").html("<span style='color:red;'>Checking</span");
                else if(data.result.review == 2)
                    $(".status").html("<span style='color:blue;'>Edited</span");
                else
                    $(".status").html("<span style='color:red;'>Unpublish</span");

                if(data.result.via_publish !== null)
                    $(".publisher").text(data.result.via_publish);
                else
                    $(".publisher").html("&mdash;");
                    
                $(".pengelola").text(data.result.via_tambah);

                for(i = 0; i < data.result.rincian.length; i++){
                    html = '';
                    html += '<div class="card-profile-stats d-flex justify-content-between" style="margin-bottom:-2rem">';
                    html +=     '<div>';
                    if(data.result.data_periode == data.result.rincian[i].bln_tagihan)
                    html +=         '<span class="heading" style="font-size:.875rem;color:blue;">' + data.result.rincian[i].bln_tagihan + '</span>';
                    else
                    html +=         '<span class="heading" style="font-size:.875rem;">' + data.result.rincian[i].bln_tagihan + '</span>';
                    html +=     '</div>';
                    html +=     '<div>';
                    if(data.result.data_periode == data.result.rincian[i].bln_tagihan)
                    html +=         '<span class="heading" style="font-size:.875rem;color:blue;">' + data.result.rincian[i].ttl_tagihan.toLocaleString("en-Us") + '</span>';
                    else
                    html +=         '<span class="heading" style="font-size:.875rem;">' + data.result.rincian[i].ttl_tagihan.toLocaleString("en-Us") + '</span>';
                    html +=     '</div>';
                    html += '</div>';
                    
                    $('#rincianrow').append(html);
                }

                $("#total-details").modal("show");
            }
        });
    });

    $(document).on('click', '.details', function(){
		id = $(this).attr('id');
        $(".divListrik").show();
        $(".divAirBersih").show();
        $(".divKeamananIpk").show();
        $(".divKebersihan").show();
        $(".divAirKotor").show();
        $(".divLain").show();
        $(".divTagihan").hide();
        $(".divHistory").show();
        $(".fasilitas").text("DETAILS TAGIHAN");
        $('#rincianrow').html('');
        $.ajax({
			url :"/tagihan/show/details/" + id,
            cache:false,
			dataType:"json",
			success:function(data)
			{
                $(".kontrol").text(data.result.kd_kontrol);
                $(".periode").text(data.result.periode);
                $(".pembayaran-heading").text("Pembayaran Tagihan");
                $(".history-heading").text("Riwayat Details Tagihan");

                $(".pengguna").text(data.result.nama);

                
                if(data.result.daya_listrik !== null)
                    $(".daya-listrik").text(data.result.daya_listrik.toLocaleString("en-US"));
                else
                    $(".daya-listrik").html("&mdash;");
                
                if(data.result.awal_listrik !== null)
                    $(".awal-listrik").text(data.result.awal_listrik.toLocaleString("en-US"));
                else
                    $(".awal-listrik").html("&mdash;")

                if(data.result.akhir_listrik !== null)
                    $(".akhir-listrik").text(data.result.akhir_listrik.toLocaleString("en-US"));
                else
                    $(".akhir-listrik").html("&mdash;")

                if(data.result.pakai_listrik !== null)
                    $(".pakai-listrik").html("<span style='color:green;'>" + data.result.pakai_listrik.toLocaleString("en-US") + "</span");
                else
                    $(".pakai-listrik").html("&mdash;")

                $(".diskon-listrik").html("<span style='color:red;'>" + data.result.dis_listrik.toLocaleString("en-US") + "</span");
                $(".tagihan-listrik").html("<span style='color:green;'>" + data.result.ttl_listrik.toLocaleString("en-US") + "</span");
                $(".denda-listrik").html("<span style='color:blue;'>" + data.result.den_listrik.toLocaleString("en-US") + "</span");
                if(data.result.awal_airbersih !== null)
                    $(".awal-airbersih").text(data.result.awal_airbersih.toLocaleString("en-US"));
                else 
                    $(".awal-airbersih").html("&mdash;");
                
                if(data.result.akhir_airbersih !== null)
                    $(".akhir-airbersih").text(data.result.akhir_airbersih.toLocaleString("en-US"));
                else
                    $(".akhir-airbersih").html("&mdash;")
                
                if(data.result.pakai_airbersih !== null)
                    $(".pakai-airbersih").html("<span style='color:green;'>" + data.result.pakai_airbersih.toLocaleString("en-US") + "</span");
                else
                    $(".pakai-airbersih").html("&mdash;")

                $(".diskon-airbersih").html("<span style='color:red;'>" + data.result.dis_airbersih.toLocaleString("en-US") + "</span");
                $(".tagihan-airbersih").html("<span style='color:green;'>" + data.result.ttl_airbersih.toLocaleString("en-US") + "</span");
                $(".denda-airbersih").html("<span style='color:blue;'>" + data.result.den_airbersih.toLocaleString("en-US") + "</span");
                $(".diskon-keamananipk").html("<span style='color:red;'>" + data.result.dis_keamananipk.toLocaleString("en-US") + "</span");
                $(".tagihan-keamananipk").html("<span style='color:green;'>" + data.result.ttl_keamananipk.toLocaleString("en-US") + "</span");
                $(".diskon-kebersihan").html("<span style='color:red;'>" + data.result.dis_kebersihan.toLocaleString("en-US") + "</span");
                $(".tagihan-kebersihan").html("<span style='color:green;'>" + data.result.ttl_kebersihan.toLocaleString("en-US") + "</span");
                $(".tagihan-airkotor").html("<span style='color:green;'>" + data.result.ttl_airkotor.toLocaleString("en-US") + "</span");
                $(".tagihan-lain").html("<span style='color:green;'>" + data.result.ttl_lain.toLocaleString("en-US") + "</span");

                if(data.result.no_alamat !== null)
                    $(".los").text(data.result.no_alamat);
                else
                    $(".los").html("&mdash;");

                if(data.result.jml_alamat !== null)
                    $(".jumlah").text(data.result.jml_alamat);
                else
                    $(".jumlah").html("&mdash;");

                if(data.result.lok_tempat !== null)
                    $(".lokasi").text(data.result.lok_tempat);
                else
                    $(".lokasi").html("&mdash;");
                
                if(data.result.sel_tagihan > 0 && data.result.stt_lunas == 0)
                    $(".pembayaran").html("<span style='color:red;'>Belum Lunas</span");
                else if(data.result.sel_tagihan == 0 && data.result.stt_lunas == 1)
                    $(".pembayaran").html("<span style='color:green;'>Lunas</span");
                else
                    $(".pembayaran").html("&times;");

                if(data.result.stt_publish == 1)
                    $(".status").html("<span style='color:green;'>Publish</span");
                else if(data.result.review == 0)
                    $(".status").html("<span style='color:red;'>Checking</span");
                else if(data.result.review == 2)
                    $(".status").html("<span style='color:blue;'>Edited</span");
                else
                    $(".status").html("<span style='color:red;'>Unpublish</span");

                if(data.result.via_publish !== null)
                    $(".publisher").text(data.result.via_publish);
                else
                    $(".publisher").html("&mdash;");
                    
                $(".pengelola").text(data.result.via_tambah);

                for(i = 0; i < data.result.listrik.length; i++){
                    html = '';
                    html += '<div class="card-profile-stats d-flex justify-content-between" style="margin-bottom:-2rem">';
                    html +=     '<div>';
                    if(data.result.data_periode == data.result.listrik[i].bln_tagihan)
                    html +=         '<span class="heading" style="font-size:.875rem;color:blue;">' + data.result.listrik[i].bln_tagihan + ' Listrik</span>';
                    else
                    html +=         '<span class="heading" style="font-size:.875rem;">' + data.result.listrik[i].bln_tagihan + ' Listrik</span>';
                    html +=     '</div>';
                    html +=     '<div>';
                    if(data.result.data_periode == data.result.listrik[i].bln_tagihan)
                    html +=         '<span class="heading" style="font-size:.875rem;color:blue;">' + data.result.listrik[i].ttl_listrik.toLocaleString("en-Us") + '</span>';
                    else
                    html +=         '<span class="heading" style="font-size:.875rem;">' + data.result.listrik[i].ttl_listrik.toLocaleString("en-Us") + '</span>';
                    html +=     '</div>';
                    html += '</div>';
                    
                    $('#rincianrow').append(html);
                }

                if(data.result.listrik.length > 0){
                    html = '<br>';
                    $('#rincianrow').append(html);   
                }
                
                for(i = 0; i < data.result.airbersih.length; i++){
                    html = '';
                    html += '<div class="card-profile-stats d-flex justify-content-between" style="margin-bottom:-2rem">';
                    html +=     '<div>';
                    if(data.result.data_periode == data.result.airbersih[i].bln_tagihan)
                    html +=         '<span class="heading" style="font-size:.875rem;color:blue;">' + data.result.airbersih[i].bln_tagihan + ' Air Bersih</span>';
                    else
                    html +=         '<span class="heading" style="font-size:.875rem;">' + data.result.airbersih[i].bln_tagihan + ' Air Bersih</span>';
                    html +=     '</div>';
                    html +=     '<div>';
                    if(data.result.data_periode == data.result.airbersih[i].bln_tagihan)
                    html +=         '<span class="heading" style="font-size:.875rem;color:blue;">' + data.result.airbersih[i].ttl_airbersih.toLocaleString("en-Us") + '</span>';
                    else
                    html +=         '<span class="heading" style="font-size:.875rem;">' + data.result.airbersih[i].ttl_airbersih.toLocaleString("en-Us") + '</span>';
                    html +=     '</div>';
                    html += '</div>';
                    
                    $('#rincianrow').append(html);
                }
                
                if(data.result.airbersih.length > 0){
                    html = '<br>';
                    $('#rincianrow').append(html);   
                }
                
                for(i = 0; i < data.result.keamananipk.length; i++){
                    html = '';
                    html += '<div class="card-profile-stats d-flex justify-content-between" style="margin-bottom:-2rem">';
                    html +=     '<div>';
                    if(data.result.data_periode == data.result.keamananipk[i].bln_tagihan)
                    html +=         '<span class="heading" style="font-size:.875rem;color:blue;">' + data.result.keamananipk[i].bln_tagihan + ' Keamanan IPK</span>';
                    else
                    html +=         '<span class="heading" style="font-size:.875rem;">' + data.result.keamananipk[i].bln_tagihan + ' Keamanan IPK</span>';
                    html +=     '</div>';
                    html +=     '<div>';
                    if(data.result.data_periode == data.result.keamananipk[i].bln_tagihan)
                    html +=         '<span class="heading" style="font-size:.875rem;color:blue;">' + data.result.keamananipk[i].ttl_keamananipk.toLocaleString("en-Us") + '</span>';
                    else
                    html +=         '<span class="heading" style="font-size:.875rem;">' + data.result.keamananipk[i].ttl_keamananipk.toLocaleString("en-Us") + '</span>';
                    html +=     '</div>';
                    html += '</div>';
                    
                    $('#rincianrow').append(html);
                }
                
                if(data.result.keamananipk.length > 0){
                    html = '<br>';
                    $('#rincianrow').append(html);   
                }
                
                for(i = 0; i < data.result.kebersihan.length; i++){
                    html = '';
                    html += '<div class="card-profile-stats d-flex justify-content-between" style="margin-bottom:-2rem">';
                    html +=     '<div>';
                    if(data.result.data_periode == data.result.kebersihan[i].bln_tagihan)
                    html +=         '<span class="heading" style="font-size:.875rem;color:blue;">' + data.result.kebersihan[i].bln_tagihan + ' Kebersihan</span>';
                    else
                    html +=         '<span class="heading" style="font-size:.875rem;">' + data.result.kebersihan[i].bln_tagihan + ' Kebersihan</span>';
                    html +=     '</div>';
                    html +=     '<div>';
                    if(data.result.data_periode == data.result.kebersihan[i].bln_tagihan)
                    html +=         '<span class="heading" style="font-size:.875rem;color:blue;">' + data.result.kebersihan[i].ttl_kebersihan.toLocaleString("en-Us") + '</span>';
                    else
                    html +=         '<span class="heading" style="font-size:.875rem;">' + data.result.kebersihan[i].ttl_kebersihan.toLocaleString("en-Us") + '</span>';
                    html +=     '</div>';
                    html += '</div>';
                    
                    $('#rincianrow').append(html);
                }

                if(data.result.kebersihan.length > 0){
                    html = '<br>';
                    $('#rincianrow').append(html);   
                }
                
                for(i = 0; i < data.result.airkotor.length; i++){
                    html = '';
                    html += '<div class="card-profile-stats d-flex justify-content-between" style="margin-bottom:-2rem">';
                    html +=     '<div>';
                    if(data.result.data_periode == data.result.airkotor[i].bln_tagihan)
                    html +=         '<span class="heading" style="font-size:.875rem;color:blue;">' + data.result.airkotor[i].bln_tagihan + ' Air Kotor</span>';
                    else
                    html +=         '<span class="heading" style="font-size:.875rem;">' + data.result.airkotor[i].bln_tagihan + ' Air Kotor</span>';
                    html +=     '</div>';
                    html +=     '<div>';
                    if(data.result.data_periode == data.result.airkotor[i].bln_tagihan)
                    html +=         '<span class="heading" style="font-size:.875rem;color:blue;">' + data.result.airkotor[i].ttl_airkotor.toLocaleString("en-Us") + '</span>';
                    else
                    html +=         '<span class="heading" style="font-size:.875rem;">' + data.result.airkotor[i].ttl_airkotor.toLocaleString("en-Us") + '</span>';
                    html +=     '</div>';
                    html += '</div>';
                    
                    $('#rincianrow').append(html);
                }

                if(data.result.airkotor.length > 0){
                    html = '<br>';
                    $('#rincianrow').append(html);   
                }
                
                for(i = 0; i < data.result.lain.length; i++){
                    html = '';
                    html += '<div class="card-profile-stats d-flex justify-content-between" style="margin-bottom:-2rem">';
                    html +=     '<div>';
                    if(data.result.data_periode == data.result.lain[i].bln_tagihan)
                    html +=         '<span class="heading" style="font-size:.875rem;color:blue;">' + data.result.lain[i].bln_tagihan + ' Lainnya</span>';
                    else
                    html +=         '<span class="heading" style="font-size:.875rem;">' + data.result.lain[i].bln_tagihan + ' Lainnya</span>';
                    html +=     '</div>';
                    html +=     '<div>';
                    if(data.result.data_periode == data.result.lain[i].bln_tagihan)
                    html +=         '<span class="heading" style="font-size:.875rem;color:blue;">' + data.result.lain[i].ttl_lain.toLocaleString("en-Us") + '</span>';
                    else
                    html +=         '<span class="heading" style="font-size:.875rem;">' + data.result.lain[i].ttl_lain.toLocaleString("en-Us") + '</span>';
                    html +=     '</div>';
                    html += '</div>';
                    
                    $('#rincianrow').append(html);
                }

                if(data.result.lain.length > 0){
                    html = '<br>';
                    $('#rincianrow').append(html);   
                }

                html = '<hr>';
                $('#rincianrow').append(html);

                for(i = 0; i < data.result.tagihan.length; i++){
                    html = '';
                    html += '<div class="card-profile-stats d-flex justify-content-between" style="margin-bottom:-2rem">';
                    html +=     '<div>';
                    if(data.result.data_periode == data.result.tagihan[i].bln_tagihan)
                    html +=         '<span class="heading" style="font-size:.875rem;color:blue;">' + data.result.tagihan[i].bln_tagihan + ' Tagihan</span>';
                    else
                    html +=         '<span class="heading" style="font-size:.875rem;">' + data.result.tagihan[i].bln_tagihan + ' Tagihan</span>';
                    html +=     '</div>';
                    html +=     '<div>';
                    if(data.result.data_periode == data.result.tagihan[i].bln_tagihan)
                    html +=         '<span class="heading" style="font-size:.875rem;color:blue;">' + data.result.tagihan[i].ttl_tagihan.toLocaleString("en-Us") + '</span>';
                    else
                    html +=         '<span class="heading" style="font-size:.875rem;">' + data.result.tagihan[i].ttl_tagihan.toLocaleString("en-Us") + '</span>';
                    html +=     '</div>';
                    html += '</div>';
                    
                    $('#rincianrow').append(html);
                }

                $("#total-details").modal("show");
            }
        });
    });

    $(document).on('click', '#checking-report', function(){
        $.ajax({
            url :"/tagihan/checking/report",
            cache:false,
            dataType:"json",
            success:function(data)
            {
                if(data.success)
                    location.reload();
                else
                    alert(data.errors);
            }
        });
    });

    $(document).on('click', '.home-tagihan', function(){
        $.ajax({
            url :"/tagihan/checking/home",
            cache:false,
            dataType:"json",
            success:function(data)
            {
                if(data.success)
                    location.reload();
                else
                    alert(data.errors);
            }
        });
    });
});
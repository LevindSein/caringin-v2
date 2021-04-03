$(document).ready(function (){
    $(document).on('click', '.totallistrik', function(){
		id = $(this).attr('id');
        $(".divListrik").show();
        $(".divAirBersih").hide();
        $(".divKeamananIpk").hide();
        $(".divKebersihan").hide();
        $(".divAirKotor").hide();
        $(".divLain").hide();
        $(".divTagihan").hide();
        $(".fasilitas").text("LISTRIK");
        $('#rincianrow').html('');
        $.ajax({
			url :"/penghapusan/show/listrik/" + id,
            cache:false,
			dataType:"json",
			success:function(data)
			{
                $(".kontrol").text(data.result.kd_kontrol);
                $(".periode").text(data.result.periode);
                $(".pembayaran-heading").text("Pembayaran Listrik");

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
        $(".fasilitas").text("AIR BERSIH");
        $('#rincianrow').html('');
        $.ajax({
			url :"/penghapusan/show/airbersih/" + id,
            cache:false,
			dataType:"json",
			success:function(data)
			{
                $(".kontrol").text(data.result.kd_kontrol);
                $(".periode").text(data.result.periode);
                $(".pembayaran-heading").text("Pembayaran Air Bersih");

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
        $(".fasilitas").text("KEAMANAN IPK");
        $('#rincianrow').html('');
        $.ajax({
			url :"/penghapusan/show/keamananipk/" + id,
            cache:false,
			dataType:"json",
			success:function(data)
			{
                $(".kontrol").text(data.result.kd_kontrol);
                $(".periode").text(data.result.periode);
                $(".pembayaran-heading").text("Pembayaran Keamanan IPK");

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
        $(".fasilitas").text("KEBERSIHAN");
        $('#rincianrow').html('');
        $.ajax({
			url :"/penghapusan/show/kebersihan/" + id,
            cache:false,
			dataType:"json",
			success:function(data)
			{
                $(".kontrol").text(data.result.kd_kontrol);
                $(".periode").text(data.result.periode);
                $(".pembayaran-heading").text("Pembayaran Kebersihan");

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
        $(".fasilitas").text("AIR KOTOR");
        $('#rincianrow').html('');
        $.ajax({
			url :"/penghapusan/show/airkotor/" + id,
            cache:false,
			dataType:"json",
			success:function(data)
			{
                $(".kontrol").text(data.result.kd_kontrol);
                $(".periode").text(data.result.periode);
                $(".pembayaran-heading").text("Pembayaran Air Kotor");

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
        $(".fasilitas").text("LAIN - LAIN");
        $('#rincianrow').html('');
        $.ajax({
			url :"/penghapusan/show/lain/" + id,
            cache:false,
			dataType:"json",
			success:function(data)
			{
                $(".kontrol").text(data.result.kd_kontrol);
                $(".periode").text(data.result.periode);
                $(".pembayaran-heading").text("Pembayaran Lainnya");

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
			url :"/penghapusan/show/tagihan/" + id,
            cache:false,
			dataType:"json",
			success:function(data)
			{
                $(".kontrol").text(data.result.kd_kontrol);
                $(".periode").text(data.result.periode);
                $(".pembayaran-heading").text("Pembayaran Jumlah Tagihan");

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
			url :"/penghapusan/show/details/" + id,
            cache:false,
			dataType:"json",
			success:function(data)
			{
                $(".kontrol").text(data.result.kd_kontrol);
                $(".periode").text(data.result.periode);
                $(".pembayaran-heading").text("Pembayaran Tagihan");

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

                $("#total-details").modal("show");
            }
        });
    });
});
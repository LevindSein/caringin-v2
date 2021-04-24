$(document).ready(function(){
    var dtable = $('#tabelTempat').DataTable({
		serverSide: true,
		ajax: {
			url: "/tempatusaha",
            cache:false,
		},
		columns: [
			{ data: 'kd_kontrol', name: 'kd_kontrol', class : 'text-center' },
			{ data: 'id_pengguna', name: 'user.nama', class : 'text-center-td' },
			{ data: 'lok_tempat', name: 'lok_tempat', class : 'text-center-td' },
			{ data: 'jml_alamat', name: 'jml_alamat', class : 'text-center' },
			{ data: 'bentuk_usaha', name: 'bentuk_usaha', class : 'text-center-td' },
			{ data: 'action', name: 'action', class : 'text-center' },
			{ data: 'show', name: 'show', class : 'text-center' },
        ],
        stateSave: true,
        deferRender: true,
        pageLength: 10,
        language: {
            paginate: {
                previous: "<i class='fas fa-angle-left'>",
                next: "<i class='fas fa-angle-right'>"
            }
        },
        aoColumnDefs: [
            { "bSortable": false, "aTargets": [5,6] }, 
            { "bSearchable": false, "aTargets": [5,6] }
        ],
        order:[[0, 'asc']],
        responsive: true,
        scrollY: "50vh",
        scrollX: true,
        aLengthMenu: [[5,10,25,50,100], [5,10,25,50,100]],
        preDrawCallback: function( settings ) {
            scrollPosition = $(".dataTables_scrollBody").scrollTop();
        },
        drawCallback: function( settings ) {
            $(".dataTables_scrollBody").scrollTop(scrollPosition);
            if(typeof rowIndex != 'undefined') {
                dtable.row(rowIndex).nodes().to$().addClass('row_selected');                       
            }
            setTimeout( function () {
                $("[data-toggle='tooltip']").tooltip();
            }, 10)
        },
    });

    setInterval(function(){ dtable.ajax.reload(function(){console.log("Refresh Automatic")}, false); }, 60000);
    $('#refresh').click(function(){
        $('#refresh-img').show();
        $('#refresh').removeClass("btn-primary").addClass("btn-success").html('Refreshing');
        dtable.ajax.reload(function(){console.log("Refresh Manual")}, false);
        setTimeout(function(){
            $('#refresh').removeClass("btn-success").addClass("btn-primary").html('<i class="fas fa-sync-alt"></i> Refresh Data');
            $('#refresh-data').text("Refresh Data");
            $('#refresh-img').hide();
        }, 2000);
    });

    //Search
    $('#meterAir').select2();
    
    $('#meterListrik').select2();

    $('#blok').select2();

    $('#pemilik').select2();

    $('#pengguna').select2();

    $('#add_tempat').click(function(){
		$('.titles').text('Tambah Tempat Usaha');
        
        $('#action_btn').val('Tambah');
        $('#action').val('Add');
        
		$('#form_result').html('');
        
        $('#form_tempat')[0].reset();
        
        $('#displayAir').hide();
        $('#diskonBayarAir').hide();
        $('#hanyaBayarAir').hide();
        $('#displayCharge').hide();
        $("#persenDiskonAir").val();
        
        $('#displayListrik').hide();
        $('#displayListrikDiskon').hide();
        $("#persenDiskonListrik").val();

        $('#displayKeamananIpk').hide();
        $('#displayKeamananIpkDiskon').hide();
        
        $('#displayKebersihan').hide();
        $('#displayKebersihanDiskon').hide();
        
        $('#displayAirKotor').hide();
        $('#displayLain').hide();
        
        $('#ketStatus').hide();
        
        $("#myDiv3 option:selected" ).text('--- Pilih Tarif ---').val('');
        $("#diskonKeamananIpk").val();
        
        $("#myDiv4 option:selected" ).text('--- Pilih Tarif ---').val('');
        $("#diskonKebersihan").val();
        
        $("#myDiv5 option:selected" ).text('--- Pilih Tarif ---').val('');
        $("#myDiv6 option:selected" ).text('--- Pilih Tarif ---').val('');

        $('#myDiv1').prop('required',false);
        $('#myDiv2').prop('required',false);
        $('#myDiv3').prop('required',false);
        $('#myDiv4').prop('required',false);
        $('#myDiv5').prop('required',false);
        $('#myDiv6').prop('required',false);
        $('#ket_tempat').prop('required',false);
        
        $('#meterAir').val('').select2({
            placeholder: '--- Pilih Alat ---',
            ajax: {
                url: "/cari/alatair",
                dataType: 'json',
                delay: 250,
                processResults: function (alats) {
                    return {
                    results:  $.map(alats, function (alat) {
                        return {
                        text: alat.kode + ' - ' + alat.nomor + ' (' + alat.akhir + ')',
                        id: alat.id
                        }
                    })
                    };
                },
                cache: true
            }
        });
        $('#meterListrik').val('').select2({
            placeholder: '--- Pilih Alat ---',
            ajax: {
                url: "/cari/alatlistrik",
                dataType: 'json',
                delay: 250,
                processResults: function (alats) {
                    return {
                    results:  $.map(alats, function (alat) {
                        return {
                        text: alat.kode + ' - ' + alat.nomor + ' (' + alat.akhir +  ' - ' + alat.daya + ' W)',
                        id: alat.id
                        }
                    })
                    };
                },
                cache: true
            }
        });
        $('#blok').val('').select2({
            placeholder: '--- Pilih Blok ---',
            ajax: {
                url: "/cari/blok",
                dataType: 'json',
                delay: 250,
                processResults: function (blok) {
                    return {
                    results:  $.map(blok, function (bl) {
                        return {
                        text: bl.nama,
                        id: bl.nama
                        }
                    })
                    };
                },
                cache: true
            }
        });
        $('#pemilik').val('').select2({
            placeholder: '--- Cari Nasabah ---',
            ajax: {
                url: "/cari/nasabah",
                dataType: 'json',
                delay: 250,
                processResults: function (nasabah) {
                    return {
                    results:  $.map(nasabah, function (nas) {
                        return {
                        text: nas.nama + " - " + nas.ktp,
                        id: nas.id
                        }
                    })
                    };
                },
                cache: true
            }
        });

        $('#pengguna').val('').select2({
            placeholder: '--- Cari Nasabah ---',
            ajax: {
                url: "/cari/nasabah",
                dataType: 'json',
                delay: 250,
                processResults: function (nasabah) {
                    return {
                    results:  $.map(nasabah, function (nas) {
                        return {
                        text: nas.nama + " - " + nas.ktp,
                        id: nas.id
                        }
                    })
                    };
                },
                cache: true
            }
        });

		$('#myModal').modal('show');
    });
    
    var id='';
    $(document).on('click', '.edit', function(){
        id = $(this).attr('id');
        
        $('#form_result').html('');

        $('#meterAir').val('').html('').select2({
            placeholder: '--- Pilih Alat ---',
            ajax: {
                url: "/cari/alatair",
                dataType: 'json',
                delay: 250,
                processResults: function (alats) {
                    return {
                    results:  $.map(alats, function (alat) {
                        return {
                        text: alat.kode + ' - ' + alat.nomor + ' (' + alat.akhir + ')',
                        id: alat.id
                        }
                    })
                    };
                },
                cache: true
            }
        });
        $('#meterListrik').val('').html('').select2({
            placeholder: '--- Pilih Alat ---',
            ajax: {
                url: "/cari/alatlistrik",
                dataType: 'json',
                delay: 250,
                processResults: function (alats) {
                    return {
                    results:  $.map(alats, function (alat) {
                        return {
                        text: alat.kode + ' - ' + alat.nomor + ' (' + alat.akhir +  ' - ' + alat.daya + ' W)',
                        id: alat.id
                        }
                    })
                    };
                },
                cache: true
            }
        });
		$('#blok').val('').html('').select2({
            placeholder: '--- Pilih Blok ---',
            ajax: {
                url: "/cari/blok",
                dataType: 'json',
                delay: 250,
                processResults: function (blok) {
                    return {
                    results:  $.map(blok, function (bl) {
                        return {
                        text: bl.nama,
                        id: bl.nama
                        }
                    })
                    };
                },
                cache: true
            }
        });
		$('#pemilik').val('').html('').select2({
            placeholder: '--- Cari Nasabah ---',
            ajax: {
                url: "/cari/nasabah",
                dataType: 'json',
                delay: 250,
                processResults: function (nasabah) {
                    return {
                    results:  $.map(nasabah, function (nas) {
                        return {
                        text: nas.nama + " - " + nas.ktp,
                        id: nas.id
                        }
                    })
                    };
                },
                cache: true
            }
        });
		$('#pengguna').val('').html('').select2({
            placeholder: '--- Cari Nasabah ---',
            ajax: {
                url: "/cari/nasabah",
                dataType: 'json',
                delay: 250,
                processResults: function (nasabah) {
                    return {
                    results:  $.map(nasabah, function (nas) {
                        return {
                        text: nas.nama + " - " + nas.ktp,
                        id: nas.id
                        }
                    })
                    };
                },
                cache: true
            }
        });

        $('#displayAir').hide();
        $('#diskonBayarAir').hide();
        $('#hanyaBayarAir').hide();
        $('#displayCharge').hide();
        $("#persenDiskonAir").val();

        $('#displayListrik').hide();
        $('#displayListrikDiskon').hide();
        $("#persenDiskonListrik").val();
        
        $('#displayKeamananIpk').hide();
        $('#displayKeamananIpkDiskon').hide();
        $("#myDiv3 option:selected" ).val();
        $("#diskonKeamananIpk").val();

        $('#displayKebersihan').hide();
        $('#displayKebersihanDiskon').hide();
        $("#myDiv4 option:selected" ).val();
        $("#diskonKebersihan").val();
        
        $('#displayAirKotor').hide();
        $("#myDiv5 option:selected" ).val();
        
        $('#displayLain').hide();
        $("#myDiv6 option:selected" ).val();
        
        $('#ketStatus').hide();
        
        $('#myDiv1').prop('required',false);
        $('#myDiv2').prop('required',false);
        $('#myDiv3').prop('required',false);
        $('#myDiv4').prop('required',false);
        $('#myDiv5').prop('required',false);
        $('#myDiv6').prop('required',false);
        $('#ket_tempat').prop('required',false);

        $('#form_tempat')[0].reset();
		$.ajax({
			url :"/tempatusaha/" + id + "/edit",
            cache:false,
			dataType:"json",
			success:function(data)
			{
                $('#los').val(data.result.no_alamat.replace(/\s/g,''));
                
                if(data.result.id_pemilik != null){
                    var pemilik = new Option(data.result.pemilik, data.result.id_pemilik, false, false);
                    $('.pemilik').append(pemilik).trigger('change');
                }
                
                if(data.result.id_pengguna != null){
                    var pengguna = new Option(data.result.pengguna, data.result.id_pengguna, false, false);
                    $('#pengguna').append(pengguna).trigger('change');
                }

                var blok = new Option(data.result.blok, data.result.blok, false, false);
                $('#blok').append(blok).trigger('change');
                
                $('#lokasi').val(data.result.lok_tempat);
                
                $('#usaha').val(data.result.bentuk_usaha);

                if(data.result.stt_tempat == 2){
                    $("#myStatus2").prop("checked", true);
                    $('#ketStatus').show();
                    $('#ket_tempat').val(data.result.ket_tempat);
                }

                if(data.result.trf_airbersih !== null || data.result.meterAirId !== null){
                    if(data.result.trf_airbersih !== null){
                        $("#myCheck1").prop("checked", true);
                        $('#displayAir').show();
                    }

                    if(data.result.meterAirId != null){
                        var meter = new Option(data.result.meterAir, data.result.meterAirId, false, false);
                        $('#meterAir').append(meter).trigger('change');
                    }

                    if(data.result.dis_airbersih != null){ 
                        if(data.result.bebasAir == 'diskon'){
                            $('#dis_airbersih').prop('checked',true);
                            $('#diskonBayarAir').show();
                            $("#persenDiskonAir").val(data.result.diskonAir);
                        }
                        else{
                            $('#hanya_airbersih').prop('checked',true);
                            $('#hanyaBayarAir').show();
                            if(jQuery.inArray("byr", data.result.diskonAir) !== -1){
                                $('#hanyaPemakaianAir').prop('checked',true);
                            }  
                            if(jQuery.inArray("beban", data.result.diskonAir) !== -1){
                                $('#hanyaBebanAir').prop('checked',true);
                            }  
                            if(jQuery.inArray("pemeliharaan", data.result.diskonAir) !== -1){
                                $('#hanyaPemeliharaanAir').prop('checked',true);
                            }  
                            if(jQuery.inArray("arkot", data.result.diskonAir) !== -1){
                                $('#hanyaArkotAir').prop('checked',true);
                            }  
                            if(typeof data.result.diskonAir[data.result.diskonAir.length - 1] === 'object'){
                                $('#hanyaChargeAir').prop('checked',true);
                                $('#displayCharge').show();
                                var charge = data.result.diskonAir[data.result.diskonAir.length - 1]['charge'];
                                charge = charge.split(',');
                                $("#persenChargeAir").val(charge[0]);
                                if(charge[1] === 'ttl'){
                                    $("#dariChargeAir").val('ttl');
                                }
                                if(charge[1] === 'byr'){
                                    $("#dariChargeAir").val('byr');
                                }
                            }
                        }
                    }
                    else{
                        $('#semua_airbersih').prop('checked',true);
                    }
                }

                if(data.result.trf_listrik !== null || data.result.meterListrikId !== null){   
                    if(data.result.trf_listrik !== null){
                        $("#myCheck2").prop("checked", true);
                        $('#displayListrik').show();
                    }
                    if(data.result.meterListrikId != null){
                        var meter = new Option(data.result.meterListrik, data.result.meterListrikId, false, false);
                        $('#meterListrik').append(meter).trigger('change');
                    }

                    if(data.result.dis_listrik != null){ 
                        $("#dis_listrik").prop("checked", true);
                        $("#persenDiskonListrik").val(data.result.dis_listrik);
                        $('#displayListrikDiskon').show();
                    }
                }
                if(data.result.trf_keamananipk != null){    
                    $("#myCheck3").prop("checked", true);
                    $('#displayKeamananIpk').show();
                    $("#myDiv3 option:selected" ).text(data.result.tarifKeamananIpk).val(data.result.tarifKeamananIpkId);
                    if(data.result.dis_keamananipk != null){ 
                        $("#dis_keamananipk").prop("checked", true);
                        $("#diskonKeamananIpk").val(data.result.dis_keamananipk.toLocaleString("en-US"));
                        $('#displayKeamananIpkDiskon').show();
                        if(data.result.dis_keamananipk == 0){
                            $("#dis_keamananipk").prop("checked", false);
                            $('#displayKeamananIpkDiskon').hide();
                        }
                    }
                }
                if(data.result.trf_kebersihan != null){    
                    $("#myCheck4").prop("checked", true);
                    $('#displayKebersihan').show();
                    $("#myDiv4 option:selected" ).text(data.result.tarifKebersihan).val(data.result.tarifKebersihanId);
                    if(data.result.dis_kebersihan != null){ 
                        $("#dis_kebersihan").prop("checked", true);
                        $("#diskonKebersihan").val(data.result.dis_kebersihan.toLocaleString("en-US"));
                        $('#displayKebersihanDiskon').show();
                        if(data.result.dis_kebersihan == 0){
                            $("#dis_kebersihan").prop("checked", false);
                            $('#displayKebersihanDiskon').hide();
                        }
                    }
                }
                if(data.result.trf_airkotor != null){    
                    $("#myCheck5").prop("checked", true);
                    $('#displayAirKotor').show();
                    $("#myDiv5 option:selected" ).text(data.result.tarifAirKotor).val(data.result.tarifAirKotorId);
                }
                if(data.result.trf_lain != null){    
                    $("#myCheck6").prop("checked", true);
                    $('#displayLain').show();
                    $("#myDiv6 option:selected" ).text(data.result.tarifLain).val(data.result.tarifLainId);
                }

                $('#hidden_id').val(id);
				$('.titles').text('Edit Tempat Usaha');
				$('#action_btn').val('Update');
                $('#action').val('Edit');
                $('#myModal').modal('show');
			}
		})
    });

    $('#form_tempat').on('submit', function(event){
		event.preventDefault();
		var action_url = '';

		if($('#action').val() == 'Add')
		{
			action_url = "/tempatusaha";
        }

        if($('#action').val() == 'Edit')
		{
			action_url = "/tempatusaha/update";
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
					
                    setTimeout(function(){
                    }, 4000);
				}
                $('#tabelTempat').DataTable().ajax.reload(function(){}, false);
				$('#form_result').html(html);
                $("#success-alert,#error-alert,#info-alert,#warning-alert")
                    .fadeTo(1000, 500)
                    .slideUp(1000, function () {
                        $("#success-alert,#error-alert").slideUp(500);
                });
                $('#myModal').modal('hide');
			}
		});
    });

    var user_id;
    $(document).on('click', '.delete', function(){
		user_id = $(this).attr('id');
		username = $(this).attr('nama');
		$('.titles').text('Hapus data ' + username + ' ?');
		$('#confirmModal').modal('show');
	});

	$('#ok_button').click(function(){
		$.ajax({
			url:"/tempatusaha/destroy/"+user_id,
            cache:false,
			beforeSend:function(){
				$('#ok_button').text('Menghapus...');
			},
			success:function(data)
			{
                $('#tabelTempat').DataTable().ajax.reload(function(){}, false);
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
                $('#confirmModal').modal('hide');
            },
            complete:function(){
                $('#ok_button').text('Hapus');
            }
        })
    });

    $(document).on('click', '.details', function(){
        $('.divListrik').hide();
        $('.divAirBersih').hide();
        $('.divKeamananIpk').hide();
        $('.divKebersihan').hide();
        $('.divAirKotor').hide();
        $('.divLain').hide();
		id = $(this).attr('id');
		kontrol = $(this).attr('nama');
		$('.titles').html("<h1 style='color:white;font-weight:700;'>" + kontrol + "</h1>");
        $.ajax({
			url :"/tempatusaha/show/"+id,
            cache:false,
			dataType:"json",
			success:function(data)
			{
                if(data.result.pengguna !== null)
                    $('.pengguna').text(data.result.pengguna);
                else
                    $('.pengguna').html("&mdash;");
                    
                if(data.result.pemilik !== null)
                    $('.pemilik').text(data.result.pemilik);
                else
                    $('.pemilik').html("&mdash;");

                $('.los').text(data.result.no_alamat);
                $('.jumlah').text(data.result.jml_alamat);
                
                if(data.result.lok_tempat !== null)
                    $('.lokasi').text(data.result.lok_tempat);
                else
                    $('.lokasi').html("&mdash;");
                    
                if(data.result.bentuk_usaha !== null)
                    $('.usaha').text(data.result.bentuk_usaha);
                else
                    $('.usaha').html("&mdash;");
                
                if(data.result.stt_tempat == 1){
                    $('.status').html("<span style='color:green;'>Aktif</span");
                    $('.keterangan').html("&mdash;");
                }
                else if(data.result.stt_tempat == 2){
                    $('.status').html("<span style='color:red;'>Pasif</span");
                    $('.keterangan').text(data.result.ket_tempat);
                }

                //Fasilitas
                if(data.result.faslistrik != null){
                    $('.divListrik').show();
                    if(data.result.diskonlistrik != null)
                        $('.diskon-listrik').html("<span style='color:red;'>&check;</span>");
                    else
                        $('.diskon-listrik').html("&times;");

                    $('.alat-listrik').text(data.result.alatlistrik);
                    $('.daya-listrik').text(data.result.dayalistrik.toLocaleString("en-US"));
                    $('.stand-listrik').text(data.result.standlistrik.toLocaleString("en-US"));
                }
                if(data.result.fasairbersih != null){
                    $('.divAirBersih').show();
                    if(data.result.diskonairbersih != null)
                        $('.diskon-airbersih').html("<span style='color:red;'>&check;</span>");
                    else
                        $('.diskon-airbersih').html("&times;");

                    $('.alat-airbersih').text(data.result.alatairbersih);
                    $('.stand-airbersih').text(data.result.standairbersih.toLocaleString("en-US"));
                }
                if(data.result.faskeamananipk != null){
                    $('.divKeamananIpk').show();
                    if(data.result.diskonkeamananipk != null)
                        $('.diskon-keamananipk').html("<span style='color:red;'>" + data.result.diskonkeamananipk.toLocaleString("en-US") + "</span>");
                    else
                        $('.diskon-keamananipk').html("&times;");
                    
                    $('.per-unit-keamananipk').html("<span style='color:blue;text-transform:lowercase;'>(" + data.result.perunitkeamananipk.toLocaleString("en-US") + " / unit)</span>");
                    $('.subtotal-keamananipk').html("<span style='color:blue;'>" + data.result.subtotalkeamananipk.toLocaleString("en-US") + "</span>");
                    $('.total-keamananipk').html("<span style='color:green;'>" + data.result.totalkeamananipk.toLocaleString("en-US") + "</span>");
                }
                if(data.result.faskebersihan != null){
                    $('.divKebersihan').show();
                    if(data.result.diskonkebersihan != null)
                        $('.diskon-kebersihan').html("<span style='color:red;'>" + data.result.diskonkebersihan.toLocaleString("en-US") + "</span>");
                    else
                        $('.diskon-kebersihan').html("&times;");
                    
                    $('.per-unit-kebersihan').html("<span style='color:blue;text-transform:lowercase;'>(" + data.result.perunitkebersihan.toLocaleString("en-US") + " / unit)</span>");
                    $('.subtotal-kebersihan').html("<span style='color:blue;'>" + data.result.subtotalkebersihan.toLocaleString("en-US") + "</span>");
                    $('.total-kebersihan').html("<span style='color:green;'>" + data.result.totalkebersihan.toLocaleString("en-US") + "</span>");
                }
                if(data.result.fasairkotor != null){
                    $('.divAirKotor').show();
                    $('.total-airkotor').html("<span style='color:green;'>" + data.result.totalairkotor.toLocaleString("en-US") + "</span>");
                }
                if(data.result.faslain != null){
                    $('.divLain').show();
                    $('.total-lain').html("<span style='color:green;'>" + data.result.totallain.toLocaleString("en-US") + "</span>");
                }
                
                if(data.result.hp_pengguna != null){
                    $('#whatsapp-number').attr("href", "https://api.whatsapp.com/send?phone=" + data.result.hp_pengguna + "&text=Halo%20" + data.result.pengguna + 
                    "%20Selaku%20Pengguna%20Tempat%20" + kontrol +"%0A*Selamat%20Berniaga%20Mitra%20BP3C*").attr("target", "_blank").css("pointer-events","").removeClass("btn-dark").addClass("btn-success");
                }
                else{
                    $('#whatsapp-number').attr("href","#").removeAttr("target").css("pointer-events", "none").removeClass("btn-success").addClass("btn-dark");
                }
                
                if(data.result.email_pengguna != null){
                    $('#email-number').attr("href", "mailto:" + data.result.email_pengguna).attr("target", "_blank").css("pointer-events","").removeClass("btn-dark").addClass("btn-danger");
                }
                else{
                    $('#email-number').attr("href","#").removeAttr("target").css("pointer-events", "none").removeClass("btn-danger").addClass("btn-dark");
                }
                
                $('#show-details').modal('show');
			}
		});
	});

    $(document).on('click', '.details-rekap', function(){
        $('#rincianrow').html('');
		blok = $(this).attr('nama');
		$('.titles').html("<h1 style='color:white;font-weight:700;'>" + blok + "</h1>");
        $.ajax({
			url :"/tempatusaha/rekap/"+blok,
            cache:false,
			dataType:"json",
			success:function(data)
			{
                if(data.result.penggunalistrik > 0)
                    $('.pengguna-listrik').text(data.result.penggunalistrik.toLocaleString('en-US'));
                else
                    $('.pengguna-listrik').text(0);
                
                if(data.result.penggunaairbersih > 0)
                    $('.pengguna-airbersih').text(data.result.penggunaairbersih.toLocaleString('en-US'));
                else
                    $('.pengguna-airbersih').text(0);

                if(data.result.penggunakeamananipk > 0)
                    $('.pengguna-keamananipk').text(data.result.penggunakeamananipk.toLocaleString('en-US'));
                else
                    $('.pengguna-keamananipk').text(0);
                
                if(data.result.penggunakebersihan > 0)
                    $('.pengguna-kebersihan').text(data.result.penggunakebersihan.toLocaleString('en-US'));
                else
                    $('.pengguna-kebersihan').text(0);

                if(data.result.potensilistrik > 0)
                    $('.potensi-listrik').text(data.result.potensilistrik.toLocaleString('en-US'));
                else
                    $('.potensi-listrik').text(0);
                
                if(data.result.potensiairbersih > 0)
                    $('.potensi-airbersih').text(data.result.potensiairbersih.toLocaleString('en-US'));
                else
                    $('.potensi-airbersih').text(0);

                if(data.result.potensikeamananipk > 0)
                    $('.potensi-keamananipk').text(data.result.potensikeamananipk.toLocaleString('en-US'));
                else
                    $('.potensi-keamananipk').text(0);
                
                if(data.result.potensikebersihan > 0)
                    $('.potensi-kebersihan').text(data.result.potensikebersihan.toLocaleString('en-US'));
                else
                    $('.potensi-kebersihan').text(0);

                for(i = 0; i < data.result.rincian.length; i++){
                    var pengguna = "";
                    if(data.result.rincian[i].trf_listrik != null)
                        pengguna += '<i class="fas fa-bolt" style="color:#fd7e14;" title="Listrik"></i>&nbsp;';
                    else
                        pengguna += '<i class="fas fa-bolt" style="color:dark;" title="Non Listrik"></i>&nbsp;';

                    if(data.result.rincian[i].trf_airbersih != null)
                        pengguna += '<i class="fas fa-tint" style="color:#36b9cc" title="Air Bersih"></i>&nbsp;';
                    else
                        pengguna += '<i class="fas fa-tint" style="color:dark" title="Non Air Bersih"></i>&nbsp;';

                    if(data.result.rincian[i].trf_keamananipk != null)
                        pengguna += '<i class="fas fa-lock" style="color:#e74a3b" title="Keamanan IPK"></i>&nbsp;';
                    else
                        pengguna += '<i class="fas fa-lock" style="color:dark" title="Non Keamanan IPK"></i>&nbsp;';

                    if(data.result.rincian[i].trf_kebersihan != null)
                        pengguna += '<i class="fas fa-leaf" style="color:#1cc88a;" title="Kebersihan"></i>&nbsp;';
                    else
                        pengguna += '<i class="fas fa-leaf" style="color:dark" title="Non Kebersihan"></i>&nbsp;';

                    html = '';
                    html += '<div class="card-profile-stats d-flex justify-content-between" style="margin-bottom:-1rem">';
                    html +=     '<div>';
                    html +=         '<span class="heading" style="font-size:.875rem;">' + data.result.rincian[i].kd_kontrol + '</span>';
                    html +=     '</div>';
                    html +=     '<div>';
                    if(data.result.rincian[i].stt_tempat == 1)
                    html +=         '<span class="heading" style="font-size:.875rem;color:green">Aktif</span>';
                    if(data.result.rincian[i].stt_tempat == 2)
                    html +=         '<span class="heading" style="font-size:.875rem;color:red">Pasif</span>';
                    html +=     '</div>';
                    html +=     '<div>';
                    html +=         '<span class="heading" style="font-size:.875rem;">' + pengguna + '</span>';
                    html +=     '</div>';
                    html += '</div>';

                    $('#rincianrow').append(html);
                }
                
                $('#show-details').modal('show');
            }
        });
    });

    $('#los').on('keypress', function (event) {
        var regex = new RegExp("^[a-zA-Z0-9,]+$");
        var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
        if (!regex.test(key)) {
        event.preventDefault();
        return false;
        }
    });

    $("#los").on("input", function() {
    if (/^,/.test(this.value)) {
        this.value = this.value.replace(/^,/, "")
    }
    else if (/^0/.test(this.value)) {
        this.value = this.value.replace(/^0/, "")
    }
    })

    document
        .getElementById('diskonKeamananIpk')
        .addEventListener(
            'input',
            event => event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US')
        );
    document
        .getElementById('diskonKebersihan')
        .addEventListener(
            'input',
            event => event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US')
        );

    // Radio Bebas Bayar
    function radioAir() {
        if ($('#hanya_airbersih').is(':checked')) {
            document
                .getElementById('hanyaBayarAir')
                .style
                .display = 'block';
            document
                .getElementById('diskonBayarAir')
                .style
                .display = 'none';
        }
        else if ($('#dis_airbersih').is(':checked')) {
            document
                .getElementById('hanyaBayarAir')
                .style
                .display = 'none';
            document
                .getElementById('diskonBayarAir')
                .style
                .display = 'block';
        }
        else {
            document
                .getElementById('hanyaBayarAir')
                .style
                .display = 'none';
            document
                .getElementById('diskonBayarAir')
                .style
                .display = 'none';
        }
    }
    $('input[type="radio"]')
        .click(radioAir)
        .each(radioAir);

    // Status Button
    function statusTempat() {
        if ($('#myStatus2').is(':checked')) {
            document
                .getElementById('ketStatus')
                .style
                .display = 'block';
            document
                .getElementById('ket_tempat')
                .required = true;
        }
        else {
            document
                .getElementById('ketStatus')
                .style
                .display = 'none';
            document
                .getElementById('ket_tempat')
                .required = false;
        }
    }
    $('input[type="radio"]')
        .click(statusTempat)
        .each(statusTempat);

    // Metode Pembayaran
    // function pembayaran() {
    //     if ($('#cicilan2').is(':checked')) {
    //         document
    //             .getElementById('ketCicil')
    //             .style
    //             .display = 'block';
    //         document
    //             .getElementById('ket_cicil')
    //             .required = true;
    //     }
    //     else {
    //         document
    //             .getElementById('ketCicil')
    //             .style
    //             .display = 'none';
    //         document
    //             .getElementById('ket_cicil')
    //             .required = false;
    //     }
    // }
    // $('input[type="radio"]')
    //     .click(pembayaran)
    //     .each(pembayaran);

    // Fasilitas Button
    function evaluate() {
        var item = $(this);
        var relatedItem = $("#" + item.attr("data-related-item")).parent();

        if (item.is(":checked")) {
            relatedItem.fadeIn();
        } else {
            relatedItem.fadeOut();
        }
    }
    $('input[type="checkbox"]')
        .click(evaluate)
        .each(evaluate);

    // Checking
    function checkAir() {
        if ($('#myCheck1').is(':checked')) {
            document
                .getElementById('myDiv1')
                .required = true;
        } else {
            document
                .getElementById('myDiv1')
                .required = false;
        }
    }
    $('input[type="checkbox"]')
        .click(checkAir)
        .each(checkAir);

    function checkListrik() {
        if ($('#myCheck2').is(':checked')) {
            document
                .getElementById('myDiv2')
                .required = true;
        } else {
            document
                .getElementById('myDiv2')
                .required = false;
        }
    }
    $('input[type="checkbox"]')
        .click(checkListrik)
        .each(checkListrik);

    function checkKeamananIpk() {
        if ($('#myCheck3').is(':checked')) {
            document
                .getElementById('myDiv3')
                .required = true;
        } else {
            document
                .getElementById('myDiv3')
                .required = false;
        }
    }
    $('input[type="checkbox"]')
        .click(checkKeamananIpk)
        .each(checkKeamananIpk);


    function checkKebersihan() {
        if ($('#myCheck4').is(':checked')) {
            document
                .getElementById('myDiv4')
                .required = true;
        } else {
            document
                .getElementById('myDiv4')
                .required = false;
        }
    }
    $('input[type="checkbox"]')
        .click(checkKebersihan)
        .each(checkKebersihan);

    function checkAirKotor() {
        if ($('#myCheck5').is(':checked')) {
            document
                .getElementById('myDiv5')
                .required = true;
        } else {
            document
                .getElementById('myDiv5')
                .required = false;
        }
    }
    $('input[type="checkbox"]')
        .click(checkAirKotor)
        .each(checkAirKotor);

    function checkLain() {
        if ($('#myCheck6').is(':checked')) {
            document
                .getElementById('myDiv6')
                .required = true;
        } else {
            document
                .getElementById('myDiv6')
                .required = false;
        }
    }
    $('input[type="checkbox"]')
        .click(checkLain)
        .each(checkLain);

    $(document).on('click', '.qr', function(){
        var id = $(this).attr('id');
        window.open(
            '/tempatusaha/qr/' + id,
            '_blank'
        );
    });
});
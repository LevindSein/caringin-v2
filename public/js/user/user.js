$(document).ready(function(){
    $('#userAdmin').DataTable({
		serverSide: true,
		ajax: {
			url: "/user",
            cache:false,
		},
		columns: [
			{ data: 'username', name: 'username', class : 'text-center' },
			{ data: 'nama', name: 'nama', class : 'text-center' },
			{ data: 'otoritas', name: 'otoritas', class : 'text-center' },
			{ data: 'action', name: 'action', class : 'text-center' },
			{ data: 'show', name: 'show', class : 'text-center' },
        ],
        stateSave: true,
        deferRender: true,
        pageLength: 8,
        language: {
            paginate: {
                previous: "<i class='fas fa-angle-left'>",
                next: "<i class='fas fa-angle-right'>"
            }
        },
        aoColumnDefs: [
            { "bSortable": false, "aTargets": [2,3,4] }, 
            { "bSearchable": false, "aTargets": [2,3,4] }
        ],
        responsive:true
    });

    $("#tab-c-1").click(function(){
        if (!$.fn.dataTable.isDataTable('#userManajer')) {
            $('#userManajer').DataTable({
                serverSide: true,
                ajax: '/user/manajer',
                columns: [
                    { data: 'username', name: 'username', class : 'text-center' },
                    { data: 'nama', name: 'nama', class : 'text-center' },
                    { data: 'action', name: 'action', class : 'text-center' },
                    { data: 'show', name: 'show', class : 'text-center' },
                ],
                stateSave: true,
                deferRender: true,
                pageLength: 8,
                language: {
                    paginate: {
                        previous: "<i class='fas fa-angle-left'>",
                        next: "<i class='fas fa-angle-right'>"
                    }
                },
                aoColumnDefs: [
                    { "bSortable": false, "aTargets": [2,3] }, 
                    { "bSearchable": false, "aTargets": [2,3] }
                ],
                responsive:true
            });
        }
    });

    $("#tab-c-2").click(function(){
        if (!$.fn.dataTable.isDataTable('#userKeuangan')) {
            $('#userKeuangan').DataTable({
                serverSide: true,
                ajax: '/user/keuangan',
                columns: [
                    { data: 'username', name: 'username', class : 'text-center' },
                    { data: 'nama', name: 'nama', class : 'text-center' },
                    { data: 'action', name: 'action', class : 'text-center' },
                    { data: 'show', name: 'show', class : 'text-center' },
                ],
                stateSave: true,
                deferRender: true,
                pageLength: 8,
                language: {
                    paginate: {
                        previous: "<i class='fas fa-angle-left'>",
                        next: "<i class='fas fa-angle-right'>"
                    }
                },
                aoColumnDefs: [
                    { "bSortable": false, "aTargets": [2,3] }, 
                    { "bSearchable": false, "aTargets": [2,3] }
                ],
                responsive:true
            });
        }
    });

    $("#tab-c-3").click(function(){
        if (!$.fn.dataTable.isDataTable('#userKasir')) {
            $('#userKasir').DataTable({
                serverSide: true,
                ajax: '/user/kasir',
                columns: [
                    { data: 'username', name: 'username', class : 'text-center' },
                    { data: 'nama', name: 'nama', class : 'text-center' },
                    { data: 'action', name: 'action', class : 'text-center' },
                    { data: 'show', name: 'show', class : 'text-center' },
                ],
                stateSave: true,
                deferRender: true,
                pageLength: 8,
                language: {
                    paginate: {
                        previous: "<i class='fas fa-angle-left'>",
                        next: "<i class='fas fa-angle-right'>"
                    }
                },
                aoColumnDefs: [
                    { "bSortable": false, "aTargets": [2,3] }, 
                    { "bSearchable": false, "aTargets": [2,3] }
                ],
                responsive:true
            });
        }
    });

    $("#tab-c-4").click(function(){
        if (!$.fn.dataTable.isDataTable('#userNasabah')) {
            $('#userNasabah').DataTable({
                serverSide: true,
                ajax: '/user/nasabah',
                columns: [
                    { data: 'username', name: 'username', class : 'text-center' },
                    { data: 'nama', name: 'nama', class : 'text-center' },
                    { data: 'action', name: 'action', class : 'text-center' },
                    { data: 'show', name: 'show', class : 'text-center' },
                ],
                stateSave: true,
                deferRender: true,
                pageLength: 8,
                language: {
                    paginate: {
                        previous: "<i class='fas fa-angle-left'>",
                        next: "<i class='fas fa-angle-right'>"
                    }
                },
                aoColumnDefs: [
                    { "bSortable": false, "aTargets": [2,3] }, 
                    { "bSearchable": false, "aTargets": [2,3] }
                ],
                responsive:true
            });
        }
    });

    var id;

    $('#add_user').click(function(){
		$('.titles').text('Tambah User');
		$('#action_btn').val('Tambah');
		$('#action').val('Add');
		$('#form_result').html('');
        $('#form_user')[0].reset();
		$('#myModal').modal('show');
    });

    $('#form_user').on('submit', function(event){
		event.preventDefault();
		var action_url = '';

		if($('#action').val() == 'Add')
		{
			action_url = "/user";
        }

        if($('#action').val() == 'Edit')
		{
			action_url = "/user/update";
		}

		$.ajax({
			url: action_url,
            cache:false,
			method:"POST",
			data:$(this).serialize(),
			dataType:"json",
			success:function(data)
			{
				var html = '';
				if(data.result.status == 'error')
				{
                    html = '<div class="alert alert-danger" id="error-alert"> <strong>Maaf ! </strong>' + data.result.message + '</div>';
				}
				if(data.result.status == 'success')
				{
					html = '<div class="alert alert-success" id="success-alert"> <strong>Sukses ! </strong>' + data.result.message + '</div>';
                    $('#form_user')[0].reset();
                    try{
                        if(data.result.role == 'admin'){
                            $('#tab-c-0').trigger('click');
                        }
                        if(data.result.role == 'manajer'){
                            $('#tab-c-1').trigger('click');
                        }
                        if(data.result.role == 'keuangan'){
                            $('#tab-c-2').trigger('click');
                        }
                        if(data.result.role == 'kasir'){
                            $('#tab-c-3').trigger('click');
                        }
                    } catch(err){}
                    finally{
                        if(data.result.role == 'admin'){
                            $('#userAdmin').DataTable().ajax.reload(function(){}, false);
                        }
                        if(data.result.role == 'manajer'){
                            $('#userManajer').DataTable().ajax.reload(function(){}, false);
                        }
                        if(data.result.role == 'keuangan'){
                            $('#userKeuangan').DataTable().ajax.reload(function(){}, false);
                        }
                        if(data.result.role == 'kasir'){
                            $('#userKasir').DataTable().ajax.reload(function(){}, false);
                        }
                    }
				}
				$('#form_result').html(html);
                $("#success-alert,#error-alert,#info-alert,#warning-alert")
                    .fadeTo(2000, 1000)
                    .slideUp(2000, function () {
                        $("#success-alert,#error-alert").slideUp(1000);
                });
                $('#myModal').modal('hide');
			}
		});
    });
    
    $(document).on('click', '.edit', function(){
		id = $(this).attr('id');
		$('#form_result').html('');
		$.ajax({
			url :"/user/"+id+"/edit",
            cache:false,
			dataType:"json",
			success:function(data)
			{
				$('#ktp').val(data.result.ktp);
                $('#nama').val(data.result.nama);
                $('#username').val(data.result.username);
                $('#password').val();
                $('#anggota').val(data.result.anggota);
                $('#email').val(data.result.email);
                $('#hp').val(data.result.hp);

                if(data.result.role == 'admin'){
                    $("#roleAdmin").prop("checked", true);
                }

                if(data.result.role == 'manajer'){
                    $("#roleManajer").prop("checked", true);
                }

                if(data.result.role == 'keuangan'){
                    $("#roleKeuangan").prop("checked", true);
                }

                if(data.result.role == 'kasir'){
                    $("#roleKasir").prop("checked", true);
                }
                
				$('#hidden_id').val(id);
				$('.titles').text('Edit Pedagang');
				$('#action_btn').val('Update');
				$('#action').val('Edit');
                $('#myModal').modal('show');
			}
		})
    });

    $('#nama').on('input',function(){
        if($('#action').val() == 'Add'){
            nama = $(this).val();
            username = nama.replace(/[^a-zA-Z]/g,'');
            nama = username;
            username = username.slice(0, 7);
            number = Math.floor(1000 + Math.random() * 9000);

            pass = shuffle('abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ123456789');
            pass = pass.slice(0, 8);

            if(nama == ''){
                document.getElementById("username").value = '';
                document.getElementById("password").value = '';
            }
            else{
                document.getElementById("username").value = username + number;
                document.getElementById("password").value = pass;
            }
        }
    });

    $(".toggle-password").click(function() {
        $(this).toggleClass("fa-eye fa-eye-slash");
        var input = $($(this).attr("toggle"));
        if (input.attr("type") == "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });

    function shuffle(string) {
        var parts = string.split('');
        for (var i = parts.length; i > 0;) {
            var random = parseInt(Math.random() * i);
            var temp = parts[--i];
            parts[i] = parts[random];
            parts[random] = temp;
        }
        return parts.join('');
    }

    var user_id;
    $(document).on('click', '.delete', function(){
		user_id = $(this).attr('id');
		nama = $(this).attr('nama');
		$('.titles').text('Hapus data ' + nama + ' ?');
		$('#confirmModal').modal('show');
	});

	$('#ok_button').click(function(){
		$.ajax({
			url:"/user/destroy/"+user_id,
            cache:false,
			beforeSend:function(){
				$('#ok_button').text('Menghapus...');
			},
			success:function(data)
			{
                if(data.result.role == 'admin'){
                    $('#userAdmin').DataTable().ajax.reload(function(){}, false);
                }
                if(data.result.role == 'manajer'){
                    $('#userManajer').DataTable().ajax.reload(function(){}, false);
                }
                if(data.result.role == 'keuangan'){
                    $('#userKeuangan').DataTable().ajax.reload(function(){}, false);
                }
                if(data.result.role == 'kasir'){
                    $('#userKasir').DataTable().ajax.reload(function(){}, false);
                }
                if(data.result.role == 'nasabah'){
                    $('#userNasabah').DataTable().ajax.reload(function(){}, false);
                }
                if(data.result.success)
                    html = '<div class="alert alert-success" id="success-alert"> <strong>Sukses! </strong>' + data.result.success + '</div>';
                if(data.result.errors)
                    html = '<div class="alert alert-danger" id="error-alert"> <strong>Oops! </strong>' + data.result.errors + '</div>';
                $('#form_result').html(html);     
                $("#success-alert,#error-alert,#info-alert,#warning-alert")
                    .fadeTo(2000, 1000)
                    .slideUp(2000, function () {
                        $("#success-alert,#error-alert").slideUp(1000);
                });
                $('#confirmModal').modal('hide');
            },
            complete:function(){
                $('#ok_button').text('Hapus');
            }
        })
    });

    $('.blokOtoritas').select2({
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
    
    $(document).on('click', '.otoritas', function(){
		id = $(this).attr('id');
		nama = $(this).attr('nama');
        $('.titles').text('Otoritas ' + nama);
		$('#blokOtoritas').select2('val','');
		$('#blokOtoritas').html('');
        $("#pedagang").prop("checked", false);
        $("#tempatusaha").prop("checked", false);
        $("#tagihan").prop("checked", false);
        $("#blok").prop("checked", false);
        $("#pemakaian").prop("checked", false);
        $("#pendapatan").prop("checked", false);
        $("#datausaha").prop("checked", false);
        $("#publish").prop("checked", false);
        $("#alatmeter").prop("checked", false);
        $("#tarif").prop("checked", false);
        $("#harilibur").prop("checked", false);
        $("#layanan").prop("checked", false);
        $("#simulasi").prop("checked", false);
        $('#myOtoritas').modal('show');
		$.ajax({
			url :"/user/"+id+"/otoritas",
            cache:false,
			dataType:"json",
			success:function(data)
			{
                $('#username_otoritas').val(data.result.username);
                $('#nama_otoritas').val(data.result.nama);

				$('#hidden_id_otoritas').val(id);
				$('#action_btn_otoritas').val('Update');

                if(data.result.blok != null){
                    var s1 = $("#blokOtoritas").select2();
                    var valBlok = data.result.bloks;
                    valBlok.forEach(function(e){
                        if(!s1.find('option:contains(' + e + ')').length) 
                            s1.append($('<option>').text(e));
                    });
                    s1.val(valBlok).trigger("change");

                    $('.blokOtoritas').select2({
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

                    if(data.result.pedagang == true) $("#pedagang").prop("checked", true);

                    if(data.result.tempatusaha == true) $("#tempatusaha").prop("checked", true);
                    
                    if(data.result.tagihan == true) $("#tagihan").prop("checked", true);

                    if(data.result.blok == true) $("#blok").prop("checked", true);

                    if(data.result.pemakaian == true) $("#pemakaian").prop("checked", true);

                    if(data.result.pendapatan == true) $("#pendapatan").prop("checked", true);

                    if(data.result.datausaha == true) $("#datausaha").prop("checked", true);
                    
                    if(data.result.publish == true) $("#publish").prop("checked", true);

                    if(data.result.alatmeter == true) $("#alatmeter").prop("checked", true);
                    
                    if(data.result.tarif == true) $("#tarif").prop("checked", true);
                    
                    if(data.result.harilibur == true) $("#harilibur").prop("checked", true);

                    if(data.result.layanan == true) $("#layanan").prop("checked", true);

                    if(data.result.simulasi == true) $("#simulasi").prop("checked", true);
                }
                else{
                    $('.blokOtoritas').select2({
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
                }
			}
		})
    });

    $('#form_otoritas').on('submit', function(event){
		event.preventDefault();
		$.ajax({
			url: '/user/otoritas',
            cache:false,
			method:"POST",
			data:$(this).serialize(),
			dataType:"json",
			success:function(data)
			{
                $('#userAdmin').DataTable().ajax.reload(function(){}, false);
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
                    .fadeTo(2000, 1000)
                    .slideUp(2000, function () {
                        $("#success-alert,#error-alert").slideUp(1000);
                });
                $('#myOtoritas').modal('hide');
            }
		});
    });

    var reset_id;
    var reset_nama;
    $(document).on('click', '.reset', function(){
		reset_id = $(this).attr('id');
		reset_nama = $(this).attr('nama');
		$('.titles').text('Reset password ' + reset_nama + ' ?');
		$('#confirmReset').modal('show');
	});

    $('#ya_button').click(function(){
        $('#confirmReset').modal('hide');
		$('.titles').text('Password baru ' + reset_nama);
        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
		$.ajax({       
			url: '/user/reset/' + reset_id,
            cache:false,
			method:"POST",
			data:$(this).serialize(),
			dataType:"json",
			success:function(data)
			{
				var html = '';
				if(data.errors)
				{
                    html = data.errors;
				}
				if(data.success)
				{
					html = data.success;
                }
				$('#password_baru').html(html);
                $('#resetModal').modal('show');
            }
		});
    });

    $(document).on('click', '.details', function(){
		id = $(this).attr('id');
		username = $(this).attr('nama');
		$('.titles').html("<h1 style='color:white;font-weight:700;'>" + username + "</h1>");
        $.ajax({
			url :"/user/details/"+id,
            cache:false,
			dataType:"json",
			success:function(data)
			{
                if(data.result.ktp !== null)
                    $('.ktp').text(data.result.ktp);
                else
                    $('.ktp').html("&mdash;");
                if(data.result.anggota !== null)
                    $('.anggota').text(data.result.anggota);
                else
                    $('.anggota').html("&mdash;");
                if(data.result.alamat !== null)
                    $('.alamat').text(data.result.alamat);
                else
                    $('.alamat').html("&mdash;");
                
                if(data.result.hp !== null)
                    $('#whatsapp-number').attr("href", "https://api.whatsapp.com/send?phone=" + data.result.hp + "&text=*Selamat%20Berniaga%20Mitra%20BP3C*").attr("target", "_blank")
                    .css("pointer-events","").removeClass("btn-dark").addClass("btn-success");
                else
                    $('#whatsapp-number').attr("href","#").removeAttr("target").css("pointer-events","none").removeClass("btn-success").addClass("btn-dark");
                
                if(data.result.email !== null)
                    $('#email-number').attr("href", "mailto:" + data.result.email).attr("target", "_blank").css("pointer-events","").removeClass("btn-dark").addClass("btn-danger");
                else
                    $('#email-number').attr("href","#").removeAttr("target").css("pointer-events", "none").removeClass("btn-danger").addClass("btn-dark");
                
                $('#show-details').modal('show');
			}
		});
	});

    $('[type=tel]').on('change', function(e) {
        $(e.target).val($(e.target).val().replace(/[^\d\.]/g, ''))
    });

    $('[type=tel]').on('keypress', function(e) {
        keys = ['0','1','2','3','4','5','6','7','8','9']
        return keys.indexOf(e.key) > -1
    });

    $("#nama").on("change paste keyup", function(e){
        $(e.target).val($(e.target).val().replace(/[^a-zA-Z.\s]/gi,''));
    });
});
$(document).ready(function(){
	var dtable = $('#tabelPedagang').DataTable({
		serverSide: true,
		ajax: {
			url: "/pedagang",
            cache:false,
		},
		columns: [
			{ data: 'nama', name: 'nama', class : 'text-center-td' },
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
            { "bSortable": false, "aTargets": [1,2] }, 
            { "bSearchable": false, "aTargets": [1,2] }
        ],
        order:[[0, 'asc']],
        responsive:true
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

    var id;
    $('.alamatPemilik').select2();
    $('.alamatPengguna').select2();

    $('#add_pedagang').click(function(){
		$('.titles').text('Tambah Pedagang');
        $('#alamatPemilik').prop('required', false);
        $('#alamatPengguna').prop('required', false);
		$('#action_btn').val('Tambah');
		$('#action').val('Add');
		$('#form_result').html('');
        $('#form_pedagang')[0].reset();
        $('#displayPemilik').hide();
        $('#displayPengguna').hide();
        $('#username').val();
        $('#form_pedagang')[0].reset();

        $('#alamatPemilik').select2("destroy").val('').html('').select2({
            placeholder: '--- Pilih Kepemilikan ---',
            ajax: {
                url: "/cari/alamat",
                dataType: 'json',
                delay: 250,
                processResults: function (alamat) {
                    return {
                    results:  $.map(alamat, function (al) {
                        return {
                        text: al.kd_kontrol,
                        id: al.id
                        }
                    })
                    };
                },
                cache: true,
            }
        });

        $('#alamatPengguna').select2("destroy").val('').html('').select2({
            placeholder: '--- Pilih Tempat ---',
            ajax: {
                url: "/cari/alamat",
                dataType: 'json',
                delay: 250,
                processResults: function (alamat) {
                    return {
                    results:  $.map(alamat, function (al) {
                        return {
                        text: al.kd_kontrol,
                        id: al.id
                        }
                    })
                    };
                },
                cache: true
            }
        });

		$('#myModal').modal('show');
    });
    
    $(document).on('click', '.edit', function(){
		id = $(this).attr('id');
        $('#hidden_id').val(id);
        $('.titles').text('Edit Pedagang');
        $('#alamatPemilik').prop('required', false);
        $('#alamatPengguna').prop('required', false);
        $('#action_btn').val('Update');
        $('#action').val('Edit');
		$('#form_result').html('');
        $('#form_pedagang')[0].reset();
        $('#displayPemilik').hide();
        $('#displayPengguna').hide();
        $('#username').val();
        $('#form_pedagang')[0].reset();

        var s1 = $('#alamatPemilik').select2("destroy").val('').html('').select2({
            placeholder: '--- Pilih Kepemilikan ---',
            ajax: {
                url: "/cari/alamat",
                dataType: 'json',
                delay: 250,
                processResults: function (alamat) {
                    return {
                    results:  $.map(alamat, function (al) {
                        return {
                        text: al.kd_kontrol,
                        id: al.id
                        }
                    })
                    };
                },
                cache: true
            }
        });

        var s2 = $('#alamatPengguna').select2("destroy").val('').html('').select2({
            placeholder: '--- Pilih Tempat ---',
            ajax: {
                url: "/cari/alamat",
                dataType: 'json',
                delay: 250,
                processResults: function (alamat) {
                    return {
                    results:  $.map(alamat, function (al) {
                        return {
                        text: al.kd_kontrol,
                        id: al.id
                        }
                    })
                    };
                },
                cache: true
            }
        });

		$.ajax({
			url :"/pedagang/"+id+"/edit",
            cache:false,
			dataType:"json",
			success:function(data)
			{
				$('#ktp').val(data.result.ktp);
                $('#nama').val(data.result.nama);
                $('#username').val(data.result.username);
                $('#anggota').val(data.result.anggota);
                $('#email').val(data.result.email);
                $('#hp').val(data.result.hp);
                $('#alamat').val(data.result.alamat);

                if(data.result.checkPemilik == 'checked'){
                    $("#pemilik").prop("checked", true);
                    $('#alamatPemilik').prop('required', true);
                    $("#displayPemilik").show();

                    var valPemilik = data.result.pemilik;

                    valPemilik.forEach(function(e){
                        if(!s1.find('option:contains(' + e + ')').length) 
                            s1.append($('<option>').text(e));
                    });
                    s1.val(valPemilik).trigger("change"); 
                }
                else{
                    $("#pemilik").prop("checked", false);
                    $("#displayPemilik").hide();
                }
                
                if(data.result.checkPengguna == 'checked'){
                    $("#pengguna").prop("checked", true);
                    $('#alamatPengguna').prop('required', true);
                    $("#displayPengguna").show();

                    var valPengguna = data.result.pengguna;
                    
                    valPengguna.forEach(function(e){
                        if(!s2.find('option:contains(' + e + ')').length) 
                            s2.append($('<option>').text(e));
                    });
                    s2.val(valPengguna).trigger("change"); 
                }
                else{
                    $("#pengguna").prop("checked", false);
                    $("#displayPengguna").hide();
                }
                
                $('#myModal').modal('show');
			}
		})
    });

    $('#form_pedagang').on('submit', function(event){
		event.preventDefault();
		var action_url = '';

		if($('#action').val() == 'Add')
		{
			action_url = "/pedagang";
        }

        if($('#action').val() == 'Edit')
		{
			action_url = "/pedagang/update";
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
                    console.log(data.errors);
				}
				if(data.success)
				{
					html = '<div class="alert alert-success" id="success-alert"> <strong>Sukses ! </strong>' + data.success + '</div>';
                }
                $('#tabelPedagang').DataTable().ajax.reload(function(){}, false);
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
    
    $('#nama').on('input',function(){
        if($('#action').val() == 'Add'){
            nama = $(this).val();
            username = nama.replace(/[^a-zA-Z]/g,'');
            nama = username;
            username = username.slice(0, 7);
            number = Math.floor(1000 + Math.random() * 9000);

            anggota = 'BP3C' + Math.floor(100000 + Math.random() * 982718);
            anggota = anggota.slice(0, 10);

            if(nama == ''){
                document.getElementById("username").value = '';
                document.getElementById("anggota").value = '';
            }
            else{
                document.getElementById("username").value = username + number;
                document.getElementById("anggota").value = anggota;
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
		username = $(this).attr('nama');
		$('.titles').text('Hapus data ' + username + ' ?');
		$('#confirmModal').modal('show');
	});

	$('#ok_button').click(function(){
		$.ajax({
			url:"/pedagang/destroy/"+user_id,
            cache:false,
			beforeSend:function(){
				$('#ok_button').text('Menghapus...');
			},
			success:function(data)
			{
                $('#tabelPedagang').DataTable().ajax.reload(function(){}, false);
                if(data.success)
                    html = '<div class="alert alert-success" id="success-alert"> <strong>Sukses! </strong>' + data.success + '</div>';
                if(data.errors)
                    html = '<div class="alert alert-danger" id="error-alert"> <strong>Oops! </strong>' + data.errors + '</div>';
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

    $(document).on('click', '.details', function(){
		id = $(this).attr('id');
		username = $(this).attr('nama');
		$('.titles').html("<h1 style='color:white;font-weight:700;'>" + username + "</h1>");
        $.ajax({
			url :"/pedagang/"+id,
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
    
    function checkPemilik() {
        if ($('#pemilik').is(':checked')) {
            document
                .getElementById('alamatPemilik')
                .required = true;
        } else {
            document
                .getElementById('alamatPemilik')
                .required = false;
        }
    }
    $('input[type="checkbox"]')
        .click(checkPemilik)
        .each(checkPemilik);

    function checkPengguna() {
        if ($('#pengguna').is(':checked')) {
            document
                .getElementById('alamatPengguna')
                .required = true;
        } else {
            document
                .getElementById('alamatPengguna')
                .required = false;
        }
    }
    $('input[type="checkbox"]')
        .click(checkPengguna)
        .each(checkPengguna);

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
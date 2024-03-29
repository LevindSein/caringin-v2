$(document).ready(function(){
    $('#form_listrik').on('submit', function(event){
		event.preventDefault();
        fas = $(this).attr('fas');
		$.ajax({
			url :"/utilities/tarif/update",
            cache:false,
			method:"POST",
			data:$(this).serialize(),
			dataType:"json",
			success:function(data)
			{
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
                window.scrollTo(0,0);
            }
        });
    });

    $('#form_air').on('submit', function(event){
		event.preventDefault();
        fas = $(this).attr('fas');
		$.ajax({
			url :"/utilities/tarif/update",
            cache:false,
			method:"POST",
			data:$(this).serialize(),
			dataType:"json",
			success:function(data)
			{
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
                window.scrollTo(0,0);
            }
        });
    });

    $("#tab-c-2").click(function(){
        $('#div-refresh').show();
        if (!$.fn.dataTable.isDataTable('#tabelKeamananIpk')) {
            var dtable = $('#tabelKeamananIpk').DataTable({
                serverSide: true,
                ajax: '/utilities/tarif/keamananipk',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', class : 'text-center' },
                    { data: 'tarif', name: 'tarif', class : 'text-center' },
                    { data: 'prs_keamanan', name: 'prs_keamanan', class : 'text-center' },
                    { data: 'prs_ipk', name: 'prs_ipk', class : 'text-center' },
                    { data: 'action', name: 'action', class : 'text-center' },
                ],
                stateSave: true,
                deferRender: true,
                pageLength: 10,
                aLengthMenu: [[5,10,25,50,100], [5,10,25,50,100]],
                language: {
                    paginate: {
                        previous: "<i class='fas fa-angle-left'>",
                        next: "<i class='fas fa-angle-right'>"
                    }
                },
                aoColumnDefs: [
                    { "bSortable": false, "aTargets": [0,4] },
                    { "bSearchable": false, "aTargets": [0,4] }
                ],
                responsive:true,
                scrollY: "50vh",
                scrollX: true,
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
        }
    });

    $("#tab-c-3").click(function(){
        $('#div-refresh').show();
        if (!$.fn.dataTable.isDataTable('#tabelKebersihan')) {
            var dtable = $('#tabelKebersihan').DataTable({
                serverSide: true,
                ajax: '/utilities/tarif/kebersihan',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', class : 'text-center' },
                    { data: 'tarif', name: 'tarif', class : 'text-center' },
                    { data: 'action', name: 'action', class : 'text-center' },
                ],
                stateSave: true,
                deferRender: true,
                pageLength: 10,
                aLengthMenu: [[5,10,25,50,100], [5,10,25,50,100]],
                language: {
                    paginate: {
                        previous: "<i class='fas fa-angle-left'>",
                        next: "<i class='fas fa-angle-right'>"
                    }
                },
                aoColumnDefs: [
                    { "bSortable": false, "aTargets": [0,2] },
                    { "bSearchable": false, "aTargets": [0,2] }
                ],
                responsive:true,
                scrollY: "50vh",
                scrollX: true,
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
        }
    });

    $("#tab-c-4").click(function(){
        $('#div-refresh').show();
        if (!$.fn.dataTable.isDataTable('#tabelAirKotor')) {
            var dtable = $('#tabelAirKotor').DataTable({
                serverSide: true,
                ajax: '/utilities/tarif/airkotor',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', class : 'text-center' },
                    { data: 'tarif', name: 'tarif', class : 'text-center' },
                    { data: 'action', name: 'action', class : 'text-center' },
                ],
                stateSave: true,
                deferRender: true,
                pageLength: 10,
                aLengthMenu: [[5,10,25,50,100], [5,10,25,50,100]],
                language: {
                    paginate: {
                        previous: "<i class='fas fa-angle-left'>",
                        next: "<i class='fas fa-angle-right'>"
                    }
                },
                aoColumnDefs: [
                    { "bSortable": false, "aTargets": [0,2] },
                    { "bSearchable": false, "aTargets": [0,2] }
                ],
                responsive:true,
                scrollY: "50vh",
                scrollX: true,
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
        }
    });

    $("#tab-c-6").click(function(){
        $('#div-refresh').show();
        if (!$.fn.dataTable.isDataTable('#tabelLain')) {
            var dtable = $('#tabelLain').DataTable({
                serverSide: true,
                ajax: '/utilities/tarif/lain',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', class : 'text-center' },
                    { data: 'tarif', name: 'tarif', class : 'text-center' },
                    { data: 'action', name: 'action', class : 'text-center' },
                ],
                stateSave: true,
                deferRender: true,
                pageLength: 10,
                aLengthMenu: [[5,10,25,50,100], [5,10,25,50,100]],
                language: {
                    paginate: {
                        previous: "<i class='fas fa-angle-left'>",
                        next: "<i class='fas fa-angle-right'>"
                    }
                },
                aoColumnDefs: [
                    { "bSortable": false, "aTargets": [0,2] },
                    { "bSearchable": false, "aTargets": [0,2] }
                ],
                responsive:true,
                scrollY: "50vh",
                scrollX: true,
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
        }
    });

    $("#tab-c-0").click(function(){
        $('#div-refresh').hide();
    });

    $("#tab-c-1").click(function(){
        $('#div-refresh').hide();
    });

    $('#add_tarif').click(function(){
		$('.titles').text('Tambah Tarif');
		$('#action_btn').val('Tambah');
		$('#action').val('Add');
		$('#form_result').html('');
        $('#form_tarif')[0].reset();
        $('#myModal').modal('show');

        $('input[id="myCheck1"]').attr('disabled',false);
        $('input[id="myCheck2"]').attr('disabled',false);
        $('input[id="myCheck3"]').attr('disabled',false);
        $('input[id="myCheck5"]').attr('disabled',false);

        $("#checkKeamananIpk").prop("checked", false);
        $("#displayKeamananIpk").hide();
        $("#checkKebersihan").prop("checked", false);
        $("#displayKebersihan").hide();
        $("#checkAirKotor").prop("checked", false);
        $("#displayAirKotor").hide();
        $("#checkLain").prop("checked", false);
        $("#displayLain").hide();

        $('#keamananIpk').prop('required',false);
        $('#prs_keamanan').prop('required',false);
        $('#prs_ipk').prop('required',false);
        $('#kebersihan').prop('required',false);
        $('#airkotor').prop('required',false);
        $('#lain').prop('required',false);
    });

    $(document).on('click', '.edit', function(){
        id = $(this).attr('id');
        fas = $(this).attr('fas');
        $('#form_result').html('');

        $('#keamananIpk').prop('required',false);
        $('#prs_keamanan').prop('required',false);
        $('#prs_ipk').prop('required',false);
        $('#kebersihan').prop('required',false);
        $('#airkotor').prop('required',false);
        $('#lain').prop('required',false);

		$.ajax({
			url :"/utilities/tarif/edit/" + fas + "/" + id,
            cache:false,
			dataType:"json",
			success:function(data)
			{
                if(fas == 'keamananipk'){
                    $('input[id="myCheck1"]').attr('disabled',false).prop("checked", true);
                    $('input[id="myCheck2"]').attr('disabled',true).prop("checked", false);
                    $('input[id="myCheck3"]').attr('disabled',true).prop("checked", false);
                    $('input[id="myCheck5"]').attr('disabled',true).prop("checked", false);

                    $("#displayKeamananIpk").show();
                    $("#displayKebersihan").hide();
                    $("#displayAirKotor").hide();
                    $("#displayLain").hide();

                    $('#keamananIpk').val(data.result.keamananipk);

                    $('#prs_keamanan').val(data.result.prs_keamanan);
                    $('#prs_ipk').val(data.result.prs_ipk);
                }

                if(fas == 'kebersihan'){
                    $('input[id="myCheck1"]').attr('disabled',true).prop("checked", false);
                    $('input[id="myCheck2"]').attr('disabled',false).prop("checked", true);
                    $('input[id="myCheck3"]').attr('disabled',true).prop("checked", false);
                    $('input[id="myCheck5"]').attr('disabled',true).prop("checked", false);

                    $("#displayKeamananIpk").hide();
                    $("#displayKebersihan").show();
                    $("#displayAirKotor").hide();
                    $("#displayLain").hide();

                    $('#kebersihan').val(data.result);
                }

                if(fas == 'airkotor'){
                    $('input[id="myCheck1"]').attr('disabled',true).prop("checked", false);
                    $('input[id="myCheck2"]').attr('disabled',true).prop("checked", false);
                    $('input[id="myCheck3"]').attr('disabled',false).prop("checked", true);
                    $('input[id="myCheck5"]').attr('disabled',true).prop("checked", false);

                    $("#displayKeamananIpk").hide();
                    $("#displayKebersihan").hide();
                    $("#displayAirKotor").show();
                    $("#displayLain").hide();

                    $('#airkotor').val(data.result);
                }

                if(fas == 'lain'){
                    $('input[id="myCheck1"]').attr('disabled',true).prop("checked", false);
                    $('input[id="myCheck2"]').attr('disabled',true).prop("checked", false);
                    $('input[id="myCheck3"]').attr('disabled',true).prop("checked", false);
                    $('input[id="myCheck5"]').attr('disabled',false).prop("checked", true);

                    $("#displayKeamananIpk").hide();
                    $("#displayKebersihan").hide();
                    $("#displayAirKotor").hide();
                    $("#displayLain").show();

                    $('#lain').val(data.result);
                }

				$('#hidden_id').val(id);
				$('.titles').text('Edit Tarif');
				$('#action_btn').val('Update');
				$('#action').val('Edit');
                $('#myModal').modal('show');
			}
		})
    });

    $('#form_tarif').on('submit', function(event){
		event.preventDefault();
		var action_url = '';

		if($('#action').val() == 'Add')
		{
			action_url = "/utilities/tarif/store";
        }

        if($('#action').val() == 'Edit')
		{
			action_url = "/utilities/tarif/update";
		}

		$.ajax({
			url: action_url,
            cache:false,
			method:"POST",
			data:$(this).serialize(),
			dataType:"json",
            beforeSend:function(){
                $("#action_btn").prop("disabled",true);
            },
			success:function(data)
			{
				var html = '';
				if(data.result.success)
				{
					html = '<div class="alert alert-success" id="success-alert"> <strong>Sukses ! </strong>' + data.result.success + '</div>';
                    $('#form_tarif')[0].reset();
                    $("#checkKeamananIpk").prop("checked", false);
                    $("#displayKeamananIpk").hide();
                    $("#checkKebersihan").prop("checked", false);
                    $("#displayKebersihan").hide();
                    $("#checkAirKotor").prop("checked", false);
                    $("#displayAirKotor").hide();
                    $("#checkLain").prop("checked", false);
                    $("#displayLain").hide();
                    try{
                        if(data.result.role == 'keamananipk'){
                            $('#tab-c-2').trigger('click');
                        }
                        if(data.result.role == 'kebersihan'){
                            $('#tab-c-3').trigger('click');
                        }
                        if(data.result.role == 'airkotor'){
                            $('#tab-c-4').trigger('click');
                        }
                        if(data.result.role == 'lain'){
                            $('#tab-c-6').trigger('click');
                        }
                    } catch(err){}
                    finally{
                        if(data.result.role == 'keamananipk'){
                            $('#tabelKeamananIpk').DataTable().ajax.reload(function(){}, false);
                        }
                        if(data.result.role == 'kebersihan'){
                            $('#tabelKebersihan').DataTable().ajax.reload(function(){}, false);
                        }
                        if(data.result.role == 'airkotor'){
                            $('#tabelAirKotor').DataTable().ajax.reload(function(){}, false);
                        }
                        if(data.result.role == 'lain'){
                            $('#tabelLain').DataTable().ajax.reload(function(){}, false);
                        }
                    }
                }
				if(data.result.errors)
				{
                    html = '<div class="alert alert-danger" id="error-alert"> <strong>Maaf ! </strong>' + data.result.errors + '</div>';
				}
				$('#form_result').html(html);
                $('input[id="myCheck1"]').attr('disabled',false);
                $('input[id="myCheck2"]').attr('disabled',false);
                $('input[id="myCheck3"]').attr('disabled',false);
                $('input[id="myCheck5"]').attr('disabled',false);
                $("#success-alert,#error-alert,#info-alert,#warning-alert")
                    .fadeTo(1000, 500)
                    .slideUp(1000, function () {
                        $("#success-alert,#error-alert").slideUp(500);
                });
			},
            complete:function(){
                $('#myModal').modal('hide');
                $("#action_btn").prop("disabled",false);
            }
		});
    });

    var user_id;
    var fas;
    $(document).on('click', '.delete', function(){
		user_id = $(this).attr('id');
		fas = $(this).attr('fas');
		nama = $(this).attr('nama');
		$('.titles').text('Hapus data ' + Number(nama).toLocaleString("en-Us") + ' ?');
		$('#confirmModal').modal('show');
        $('#form_result').html('');
	});

	$('#ok_button').click(function(){
		$.ajax({
			url:"/utilities/tarif/destroy/"+ fas + "/" + user_id,
            cache:false,
			beforeSend:function(){
				$('#ok_button').text('Menghapus...').prop("disabled",true);
			},
			success:function(data)
			{
                if(data.result.role == 'keamananipk'){
                    $('#tabelKeamananIpk').DataTable().ajax.reload(function(){}, false);
                }
                if(data.result.role == 'kebersihan'){
                    $('#tabelKebersihan').DataTable().ajax.reload(function(){}, false);
                }
                if(data.result.role == 'airkotor'){
                    $('#tabelAirKotor').DataTable().ajax.reload(function(){}, false);
                }
                if(data.result.role == 'lain'){
                    $('#tabelLain').DataTable().ajax.reload(function(){}, false);
                }
                if(data.result.success){
                    swal({
                        title: 'Success',
                        text: data.result.success,
                        type: 'success',
                        buttonsStyling: false,
                        confirmButtonClass: 'btn btn-success'
                    });
                    // html = '<div class="alert alert-success" id="success-alert"> <strong>Sukses! </strong>' + data.result.success + '</div>';
                }
                if(data.result.errors){
                    swal({
                        title: 'Oops!',
                        text: data.result.errors,
                        type: 'error',
                        buttonsStyling: false,
                        confirmButtonClass: 'btn btn-danger'
                    });
                    // html = '<div class="alert alert-danger" id="error-alert"> <strong>Oops! </strong>' + data.result.errors + '</div>';
                }
                // $('#form_result').html(html);
                $("#success-alert,#error-alert,#info-alert,#warning-alert")
                    .fadeTo(1000, 500)
                    .slideUp(1000, function () {
                        $("#success-alert,#error-alert").slideUp(500);
                });
            },
            complete:function(){
                $('#confirmModal').modal('hide');
                $('#ok_button').text('Hapus').prop("disabled",false);
            }
        })
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

    document
        .getElementById('keamananIpk')
        .addEventListener(
            'input',
            event => event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US')
        );
    document
        .getElementById('kebersihan')
        .addEventListener(
            'input',
            event => event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US')
        );
    document
        .getElementById('airkotor')
        .addEventListener(
            'input',
            event => event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US')
        );
    document
        .getElementById('lain')
        .addEventListener(
            'input',
            event => event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US')
        );
    function checkKeamananIpk() {
        if ($('#myCheck1').is(':checked')) {
            document
                .getElementById('keamananIpk')
                .required = true;
            document
                .getElementById('prs_keamanan')
                .required = true;
            document
                .getElementById('prs_ipk')
                .required = true;
        } else {
            document
                .getElementById('keamananIpk')
                .required = false;
            document
                .getElementById('prs_keamanan')
                .required = false;
            document
                .getElementById('prs_ipk')
                .required = false;
        }
    }
    $('input[type="checkbox"]')
        .click(checkKeamananIpk)
        .each(checkKeamananIpk);


    function checkKebersihan() {
        if ($('#myCheck2').is(':checked')) {
            document
                .getElementById('kebersihan')
                .required = true;
        } else {
            document
                .getElementById('kebersihan')
                .required = false;
        }
    }
    $('input[type="checkbox"]')
        .click(checkKebersihan)
        .each(checkKebersihan);

    function checkAirKotor() {
        if ($('#myCheck3').is(':checked')) {
            document
                .getElementById('airkotor')
                .required = true;
        } else {
            document
                .getElementById('airkotor')
                .required = false;
        }
    }
    $('input[type="checkbox"]')
        .click(checkAirKotor)
        .each(checkAirKotor);

    function checkLain() {
        if ($('#myCheck5').is(':checked')) {
            document
                .getElementById('lain')
                .required = true;
        } else {
            document
                .getElementById('lain')
                .required = false;
        }
    }
    $('input[type="checkbox"]')
        .click(checkLain)
        .each(checkLain);

    document
    .getElementById('tarif1')
    .addEventListener(
        'input',
        event => event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US')
    );
    document
    .getElementById('tarif2')
    .addEventListener(
        'input',
        event => event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US')
    );
    document
    .getElementById('pemeliharaan')
    .addEventListener(
        'input',
        event => event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US')
    );
    document
    .getElementById('bebanAir')
    .addEventListener(
        'input',
        event => event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US')
    );
    document
    .getElementById('dendaAir')
    .addEventListener(
        'input',
        event => event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US')
    );
    document
    .getElementById('pasangAir')
    .addEventListener(
        'input',
        event => event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US')
    );


    document
    .getElementById('blok1')
    .addEventListener(
        'input',
        event => event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US')
    );
    document
    .getElementById('blok2')
    .addEventListener(
        'input',
        event => event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US')
    );
    document
    .getElementById('bebanListrik')
    .addEventListener(
        'input',
        event => event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US')
    );
    document
    .getElementById('denda1')
    .addEventListener(
        'input',
        event => event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US')
    );
    document
    .getElementById('pasangListrik')
    .addEventListener(
        'input',
        event => event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US')
    );

    $('#rekmin').keypress(function(event) {
        if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
    });
});

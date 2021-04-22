$(document).ready(function () {
    var dtable = $('#tabelAlatListrik').DataTable({
		serverSide: true,
		ajax: {
			url: "/utilities/alatmeter",
            cache:false,
		},
		columns: [
			{ data: 'kode', name: 'kode', class : 'text-center' },
			{ data: 'nomor', name: 'nomor', class : 'text-center-td' },
			{ data: 'akhir', name: 'akhir', class : 'text-center' },
			{ data: 'daya', name: 'daya', class : 'text-center' },
			{ data: 'kd_kontrol', name: 'tempat_usaha.kd_kontrol', class : 'text-center' },
			{ data: 'action', name: 'action', class : 'text-center' },
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
            { "bSortable": false, "aTargets": [5] }, 
            { "bSearchable": false, "aTargets": [5] }
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

    $("#tab-c-1").click(function(){
        if (!$.fn.dataTable.isDataTable('#tabelAlatAir')) {
            var dtable = $('#tabelAlatAir').DataTable({
                serverSide: true,
                ajax: {
                    url: "/utilities/alatmeter/air",
                    cache:false,
                },
                columns: [
                    { data: 'kode', name: 'kode', class : 'text-center' },
                    { data: 'nomor', name: 'nomor', class : 'text-center-td' },
                    { data: 'akhir', name: 'akhir', class : 'text-center' },
                    { data: 'kd_kontrol', name: 'tempat_usaha.kd_kontrol', class : 'text-center' },
                    { data: 'action', name: 'action', class : 'text-center' },
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
                    { "bSortable": false, "aTargets": [4] }, 
                    { "bSearchable": false, "aTargets": [4] }
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

    $('#add_alat').click(function(){
		$('.titles').text('Tambah Alat');
		$('#action_btn').val('Tambah');
		$('#action').val('Add');
		$('#form_result').html('');
        $('#form_alat')[0].reset();
		$('#myModal').modal('show');
        $("#listrik").prop("checked", true);
        $("#meteranListrik").show();
        $("#meteranAir").hide();
    });

    $(document).on('click', '.edit', function(){
		id = $(this).attr('id');
		fas = $(this).attr('fas');
		$('#form_result').html('');
		$.ajax({
			url :"/utilities/alatmeter/edit/"+ fas + "/" + id,
            cache:false,
			dataType:"json",
			success:function(data)
			{
                $('#nomor').val(data.result.nomor);
                if(fas == 'listrik'){
                    $('#listrik').prop("checked", true);
                    $('input[id="listrik"]').attr('disabled',false);
                    $('input[id="air"]').attr('disabled',true);
                    $("#meteranListrik").show();
                    $("#meteranAir").hide();
                    $('#standListrik').val(data.result.akhir);
                    $('#dayaListrik').val(data.result.daya);
                    $("#standAir").prop('required',false);
                }
                else{
                    $('#air').prop("checked", true);
                    $('input[id="air"]').attr('disabled',false);
                    $('input[id="listrik"]').attr('disabled',true);
                    $("#meteranListrik").hide();
                    $("#meteranAir").show();
                    $('#standAir').val(data.result.akhir);
                    $("#standListrik").prop('required',false);
                    $("#dayaListrik").prop('required',false);
                }
                
				$('#hidden_id').val(id);
				$('.titles').text('Edit Alat');
				$('#action_btn').val('Update');
				$('#action').val('Edit');
                $('#myModal').modal('show');
			}
		})
    });

    $('#form_alat').on('submit', function(event){
		event.preventDefault();
		var action_url = '';

		if($('#action').val() == 'Add')
		{
			action_url = "/utilities/alatmeter/store";
        }

        if($('#action').val() == 'Edit')
		{
			action_url = "/utilities/alatmeter/update";
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
                    $('#form_alat')[0].reset();
					try{
                        if(data.result.role == 'listrik'){
                            $('#tab-c-0').trigger('click');
                        }
                        if(data.result.role == 'air'){
                            $('#tab-c-1').trigger('click');
                        }
                    } catch(err){}
                    finally{
                        if(data.result.role == 'listrik'){
                            $('#tabelAlatListrik').DataTable().ajax.reload(function(){}, false);
                        }
                        if(data.result.role == 'air'){
                            $('#tabelAlatAir').DataTable().ajax.reload(function(){}, false);
                        }
                    }
                }
                if($('#action').val() == 'Edit')
                {
                    setTimeout(function(){
                        $('#myModal').modal('hide');
                    }, 3000);
                }
				$('#form_result').html(html);
                $("#listrik").prop("checked", true);
                $("#meteranListrik").show();
                $("#meteranAir").hide();
                $('input[id="listrik"]').attr('disabled',false);
                $('input[id="air"]').attr('disabled',false);
                $('#form_alat')[0].reset();
                $("#standAir").prop('required',false);
                $("#standListrik").prop('required',false);
                $("#dayaListrik").prop('required',false);
                $("#success-alert,#error-alert,#info-alert,#warning-alert")
                    .fadeTo(1000, 500)
                    .slideUp(1000, function () {
                        $("#success-alert,#error-alert").slideUp(500);
                });
			}
		});
    });

    $(document).on('click', '.qr', function(){
        var id = $(this).attr('id');
        var fas = $(this).attr('fas');
        window.open(
            '/utilities/alatmeter/qr/' + fas + '/' + id,
            '_blank'
        );
	});

    var user_id;
    var fas_id;
    $(document).on('click', '.delete', function(){
		user_id = $(this).attr('id');
		fas_id = $(this).attr('fas');
		nama = $(this).attr('nama');
		$('.titles').text('Hapus data ' + fas_id + ' ' + nama + ' ?');
		$('#confirmModal').modal('show');
	});

	$('#ok_button').click(function(){
		$.ajax({
			url:"/utilities/alatmeter/destroy/" + fas_id + '/' + user_id,
            cache:false,
			beforeSend:function(){
				$('#ok_button').text('Menghapus...');
			},
			success:function(data)
			{
                if(data.result.role == 'listrik'){
                    $('#tabelAlatListrik').DataTable().ajax.reload(function(){}, false);
                }
                if(data.result.role == 'air'){
                    $('#tabelAlatAir').DataTable().ajax.reload(function(){}, false);
                }
                if(data.result.success)
                    html = '<div class="alert alert-success" id="success-alert"> <strong>Sukses! </strong>' + data.result.success + '</div>';
                if(data.result.errors)
                    html = '<div class="alert alert-danger" id="error-alert"> <strong>Oops! </strong>' + data.result.errors + '</div>';
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

    function statusMeter() {
        if ($('#listrik').is(':checked')) {
            document
                .getElementById('meteranListrik')
                .style
                .display = 'block';
            document
                .getElementById('meteranAir')
                .style
                .display = 'none';
        }
        else {
            document
                .getElementById('meteranListrik')
                .style
                .display = 'none';
            document
                .getElementById('meteranAir')
                .style
                .display = 'block';
        }
    }
    $('input[type="radio"]')
        .click(statusMeter)
        .each(statusMeter);
    
    $('#myModal').on('shown.bs.modal', function () {
        $('#nomor').trigger('focus');
    }); 

    document
        .getElementById('standAir')
        .addEventListener(
            'input',
            event => event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US')
        );
    document
        .getElementById('standListrik')
        .addEventListener(
            'input',
            event => event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US')
        );
    document
        .getElementById('dayaListrik')
        .addEventListener(
            'input',
            event => event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US')
        );

    function checkListrik() {
        if ($('#listrik').is(':checked')) {
            document
                .getElementById('standListrik')
                .required = true;
            document
                .getElementById('dayaListrik')
                .required = true;
        } else {
            document
                .getElementById('standListrik')
                .required = false;
            document
                .getElementById('dayaListrik')
                .required = false;
        }
    }
    $('input[type="radio"]')
        .click(checkListrik)
        .each(checkListrik);

    function checkAir() {
        if ($('#air').is(':checked')) {
            document
                .getElementById('standAir')
                .required = true;
        } else {
            document
                .getElementById('standAir')
                .required = false;
        }
    }
    $('input[type="radio"]')
        .click(checkAir)
        .each(checkAir);


    $('#nomor').on('keypress', function (event) {
        var regex = new RegExp("^[a-zA-Z0-9\s\-]+$");
        var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
        if (!regex.test(key)) {
        event.preventDefault();
        return false;
        }
    });

    $("#nomor").on("input", function() {
        if (/^,/.test(this.value)) {
            this.value = this.value.replace(/^,/, "")
        }
    });
});
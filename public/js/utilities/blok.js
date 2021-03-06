$(document).ready(function () {
    var dtable = $('#tabelBlok').DataTable({
		serverSide: true,
		ajax: {
			url: "/utilities/blok",
            cache:false,
		},
		columns: [
			{ data: 'nama', name: 'nama', class : 'text-center' },
			{ data: 'jumlah', name: 'jumlah', class : 'text-center' },
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
            { "bSortable": false, "aTargets": [2] }, 
            { "bSearchable": false, "aTargets": [2] }
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

    $('#add_blok').click(function(){
		$('.titles').text('Tambah Blok');
		$('#action_btn').val('Tambah');
		$('#action').val('Add');
		$('#form_result').html('');
        $('#form_blok')[0].reset();
		$('#myModal').modal('show');
    });

    $(document).on('click', '.edit', function(){
		id = $(this).attr('id');
		$('#form_result').html('');
		$.ajax({
			url :"/utilities/blok/edit/"+id,
            cache:false,
			dataType:"json",
			success:function(data)
			{
                $('#blokInput').val(data.result.nama);                
				$('#hidden_id').val(id);
				$('.titles').text('Edit Blok');
				$('#action_btn').val('Update');
				$('#action').val('Edit');
                $('#myModal').modal('show');
			}
		})
    });

    $('#form_blok').on('submit', function(event){
		event.preventDefault();
		var action_url = '';

		if($('#action').val() == 'Add')
		{
			action_url = "/utilities/blok/store";
        }

        if($('#action').val() == 'Edit')
		{
			action_url = "/utilities/blok/update";
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
				if(data.errors)
				{
                    html = '<div class="alert alert-danger" id="error-alert"> <strong>Maaf ! </strong>' + data.errors + '</div>';
				}
				if(data.success)
				{
					html = '<div class="alert alert-success" id="success-alert"> <strong>Sukses ! </strong>' + data.success + '</div>';
                    $('#form_blok')[0].reset();
					$('#tabelBlok').DataTable().ajax.reload(function(){}, false);
				}
				$('#form_result').html(html);
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
    $(document).on('click', '.delete', function(){
		user_id = $(this).attr('id');
		nama = $(this).attr('nama');
		$('.titles').text('Hapus data ' + nama + ' ?');
		$('#confirmModal').modal('show');
        $('#form_result').html('');
	});

	$('#ok_button').click(function(){
		$.ajax({
			url:"/utilities/blok/destroy/"+user_id,
            cache:false,
			beforeSend:function(){
				$('#ok_button').text('Menghapus...').prop("disabled",true);
			},
			success:function(data)
			{
                $('#tabelBlok').DataTable().ajax.reload(function(){}, false);
                if(data.success){
                    swal({
                        title: 'Success',
                        text: data.success,
                        type: 'success',
                        buttonsStyling: false,
                        confirmButtonClass: 'btn btn-success'
                    });
                    // html = '<div class="alert alert-success" id="success-alert"> <strong>Sukses! </strong>' + data.success + '</div>';
                }
                if(data.errors){
                    swal({
                        title: 'Oops!',
                        text: data.errors,
                        type: 'error',
                        buttonsStyling: false,
                        confirmButtonClass: 'btn btn-danger'
                    });
                    // html = '<div class="alert alert-danger" id="error-alert"> <strong>Oops! </strong>' + data.errors + '</div>';
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

    $('#blokInput').on('keypress', function (event) {
        var regex = new RegExp("^[a-zA-Z0-9\s\-]+$");
        var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
        if (!regex.test(key)) {
        event.preventDefault();
        return false;
        }
    });

    $("#blokInput").on("input", function() {
    if (/^,/.test(this.value)) {
        this.value = this.value.replace(/^,/, "")
    }
    else if (/^0/.test(this.value)) {
        this.value = this.value.replace(/^0/, "")
    }
    });
});
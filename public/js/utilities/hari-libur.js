$(document).ready(function () {
    $('#tabelHariLibur').DataTable({
		serverSide: true,
		ajax: {
			url: "/utilities/harilibur",
            cache:false,
		},
		columns: [
			{ data: 'tanggal', name: 'tanggal', class : 'text-center' },
			{ data: 'ket', name: 'ket', class : 'text-center-td' },
			{ data: 'action', name: 'action', class : 'text-center' },
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
            { "bSortable": false, "aTargets": [2] }, 
            { "bSearchable": false, "aTargets": [2] }
        ],
        responsive:true
    });

    $('#add_tanggal').click(function(){
		$('.titles').text('Tambah Hari Libur');
		$('#action_btn').val('Tambah');
		$('#action').val('Add');
		$('#form_result').html('');
        $('#form_harilibur')[0].reset();
		$('#myModal').modal('show');
    });

    $(document).on('click', '.edit', function(){
		id = $(this).attr('id');
		$('#form_result').html('');
		$.ajax({
			url :"/utilities/harilibur/edit/"+id,
            cache:false,
			dataType:"json",
			success:function(data)
			{
                $('#tanggal').val(data.result.tanggal);
                $('#ket').val(data.result.ket);                
				$('#hidden_id').val(id);
				$('.titles').text('Edit Blok');
				$('#action_btn').val('Update');
				$('#action').val('Edit');
                $('#myModal').modal('show');
			}
		})
    });

    $('#form_harilibur').on('submit', function(event){
		event.preventDefault();
		var action_url = '';

		if($('#action').val() == 'Add')
		{
			action_url = "/utilities/harilibur/store";
        }

        if($('#action').val() == 'Edit')
		{
			action_url = "/utilities/harilibur/update";
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
				if(data.errors)
				{
                    html = '<div class="alert alert-danger" id="error-alert"> <strong>Maaf ! </strong>' + data.errors + '</div>';
				}
				if(data.success)
				{
					html = '<div class="alert alert-success" id="success-alert"> <strong>Sukses ! </strong>' + data.success + '</div>';
                    $('#form_harilibur')[0].reset();
					$('#tabelHariLibur').DataTable().ajax.reload(function(){}, false);
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

    var user_id;
    $(document).on('click', '.delete', function(){
		user_id = $(this).attr('id');
        nama = $(this).attr('nama');
		$('#confirmModal').modal('show');
		$('.titles').text('Hapus data ' + nama + ' ?');
	});

	$('#ok_button').click(function(){
		$.ajax({
			url:"/utilities/harilibur/destroy/"+user_id,
            cache:false,
			beforeSend:function(){
				$('#ok_button').text('Menghapus...');
			},
			success:function(data)
			{
				$('#tabelHariLibur').DataTable().ajax.reload(function(){}, false);
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
});
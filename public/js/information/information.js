$(document).ready(function(){
	var dtable = $('#tabelInformation').DataTable({
		serverSide: true,
		ajax: {
			url: "/information",
            cache:false,
		},
		columns: [
			{ data: 'tanggal', name: 'tanggal', class : 'text-center' },
			{ data: 'keterangan', name: 'keterangan', class : 'text-center-td' },
			{ data: 'pengaruh', name: 'pengaruh', class : 'text-center-td' },
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
            { "bSortable": false, "aTargets": [0,1,2,3] }, 
            { "bSearchable": false, "aTargets": [0,2,3] }
        ],
        order:[[0, 'asc']],
        responsive:true
    });
    
    setInterval(function(){ dtable.ajax.reload(function(){}, false); }, 30000);

    $('#add_information').click(function(){
		$('.titles').text('Tambah Info Patch');
		$('#action_btn').val('Tambah');
		$('#myModal').modal('show');
    });
    
    $('#myModal').on('shown.bs.modal', function () {
        $('#ket_info').focus();
    })  

    $(document).on('click', '.edit', function(){
		
    });

    $('#form_information').on('submit', function(event){
		event.preventDefault();
		var action_url = '';

		if($('#action').val() == 'Add')
		{
			action_url = "/information";
        }

        if($('#action').val() == 'Edit')
		{
			action_url = "/information/update";
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
                $('#tabelInformation').DataTable().ajax.reload(function(){}, false);
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

    var id;
    $(document).on('click', '.delete', function(){
		id = $(this).attr('id');
		$('#confirmModal').modal('show');
	});

	$('#ok_button').click(function(){
		$.ajax({
			url:"/information/destroy/" + id,
            cache:false,
			beforeSend:function(){
				$('#ok_button').text('Menghapus...');
			},
			success:function(data)
			{
                $('#tabelInformation').DataTable().ajax.reload(function(){}, false);
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
$(document).ready(function(){
	var dtable = $('#tabelBongkaran').DataTable({
		serverSide: true,
		ajax: {
			url: "/layanan/bongkaran",
            cache:false,
		},
		columns: [
			{ data: 'kd_kontrol', name: 'kd_kontrol', class : 'text-center-td' },
			{ data: 'stt_bongkar', name: 'stt_bongkar', class : 'text-center-td' },
			{ data: 'show', name: 'show', class : 'text-center' },
			{ data: 'action', name: 'action', class : 'text-center' },
        ],
        stateSave: true,
        language: {
            paginate: {
                previous: "<i class='fas fa-angle-left'>",
                next: "<i class='fas fa-angle-right'>"
            }
        },
        deferRender: true,
        pageLength: 10,
        aLengthMenu: [[5,10,25,50,100], [5,10,25,50,100]],
        responsive: true,
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
    
    var id;

    $(document).on('click', '.selesai', function(){
		id = $(this).attr('id');
		username = $(this).attr('nama');
		$('.titles').text('Selesai ' + username + ' ?');
		$('#confirmModal').modal('show');
        $('#form_result').html('');
	});
    $('#ok_button').click(function(){
        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
		$.ajax({
			url:"/layanan/bongkaran/selesai/" + id,
            type: 'POST',
            cache:false,
			beforeSend:function(){
				$('#ok_button').text('Proccessing...').prop("disabled",true);
			},
			success:function(data)
			{
                $('#tabelBongkaran').DataTable().ajax.reload(function(){}, false);
                if(data.success){
                    swal({
                        title: 'Success',
                        text: data.success,
                        type: 'success',
                        buttonsStyling: false,
                        confirmButtonClass: 'btn btn-success'
                    });
                }
                if(data.errors){
                    swal({
                        title: 'Oops!',
                        text: data.errors,
                        type: 'error',
                        buttonsStyling: false,
                        confirmButtonClass: 'btn btn-danger'
                    });
                }
            },
            complete:function(){
                $('#confirmModal').modal('hide');
                $('#ok_button').text('Ok').prop("disabled",false);
            }
        })
    });

    $(document).on('click', '.surat', function(){
		id = $(this).attr('id');
		username = $(this).attr('nama');
		$('.titles').text('Unduh Surat ' + username);
        $('#surat_id').val(id);
		$('#suratModal').modal('show');
        $('#form_result').html('');
	});

    $(document).on('click', '.details', function(){
        $('#rincianrow').html('');
		id = $(this).attr('id');
		username = $(this).attr('nama');
		$('.titles').html("<h1 style='color:white;font-weight:700;'>" + username + "</h1>");
        $.ajax({
			url :"/layanan/bongkaran/show/"+id,
            cache:false,
			dataType:"json",
			success:function(data)
			{
                for(i = 0; i < data.result.length; i++){
                    html = '';
                    html += '<div class="card-profile-stats d-flex justify-content-between" style="margin-bottom:-1rem">';
                    html +=     '<div>';
                    html +=         '<span class="heading" style="font-size:.875rem;">' + data.result[i].bulan + '</span>';
                    html +=     '</div>';
                    html +=     '<div>';
                    html +=         '<span class="heading" style="font-size:.875rem;">' + data.result[i].sel_tagihan.toLocaleString('en-US') + '</span>';
                    html +=     '</div>';
                    html += '</div>';

                    $('#rincianrow').append(html);
                }

                $('#show-details').modal('show');
			}
		});
	});
});
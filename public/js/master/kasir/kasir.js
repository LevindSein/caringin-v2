$(document).ready(function () {
    $(document).on('click', '.edit', function(){
        id = $(this).attr("id");
        nama = $(this).attr("nama");
        $("#hidden_ref").val(id);
		$('.titles').text('Edit Tanggal ' + nama);
        $("#myModal").modal('show');
    });

    $('#form_edit').on('submit', function(event){
		event.preventDefault();
		$.ajax({
			url: '/master/kasir/edit',
            cache:false,
			method:"POST",
			data:$(this).serialize(),
			dataType:"json",
			success:function(data)
			{
                $('#tabel').DataTable().ajax.reload(function(){}, false);
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
                $('#myModal').modal('hide');
			}
		});
    });

	$(document).on('click', '.cetak', function(event){
        id = $(this).attr('id');
        ajax_print('/master/kasir/struk/tagihan/' + id);
        console.log('Printing . . .');
    });

    //Print Via Bluetooth atau USB
    function pc_print(data){
        var socket = new WebSocket("ws://127.0.0.1:40213/");
        socket.bufferType = "arraybuffer";
        socket.onerror = function(error) {  
            alert("Printer Not Ready");
        };			
        socket.onopen = function() {
            socket.send(data);
            socket.close(1000, "Work complete");
        };
    }	

    function android_print(data){
        window.location.href = data;  
    }

    function ajax_print(url) {
        $.get(url, function (data) {
            var ua = navigator.userAgent.toLowerCase();
            var isAndroid = ua.indexOf("android") > -1; 
            if(isAndroid) {
                android_print(data);
            }else{
                pc_print(data);
            }
        }).fail(function (data) {
            console.log(data);
        });
    }
});
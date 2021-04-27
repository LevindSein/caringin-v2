$(document).ready(function(){
    if(getCookie('saran') != 1){
        swal({
            title: 'Info',
            text: "Silakan gunakan kotak saran dengan bijak. Terimakasih",
            type: 'info',
            buttonsStyling: false,
            confirmButtonClass: 'btn btn-info'
        });
    }
    
    setCookie('saran', 1, 1);

    $('#form_saran').on('submit', function(event){
		event.preventDefault();

        $(".form_result").html('');
        $("#kirim").prop('disabled',true);

		$.ajax({
			url: "/saran",
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
                    $('.form_result').html(html);
				}
				if(data.success)
				{
                    swal({
                        title: 'Success',
                        text: data.success + '. Apabila saran anda membutuhkan konfirmasi, maka kami akan mengirimkan pesan kepada anda. Terimakasih',
                        type: 'success',
                        buttonsStyling: false,
                        confirmButtonClass: 'btn btn-success'
                    });
                    $('#form_saran')[0].reset();
                }
                $("#success-alert,#error-alert,#info-alert,#warning-alert")
                    .fadeTo(1000, 500)
                    .slideUp(1000, function () {
                        $("#success-alert,#error-alert").slideUp(500);
                });
			}
		});
        setTimeout(function() {
            $("#kirim").prop('disabled',false);
        }, 1000);
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

    function setCookie(name,value,days) {
        var expires = "";
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days*24*60*60*1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "")  + expires + "; path=/";
    }
    function getCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for(var i=0;i < ca.length;i++) {
            var c = ca[i];
            while (c.charAt(0)==' ') c = c.substring(1,c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
        }
        return null;
    }
    function eraseCookie(name) {   
        document.cookie = name +'=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
    }
});
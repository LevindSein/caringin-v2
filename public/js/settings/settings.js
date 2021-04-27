$(document).ready(function(){
    $('#form_settings').on('submit', function(event){
		event.preventDefault();
        if($("#password").val() === $("#konfirmasi_password").val()){
            $.ajax({
                url: "/settings",
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
                            text: data.success,
                            type: 'success',
                            buttonsStyling: false,
                            confirmButtonClass: 'btn btn-success'
                        });
                        $('#form_settings')[0].reset();
                    }
                    $("#success-alert,#error-alert,#info-alert,#warning-alert")
                        .fadeTo(1000, 500)
                        .slideUp(1000, function () {
                            $("#success-alert,#error-alert").slideUp(500);
                    });

                    setTimeout( function(){
                        location.reload();
                    }, 2000);
                }
            });
        }
        else{
            $.notify({
                icon: 'fas fa-bell',
                title: 'Password',
                message: 'Password tidak cocok dengan Konfirmasi Password',
            },{
                type: "danger",
                animate: { enter: "animated fadeInRight", exit: "animated fadeOutRight" },
            })
        }
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
    
    $("#username").on("change paste keyup", function(e){
        $(e.target).val($(e.target).val().replace(/[^a-zA-Z0-9_]/gi,''));
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
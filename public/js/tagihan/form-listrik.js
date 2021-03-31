$(document).ready(function(){
    document
        .getElementById('akhir')
        .addEventListener(
            'input',
            event => event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US')
        );
    
    document
        .getElementById('awal')
        .addEventListener(
            'input',
            event => event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US')
        );
    document
        .getElementById('daya')
        .addEventListener(
            'input',
            event => event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US')
        );

    var input = $("#akhir");
    var len = input.val().length;
    input[0].focus();
    input[0].setSelectionRange(len, len);

    var awal = $('#awal').val();
    awal = awal.split(',');
    awal = awal.join('');
    awal = parseInt(awal); 

    var akhir = $('#akhir').val();
    akhir = akhir.split(',');
    akhir = akhir.join('');
    akhir = parseInt(akhir);
    
    $("#tambah").prop("disabled", false);

    $("#akhir,#awal").on("change paste keyup", function() {
        var akhir = $('#akhir').val();
        akhir = akhir.split(',');
        akhir = akhir.join('');
        akhir = parseInt(akhir);

        var awal = $('#awal').val();
        awal = awal.split(',');
        awal = awal.join('');
        awal = parseInt(awal); 
        
        $("#tambah").prop("disabled", false);
    });

    var daya = $('#daya').val();
    daya = daya.split(',');
    daya = daya.join('');
    daya = parseInt(daya);

    var awal = $('#awal').val();
    awal = awal.split(',');
    awal = awal.join('');
    awal = parseInt(awal); 

    var akhir = $('#akhir').val();
    akhir = akhir.split(',');
    akhir = akhir.join('');
    akhir = parseInt(akhir);
    
    if(daya > 0){
        $("#tambah").prop("disabled", false);
    }
    else{
        $("#tambah").prop("disabled", true);
    }
    
    $("#reset").prop('checked', false);

    $("#daya,#awal,#akhir").on("change paste keyup", function() {
        var daya = $('#daya').val();
        daya = daya.split(',');
        daya = daya.join('');
        daya = parseInt(daya);

        var awal = $('#awal').val();
        awal = awal.split(',');
        awal = awal.join('');
        awal = parseInt(awal); 
    
        var akhir = $('#akhir').val();
        akhir = akhir.split(',');
        akhir = akhir.join('');
        akhir = parseInt(akhir);

        if(daya > 0 && akhir >= awal){
            $("#tambah").prop("disabled", false);
        }
        else{
            if ($("#reset").prop('checked')==true)
                $("#tambah").prop("disabled", false);
            else
                $("#tambah").prop("disabled", true);
        }
    });

    $("#reset").change(function() {
        var daya = $('#daya').val();
        daya = daya.split(',');
        daya = daya.join('');
        daya = parseInt(daya);

        var awal = $('#awal').val();
        awal = awal.split(',');
        awal = awal.join('');
        awal = parseInt(awal); 
    
        var akhir = $('#akhir').val();
        akhir = akhir.split(',');
        akhir = akhir.join('');
        akhir = parseInt(akhir);
        
        if(this.checked) {
            if(daya > 0){
                $("#tambah").prop("disabled", false);
            }
            else{
                $("#tambah").prop("disabled", true);
            }
        }
        else{
            if(daya > 0 && akhir >= awal){
                $("#tambah").prop("disabled", false);
            }
            else{
                $("#tambah").prop("disabled", true);
            }
        }
    });
});
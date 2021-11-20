//Show Tagihan
$(document).ready(function () {
    var scrollPosition;
    var rowIndex;
    var dtable = $('#tabelKasir').DataTable({
		serverSide: true,
		ajax: {
            url: "/kasir",
            cache:false,
		},
		columns: [
			{ data: 'kd_kontrol', name: 'kd_kontrol', class : 'text-center' },
			{ data: 'tagihan', name: 'tagihan', class : 'text-center' },
			{ data: 'pengguna', name: 'pengguna', class : 'text-center-td' },
			{ data: 'lokasi', name: 'lokasi', class : 'text-center-td' },
			{ data: 'action', name: 'action', class : 'text-center' }
        ],
        pageLength: 5,
        stateSave: true,
        lengthMenu: [[5,10,25,50,100,-1], [5,10,25,50,100,"All"]],
        deferRender: true,
        language: {
            paginate: {
                previous: "<i class='fas fa-angle-left'>",
                next: "<i class='fas fa-angle-right'>"
            }
        },
        aoColumnDefs: [
            { "bSortable": false, "aTargets": [1,2,3,4] },
            { "bSearchable": false, "aTargets": [1,2,3,4] }
        ],
        order:[[0, 'asc']],
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
    }).columns.adjust().draw();

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
        if (!$.fn.dataTable.isDataTable('#tabelRestore')) {
            var dtable = $('#tabelRestore').DataTable({
                serverSide: true,
                ajax: {
                    url: "/kasir/restore",
                    cache:false,
                },
                columns: [
                    { data: 'kd_kontrol', name: 'kd_kontrol', class : 'text-center' },
                    { data: 'tagihan', name: 'tagihan', class : 'text-center' },
                    { data: 'pengguna', name: 'pengguna', class : 'text-center-td' },
                    { data: 'lokasi', name: 'lokasi', class : 'text-center-td' },
                    { data: 'action', name: 'action', class : 'text-center' },
                ],
                pageLength: 5,
                stateSave: true,
                lengthMenu: [[5,10,25,50,100,-1], [5,10,25,50,100,"All"]],
                deferRender: true,
                language: {
                    paginate: {
                        previous: "<i class='fas fa-angle-left'>",
                        next: "<i class='fas fa-angle-right'>"
                    }
                },
                aoColumnDefs: [
                    { "bSortable": false, "aTargets": [1,4] },
                    { "bSearchable": false, "aTargets": [1,4] }
                ],
                order: [[0, 'asc']],
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
            }).columns.adjust().draw();

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

    $("#tab-c-2").click(function(){
        if (!$.fn.dataTable.isDataTable('#tabelStruk')) {
            var dtable = $('#tabelStruk').DataTable({
                serverSide: true,
                ajax: {
                    url: "/kasir/struk/tagihan",
                    cache:false,
                },
                columns: [
                    { data: 'kd_kontrol', name: 'kd_kontrol', class : 'text-center' },
                    { data: 'bayar', name: 'bayar', class : 'text-center' },
                    { data: 'totalTagihan', name: 'totalTagihan', class : 'text-center' },
                    { data: 'ref', name: 'ref', class : 'text-center' },
                    { data: 'kasir', name: 'kasir', class : 'text-center-td' },
                    { data: 'action', name: 'action', class : 'text-center' }
                ],
                pageLength: 5,
                stateSave: true,
                lengthMenu: [[5,10,25,50,100,-1], [5,10,25,50,100,"All"]],
                deferRender: true,
                language: {
                    paginate: {
                        previous: "<i class='fas fa-angle-left'>",
                        next: "<i class='fas fa-angle-right'>"
                    }
                },
                aoColumnDefs: [
                    { "bSortable": false, "aTargets": [2,5] },
                    { "bSearchable": false, "aTargets": [2,5] }
                ],
                order:[[0, 'asc']],
                "preDrawCallback": function( settings ) {
                    scrollPosition = $(".dataTables_scrollBody").scrollTop();
                },
                "drawCallback": function( settings ) {
                    $(".dataTables_scrollBody").scrollTop(scrollPosition);
                    if(typeof rowIndex != 'undefined') {
                        dtable.row(rowIndex).nodes().to$().addClass('row_selected');
                    }
                },
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
            }).columns.adjust().draw();


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

    //Cetak Struk
    $(document).on('click', '.cetak', function(event){
        id = $(this).attr('id');
        ajax_print('/kasir/struk/tagihan/' + id);
        console.log('Printing . . .');
    });

    //Restore
    var id = '';
    $(document).on('click', '.restore', function(){
		id = $(this).attr('id');
		username = $(this).attr('nama');
		$('.titles').text('Restore Pembayaran ' + username + ' ?');
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
			url: "/kasir/restore/"+id,
            cache:false,
			method:"POST",
			dataType:"json",
            beforeSend:function(){
                $("#ok_button").prop("disabled",true);
            },
			success:function(data)
			{
                $('#tabelRestore').DataTable().ajax.reload(function(){}, false);
                var html = '';
				if(data.errors)
				{
                    swal({
                        title: 'Oops!',
                        text: data.errors,
                        type: 'error',
                        buttonsStyling: false,
                        confirmButtonClass: 'btn btn-danger'
                    });
                    // html = '<div class="alert alert-danger" id="error-alert"> <strong>Maaf ! </strong>' + data.errors + '</div>';
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
					// html = '<div class="alert alert-success" id="success-alert"> <strong>Sukses ! </strong>' + data.success + '</div>';
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
                $("#ok_button").prop("disabled",false);
            }
		});
    });

    $(document).on('click', '.bayar', function(){
        kontrol = $(this).attr('id');
        nama = $(this).attr('nama');
        $('#form_result').html('');
        $('#form_rincian')[0].reset();
        var total = 0;
        checkListrik = 0;
        checkAirBersih = 0;
        checkKeamananIpk = 0;
        checkKebersihan = 0;
        checkAirKotor = 0;
        checkLain = 0;
        $.ajax({
			url :"/kasir/rincian/" + kontrol,
			dataType:"json",
            cache:false,
			success:function(data)
			{
                $("#tempatId").val(kontrol);
                $("#judulRincian").html(nama);

                $("#pedagang").val(data.hidden.pedagang);
                $("#los").val(data.hidden.los);
                $("#lokasi").val(data.hidden.lokasi);
                $("#faktur").val(data.hidden.faktur);
                $("#ref").val(data.hidden.ref);

                total = 0;

                //Listrik
                listrik = data.result.listrik;
                $("#nominalListrik").html(data.result.listrik.toLocaleString("en-US"));
                total = total + listrik;

                tunglistrik = data.result.tunglistrik;
                $("#tungnominalListrik").html(data.result.tunglistrik.toLocaleString("en-US"));
                total = total + tunglistrik;

                denlistrik = data.result.denlistrik;
                $("#dennominalListrik").html(data.result.denlistrik.toLocaleString("en-US"));
                total = total + denlistrik;

                if(listrik == 0)
                    $("#divListrik").hide();
                else
                    $("#divListrik").show();

                if(tunglistrik == 0)
                    $("#tungdivListrik").hide();
                else
                    $("#tungdivListrik").show();

                if(denlistrik == 0)
                    $("#dendivListrik").hide();
                else
                    $("#dendivListrik").show();

                if(listrik == 0 && tunglistrik == 0 && denlistrik == 0){
                    $("#fasListrik").hide();
                    $("#checkListrik").prop("checked", false).prop("disabled", true);
                    checkListrik = 0;
                }
                else{
                    $("#fasListrik").show();
                    $("#checkListrik").prop("checked", true).prop("disabled", false);
                    checkListrik = 1;
                }

                $("#taglistrik").val(data.hidden.listrik);
                $("#tagtunglistrik").val(data.hidden.tunglistrik);
                $("#tagdenlistrik").val(data.hidden.denlistrik);

                $("#tagdylistrik").val(data.hidden.dylistrik);
                $("#tagawlistrik").val(data.hidden.awlistrik);
                $("#tagaklistrik").val(data.hidden.aklistrik);
                $("#tagpklistrik").val(data.hidden.pklistrik);

                $('#checkListrik').click(function() {
                    if(!$(this).is(':checked')){
                        total = total - listrik - tunglistrik - denlistrik;
                        checkListrik = 0;
                        $("#fasListrik").hide();

                        $.ajax({
                            url :"/encrypt/value",
                            dataType:"json",
                            cache:false,
                            success:function(d)
                            {
                                $("#taglistrik").val(d.result);
                                $("#tagtunglistrik").val(d.result);
                                $("#tagdenlistrik").val(d.result);
                                $("#tagdylistrik").val(d.result);
                                $("#tagawlistrik").val(d.result);
                                $("#tagaklistrik").val(d.result);
                                $("#tagpklistrik").val(d.result);
                            }
                        });
                    }
                    else{
                        total = total + listrik + tunglistrik + denlistrik;
                        checkListrik = 1;
                        $("#fasListrik").show();

                        $("#taglistrik").val(data.hidden.listrik);
                        $("#tagtunglistrik").val(data.hidden.tunglistrik);
                        $("#tagdenlistrik").val(data.hidden.denlistrik);
                        $("#tagdylistrik").val(data.hidden.dylistrik);
                        $("#tagawlistrik").val(data.hidden.awlistrik);
                        $("#tagaklistrik").val(data.hidden.aklistrik);
                        $("#tagpklistrik").val(data.hidden.pklistrik);
                    }
                    $('#nominalTotal').html('Rp. ' + total.toLocaleString("en-US"));
                    // $("#totalTagihan").val(total);
                });

                //Air Bersih
                airbersih = data.result.airbersih;
                $("#nominalAirBersih").html(data.result.airbersih.toLocaleString("en-US"));
                total = total + airbersih;

                tungairbersih = data.result.tungairbersih;
                $("#tungnominalAirBersih").html(data.result.tungairbersih.toLocaleString("en-US"));
                total = total + tungairbersih;

                denairbersih = data.result.denairbersih;
                $("#dennominalAirBersih").html(data.result.denairbersih.toLocaleString("en-US"));
                total = total + denairbersih;

                if(airbersih == 0)
                    $("#divAirBersih").hide();
                else
                    $("#divAirBersih").show();
                if(tungairbersih == 0)
                    $("#tungdivAirBersih").hide();
                else
                    $("#tungdivAirBersih").show();
                if(denairbersih == 0)
                    $("#dendivAirBersih").hide();
                else
                    $("#dendivAirBersih").show();
                if(airbersih == 0 && tungairbersih == 0 && denairbersih == 0){
                    $("#fasAirBersih").hide();
                    $("#checkAirBersih").prop("checked", false).prop("disabled", true);
                    checkAirBersih = 0;
                }
                else{
                    $("#fasAirBersih").show();
                    $("#checkAirBersih").prop("checked", true).prop("disabled", false);
                    checkAirBersih = 1;
                }

                $("#tagairbersih").val(data.hidden.airbersih);
                $("#tagtungairbersih").val(data.hidden.tungairbersih);
                $("#tagdenairbersih").val(data.hidden.denairbersih);

                $("#tagawairbersih").val(data.hidden.awairbersih);
                $("#tagakairbersih").val(data.hidden.akairbersih);
                $("#tagpkairbersih").val(data.hidden.pkairbersih);

                $('#checkAirBersih').click(function() {
                    if(!$(this).is(':checked')){
                        checkAirBersih = 0;
                        total = total - airbersih - tungairbersih - denairbersih;
                        $("#fasAirBersih").hide();

                        $.ajax({
                            url :"/encrypt/value",
                            dataType:"json",
                            cache:false,
                            success:function(d)
                            {
                                $("#tagairbersih").val(d.result);
                                $("#tagtungairbersih").val(d.result);
                                $("#tagdenairbersih").val(d.result);
                                $("#tagawairbersih").val(d.result);
                                $("#tagakairbersih").val(d.result);
                                $("#tagpkairbersih").val(d.result);
                            }
                        });
                    }
                    else{
                        checkAirBersih = 1;
                        total = total + airbersih + tungairbersih + denairbersih;
                        $("#fasAirBersih").show();

                        $("#tagairbersih").val(data.hidden.airbersih);
                        $("#tagtungairbersih").val(data.hidden.tungairbersih);
                        $("#tagdenairbersih").val(data.hidden.denairbersih);

                        $("#tagawairbersih").val(data.hidden.awairbersih);
                        $("#tagakairbersih").val(data.hidden.akairbersih);
                        $("#tagpkairbersih").val(data.hidden.pkairbersih);
                    }
                    $('#nominalTotal').html('Rp. ' + total.toLocaleString("en-US"));
                    // $("#totalTagihan").val(total);
                });

                //Keamanan IPK
                keamananipk = data.result.keamananipk;
                $("#nominalKeamananIpk").html(data.result.keamananipk.toLocaleString("en-US"));
                total = total + keamananipk;

                tungkeamananipk = data.result.tungkeamananipk;
                $("#tungnominalKeamananIpk").html(data.result.tungkeamananipk.toLocaleString("en-US"));
                total = total + tungkeamananipk;

                if(keamananipk == 0)
                    $("#divKeamananIpk").hide();
                else
                    $("#divKeamananIpk").show();
                if(tungkeamananipk == 0)
                    $("#tungdivKeamananIpk").hide();
                else
                    $("#tungdivKeamananIpk").show();
                if(keamananipk == 0 && tungkeamananipk == 0){
                    $("#fasKeamananIpk").hide();
                    $("#checkKeamananIpk").prop("checked", false).prop("disabled", true);
                    checkKeamananIpk = 0;
                }
                else{
                    $("#fasKeamananIpk").show();
                    $("#checkKeamananIpk").prop("checked", true).prop("disabled", false);
                    checkKeamananIpk = 1;
                }

                $("#tagkeamananipk").val(data.hidden.keamananipk);
                $("#tagtungkeamananipk").val(data.hidden.tungkeamananipk);

                $('#checkKeamananIpk').click(function() {
                    if(!$(this).is(':checked')){
                        checkKeamananIpk = 0;
                        total = total - keamananipk - tungkeamananipk;
                        $("#fasKeamananIpk").hide();

                        $.ajax({
                            url :"/encrypt/value",
                            dataType:"json",
                            cache:false,
                            success:function(d)
                            {
                                $("#tagkeamananipk").val(d.result);
                                $("#tagtungkeamananipk").val(d.result);
                            }
                        });
                    }
                    else{
                        checkKeamananIpk = 1;
                        total = total + keamananipk + tungkeamananipk;
                        $("#fasKeamananIpk").show();

                        $("#tagkeamananipk").val(data.hidden.keamananipk);
                        $("#tagtungkeamananipk").val(data.hidden.tungkeamananipk);
                    }
                    $('#nominalTotal').html('Rp. ' + total.toLocaleString("en-US"));
                    // $("#totalTagihan").val(total);
                });

                //Kebersihan
                kebersihan = data.result.kebersihan;
                $("#nominalKebersihan").html(data.result.kebersihan.toLocaleString("en-US"));
                total = total + kebersihan;

                tungkebersihan = data.result.tungkebersihan;
                $("#tungnominalKebersihan").html(data.result.tungkebersihan.toLocaleString("en-US"));
                total = total + tungkebersihan;

                if(kebersihan == 0)
                    $("#divKebersihan").hide();
                else
                    $("#divKebersihan").show();
                if(tungkebersihan == 0)
                    $("#tungdivKebersihan").hide();
                else
                    $("#tungdivKebersihan").show();
                if(kebersihan == 0 && tungkebersihan == 0){
                    $("#fasKebersihan").hide();
                    $("#checkKebersihan").prop("checked", false).prop("disabled", true);
                    checkKebersihan = 0;
                }
                else{
                    $("#fasKebersihan").show();
                    $("#checkKebersihan").prop("checked", true).prop("disabled", false);
                    checkKebersihan = 1;
                }

                $("#tagkebersihan").val(data.hidden.kebersihan);
                $("#tagtungkebersihan").val(data.hidden.tungkebersihan);

                $('#checkKebersihan').click(function() {
                    if(!$(this).is(':checked')){
                        checkKebersihan = 0;
                        total = total - kebersihan - tungkebersihan;
                        $("#fasKebersihan").hide();

                        $.ajax({
                            url :"/encrypt/value",
                            dataType:"json",
                            cache:false,
                            success:function(d)
                            {
                                $("#tagkebersihan").val(d.result);
                                $("#tagtungkebersihan").val(d.result);
                            }
                        });
                    }
                    else{
                        checkKebersihan = 1;
                        total = total + kebersihan + tungkebersihan;
                        $("#fasKebersihan").show();

                        $("#tagkebersihan").val(data.hidden.kebersihan);
                        $("#tagtungkebersihan").val(data.hidden.tungkebersihan);
                    }
                    $('#nominalTotal').html('Rp. ' + total.toLocaleString("en-US"));
                    // $("#totalTagihan").val(total);
                });

                //Air Kotor
                airkotor = data.result.airkotor;
                $("#nominalAirKotor").html(data.result.airkotor.toLocaleString("en-US"));
                total = total + airkotor;

                tungairkotor = data.result.tungairkotor;
                $("#tungnominalAirKotor").html(data.result.tungairkotor.toLocaleString("en-US"));
                total = total + tungairkotor;

                if(airkotor == 0)
                    $("#divAirKotor").hide();
                else
                    $("#divAirKotor").show();
                if(tungairkotor == 0)
                    $("#tungdivAirKotor").hide();
                else
                    $("#tungdivAirKotor").show();
                if(airkotor == 0 && tungairkotor == 0){
                    $("#fasAirKotor").hide();
                    $("#checkAirKotor").prop("checked", false).prop("disabled", true);
                    checkAirKotor = 0;
                }
                else{
                    $("#fasAirKotor").show();
                    $("#checkAirKotor").prop("checked", true).prop("disabled", false);
                    checkAirKotor = 1;
                }

                $("#tagairkotor").val(data.hidden.airkotor);
                $("#tagtungairkotor").val(data.hidden.tungairkotor);

                $('#checkAirKotor').click(function() {
                    if(!$(this).is(':checked')){
                        checkAirKotor = 0;
                        total = total - airkotor - tungairkotor;
                        $("#fasAirKotor").hide();

                        $.ajax({
                            url :"/encrypt/value",
                            dataType:"json",
                            cache:false,
                            success:function(d)
                            {
                                $("#tagairkotor").val(d.result);
                                $("#tagtungairkotor").val(d.result);
                            }
                        });
                    }
                    else{
                        checkAirKotor = 1;
                        total = total + airkotor + tungairkotor;
                        $("#fasAirKotor").show();

                        $("#tagairkotor").val(data.hidden.airkotor);
                        $("#tagtungairkotor").val(data.hidden.tungairkotor);
                    }
                    $('#nominalTotal').html('Rp. ' + total.toLocaleString("en-US"));
                    // $("#totalTagihan").val(total);
                });

                //Lain
                lain = data.result.lain;
                $("#nominalLain").html(data.result.lain.toLocaleString("en-US"));
                total = total + lain;

                tunglain = data.result.tunglain;
                $("#tungnominalLain").html(data.result.tunglain.toLocaleString("en-US"));
                total = total + tunglain;

                if(lain == 0)
                    $("#divLain").hide();
                else
                    $("#divLain").show();
                if(tunglain == 0)
                    $("#tungdivLain").hide();
                else
                    $("#tungdivLain").show();
                if(lain == 0 && tunglain == 0){
                    $("#fasLain").hide();
                    $("#checkLain").prop("checked", false).prop("disabled", true);
                    checkLain = 0;
                }
                else{
                    $("#fasLain").show();
                    $("#checkLain").prop("checked", true).prop("disabled", false);
                    checkLain = 1;
                }

                $("#taglain").val(data.hidden.lain);
                $("#tagtunglain").val(data.hidden.tunglain);

                $('#checkLain').click(function() {
                    if(!$(this).is(':checked')){
                        checkLain = 0;
                        total = total - lain - tunglain;
                        $("#fasLain").hide();

                        $.ajax({
                            url :"/encrypt/value",
                            dataType:"json",
                            cache:false,
                            success:function(d)
                            {
                                $("#taglain").val(d.result);
                                $("#tagtunglain").val(d.result);
                            }
                        });
                    }
                    else{
                        checkLain = 1;
                        total = total + lain + tunglain;
                        $("#fasLain").show();

                        $("#taglain").val(data.hidden.lain);
                        $("#tagtunglain").val(data.hidden.tunglain);
                    }
                    $('#nominalTotal').html('Rp. ' + total.toLocaleString("en-US"));
                    // $("#totalTagihan").val(total);
                });

                //Total
                $("#nominalTotal").html("Rp. " + total.toLocaleString("en-US"));

                // $("#totalTagihan").val(total);

                if(checkListrik == 0 && checkAirBersih == 0 && checkKeamananIpk == 0 && checkKebersihan == 0 && checkAirKotor == 0 && checkLain == 0)
                    $("#printStruk").prop("disabled",true);
                else
                    $("#printStruk").prop("disabled",false);

                $('#checkListrik, #checkAirBersih, #checkKeamananIpk, #checkKebersihan, #checkAirKotor, #checkLain').on("click", function() {
                    if(checkListrik == 0 && checkAirBersih == 0 && checkKeamananIpk == 0 && checkKebersihan == 0 && checkAirKotor == 0 && checkLain == 0){
                        $("#printStruk").prop("disabled",true);
                        $.notify({
                            icon: 'fas fa-exclamation',
                            title: 'Transaksi Gagal',
                            message: 'Tidak Dapat Melakukan Transaksi Rp.0',
                        },{
                            type: "danger",
                            animate: { enter: "animated fadeInRight", exit: "animated fadeOutRight" },
                        })
                    }
                    else{
                        $("#printStruk").prop("disabled",false);
                    }
                });

                $('#myRincian').modal('show');
			}
        })
        kode_kontrol = kontrol;
    });

    $('#form_rincian').on('submit', function(event){
		event.preventDefault();
		$.ajax({
			url: '/kasir',
            cache:false,
			method:"POST",
			data:$(this).serialize(),
			dataType:"json",
            beforeSend:function(){
                $("#printStruk").prop("disabled",true);
            },
			success:function(data)
			{
				if(data.result.status == 'error')
				{
                    swal({
                        title: 'Oops!',
                        text: 'Transaksi Gagal',
                        type: 'error',
                        buttonsStyling: false,
                        confirmButtonClass: 'btn btn-danger'
                    });
                    // html = '<div class="alert alert-danger" id="error-alert"> <strong>Oops ! </strong> Transaksi Gagal</div>';
				}
				if(data.result.status == 'success')
				{
                    swal({
                        title: 'Success',
                        text: 'Transaksi Sukses',
                        type: 'success',
                        buttonsStyling: false,
                        confirmButtonClass: 'btn btn-success'
                    });
                    // html = '<div class="alert alert-success" id="success-alert"> <strong>Sukses ! </strong>Transaksi Berhasil</div>';
                    if(data.result.totalTagihan){
                        // kasir_print('/kasir/bayar/' + JSON.stringify(data.hidden));
                        kasir_print('/kasir/bayar/' + data.hidden);
                    }
				}

                $('#tabelKasir').DataTable().ajax.reload(function(){}, false);
                // $('#form_result').html(html);
                $("#success-alert,#error-alert,#info-alert,#warning-alert")
                    .fadeTo(1000, 500)
                    .slideUp(1000, function () {
                        $("#success-alert,#error-alert").slideUp(500);
                });
            },
            complete:function(){
                $('#myRincian').modal('hide');
                $("#printStruk").prop("disabled",false);
            }
		});
    });

    $("#sebagian").prop('required',false);

    $('#sebagian').select2({
        placeholder: '--- Pilih Blok ---',
        ajax: {
            url: "/cari/blok",
            dataType: 'json',
            delay: 250,
            processResults: function (blok) {
                return {
                results:  $.map(blok, function (bl) {
                    return {
                    text: bl.nama,
                    id: bl.nama
                    }
                })
                };
            },
            cache: true
        }
    });

    $("#sisatagihan").change(function() {
        var val = $(this).val();
        if(val === "all") {
            $("#divrekapsisa").hide();
            $("#sebagian").prop('required',false);
            $('#sebagian').val('');
            $('#sebagian').html('');
        }
        else if(val === "sebagian") {
            $("#divrekapsisa").show();
            $("#sebagian").prop('required',true);
        }
    });

    //Print Via Bluetooth atau USB
    function pc_print(data){
        var socket = new WebSocket("ws://127.0.0.1:40213/");
        socket.bufferType = "arraybuffer";
        socket.onerror = function(error) {
            console.log("Transaksi Berhasil Tanpa Print Struk");
            swal({
                title: 'Info',
                text: 'Printer Belum Siap, Transaksi Berhasil',
                type: 'info',
                buttonsStyling: false,
                confirmButtonClass: 'btn btn-info'
            });
        };
        socket.onopen = function() {
            socket.send(data);
            socket.close(1000, "Work complete");
            swal({
                title: 'Success',
                text: 'Cetak Berhasil',
                type: 'success',
                buttonsStyling: false,
                confirmButtonClass: 'btn btn-success'
            });
        };
    }

    function android_print(data){
        window.location.href = data;
        swal({
            title: 'Success',
            text: 'Cetak Berhasil',
            type: 'success',
            buttonsStyling: false,
            confirmButtonClass: 'btn btn-success'
        });
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

    //Print Via Bluetooth atau USB
    function pcc_print(data){
        var socket = new WebSocket("ws://127.0.0.1:40213/");
        socket.bufferType = "arraybuffer";
        socket.onerror = function(error) {
            console.log("Transaksi Berhasil Tanpa Print Struk");
            swal({
                title: 'Info',
                text: 'Printer Belum Siap, Transaksi Berhasil',
                type: 'info',
                buttonsStyling: false,
                confirmButtonClass: 'btn btn-info'
            });
        };
        socket.onopen = function() {
            socket.send(data);
            socket.close(1000, "Work complete");
        };
    }

    function androidd_print(data){
        window.location.href = data;
    }

    function kasir_print(url) {
        $.get(url, function (data) {
            var ua = navigator.userAgent.toLowerCase();
            var isAndroid = ua.indexOf("android") > -1;
            if(isAndroid) {
                androidd_print(data);
            }else{
                pcc_print(data);
            }
        }).fail(function (data) {
            console.log(data);
        });
    }

    $.ajax({
        type: 'GET',
        url: '/work',
        dataType: "json",
        success: function(data) {
            if(data.result == 1){
                $('#workasir').html('SHIFT 1').addClass('btn-success').removeClass('btn-danger');
            }
            else{
                $('#workasir').html('SHIFT 2').addClass('btn-danger').removeClass('btn-success');
            }
        }

    });

    $(document).on('click', '#workasir', function(){
        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'POST',
            url: '/work',
            dataType: "json",
            success: function(data) {
                if(data.result == 1){
                    $('#workasir').html('SHIFT 1').addClass('btn-success').removeClass('btn-danger');
                }
                else{
                    $('#workasir').html('SHIFT 2').addClass('btn-danger').removeClass('btn-success');
                }
            }
        });
    });

    $(document).on('change', '#printer', function() {
        printer = $(this).val();
        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
			url :"/kasir/printer/" + printer,
            cache:false,
			method:"POST",
			dataType:"json",
			success:function(data)
			{
                if(data.errors){
                    $.notify({
                        icon: 'fas fa-exclamation',
                        title: 'Kegagalan Printer',
                        message: 'Gagal mengganti printer',
                    },{
                        type: "danger",
                        animate: { enter: "animated fadeInRight", exit: "animated fadeOutRight" },
                    })
                }
                if(data.success){
                    $.notify({
                        icon: 'fas fa-check',
                        title: 'Printer Changed',
                        message: data.success,
                    },{
                        type: "success",
                        animate: { enter: "animated fadeInRight", exit: "animated fadeOutRight" },
                    })
                }
            },
            error:function(data){
                console.log(data);
            }
        });
    });
});

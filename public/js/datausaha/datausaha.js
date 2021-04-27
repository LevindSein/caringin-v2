$(document).ready(function(){
    var dtable = $('#tabelTagihan').DataTable({
		serverSide: true,
		ajax: {
			url: "/datausaha",
            cache:false,
		},
		columns: [
			{ data: 'bln_tagihan', name: 'bln_tagihan', class : 'text-center' },
			{ data: 'ttl_tagihan', name: 'ttl_tagihan', class : 'text-center' },
			{ data: 'show', name: 'show', class : 'text-center' },
        ],
        stateSave: true,
        deferRender: true,
        language: {
            paginate: {
                previous: "<i class='fas fa-angle-left'>",
                next: "<i class='fas fa-angle-right'>"
            }
        },
        aoColumnDefs: [
            { "bSortable": false, "aTargets": [1,2] }, 
            { "bSearchable": false, "aTargets": [1,2] }
        ],
        aLengthMenu: [[5,10,25,50,100], [5,10,25,50,100]],
        pageLength: 10,
        scrollX: true,
        scrollY: "50vh",
        order: [[0,'desc']],
        "preDrawCallback": function( settings ) {
            scrollPosition = $(".dataTables_scrollBody").scrollTop();
        },
        "drawCallback": function( settings ) {
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
        if (!$.fn.dataTable.isDataTable('#tabelTunggakan')) {
            var dtable = $('#tabelTunggakan').DataTable({
                serverSide: true,
                ajax: {
                    url: "/datausaha/tunggakan",
                    cache:false,
                },
                columns: [
                    { data: 'bln_tagihan', name: 'bln_tagihan', class : 'text-center' },
                    { data: 'sel_tagihan', name: 'sel_tagihan', class : 'text-center' },
                    { data: 'show', name: 'show', class : 'text-center' },
                ],
                stateSave: true,
                deferRender: true,
                language: {
                    paginate: {
                        previous: "<i class='fas fa-angle-left'>",
                        next: "<i class='fas fa-angle-right'>"
                    }
                },
                aoColumnDefs: [
                    { "bSortable": false, "aTargets": [1,2] }, 
                    { "bSearchable": false, "aTargets": [1,2] }
                ],
                aLengthMenu: [[5,10,25,50,100], [5,10,25,50,100]],
                pageLength: 10,
                scrollX: true,
                scrollY: "50vh",
                order: [[0,'desc']],
                "preDrawCallback": function( settings ) {
                    scrollPosition = $(".dataTables_scrollBody").scrollTop();
                },
                "drawCallback": function( settings ) {
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

    $("#tab-c-2").click(function(){
        if (!$.fn.dataTable.isDataTable('#tabelPendapatan')) {
            var dtable = $('#tabelPendapatan').DataTable({
                serverSide: true,
                ajax: {
                    url: "/datausaha/pendapatan",
                    cache:false,
                },
                columns: [
                    { data: 'bln_tagihan', name: 'bln_tagihan', class : 'text-center' },
                    { data: 'rea_tagihan', name: 'rea_tagihan', class : 'text-center' },
                    { data: 'show', name: 'show', class : 'text-center' },
                ],
                stateSave: true,
                deferRender: true,
                language: {
                    paginate: {
                        previous: "<i class='fas fa-angle-left'>",
                        next: "<i class='fas fa-angle-right'>"
                    }
                },
                aoColumnDefs: [
                    { "bSortable": false, "aTargets": [1,2] }, 
                    { "bSearchable": false, "aTargets": [1,2] }
                ],
                aLengthMenu: [[5,10,25,50,100], [5,10,25,50,100]],
                pageLength: 10,
                scrollX: true,
                scrollY: "50vh",
                order: [[0,'desc']],
                "preDrawCallback": function( settings ) {
                    scrollPosition = $(".dataTables_scrollBody").scrollTop();
                },
                "drawCallback": function( settings ) {
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

    $(document).on('click', '.details', function(){
        $(".fasilitas").text("DETAILS");
        $(".periode").text($(this).attr("nama"));
        id = $(this).attr('id');
        $.ajax({
			url :"/datausaha/show/details/" + id,
            cache:false,
			dataType:"json",
			success:function(data)
			{
                $(".diskon-listrik").html("<span style='color:red;'>" + data.result.diskon_listrik + "</span");
                $(".tagihan-listrik").html("<span style='color:green;'>" + data.result.tagihan_listrik + "</span");
                $(".denda-listrik").html("<span style='color:blue;'>" + data.result.denda_listrik + "</span");
                $(".realisasi-listrik").html("<span style='color:green;'>" + data.result.realisasi_listrik + "</span");
                $(".selisih-listrik").html("<span style='color:green;'>" + data.result.selisih_listrik + "</span");

                $(".diskon-airbersih").html("<span style='color:red;'>" + data.result.diskon_airbersih + "</span");
                $(".tagihan-airbersih").html("<span style='color:green;'>" + data.result.tagihan_airbersih + "</span");
                $(".denda-airbersih").html("<span style='color:blue;'>" + data.result.denda_airbersih + "</span");
                $(".realisasi-airbersih").html("<span style='color:green;'>" + data.result.realisasi_airbersih + "</span");
                $(".selisih-airbersih").html("<span style='color:green;'>" + data.result.selisih_airbersih + "</span");

                $(".diskon-keamananipk").html("<span style='color:red;'>" + data.result.diskon_keamananipk + "</span");
                $(".tagihan-keamananipk").html("<span style='color:green;'>" + data.result.tagihan_keamananipk + "</span");
                $(".realisasi-keamananipk").html("<span style='color:green;'>" + data.result.realisasi_keamananipk + "</span");
                $(".selisih-keamananipk").html("<span style='color:green;'>" + data.result.selisih_keamananipk + "</span");
                
                $(".diskon-kebersihan").html("<span style='color:red;'>" + data.result.diskon_kebersihan + "</span");
                $(".tagihan-kebersihan").html("<span style='color:green;'>" + data.result.tagihan_kebersihan + "</span");
                $(".realisasi-kebersihan").html("<span style='color:green;'>" + data.result.realisasi_kebersihan + "</span");
                $(".selisih-kebersihan").html("<span style='color:green;'>" + data.result.selisih_kebersihan + "</span");
                
                $(".tagihan-airkotor").html("<span style='color:green;'>" + data.result.tagihan_airkotor + "</span");
                $(".realisasi-airkotor").html("<span style='color:green;'>" + data.result.realisasi_airkotor + "</span");
                $(".selisih-airkotor").html("<span style='color:green;'>" + data.result.selisih_airkotor + "</span");
                
                $(".tagihan-lain").html("<span style='color:green;'>" + data.result.tagihan_lain + "</span");
                $(".realisasi-lain").html("<span style='color:green;'>" + data.result.realisasi_lain + "</span");
                $(".selisih-lain").html("<span style='color:green;'>" + data.result.selisih_lain + "</span");
                
                $(".diskon-total").html("<span style='color:red;'>" + data.result.diskon_total + "</span");
                $(".tagihan-total").html("<span style='color:green;'>" + data.result.tagihan_total + "</span");
                $(".denda-total").html("<span style='color:blue;'>" + data.result.denda_total + "</span");
                $(".realisasi-total").html("<span style='color:green;'>" + data.result.realisasi_total + "</span");
                $(".selisih-total").html("<span style='color:green;'>" + data.result.selisih_total + "</span");

                $("#show-details").modal("show");
            }
        })
    });
});
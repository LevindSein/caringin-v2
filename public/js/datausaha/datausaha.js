$(document).ready(function(){
    var dtable = $('#tabelTagihan').DataTable({
		serverSide: true,
		ajax: {
			url: "/datausaha",
            cache:false,
		},
		columns: [
			{ data: 'bln_tagihan', name: 'bln_tagihan', class : 'text-center' },
			{ data: 'ttl_listrik', name: 'ttl_listrik', class : 'text-center' },
			{ data: 'ttl_airbersih', name: 'ttl_airbersih', class : 'text-center' },
			{ data: 'ttl_keamananipk', name: 'ttl_keamananipk', class : 'text-center' },
			{ data: 'ttl_kebersihan', name: 'ttl_kebersihan', class : 'text-center' },
			{ data: 'ttl_airkotor', name: 'ttl_airkotor', class : 'text-center' },
			{ data: 'ttl_lain', name: 'ttl_lain', class : 'text-center' },
			{ data: 'ttl_tagihan', name: 'ttl_tagihan', class : 'text-center' },
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
            { "bSortable": false, "aTargets": [1,2,3,4,5,6,7] }, 
            { "bSearchable": false, "aTargets": [1,2,3,4,5,6,7] }
        ],
        aLengthMenu: [[5,10,25,50,100,-1], [5,10,25,50,100,"All"]],
        pageLength: 5,
        scrollX: true,
        scrollY: "50vh",
        fixedColumns: {
            "rightColumns": 1,
            "leftColumns": 1,
        },
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
                    { data: 'sel_listrik', name: 'sel_listrik', class : 'text-center' },
                    { data: 'sel_airbersih', name: 'sel_airbersih', class : 'text-center' },
                    { data: 'sel_keamananipk', name: 'sel_keamananipk', class : 'text-center' },
                    { data: 'sel_kebersihan', name: 'sel_kebersihan', class : 'text-center' },
                    { data: 'sel_airkotor', name: 'sel_airkotor', class : 'text-center' },
                    { data: 'sel_lain', name: 'sel_lain', class : 'text-center' },
                    { data: 'sel_tagihan', name: 'sel_tagihan', class : 'text-center' },
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
                    { "bSortable": false, "aTargets": [1,2,3,4,5,6,7] }, 
                    { "bSearchable": false, "aTargets": [1,2,3,4,5,6,7] }
                ],
                aLengthMenu: [[5,10,25,50,100,-1], [5,10,25,50,100,"All"]],
                pageLength: 5,
                scrollX: true,
                scrollY: "50vh",
                fixedColumns: {
                    "rightColumns": 1,
                    "leftColumns": 1,
                },
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
});
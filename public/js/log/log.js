$(document).ready(function(){
	var dtable = $('#tabelLog').DataTable({
		serverSide: true,
		ajax: {
			url: "/log",
            cache:false,
		},
		columns: [
			{ data: 'username', name: 'username', class : 'text-center-td' },
			{ data: 'nama', name: 'nama', class : 'text-center-td' },
			{ data: 'ktp', name: 'ktp', class : 'text-center-td' },
			{ data: 'role', name: 'role', class : 'text-center' },
			{ data: 'platform', name: 'platform', class : 'text-center-td' },
			{ data: { '_': 'created_at.display', 'sort': 'created_at.timestamp' }, name: 'created_at', class : 'text-center-td'  }
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
});
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
			{ name: 'created_at', data: { '_': 'created_at.display', 'sort': 'created_at.timestamp' }, class : 'text-center-td'  }
        ],
        stateSave: true,
        language: {
            paginate: {
                previous: "<i class='fas fa-angle-left'>",
                next: "<i class='fas fa-angle-right'>"
            }
        },
        deferRender: true,
        pageLength: 8,
        responsive: true
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
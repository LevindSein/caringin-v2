$(document).ready(function(){
    var dtable = $('#harian').DataTable({
		serverSide: true,
		ajax: {
			url: "/rekap/pendapatan",
            cache:false,
		},
		columns: [
            { data: 'tgl_bayar', name: 'tgl_bayar', class : 'text-center' },
            { data: 'kd_kontrol', name: 'kd_kontrol', class : 'text-center' },
            { data: 'pengguna', name: 'pengguna', class : 'text-center-td' },
            { data: 'ttl_tagihan', name: 'ttl_tagihan', class : 'text-center' },
            { data: 'realisasi', name: 'realisasi', class : 'text-center' },
            { data: 'sel_tagihan', name: 'sel_tagihan', class : 'text-center' },
            { data: 'show', name: 'show', class : 'text-center' },
        ],
        stateSave: true,
        deferRender: true,
        pageLength: 8,
        language: {
            paginate: {
                previous: "<i class='fas fa-angle-left'>",
                next: "<i class='fas fa-angle-right'>"
            }
        },
        aoColumnDefs: [
            { "bSortable": false, "aTargets": [6] }, 
            { "bSearchable": false, "aTargets": [6] }
        ],
        scrollY: "50vh",
        responsive:true
    });

    $("#tab-c-1").click(function(){
        if (!$.fn.dataTable.isDataTable('#bulanan')) {
            var dtable = $('#bulanan').DataTable({
                serverSide: true,
                ajax: {
                    url: "/rekap/pendapatan/bulanan",
                    cache:false,
                },
                columns: [
                    { data: 'bln_bayar', name: 'bln_bayar', class : 'text-center' },
                    { data: 'realisasi', name: 'realisasi', class : 'text-center' },
                    { data: 'show', name: 'show', class : 'text-center' },
                ],
                stateSave: true,
                deferRender: true,
                pageLength: 8,
                language: {
                    paginate: {
                        previous: "<i class='fas fa-angle-left'>",
                        next: "<i class='fas fa-angle-right'>"
                    }
                },
                aoColumnDefs: [
                    { "bSortable": false, "aTargets": [2] }, 
                    { "bSearchable": false, "aTargets": [2] }
                ],
                scrollY: "50vh",
                responsive:true
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
        if (!$.fn.dataTable.isDataTable('#tahunan')) {
            var dtable = $('#tahunan').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "/rekap/pendapatan/tahunan",
                    cache:false,
                },
                columns: [
                    { data: 'thn_bayar', name: 'thn_bayar', class : 'text-center' },
                    { data: 'realisasi', name: 'realisasi', class : 'text-center' },
                    { data: 'show', name: 'show', class : 'text-center' },
                ],
                stateSave: true,
                deferRender: true,
                pageLength: 8,
                language: {
                    paginate: {
                        previous: "<i class='fas fa-angle-left'>",
                        next: "<i class='fas fa-angle-right'>"
                    }
                },
                aoColumnDefs: [
                    { "bSortable": false, "aTargets": [2] }, 
                    { "bSearchable": false, "aTargets": [2] }
                ],
                scrollY: "50vh",
                responsive:true
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
    
    $(document).on('click', '.harian', function(){
		id = $(this).attr('id');
		nama = $(this).attr('nama');
		bayar = $(this).attr('bayar');
		$('.titles').html("<h1 style='color:white;font-weight:700;'>" + nama + "</h1>");
		$('.bayars').html("<h3 style='color:white;font-weight:500;'>" + bayar + "</h3>");
        $.ajax({
			url :"/rekap/pendapatan/show/harian/" + id,
            cache:false,
			dataType:"json",
			success:function(data)
			{
                if(data.result.pengguna !== null)
                    $('.pengguna').text(data.result.pengguna);
                else
                    $('.pengguna').html("&mdash;");

                if(data.result.kasir !== null)
                    $('.kasir').text(data.result.nama);
                else
                    $('.kasir').html("&mdash;");
                    
                if(data.result.ttl_tagihan !== null)
                    $('.tagihan').text(data.result.ttl_tagihan.toLocaleString('en-US'));
                else
                    $('.tagihan').html("&mdash;");

                if(data.result.realisasi !== null)
                    $('.realisasi').text(data.result.realisasi.toLocaleString('en-US'));
                else
                    $('.realisasi').html("&mdash;");
                    
                if(data.result.sel_tagihan !== null)
                    $('.selisih').text(data.result.sel_tagihan.toLocaleString('en-US'));
                else
                    $('.selisih').html("&mdash;");
                    
                if(data.result.diskon !== null)
                    $('.diskon').text(data.result.diskon.toLocaleString('en-US'));
                else
                    $('.diskon').html("&mdash;");

                if(data.result.byr_listrik !== null){
                    $('.bayar-listrik').text((data.result.byr_listrik - data.result.byr_denlistrik).toLocaleString('en-US'));
                    $('.denda-listrik').text(data.result.byr_denlistrik.toLocaleString('en-US'));
                    $('.diskon-listrik').text(data.result.dis_listrik.toLocaleString('en-US'));
                }
                else{
                    $('.bayar-listrik').html("&mdash;");
                    $('.denda-listrik').html("&mdash;");
                    $('.diskon-listrik').html("&mdash;");
                }

                if(data.result.byr_airbersih !== null){
                    $('.bayar-airbersih').text((data.result.byr_airbersih - data.result.byr_denairbersih).toLocaleString('en-US'));
                    $('.denda-airbersih').text(data.result.byr_denairbersih.toLocaleString('en-US'));
                    $('.diskon-airbersih').text(data.result.dis_airbersih.toLocaleString('en-US'));
                }
                else{
                    $('.bayar-airbersih').html("&mdash;");
                    $('.denda-airbersih').html("&mdash;");
                    $('.diskon-airbersih').html("&mdash;");
                }
                
                if(data.result.byr_keamananipk !== null){
                    $('.bayar-keamananipk').text(data.result.byr_keamananipk.toLocaleString('en-US'));
                    $('.diskon-keamananipk').text(data.result.dis_keamananipk.toLocaleString('en-US'));
                }
                else{
                    $('.bayar-keamananipk').html("&mdash;");
                    $('.diskon-keamananipk').html("&mdash;");
                }
                
                if(data.result.byr_kebersihan !== null){
                    $('.bayar-kebersihan').text(data.result.byr_kebersihan.toLocaleString('en-US'));
                    $('.diskon-kebersihan').text(data.result.dis_kebersihan.toLocaleString('en-US'));
                }
                else{
                    $('.bayar-kebersihan').html("&mdash;");
                    $('.diskon-kebersihan').html("&mdash;");
                }
                
                if(data.result.byr_airkotor !== null)
                    $('.bayar-airkotor').text(data.result.byr_airkotor.toLocaleString('en-US'));
                else
                    $('.bayar-airkotor').html("&mdash;");

                if(data.result.byr_lain !== null)
                    $('.bayar-lain').text(data.result.byr_lain.toLocaleString('en-US'));
                else
                    $('.bayar-lain').html("&mdash;");
			}
		});
        
        $('#harian-details').modal('show');
	});

    $(document).on('click', '.bulanan', function(){
		id = $(this).attr('id');
        $.ajax({
			url :"/rekap/pendapatan/show/bulanan/" + id,
            cache:false,
			dataType:"json",
			success:function(data)
			{
                $('.titles').html("<h1 style='color:white;font-weight:700;'>" + data.result.bulan + "</h1>");
                if(data.result.realisasi !== null)
                    $('.realisasi').text(Number(data.result.realisasi).toLocaleString('en-US'));
                else
                    $('.realisasi').html("&mdash;");
                    
                if(data.result.diskon !== null)
                    $('.diskon').text(Number(data.result.diskon).toLocaleString('en-US'));
                else
                    $('.diskon').html("&mdash;");

                if(data.result.byr_listrik !== null){
                    $('.bayar-listrik').text((data.result.byr_listrik - data.result.byr_denlistrik).toLocaleString('en-US'));
                    $('.denda-listrik').text(Number(data.result.byr_denlistrik).toLocaleString('en-US'));
                    $('.diskon-listrik').text(Number(data.result.dis_listrik).toLocaleString('en-US'));
                }
                else{
                    $('.bayar-listrik').html("&mdash;");
                    $('.denda-listrik').html("&mdash;");
                    $('.diskon-listrik').html("&mdash;");
                }

                if(data.result.byr_airbersih !== null){
                    $('.bayar-airbersih').text((data.result.byr_airbersih - data.result.byr_denairbersih).toLocaleString('en-US'));
                    $('.denda-airbersih').text(Number(data.result.byr_denairbersih).toLocaleString('en-US'));
                    $('.diskon-airbersih').text(Number(data.result.dis_airbersih).toLocaleString('en-US'));
                }
                else{
                    $('.bayar-airbersih').html("&mdash;");
                    $('.denda-airbersih').html("&mdash;");
                    $('.diskon-airbersih').html("&mdash;");
                }
                
                if(data.result.byr_keamananipk !== null){
                    $('.bayar-keamananipk').text(Number(data.result.byr_keamananipk).toLocaleString('en-US'));
                    $('.diskon-keamananipk').text(Number(data.result.dis_keamananipk).toLocaleString('en-US'));
                }
                else{
                    $('.bayar-keamananipk').html("&mdash;");
                    $('.diskon-keamananipk').html("&mdash;");
                }
                
                if(data.result.byr_kebersihan !== null){
                    $('.bayar-kebersihan').text(Number(data.result.byr_kebersihan).toLocaleString('en-US'));
                    $('.diskon-kebersihan').text(Number(data.result.dis_kebersihan).toLocaleString('en-US'));
                }
                else{
                    $('.bayar-kebersihan').html("&mdash;");
                    $('.diskon-kebersihan').html("&mdash;");
                }
                
                if(data.result.byr_airkotor !== null)
                    $('.bayar-airkotor').text(Number(data.result.byr_airkotor).toLocaleString('en-US'));
                else
                    $('.bayar-airkotor').html("&mdash;");

                if(data.result.byr_lain !== null)
                    $('.bayar-lain').text(Number(data.result.byr_lain).toLocaleString('en-US'));
                else
                    $('.bayar-lain').html("&mdash;");
			}
		});
        
        $('#show-details').modal('show');
	});

    $(document).on('click', '.tahunan', function(){
		id = $(this).attr('id');
        $('.titles').html("<h1 style='color:white;font-weight:700;'>" + id + "</h1>");
        $.ajax({
			url :"/rekap/pendapatan/show/tahunan/" + id,
            cache:false,
			dataType:"json",
			success:function(data)
			{
                if(data.result.realisasi !== null)
                    $('.realisasi').text(Number(data.result.realisasi).toLocaleString('en-US'));
                else
                    $('.realisasi').html("&mdash;");
                    
                if(data.result.diskon !== null)
                    $('.diskon').text(Number(data.result.diskon).toLocaleString('en-US'));
                else
                    $('.diskon').html("&mdash;");

                if(data.result.byr_listrik !== null){
                    $('.bayar-listrik').text((data.result.byr_listrik - data.result.byr_denlistrik).toLocaleString('en-US'));
                    $('.denda-listrik').text(Number(data.result.byr_denlistrik).toLocaleString('en-US'));
                    $('.diskon-listrik').text(Number(data.result.dis_listrik).toLocaleString('en-US'));
                }
                else{
                    $('.bayar-listrik').html("&mdash;");
                    $('.denda-listrik').html("&mdash;");
                    $('.diskon-listrik').html("&mdash;");
                }

                if(data.result.byr_airbersih !== null){
                    $('.bayar-airbersih').text((data.result.byr_airbersih - data.result.byr_denairbersih).toLocaleString('en-US'));
                    $('.denda-airbersih').text(Number(data.result.byr_denairbersih).toLocaleString('en-US'));
                    $('.diskon-airbersih').text(Number(data.result.dis_airbersih).toLocaleString('en-US'));
                }
                else{
                    $('.bayar-airbersih').html("&mdash;");
                    $('.denda-airbersih').html("&mdash;");
                    $('.diskon-airbersih').html("&mdash;");
                }
                
                if(data.result.byr_keamananipk !== null){
                    $('.bayar-keamananipk').text(Number(data.result.byr_keamananipk).toLocaleString('en-US'));
                    $('.diskon-keamananipk').text(Number(data.result.dis_keamananipk).toLocaleString('en-US'));
                }
                else{
                    $('.bayar-keamananipk').html("&mdash;");
                    $('.diskon-keamananipk').html("&mdash;");
                }
                
                if(data.result.byr_kebersihan !== null){
                    $('.bayar-kebersihan').text(Number(data.result.byr_kebersihan).toLocaleString('en-US'));
                    $('.diskon-kebersihan').text(Number(data.result.dis_kebersihan).toLocaleString('en-US'));
                }
                else{
                    $('.bayar-kebersihan').html("&mdash;");
                    $('.diskon-kebersihan').html("&mdash;");
                }
                
                if(data.result.byr_airkotor !== null)
                    $('.bayar-airkotor').text(Number(data.result.byr_airkotor).toLocaleString('en-US'));
                else
                    $('.bayar-airkotor').html("&mdash;");

                if(data.result.byr_lain !== null)
                    $('.bayar-lain').text(Number(data.result.byr_lain).toLocaleString('en-US'));
                else
                    $('.bayar-lain').html("&mdash;");
			}
		});
        
        $('#show-details').modal('show');
	});
});
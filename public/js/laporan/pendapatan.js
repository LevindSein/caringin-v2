$(document).ready(function(){
    $('#harian').DataTable({
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
        responsive:true
    });

    $("#tab-c-1").click(function(){
        if (!$.fn.dataTable.isDataTable('#bulanan')) {
            $('#bulanan').DataTable({
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
                responsive:true
            });
        }
    });

    $("#tab-c-2").click(function(){
        if (!$.fn.dataTable.isDataTable('#tahunan')) {
            $('#tahunan').DataTable({
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
                responsive:true
            });
        }
    });
});
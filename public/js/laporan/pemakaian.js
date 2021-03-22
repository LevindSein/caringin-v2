$(document).ready(function () {
    $('#pemakaian').DataTable({
        language: {
            paginate: {
                previous: "<i class='fas fa-angle-left'>",
                next: "<i class='fas fa-angle-right'>"
            }
        },
        pageLength:8,
        bSortable: false,
        deferRender: true,
        ordering: false,
        responsive:true
    });

    $(document).on('click', '.cetak', function(){
		id = $(this).attr('id');
		bulan = $(this).attr('nama');
		$('#hidden_value').val(id);
		$('.titles').text(bulan);
        $('#myModal').modal('show');
	});
});
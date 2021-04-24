$(document).ready(function () {
    $('#pemakaian').DataTable({
        language: {
            paginate: {
                previous: "<i class='fas fa-angle-left'>",
                next: "<i class='fas fa-angle-right'>"
            }
        },
        pageLength:10,
        aLengthMenu: [[5,10,25,50,100], [5,10,25,50,100]],
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
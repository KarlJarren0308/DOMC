$(document).ready(function() {
    $('#weeded-table').dataTable({
        initComplete : function() {
            var input = $('.dataTables_filter input').unbind();
            var self = this.api();
            var searchButton = $('<button class="btn-search">').text('Search').click(function() {
                self.search(input.val()).draw();
            });

            $('.dataTables_filter label').append(searchButton);
        },
        aoColumnDefs: [
            { bSearchable: false, bSortable: false, aTargets: [6] }
        ]
    });

    $(document).on('click', '[data-button="manage-accession-button"]', function() {
        $('input[name="accessionNumber"]').val($(this).data('var-id'));

        openModal(true, 'status-modal');
    });
});
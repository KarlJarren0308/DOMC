$(document).ready(function() {
    $('#accessions-table').dataTable({
        initComplete : function() {
            var input = $('.dataTables_filter input').unbind();
            var self = this.api();
            var searchButton = $('<button class="btn-search">').text('Search').click(function() {
                self.search(input.val()).draw();
            });

            $('.dataTables_filter label').append(searchButton);
        },
        aoColumnDefs: [
            { bSearchable: false, aTargets: [1] },
            { bSearchable: false, aTargets: [2] },
            { bSearchable: false, aTargets: [3] },
            { bSearchable: false, bSortable: false, aTargets: [4] }
        ]
    });

    $(document).on('click', '[data-button="manage-accession-button"]', function() {
        $('input[name="accessionNumber"]').val($(this).data('var-id'));

        openModal(true, 'status-modal');
    });

    $(document).on('click', '[data-button="add-accession-button"]', function() {
        openModal(true, 'add-modal');
    });
});
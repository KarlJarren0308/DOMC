$(document).ready(function() {
    $('#materials-table').dataTable({
        initComplete : function() {
            var input = $('.dataTables_filter input').unbind();
            var self = this.api();
            var searchButton = $('<button class="btn-search">').text('Search').click(function() {
                self.search(input.val()).draw();
            });

            $('.dataTables_filter label').append(searchButton);
        },
        aoColumnDefs: [
            { bSearchable: false, aTargets: [3] },
            { bSearchable: false, bSortable: false, aTargets: [4] }
        ]
    });
});
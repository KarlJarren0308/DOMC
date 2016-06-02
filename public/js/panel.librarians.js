$(document).ready(function() {
    $('#librarians-table').dataTable({
        aoColumnDefs: [
            { bSearchable: false, bSortable: false, aTargets: [2] }
        ]
    });
});
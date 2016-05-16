$(document).ready(function() {
    $('#authors-table').dataTable({
        aoColumnDefs: [
            { bSearchable: false, bSortable: false, aTargets: [1] }
        ]
    });
});
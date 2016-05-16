$(document).ready(function() {
    $('#publishers-table').dataTable({
        aoColumnDefs: [
            { bSearchable: false, bSortable: false, aTargets: [1] }
        ]
    });
});
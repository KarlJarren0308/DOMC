$(document).ready(function() {
    $('#reserved-table').dataTable({
        aoColumnDefs: [
            { bSearchable: false, bSortable: false, aTargets: [4] }
        ]
    });
});
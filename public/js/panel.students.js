$(document).ready(function() {
    $('#students-table').dataTable({
        aoColumnDefs: [
            { bSearchable: false, bSortable: false, aTargets: [2] }
        ]
    });
});
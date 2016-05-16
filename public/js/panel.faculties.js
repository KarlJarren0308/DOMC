$(document).ready(function() {
    $('#faculties-table').dataTable({
        aoColumnDefs: [
            { bSearchable: false, bSortable: false, aTargets: [2] }
        ]
    });
});
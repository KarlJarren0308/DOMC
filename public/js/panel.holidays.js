$(document).ready(function() {
    $('#holidays-table').dataTable({
        aoColumnDefs: [
            { bSearchable: false, bSortable: false, aTargets: [3] }
        ]
    });
});
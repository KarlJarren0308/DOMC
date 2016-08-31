$(document).ready(function() {
    $('#users-table').dataTable({
        aoColumnDefs: [
            { bSearchable: false, bSortable: false, aTargets: [4] }
        ]
    });
});
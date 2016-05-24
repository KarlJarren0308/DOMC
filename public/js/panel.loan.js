$(document).ready(function() {
    $('#loan-table').dataTable({
        aoColumnDefs: [
            { bSearchable: false, bSortable: false, aTargets: [4] }
        ]
    });

    $('[data-button="loan-button"]').click(function() {
        $('input[type="hidden"][name="arg1"]').val($(this).data('var-id'));
        $('#input-material-title').text($(this).data('var-title'));

        openModal();
    });
});
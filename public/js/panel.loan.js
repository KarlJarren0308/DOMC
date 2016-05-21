$(document).ready(function() {
    $('[data-button="loan-button"]').click(function() {
        alert($(this).data('var-id'));
    });
});
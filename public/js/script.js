$(document).ready(function() {
    $('html').click(function() {
        $('.dropdown-menu').removeClass('show');
    });

    $('.dropdown-toggle').click(function(e) {
        $(this).parent().find('.dropdown-menu').toggleClass('show');

        return false;
    });
});
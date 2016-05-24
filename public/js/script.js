function openModal() {
    $('.modal').fadeIn(250);
}

$(document).ready(function() {
    $('html').click(function() {
        $('.dropdown-menu').removeClass('show');
    });

    $('.dropdown-toggle').click(function(e) {
        $(this).parent().find('.dropdown-menu').toggleClass('show');

        return false;
    });

    $('.modal').click(function() {
        $('.modal').fadeOut(250);
    });

    $('.modal>.modal-container').click(function(e) {
        e.stopPropagation();
    });
});
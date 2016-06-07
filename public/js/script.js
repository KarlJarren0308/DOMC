var isModalDismissableByClick = true;

function openModal(isDismissableByClick) {
    if(isDismissableByClick != null) {
        if(typeof isDismissableByClick === 'boolean') {
            isModalDismissableByClick = isDismissableByClick;
        }
    }

    $('.modal').fadeIn(250);
}

function closeModal() {
    $('.modal').fadeOut(250);
}

function setModalContent(headerContent, bodyContent) {
    $('.modal > .modal-container > .modal-header').text(headerContent);
    $('.modal > .modal-container > .modal-body').text(bodyContent);
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
        if(isModalDismissableByClick) {
            closeModal();
        }
    });

    $('.modal>.modal-container').click(function(e) {
        e.stopPropagation();
    });
});
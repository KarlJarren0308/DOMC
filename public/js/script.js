var isModalDismissableByClick = true;

function openModal(isDismissableByClick, id) {
    if(isDismissableByClick != null) {
        if(typeof isDismissableByClick === 'boolean') {
            isModalDismissableByClick = isDismissableByClick;
        }
    }

    if(id != null) {
        $('.modal#' + id).fadeIn(250);
    } else {
        $('.modal').fadeIn(250);
    }
}

function closeModal(id) {
    if(id != null) {
        $('.modal#' + id).fadeOut(250);
    } else {
        $('.modal').fadeOut(250);
    }
}

function setModalContent(headerContent, bodyContent, id) {
    if(id != null) {
        $('.modal#' + id + ' > .modal-container > .modal-header').html(headerContent);
        $('.modal#' + id + ' > .modal-container > .modal-body').html(bodyContent);
    } else {
        $('.modal > .modal-container > .modal-header').html(headerContent);
        $('.modal > .modal-container > .modal-body').html(bodyContent);
    }
}

function initializeCarousel() {
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
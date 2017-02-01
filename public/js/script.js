var isModalDismissableByClick = true;

function padZeros(number, length) {
    var output = number.toString();

    while(output.length < length) {
        output = '0' + output;
    }

    return output;
}

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

function setModalLoader(id) {
    if(id != null) {
        $('.modal#' + id + ' > .modal-container > .modal-header').html('');
        $('.modal#' + id + ' > .modal-container > .modal-body').html('<div class="text-center gap-top gap-bottom"><span class="fa fa-spinner fa-4x fa-pulse"></span><div class="gap-top">Now Searching... Please Wait...</div></div>');
    } else {
        $('.modal > .modal-container > .modal-header').html('');
        $('.modal > .modal-container > .modal-body').html('<div class="text-center gap-top gap-bottom"><span class="fa fa-spinner fa-4x fa-pulse"></span><div class="gap-top">Now Searching... Please Wait...</div></div>');
    }
}

function dateTimeToString(dateTime) {
    var months = [
        'January',
        'February',
        'March',
        'April',
        'May',
        'June',
        'July',
        'August',
        'September',
        'October',
        'November',
        'December'
    ];

    var month = dateTime.getMonth();
    var day = dateTime.getDate();
    var year = dateTime.getFullYear();

    return months[month] + ' ' + day + ', ' + year;
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
            $(this).fadeOut(250);
        }
    });

    $('.modal>.modal-container').click(function(e) {
        e.stopPropagation();
    });
});
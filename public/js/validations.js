function isAlpha(selector) {
    var regex = /[^a-zA-Z\s]+/i;

    if(regex.test(selector.value)) {
        sweetAlert({
            title: 'Invalid Input',
            text: 'Input should only contain alphabet characters.',
            timer: 2000,
            showConfirmButton: false
        });

        selector.value = '';
        selector.focus();
    }
}

function isNumeric(selector) {
    var regex = /[^0-9.]/;

    if(isNaN(parseFloat(selector.value)) || regex.test(selector.value)) {
        sweetAlert({
            title: 'Invalid Input',
            text: 'Input should only contain numeric characters.',
            timer: 2000,
            showConfirmButton: false
        });

        selector.value = '';
        selector.focus();
    }
}

function isAlphaNumeric(selector) {
    var regex = /[^a-zA-Z0-9()%&!?,.-\/\s]+/i;

    if(regex.test(selector.value)) {
        sweetAlert({
            title: 'Invalid Input',
            text: 'Input should only contain alphanumeric characters.',
            timer: 2000,
            showConfirmButton: false
        });

        selector.value = '';
        selector.focus();
    }
}

function isDate(selector) {
    var regex = /[^0-9-]/i;

    if((regex.test(selector.value)) || (selector.value.length == 10 && selector.value.substring(4, 5) != '-' && selector.value.substring(7, 8) != '-')) {
        sweetAlert({
            title: 'Invalid Input',
            text: 'Input should be a date in yyyy-mm-dd format.',
            timer: 2000,
            showConfirmButton: false
        });

        selector.value = '';
        selector.focus();
    }
}
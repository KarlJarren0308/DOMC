var sweetAlertPrompt = true;

function isAlpha(selector) {
    var regex = /[^a-zA-Z\s]+/i;

    if(regex.test(selector.value)) {
        if(sweetAlertPrompt == true) {
            sweetAlert({
                title: 'Invalid Input',
                text: 'Input should only contain alphabet characters.',
                timer: 2000,
                showConfirmButton: false
            });
        }

        selector.value = '';
        selector.focus();
    }
}

function isNumeric(selector) {
    var regex = /[^0-9.]/;

    if(isNaN(parseFloat(selector.value)) || regex.test(selector.value)) {
        if(sweetAlertPrompt == true) {
            sweetAlert({
                title: 'Invalid Input',
                text: 'Input should only contain numeric characters.',
                timer: 2000,
                showConfirmButton: false
            });
        }

        selector.value = '';
        selector.focus();
    }
}

function isAlphaNumeric(selector) {
    var regex = /[^a-zA-Z0-9()%&!?,.-\/\s]+/i;

    if(regex.test(selector.value)) {
        if(sweetAlertPrompt == true) {
            sweetAlert({
                title: 'Invalid Input',
                text: 'Input should only contain alphanumeric characters.',
                timer: 2000,
                showConfirmButton: false
            });
        }

        selector.value = '';
        selector.focus();
    }
}

function isDate(selector) {
    var regex = /[^0-9-]/i;
    var format = /^([0-9]{4})-([0-9]{2})-([0-9]{2})$/;

    if((regex.test(selector.value)) || (selector.value.length == 10 && !format.test(selector.value))) {
        if(sweetAlertPrompt == true) {
            sweetAlert({
                title: 'Invalid Input',
                text: 'Input should be a date in yyyy-mm-dd format.',
                timer: 2000,
                showConfirmButton: false
            });
        }

        selector.value = '';
        selector.focus();
    }
}

/* Library Validations */

function isISBN(selector) {
    var regex = /[^0-9-]/i;
    var format = /^([0-9]{4})-([0-9]{2})-([0-9]{2})$/;

    if((regex.test(selector.value)) || (selector.value.length == 10 && !format.test(selector.value))) {
        if(sweetAlertPrompt == true) {
            sweetAlert({
                title: 'Invalid Input',
                text: 'Input should be a date in yyyy-mm-dd format.',
                timer: 2000,
                showConfirmButton: false
            });
        }

        selector.value = '';
        selector.focus();
    }
}
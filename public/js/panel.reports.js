$(document).ready(function() {
    $('[data-form="loan-report"]').submit(function() {
        var thisForm = $(this);
        var startDate = moment($('[data-form="loan-report"] input[name="from"]').val()).format('YYYY-MM-DD');
        var endDate = moment($('[data-form="loan-report"] input[name="to"]').val()).format('YYYY-MM-DD');

        if(moment(endDate).isSameOrAfter(startDate)) {
            thisForm.off('submit');
            thisForm.submit();
        } else {
            setModalContent('Library Reports', 'Oops! "To" Date should be later than "From" Date.', 'modal');
            openModal(false, 'modal');

            setTimeout(function() {
                closeModal('modal');
            }, 2000);
        }

        return false;
    });

    $('[data-form="material-report"]').submit(function() {
        var thisForm = $(this);
        var startDate = moment($('[data-form="material-report"] input[name="from"]').val()).format('YYYY-MM-DD');
        var endDate = moment($('[data-form="material-report"] input[name="to"]').val()).format('YYYY-MM-DD');

        if(moment(endDate).isSameOrAfter(startDate)) {
            thisForm.off('submit');
            thisForm.submit();
        } else {
            setModalContent('Library Reports', 'Oops! "To" Date should be later than "From" Date.', 'modal');
            openModal(false, 'modal');

            setTimeout(function() {
                closeModal('modal');
            }, 2000);
        }

        return false;
    });

    $('[data-form="receive-report"]').submit(function() {
        var thisForm = $(this);
        var startDate = moment($('[data-form="receive-report"] input[name="from"]').val()).format('YYYY-MM-DD');
        var endDate = moment($('[data-form="receive-report"] input[name="to"]').val()).format('YYYY-MM-DD');

        if(moment(endDate).isSameOrAfter(startDate)) {
            thisForm.off('submit');
            thisForm.submit();
        } else {
            setModalContent('Library Reports', 'Oops! "To" Date should be later than "From" Date.', 'modal');
            openModal(false, 'modal');

            setTimeout(function() {
                closeModal('modal');
            }, 2000);
        }

        return false;
    });

    $('[data-form="user-list-report"]').submit(function() {
        var thisForm = $(this);
        var startDate = moment($('[data-form="user-list-report"] input[name="from"]').val()).format('YYYY-MM-DD');
        var endDate = moment($('[data-form="user-list-report"] input[name="to"]').val()).format('YYYY-MM-DD');

        if(moment(endDate).isSameOrAfter(startDate)) {
            thisForm.off('submit');
            thisForm.submit();
        } else {
            setModalContent('Library Reports', 'Oops! "To" Date should be later than "From" Date.', 'modal');
            openModal(false, 'modal');

            setTimeout(function() {
                closeModal('modal');
            }, 2000);
        }

        return false;
    });
});
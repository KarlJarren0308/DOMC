$(document).ready(function() {
    $(function() {
        $('#submit-button').css({
            'cursor': 'not-allowed'
        }).attr('disabled', 'disabled');
    });

    $('input[name="newPassword"]').keyup(function() {
        if($('input[name="confirmPassword"]').val() != '') {
            if($(this).val() == $('input[name="confirmPassword"]').val()) {
                $('.prompt').html('<div class="alert success">Password matched.</div>');

                $('#submit-button').css({
                    'cursor': 'pointer'
                }).removeAttr('disabled');
            } else {
                $('.prompt').html('<div class="alert danger">Oops! Password doesn\'t match.</div>');

                $('#submit-button').css({
                    'cursor': 'not-allowed'
                }).attr('disabled', 'disabled');
            }
        }
    });

    $('input[name="confirmPassword"]').keyup(function() {
        if($('input[name="newPassword"]').val() != '') {
            if($(this).val() == $('input[name="newPassword"]').val()) {
                $('.prompt').html('<div class="alert success">Password matched.</div>');

                $('#submit-button').css({
                    'cursor': 'pointer'
                }).removeAttr('disabled');
            } else {
                $('.prompt').html('<div class="alert danger">Oops! Password doesn\'t match.</div>');

                $('#submit-button').css({
                    'cursor': 'not-allowed'
                }).attr('disabled', 'disabled');
            }
        }
    });
});
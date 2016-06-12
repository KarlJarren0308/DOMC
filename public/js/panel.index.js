$(document).ready(function() {
    $(function() {
        openModal(false);

        $.ajax({
            url: url,
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': atob(token.substring(32, token.length - 32)) },
            dataType: 'json',
            success: function(response) {
                closeModal();

                $('#r-count').text(response['data']['reserved']);
                $('#l-count').text(response['data']['loaned']);
            }
        });

        return false;
    });
});
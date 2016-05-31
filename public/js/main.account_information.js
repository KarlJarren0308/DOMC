$(document).ready(function() {
    $('.countdown').each(function() {
        var thisCountdown = $(this);
        var interval = thisCountdown.data('var-start') - thisCountdown.data('var-end');
        var hours, minutes, seconds;

        if(interval > 0) {
            var timeInterval = setInterval(function() {
                if(interval > 0) {
                    hours = Math.floor(interval / 3600);
                    minutes = Math.floor((interval - (hours * 3600)) / 60);
                    seconds = interval - (hours * 3600) - (minutes * 60);

                    thisCountdown.text(hours + ' hour(s), ' + minutes + ' minute(s), and ' + seconds + ' second(s)');

                    interval--;
                } else {
                    $.ajax({
                        url: url,
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': token },
                        data: { arg0: '2705a83a5a0659cce34583972637eda5', arg1: thisCountdown.data('var-id') },
                        dataType: 'json',
                        success: function(response) {
                            if(response['status'] == 'Success') {
                                thisCountdown.parent().parent().html('<div class="text-right">Reservation Cancelled.</div>');
                            }
                        }
                    });

                    return false;
                }
            }, 1000);
        } else {
            thisCountdown.parent().parent().html('<div class="text-right">Reservation Cancelled.</div>');
        }
    });
});
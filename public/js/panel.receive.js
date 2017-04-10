function isHoliday(dateStamp, holidays) {
    dateStamp = moment(dateStamp).format('YYYY-MM-DD');

    if(holidays.length > 0) {
        for(var i = 0; i < holidays.length; i++) {
            if(moment(dateStamp).isSame(holidays[i]['Holiday_Date'])) {
                return true;
            }
        }

        return false;
    } else {
        return false;
    }
}

function isWeekend(dateStamp) {
    dateStamp = moment(dateStamp).format('dddd');

    if(dateStamp == 'Sunday') {
        return true;
    /*} else if(dateStamp == 'Saturday') {
        return true;*/
    } else {
        return false;
    }
}

function nextDay(dateStamp) {
    return moment(dateStamp).add(1, 'days').format('YYYY-MM-DD');
}

function computePenalty(dateLoaned, holidays, startPenaltyAfter, perDayPenalty) {
    dateLoaned = moment(dateLoaned).format('YYYY-MM-DD HH:mm:ss');
    var dayEnd = moment(dateLoaned).add(startPenaltyAfter, 'days').format('YYYY-MM-DD HH:mm:ss');
    var graceDays = moment(dayEnd).diff(dateLoaned, 'days');
    var i = 1;
    var j = 1;
    var markedDate;

    while(i <= graceDays) {
        markedDate = moment(dateLoaned).add(i, 'days');

        if(isWeekend(markedDate)) {
            graceDays++;
            dayEnd = nextDay(dayEnd);
        } else {
            if(isHoliday(markedDate, holidays)) {
                graceDays++;
                dayEnd = nextDay(dayEnd);
            }
        }

        i++;
    }

    dateTimeToday = moment(dateTimeToday).format('YYYY-MM-DD HH:mm:ss');
    graceDays = moment(dateTimeToday).diff(dayEnd, 'days');

    while(j <= graceDays) {
        markedDate = moment(dayEnd).add(j, 'days');

        if(isWeekend(markedDate)) {
            graceDays++;
            dayEnd = nextDay(dayEnd);
        } else {
            if(isHoliday(markedDate, holidays)) {
                graceDays++;
                dayEnd = nextDay(dayEnd);
            }
        }

        j++;
    }

    return moment(dateTimeToday).diff(dayEnd, 'days') * parseFloat(perDayPenalty);
}

$(document).ready(function() {
    $('#receive-table').dataTable({
        aoColumnDefs: [
            { bSearchable: false, bSortable: false, aTargets: [5] }
        ]
    });
    
    $('[data-form="search-loan-form"]').submit(function() {
        openModal(false, 'loader-modal');

        $('#loans-table-block').html('');

        $.ajax({
            url: '/search/receive',
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                var element = '<form data-form="receive-list-form"><table id="loans-table" class="u-full-width">';
                var name = '';
                var totalPenalty = 0;
                var holidays;
                var dateLoaned;
                var startPenaltyAfter;
                var perDayPenalty;

                element += '<thead>';
                element += '<tr>';
                element += '<th>Call Number</th>';
                element += '<th>Accession Number</th>';
                element += '<th>Title</th>';
                element += '<th>Loaned By</th>';
                element += '<th>Date Loaned</th>';
                element += '<th>Penalty</th>';
                element += '<th>Status</th>';
                element += '<th></th>';
                element += '</tr>';
                element += '</thead>';
                element += '<tbody>';

                var isFirst = true;

                for(var i = 0; i < response['data']['loans'].length; i++) {
                    totalPenalty = 0;

                    if(response['data']['loans'][i]['Account_Type'] == 'Faculty') {
                        for(var m = 0; m < response['data']['faculty_accounts'].length; m++) {
                            if(response['data']['faculty_accounts'][m]['Faculty_ID'] == response['data']['loans'][i]['Account_Owner']) {
                                if(response['data']['faculty_accounts'][m]['Faculty_Middle_Name'].length > 1) {
                                    name = response['data']['faculty_accounts'][m]['Faculty_First_Name'] + ' ' + response['data']['faculty_accounts'][m]['Faculty_Middle_Name'].substring(0, 1) + '. ' + response['data']['faculty_accounts'][m]['Faculty_Last_Name'];
                                } else {
                                    name = response['data']['faculty_accounts'][m]['Faculty_First_Name'] + ' ' + response['data']['faculty_accounts'][m]['Faculty_Last_Name'];
                                }
                            }
                        }
                    } else if(response['data']['loans'][i]['Account_Type'] == 'Librarian') {
                        for(var m = 0; m < response['data']['librarian_accounts'].length; m++) {
                            if(response['data']['librarian_accounts'][m]['Librarian_ID'] == response['data']['loans'][i]['Account_Owner']) {
                                if(response['data']['librarian_accounts'][m]['Librarian_Middle_Name'].length > 1) {
                                    name = response['data']['librarian_accounts'][m]['Librarian_First_Name'] + ' ' + response['data']['librarian_accounts'][m]['Librarian_Middle_Name'].substring(0, 1) + '. ' + response['data']['librarian_accounts'][m]['Librarian_Last_Name'];
                                } else {
                                    name = response['data']['librarian_accounts'][m]['Librarian_First_Name'] + ' ' + response['data']['librarian_accounts'][m]['Librarian_Last_Name'];
                                }
                            }
                        }
                    } else if(response['data']['loans'][i]['Account_Type'] == 'Student') {
                        for(var m = 0; m < response['data']['student_accounts'].length; m++) {
                            if(response['data']['student_accounts'][m]['Student_ID'] == response['data']['loans'][i]['Account_Owner']) {
                                if(response['data']['student_accounts'][m]['Student_Middle_Name'].length > 1) {
                                    name = response['data']['student_accounts'][m]['Student_First_Name'] + ' ' + response['data']['student_accounts'][m]['Student_Middle_Name'].substring(0, 1) + '. ' + response['data']['student_accounts'][m]['Student_Last_Name'];
                                } else {
                                    name = response['data']['student_accounts'][m]['Student_First_Name'] + ' ' + response['data']['student_accounts'][m]['Student_Last_Name'];
                                }
                            }
                        }
                    }

                    element += '<tr>';
                    element += '<td>' + response['data']['loans'][i]['Material_Call_Number'] + '</td>';
                    element += '<td>' + padZeros(response['data']['loans'][i]['Accession_Number'], 4) + '</td>';
                    element += '<td>' + response['data']['loans'][i]['Material_Title'] + '</td>';
                    element += '<td>' + name + '</td>';
                    element += '<td>' + moment(response['data']['loans'][i]['Loan_Date_Stamp']).format('MMMM D, YYYY') + '</td>';
                    element += '<td>';

                    if(response['data']['holidays'].length > 0) {
                        holidays = response['data']['holidays'];
                    } else {
                        holidays = [];
                    }

                    dateLoaned = response['data']['loans'][i]['Loan_Date_Stamp'] + ' ' + response['data']['loans'][i]['Loan_Time_Stamp'];
                    startPenaltyAfter = response['data']['start_penalty_after'][0];
                    perDayPenalty = response['data']['per_day_penalty'][0];
                    var receiveID = '';
                    var datetimeReceived = '';
                    
                    if(response['data']['loans'][i]['Loan_Status'] == 'active') {
                        totalPenalty = computePenalty(dateLoaned, holidays, startPenaltyAfter, perDayPenalty);
                    } else {
                        for(var j = 0; j < response['data']['receives'].length; j++) {
                            if(response['data']['receives'][j]['Receive_Reference'] == response['data']['loans'][i]['Loan_ID']) {
                                receiveID = response['data']['receives'][j]['Receive_ID'];
                                datetimeReceived = moment(response['data']['receives'][j]['Receive_Date_Stamp'] + ' ' + response['data']['receives'][j]['Receive_Time_Stamp']).format('YYYY-MM-DD HH:mm:ss');
                                totalPenalty = response['data']['receives'][j]['Penalty'];

                                break;
                            }
                        }
                    }

                    if(response['data']['loans'][i]['Loan_Status'] == 'active') {
                        if(totalPenalty > 0) {
                            element += '&#8369; ' + totalPenalty + '.00';
                        } else {
                            element += '&#8369; 0.00';
                        }
                    } else {
                        for(var k = 0; k < response['data']['receives'].length; k++) {
                            if(response['data']['loans'][i]['Loan_ID'] == response['data']['receives'][k]['Receive_Reference']) {
                                element += '&#8369; ' + response['data']['receives'][k]['Penalty'] + '.00';
                            }
                        }
                    }

                    element += '</td>';
                    element += '<td>';

                    if(response['data']['loans'][i]['Loan_Status'] == 'active') {
                        element += 'unpaid';
                    } else {
                        for(var k = 0; k < response['data']['receives'].length; k++) {
                            if(response['data']['loans'][i]['Loan_ID'] == response['data']['receives'][k]['Receive_Reference']) {
                                element += response['data']['receives'][k]['Clearance'];
                            }
                        }
                    }

                    element += '</td>';
                    element += '<td class="text-center">';
                    element += '<input type="checkbox">';

                    // if(receiveID == '') {
                    //     datetimeReceived = moment().format('MMMM D, YYYY');
                    // }

                    // if(totalPenalty > 0) {
                    //     element += '<button class="btn btn-orange btn-sm" data-button="print-receipt-button" data-var-id="' + response['data']['loans'][i]['Loan_ID'] + '" data-var-title="' + response['data']['loans'][i]['Material_Title'] + '" data-var-date-loaned="' + moment(response['data']['loans'][i]['Loan_Date_Stamp']).format('MMMM D, YYYY') + '" data-var-date-received="' + datetimeReceived + '" data-var-loaned-by="' + name + '" data-var-penalty="' + totalPenalty + '">Print Receipt</button>&nbsp;';
                    // }

                    // if(response['data']['loans'][i]['Loan_Status'] == 'active') {
                    //     element += '<button class="btn btn-green btn-sm" data-button="receive-button" data-var-id="' + response['data']['loans'][i]['Loan_ID'] + '" data-var-title="' + response['data']['loans'][i]['Material_Title'] + '" data-var-date-loaned="' + moment(response['data']['loans'][i]['Loan_Date_Stamp']).format('MMMM D, YYYY') + '" data-var-loaned-by="' + name + '" data-var-penalty="';

                    //     if(totalPenalty > 0) {
                    //         element += totalPenalty;
                    //     } else {
                    //         element += 0;
                    //     }

                    //     element += '">Receive</button>';
                    // } else {
                    //     element += '<div class="btn btn-red btn-sm">Returned</div>';
                    // }

                    // element += '&nbsp;<button class="btn btn-red btn-sm" data-button="remarks-button">Remarks</button>';
                    element += '</td>';
                    element += '</tr>';
                }

                element += '</tbody>';
                element += '</table></form>';
                element += '<div style="margin-top: 15px;"><button class="btn btn-orange">Print Receipt</button><button class="btn btn-green">Receive</button><button class="btn btn-red">Clear/Pay</button></div>';

                $('#loans-table-block').html(element).promise().done(function() {
                    closeModal();

                    $('#loans-table').dataTable({
                        aoColumnDefs: [
                            { bSearchable: false, bSortable: false, aTargets: [7] }
                        ],
                        bFilter: false
                    });
                });
            },
            error: function(arg0, arg1, arg2) {
                console.log(arg0.responseText);
            }
        });

        return false;
    });

    $('body').on('click', '[data-button="print-receipt-button"]', function() {
        // TODO
        var data1 = $(this).data('var-id');
        var data2 = $(this).data('var-penalty');
        var data3 = $(this).data('var-date-loaned');
        var data4 = $(this).data('var-loaned-by');
        var data5 = $(this).data('var-title');
        var data6 = $(this).data('var-date-received');
        var tab = window.open();

        tab.document.write('<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><title>De Ocampo Memorial College</title><style>.receipt { border: 1px solid black; display: inline-block; font-family: "Helvetica"; padding: 10px 15px; width: 250px; } .title { font-size: 1.5em; font-weight: bold; margin-bottom: 15px; } .info { font-size: 0.8em; text-indent: -15px; padding: 0 15px; }</style></head><body><div class="receipt"><div class="title">De Ocampo Memorial College</div><div class="info">Book Title: ' + data5 + '</div><div class="info">Loaned By: ' + data4 + '</div><div class="info">Date Loaned: ' + data3 + '</div><div class="info">Date Returned: ' + moment(dateTimeToday).format('MMMM DD, YYYY') + '</div><div class="info">Total Penalty: &#8369; ' + data2 + '</div></div></body></html>');
        tab.print();
        tab.close();
    });

    $('body').on('click', '[data-button="receive-button"]', function() {
        var data1 = $(this).data('var-id');
        var data2 = $(this).data('var-penalty');
        var data3 = $(this).data('var-date-loaned');
        var data4 = $(this).data('var-loaned-by');
        var data5 = $(this).data('var-title');

        setModalContent('Receive Book(s)', 'Are you sure ' + data4 + ' is returning a book title "' + data5 + '"?<br><br><div class="text-right"><button class="btn btn-orange" data-button="yes-button">Yes</button>&nbsp;<button class="btn btn-red" data-button="no-button">No</button></div>', 'receipt-modal');
        openModal(false, 'receipt-modal');

        $('[data-button="no-button"]').click(function() {
            closeModal('receipt-modal');
        });

        $('[data-button="yes-button"]').click(function() {
            openModal(false, 'loader-modal');

            $.ajax({
                url: '/panel/receive',
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: {
                    arg0: data1,
                    arg1: data2
                },
                dataType: 'json',
                success: function(response) {
                    closeModal('loader-modal');
                    setModalContent('Receive Book(s)', response['message'], 'receipt-modal');
                    openModal(true, 'receipt-modal');

                    setTimeout(function() {
                        closeModal();

                        location.reload();
                    }, 2000);
                }
            });

            return false;
        });
    });
});
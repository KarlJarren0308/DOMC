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
                var element = '<table id="loans-table" class="u-full-width">';
                var name = '';
                var totalPenalty = 0;

                element += '<thead>';
                element += '<tr>';
                element += '<th>Call Number</th>';
                element += '<th>Title</th>';
                element += '<th>Loaned By</th>';
                element += '<th>Date Loaned</th>';
                element += '<th>Penalty</th>';
                element += '<th></th>';
                element += '</tr>';
                element += '</thead>';
                element += '<tbody>';

                var isFirst = true;

                for(var i = 0; i < response['data']['loans'].length; i++) {
                    totalPenalty = 0;

                    if(response['data']['loans'][i]['Account_Type'] == 'Faculty') {
                        for(var m = 0; m < response['data']['faculty_accounts'].length; m++) {
                            if(response['data']['faculty_accounts'][m]['Faculty_Middle_Name'].length > 1) {
                                name = response['data']['faculty_accounts'][m]['Faculty_First_Name'] + ' ' + response['data']['faculty_accounts'][m]['Faculty_Middle_Name'].substring(0, 1) + '. ' + response['data']['faculty_accounts'][m]['Faculty_Last_Name'];
                            } else {
                                name = response['data']['faculty_accounts'][m]['Faculty_First_Name'] + ' ' + response['data']['faculty_accounts'][m]['Faculty_Last_Name'];
                            }
                        }
                    } else if(response['data']['loans'][i]['Account_Type'] == 'Librarian') {
                        for(var m = 0; m < response['data']['librarian_accounts'].length; m++) {
                            if(response['data']['librarian_accounts'][m]['Librarian_Middle_Name'].length > 1) {
                                name = response['data']['librarian_accounts'][m]['Librarian_First_Name'] + ' ' + response['data']['librarian_accounts'][m]['Librarian_Middle_Name'].substring(0, 1) + '. ' + response['data']['librarian_accounts'][m]['Librarian_Last_Name'];
                            } else {
                                name = response['data']['librarian_accounts'][m]['Librarian_First_Name'] + ' ' + response['data']['librarian_accounts'][m]['Librarian_Last_Name'];
                            }
                        }
                    } else if(response['data']['loans'][i]['Account_Type'] == 'Student') {
                        for(var m = 0; m < response['data']['student_accounts'].length; m++) {
                            if(response['data']['student_accounts'][m]['Student_Middle_Name'].length > 1) {
                                name = response['data']['student_accounts'][m]['Student_First_Name'] + ' ' + response['data']['student_accounts'][m]['Student_Middle_Name'].substring(0, 1) + '. ' + response['data']['student_accounts'][m]['Student_Last_Name'];
                            } else {
                                name = response['data']['student_accounts'][m]['Student_First_Name'] + ' ' + response['data']['student_accounts'][m]['Student_Last_Name'];
                            }
                        }
                    }

                    element += '<tr>';
                    element += '<td>' + response['data']['loans'][i]['Material_Call_Number'] + '</td>';
                    element += '<td>' + response['data']['loans'][i]['Material_Title'] + '</td>';
                    element += '<td>' + name + '</td>';
                    element += '<td>' + response['data']['loans'][i]['Loan_Date_Stamp'] + '</td>';
                    element += '<td>';

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
                    element += '<td>'

                    if(response['data']['loans'][i]['Loan_Status'] == 'active') {
                        element += '<form method="POST" action="http://localhost:8000/panel/receive" accept-charset="UTF-8" class="no-margin"><input name="_token" type="hidden" value="' + $('meta[name="csrf-token"]').attr('content') + '"><input name="arg0" type="hidden" value="' + response['data']['loans'][i]['Loan_ID'] + '"><input name="arg1" type="hidden" value="';

                        if(totalPenalty > 0) {
                            element += totalPenalty;
                        } else {
                            element += 0;
                        }

                        element += '"><input class="btn btn-green btn-sm" type="submit" value="Receive"></form>';
                    } else {
                        element += '<div class="btn btn-red btn-sm">Returned</div>';
                    }

                    element += '</td>';
                    element += '</tr>';
                }

                element += '</tbody>';
                element += '</table>';
                element += '';

                $('#loans-table-block').html(element).promise().done(function() {
                    closeModal();

                    $('#loans-table').dataTable({
                        aoColumnDefs: [
                            { bSearchable: false, bSortable: false, aTargets: [5] }
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
});
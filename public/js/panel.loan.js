$(document).ready(function() {
    var usersID = '';
    var usersName = '';
    var materialID = '';
    var materialTitle = '';
    var addedAccessions = [];
    var addedMaterials = [];

    $('[data-form="search-borrower-form"]').submit(function() {
        openModal(false, 'loader-modal');

        $('#borrowers-table-block').html('');
        $('#pending-table-block').html('');

        addedAccessions = [];
        addedMaterials = [];

        $.ajax({
            url: '/search/loan_borrowers',
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                var element = '<table id="borrowers-table" class="u-full-width">';
                var name = '';
                var type = '';

                element += '<thead>';
                element += '<tr>';
                element += '<th>User I.D. Number</th>';
                element += '<th>Name</th>';
                element += '<th>Birth Date</th>';
                element += '<th>Type</th>';
                element += '<th></th>';
                element += '</tr>';
                element += '</thead>';
                element += '<tbody>';

                if(response['data']['users'].length > 0) {
                    for(var i = 0; i < response['data']['users'].length; i++) {
                        if(response['data']['users'][i]['Account_Type'] == 'Student') {
                            if(response['data']['users'][i]['Student_Middle_Name'].length > 1) {
                                name = response['data']['users'][i]['Student_First_Name'] + ' ' + response['data']['users'][i]['Student_Middle_Name'].substring(0, 1) + '. ' + response['data']['users'][i]['Student_Last_Name'];
                            } else {
                                name = response['data']['users'][i]['Student_First_Name'] + ' ' + response['data']['users'][i]['Student_Last_Name'];
                            }
                        } else {
                            if(response['data']['users'][i]['Faculty_Middle_Name'].length > 1) {
                                name = response['data']['users'][i]['Faculty_First_Name'] + ' ' + response['data']['users'][i]['Faculty_Middle_Name'].substring(0, 1) + '. ' + response['data']['users'][i]['Faculty_Last_Name'];
                            } else {
                                name = response['data']['users'][i]['Faculty_First_Name'] + ' ' + response['data']['users'][i]['Faculty_Last_Name'];
                            }
                        }

                        element += '<tr>';
                        element += '<td>' + response['data']['users'][i]['Account_Username'] + '</td>';
                        element += '<td>' + name + '</td>';
                        element += '<td>';

                        if(response['data']['users'][i]['Account_Type'] == 'Student') {
                            element += moment(response['data']['users'][i]['Student_Birth_Date']).format('MMMM D, YYYY');
                        } else {
                            element += moment(response['data']['users'][i]['Faculty_Birth_Date']).format('MMMM D, YYYY');
                        }

                        element += '</td>';
                        element += '<td>' + response['data']['users'][i]['Account_Type'] + '</td>';
                        element += '<td class="text-center"><button class="btn btn-orange btn-sm" data-button="loan-button" data-var-id="' + response['data']['users'][i]['Account_Username'] + '" data-var-name="' + name + '">Loan</button></td>';
                        element += '</tr>';
                    }
                }

                element += '</tbody>';
                element += '</table>';
                element += '';

                $('#borrowers-table-block').html(element).promise().done(function() {
                    closeModal();

                    $('#borrowers-table').dataTable({
                        aoColumnDefs: [
                            { bSearchable: false, bSortable: false, aTargets: [4] }
                        ],
                        bFilter: false
                    });

                    $('[data-button="loan-button"]').click(function() {
                        openModal(true, 'loan-book-modal');

                        usersID = $(this).data('var-id');
                        usersName = $(this).data('var-name');
                    });
                });
            }
        });

        return false;
    });

    $('[data-form="search-book-form"]').submit(function() {
        openModal(false, 'loader-modal');

        $('#books-table-block').html('');

        $.ajax({
            url: '/search/loan_books',
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                var element = '<table id="books-table" class="u-full-width">';
                var name = '';
                var type = '';
                var accessionNumbers, reservedCount, loanedCount;

                isModalDismissableByClick = true;

                element += '<thead>';
                element += '<tr>';
                element += '<th>Call Number</th>';
                element += '<th>Title</th>';
                element += '<th>ISBN</th>';
                element += '<th>Author(s)</th>';
                element += '<th>Available Copies</th>';
                element += '<th></th>';
                element += '</tr>';
                element += '</thead>';
                element += '<tbody>';

                for(var i = 0; i < response['data']['works_materials'].length; i++) {
                    isFirst = true;

                    element += '<tr>';
                    element += '<td>' + response['data']['works_materials'][i]['Material_Call_Number'] + '</td>';
                    element += '<td><a href="" data-link="book-info-link" data-var-reference="' + i + '">' + response['data']['works_materials'][i]['Material_Title'] + '</a></td>';
                    element += '<td>' + response['data']['works_materials'][i]['Material_ISBN'] + '</td>';
                    element += '<td>';

                    for(var j = 0; j < response['data']['works_authors'].length; j++) {
                        if(response['data']['works_authors'][j]['Material_ID'] == response['data']['works_materials'][i]['Material_ID']) {
                            if(isFirst) {
                                isFirst = false;
                            } else {
                                element += '<br>';
                            }

                            if(response['data']['works_authors'][j]['Author_Middle_Name'].length > 1) {
                                element += response['data']['works_authors'][j]['Author_First_Name'] + ' ' + response['data']['works_authors'][j]['Author_Middle_Name'].substring(0, 1) + '. ' + response['data']['works_authors'][j]['Author_Last_Name'];
                            } else {
                                element += response['data']['works_authors'][j]['Author_First_Name'] + ' ' + response['data']['works_authors'][j]['Author_Last_Name'];
                            }
                        }
                    }

                    element += '</td>';
                    element += '<td class="text-center">';

                    accessionNumbers = 0;
                    reservedCount = 0;
                    loanedCount = 0;

                    for(var k = 0; k < response['data']['accession_numbers'].length; k++) {
                        if(response['data']['accession_numbers'][k]['Material_ID'] == response['data']['works_materials'][i]['Material_ID']) {
                            accessionNumbers++;
                        }
                    }

                    for(var k = 0; k < response['data']['reserved_materials'].length; k++) {
                        if(response['data']['reserved_materials'][k]['Material_ID'] == response['data']['works_materials'][i]['Material_ID']) {
                            reservedCount++;
                        }
                    }

                    for(var l = 0; l < response['data']['loaned_materials'].length; l++) {
                        if(response['data']['loaned_materials'][l]['Material_ID'] == response['data']['works_materials'][i]['Material_ID']) {
                            loanedCount++;
                        }
                    }

                    materialCount = accessionNumbers - reservedCount - loanedCount;

                    if(materialCount > 0) {
                        element += materialCount;
                    } else {
                        element += '0';
                    }

                    element += '</td>';
                    element += '<td><button class="btn btn-orange btn-sm" data-button="loan-book-button" data-var-id="' + response['data']['works_materials'][i]['Material_ID'] + '" data-var-title="' + response['data']['works_materials'][i]['Material_Title'] + '">Loan this book</button></td>';
                    element += '</tr>';
                }

                element += '</tbody>';
                element += '</table>';
                element += '';

                $('#books-table-block').html(element).promise().done(function() {
                    closeModal('loader-modal');

                    $('#books-table').dataTable({
                        aoColumnDefs: [
                            { bSearchable: false, bSortable: false, aTargets: [5] }
                        ],
                        bFilter: false
                    });

                    $('[data-link="book-info-link"]').click(function() {
                        var referenceID = $(this).data('var-reference');
                        var bookInfo = '';

                        isFirst = true;

                        bookInfo += '<h4 class="no-margin">' + response['data']['works_materials'][referenceID]['Material_Title'] + '</h4>';
                        bookInfo += '<div>Author(s): ';

                        for(var i = 0; i < response['data']['works_authors'].length; i++) {
                            if(response['data']['works_authors'][i]['Material_ID'] == response['data']['works_materials'][referenceID]['Material_ID']) {
                                if(isFirst) {
                                    isFirst = false;
                                } else {
                                    bookInfo += ', ';
                                }

                                if(response['data']['works_authors'][i]['Author_Middle_Name'].length > 1) {
                                    bookInfo += response['data']['works_authors'][i]['Author_First_Name'] + ' ' + response['data']['works_authors'][i]['Author_Middle_Name'].substring(0, 1) + '. ' + response['data']['works_authors'][i]['Author_Last_Name'];
                                } else {
                                    bookInfo += response['data']['works_authors'][i]['Author_First_Name'] + ' ' + response['data']['works_authors'][i]['Author_Last_Name'];
                                }
                            }
                        }

                        bookInfo += '</div>';
                        bookInfo += '<hr>';
                        bookInfo += '<div class="row">';
                        bookInfo += '<div class="eight columns">';
                        bookInfo += '<div class="row">';
                        bookInfo += '<div class="five columns text-right">Call Number:</div>';
                        bookInfo += '<div class="seven columns">' + response['data']['works_materials'][referenceID]['Material_Call_Number'] + '</div>';
                        bookInfo += '</div>';
                        bookInfo += '<div class="row">';
                        bookInfo += '<div class="five columns text-right">ISBN:</div>';
                        bookInfo += '<div class="seven columns">' + response['data']['works_materials'][referenceID]['Material_ISBN'] + '</div>';
                        bookInfo += '</div>';
                        bookInfo += '<div class="row">';
                        bookInfo += '<div class="five columns text-right">Copyright Year:</div>';
                        bookInfo += '<div class="seven columns">' + response['data']['works_materials'][referenceID]['Material_Copyright_Year'] + '</div>';
                        bookInfo += '</div>';
                        bookInfo += '<div class="row">';
                        bookInfo += '<div class="five columns text-right">Location:</div>';
                        bookInfo += '<div class="seven columns">' + response['data']['works_materials'][referenceID]['Material_Location'] + '</div>';
                        bookInfo += '</div>';

                        if(response['data']['works_materials'][referenceID]['Publisher_ID'] != '-1') {
                            bookInfo += '<div class="row">';
                            bookInfo += '<div class="five columns text-right">Publisher:</div>';
                            bookInfo += '<div class="seven columns">';

                            for(var j = 0; j < response['data']['materials_publishers'].length; j++) {
                                if(response['data']['materials_publishers'][j]['Publisher_ID'] == response['data']['works_materials'][referenceID]['Publisher_ID']) {
                                    bookInfo += response['data']['materials_publishers'][j]['Publisher_Name'];
                                }
                            }

                            bookInfo += '</div>';
                            bookInfo += '</div>';
                        }

                        bookInfo += '</div>';
                        bookInfo += '<div class="four columns text-center">';
                        bookInfo += '<div style="font-size: 3em;">';

                        reservedCount = 0;
                        loanedCount = 0;
                        materialCount = 0;

                        for(var k = 0; k < response['data']['reserved_materials'].length; k++) {
                            if(response['data']['reserved_materials'][k]['Material_ID'] == response['data']['works_materials'][referenceID]['Material_ID']) {
                                reservedCount++;
                            }
                        }

                        for(var l = 0; l < response['data']['loaned_materials'].length; l++) {
                            if(response['data']['loaned_materials'][l]['Material_ID'] == response['data']['works_materials'][referenceID]['Material_ID']) {
                                loanedCount++;
                            }
                        }

                        materialCount = response['data']['works_materials'][referenceID]['Material_Copies'] - reservedCount - loanedCount;

                        if(materialCount > 0) {
                            bookInfo += materialCount;
                        } else {
                            bookInfo += '0';
                        }

                        bookInfo += '</div>';
                        bookInfo += '<div>Available Cop';

                        if(response['data']['works_materials'][referenceID]['Material_Copies'] > 1) {
                            bookInfo += 'ies';
                        } else {
                            bookInfo += 'y';
                        }

                        bookInfo += '</div>';
                        bookInfo += '</div>';
                        bookInfo += '</div>';

                        setModalContent('Book Information', bookInfo, 'book-info-modal');
                        openModal(true, 'book-info-modal');

                        return false;
                    });

                    $('[data-button="loan-book-button"]').click(function() {
                        materialID = $(this).data('var-id');
                        materialTitle = $(this).data('var-title');

                        /*$('.modal#confirmation-modal > .modal-container > .modal-body').html('Are you sure you want to lend the book title <strong>' + materialTitle + '</strong> to <strong>' + usersName + '</strong>');
                        openModal(false, 'confirmation-modal');*/
                        openModal(false, 'loader-modal');

                        $.ajax({
                            url: '/search/accessions',
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                            data: {
                                materialID: $(this).data('var-id')
                            },
                            dataType: 'json',
                            success: function(response) {
                                var accessionInfo = '<table id="books-table" class="u-full-width">';

                                closeModal('loader-modal');

                                accessionInfo += '<thead>';
                                accessionInfo += '<tr>';
                                accessionInfo += '<th>Accession Number</th>';
                                accessionInfo += '<th>Status</th>';
                                accessionInfo += '<th></th>';
                                accessionInfo += '</tr>';
                                accessionInfo += '</thead>';
                                accessionInfo += '<tbody>';

                                for(var i = 0; i < response['data'].length; i++) {
                                    accessionInfo += '<tr>';
                                    accessionInfo += '<td>' + response['data'][i]['Accession_Number'] + '</td>';
                                    accessionInfo += '<td>' + response['data'][i]['Accession_Status'] + '</td>';
                                    accessionInfo += '<td><button class="btn btn-orange btn-sm" data-button="loan-accession-button" data-var-id="' + materialID + '" data-var-title="' + materialTitle + '" data-var-accession="' + response['data'][i]['Accession_Number'] + '">Loan</button></td>';
                                    accessionInfo += '</tr>';
                                }

                                accessionInfo += '</tbody>';
                                accessionInfo += '</table>';

                                setModalContent('Loan Book(s)', accessionInfo, 'accession-modal');
                                openModal(true, 'accession-modal');

                                $('[data-button="loan-accession-button"]').click(function() {
                                    if($('#pending-table').length == 0) {
                                        $('#pending-table-block').html('<form data-form="loan-list-form"><input type="hidden" name="borrowerID" value="' + usersID + '"><input type="hidden" name="borrowerName" value="' + usersName + '"><input type="hidden" name="arg0" value="1887a0a8a240d26489023340292501c0"><table id="pending-table" class="u-full-width"><thead><tr><th>Book Title</th><th>Accession Number</th><th>Date Borrowed</th><th></th></tr></thead><tbody></tbody></table><div><input type="submit" class="btn btn-orange" value="Print Loaned Books" disabled></div></form>');
                                    }

                                    if(addedMaterials.indexOf($(this).data('var-id')) == -1 && addedAccessions.indexOf($(this).data('var-accession')) == -1) {
                                        $('#pending-table tbody').append('<tr><td>' + $(this).data('var-title') + '</td><td>' + $(this).data('var-accession') + '</td><td>' + moment().format('MMMM D, YYYY') + '</td><td><input type="checkbox" name="accessionNumbers[]" value="' + $(this).data('var-accession') + '"></td></tr>');

                                        addedAccessions.push($(this).data('var-accession'));
                                        addedMaterials.push($(this).data('var-id'));
                                    }

                                    /*$.ajax({
                                        url: '/panel/loan',
                                        method: 'POST',
                                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                                        data: {
                                            arg0: '47e3a812a18f9ae64a8c3ac8b8cc78af',
                                            arg1: $(this).data('var-id'),
                                            arg2: $(this).data('var-accession'),
                                            arg3: usersID
                                        },
                                        dataType: 'json',
                                        success: function(response) {
                                            closeModal('loader-modal');
                                            setModalContent('Loan Book(s)', response['message'], 'done-modal');
                                            openModal(false, 'done-modal');

                                            setTimeout(function() {
                                                closeModal('done-modal');

                                                location.reload();
                                            }, 2000);
                                        }
                                    });

                                    return false;*/
                                });

                                console.log(response['data']);
                            },
                            error: function(arg0, arg1, arg2) {
                                console.log(arg0.responseText);
                            }
                        });

                        return false;
                    });
                });
            }
        });

        return false;
    });

    $('[data-button="yes-button"]').click(function() {
        closeModal('confirmation-modal');
        openModal(false, 'loader-modal');

        $.ajax({
            url: '/panel/loan',
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: {
                arg0: '0de0902f12c4b3d0843ecb8288240e96',
                arg1: materialID,
                arg2: usersID
            },
            dataType: 'json',
            success: function(response) {
                var tm = 2000;

                closeModal('loader-modal');
                setModalContent('Loan Book(s)', response['message'], 'done-modal');
                openModal(false, 'done-modal');

                if(response['status'] == 'Success') {
                    tm = 5000;
                } else {
                    tm = 2000;
                }

                setTimeout(function() {
                    location.reload();
                }, tm);
            },
            error: function(arg0, arg1, arg2) {
                console.log(arg0.responseText);
            }
        });

        return false;
    });

    $('[data-button="no-button"]').click(function() {
        closeModal('confirmation-modal');
    });

    $('body').on('change', 'input[name="accessionNumbers[]"]', function() {
        if($('input[name="accessionNumbers[]"]:checked').length > 0) {
            $('[data-form="loan-list-form"] input[type="submit"]').attr('disabled', false);
        }
    });

    $('body').on('submit', '[data-form="loan-list-form"]', function() {
        openModal(false, 'loader-modal');

        $.ajax({
            url: '/panel/loan',
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                closeModal('loader-modal');
                setModalContent('Loan Book(s)', response['message'] + '<div>If receipt did not open, <a target="_blank" href="/receipts/' + response['receipt'] + '">Click Here</a></div>', 'done-modal');
                openModal(true, 'done-modal');

                if(response['status'] == 'Success') {
                    window.open('/receipts/' + response['receipt']);

                    $('#done-modal.modal').click(function() {
                        if(isModalDismissableByClick) {
                            $(this).fadeOut(250);

                            location.reload();
                        }
                    });

                    $('#done-modal.modal>.modal-container').click(function(e) {
                        e.stopPropagation();
                    });
                }
            },
            error: function(arg0, arg1, arg2) {
                console.log(arg0.responseText)
            }
        });

        return false;
    });
});
$(document).ready(function() {
    $('[data-form="search-opac-form"]').submit(function() {
        openModal(false);

        $.ajax({
            url: '/search_opac',
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                var element = '<table id="materials-table" class="u-full-width">';
                var isFirst, reservedCount, loanedCount, materialCount, isReserved;

                element += '<thead>';
                element += '<tr>';
                element += '<th>Call Number</th>';
                element += '<th>Title</th>';
                element += '<th>ISBN</th>';
                element += '<th>Author(s)</th>';
                element += '<th>Available Copies</th>';
                element += '<th width="15%"></th>';
                element += '</tr>';
                element += '</thead>';
                element += '<tbody>';

                for(var i = 0; i < response['data']['works_materials'].length; i++) {
                    isFirst = true;

                    element += '<tr>';
                    element += '<td>' + response['data']['works_materials'][i]['Material_Call_Number'] + '</td>';
                    element += '<td>' + response['data']['works_materials'][i]['Material_Title'] + '</td>';
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

                    reservedCount = 0;
                    loanedCount = 0;

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

                    materialCount = response['data']['works_materials'][i]['Material_Copies'] - reservedCount - loanedCount;

                    if(materialCount > 0) {
                        element += materialCount;
                    } else {
                        element += '0';
                    }

                    element += '</td>';
                    element += '<td>';

                    if(issetUsername == true) {
                        isReserved = false;

                        for(var m = 0; m < response['data']['reservations'].length; m++) {
                            if(response['data']['reservations'][m]['Material_ID'] == response['data']['works_materials'][i]['Material_ID']) {
                                isReserved = true;

                                break;
                            }
                        }


                        if(isReserved) {
                            element += '<a class="btn btn-red btn-sm">Already Reserved</a>';
                        } else {
                            if(response['data']['on_reserved'] < response['data']['reservation_limit']) {
                                if(materialCount > 0) {
                                    element += '<a href="/opac/reserve/' + response['data']['works_materials'][i]['Material_ID'] + '" class="btn btn-orange btn-sm">Reserve</a>';
                                }
                            }
                        }
                    }

                    element += '</td>';
                    element += '</tr>';
                }

                element += '</tbody>';
                element += '</table>';

                $('#materials-table-block').html(element).promise().done(function() {
                    closeModal();
                    
                    $('#materials-table').dataTable({
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
$(document).ready(function() {
    $('#users-table').dataTable({
        initComplete : function() {
            var input = $('.dataTables_filter input').unbind();
            var self = this.api();
            var searchButton = $('<button class="btn-search">').text('Search').click(function() {
                self.search(input.val()).draw();
            });

            $('.dataTables_filter label').append(searchButton);
        },
        aoColumnDefs: [
            { bSearchable: false, bSortable: false, aTargets: [4] }
        ]
    });

    $('[data-form="users-confirmation-form"]').submit(function() {
        var thisForm = $(this);

        setModalLoader();
        openModal(false);

        $.ajax({
            url: '/search/username',
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: {
                username: $('input[name="userID"]').val()
            },
            dataType: 'json',
            success: function(response) {
                if(response['status'] == 'Success') {
                    setModalContent('Manage Users', 'User ID has already been used.');

                    setTimeout(function() {
                        closeModal();
                    }, 2000);
                } else {
                    $.ajax({
                        url: '/search/users',
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        data: {
                            first_name: $('input[name="userFirstName"]').val(),
                            last_name: $('input[name="userLastName"]').val()
                        },
                        dataType: 'json',
                        success: function(response) {
                            if(response['status'] == 'Success') {
                                setModalContent('Manage Users', 'Oops! A user with the same first and last name already exist. Do you still want to submit this form?<div class="text-right"><button class="btn btn-orange" data-button="yes-button">Yes</button>&nbsp;<button class="btn btn-red" data-button="no-button">No</button></div>');

                                $('[data-button="yes-button"]').click(function() {
                                    thisForm.off('submit');
                                    thisForm.submit();
                                });

                                $('[data-button="no-button"]').click(function() {
                                    closeModal();
                                });
                            } else {
                                thisForm.off('submit');
                                thisForm.submit();
                            }
                        }
                    });
                }
            }
        });

        return false;
    });
});
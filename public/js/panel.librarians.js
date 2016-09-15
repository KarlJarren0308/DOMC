$(document).ready(function() {
    $('#librarians-table').dataTable({
        initComplete : function() {
            var input = $('.dataTables_filter input').unbind();
            var self = this.api();
            var searchButton = $('<button class="btn-search">').text('Search').click(function() {
                self.search(input.val()).draw();
            });

            $('.dataTables_filter label').append(searchButton);
        },
        aoColumnDefs: [
            { bSearchable: false, bSortable: false, aTargets: [2] }
        ]
    });

    $('[data-form="librarians-confirmation-form"]').submit(function() {
        var thisForm = $(this);

        setModalLoader();
        openModal(false);

        $.ajax({
            url: '/search/librarians',
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: {
                first_name: $('input[name="librarianFirstName"]').val(),
                last_name: $('input[name="librarianLastName"]').val()
            },
            dataType: 'json',
            success: function(response) {
                if(response['status'] == 'Success') {
                    setModalContent('Manage Users', 'Oops! A librarian with the same first and last name already exist. Do you still want to submit this form?<div class="text-right"><button class="btn btn-orange" data-button="yes-button">Yes</button>&nbsp;<button class="btn btn-red" data-button="no-button">No</button></div>');

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

        return false;
    });
});
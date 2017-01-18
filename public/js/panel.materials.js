function getAuthorList(func) {
    $.ajax({
        url: '/search/authors',
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: {},
        dataType: 'json',
        success: func
    });
}

$(document).ready(function() {
    $('#materials-table').dataTable({
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

    $('[data-form="materials-confirmation-form"]').submit(function() {
        var thisForm = $(this);

        setModalLoader();
        openModal(false);

        $.ajax({
            url: '/search/materials',
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: {
                call_number: $('input[name="materialCallNumber"]').val(),
                title: $('input[name="materialTitle"]').val()
            },
            dataType: 'json',
            success: function(response) {
                if(response['status'] == 'Success') {
                    setModalContent('Manage Books', 'Oops! A book with the same call number and/or title already exist. Do you still want to submit this form?<div class="text-right"><button class="btn btn-orange" data-button="yes-button">Yes</button>&nbsp;<button class="btn btn-red" data-button="no-button">No</button></div>');

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

    $('[data-button="new-publisher-button"]').click(function() {
        setModalContent('Create New Publisher', '<form data-form="new-publisher-form"><div class="input-block"><label for="">Publisher\'s Name:</label><input type="text" class="u-full-width" name="publisherName" placeholder="Enter Publisher\'s Name Here" required></div><div class="input-block"><label for="">Contact Number:</label><input type="text" class="u-full-width" name="publisherContact" placeholder="Enter Contact Number Here"></div><div class="input-block text-right"><input type="submit" class="btn btn-orange" value="Create Publisher"></div></form>', 'new-modal');
        openModal(true, 'new-modal');

        $('[data-form="new-publisher-form"]:first *:input[type!=hidden]:first').focus();

        $('[data-form="new-publisher-form"]').submit(function() {
            var formSerialize = $(this).serialize();

            openModal(false, 'loader-modal');

            $.ajax({
                url: '/panel/manage/publishers/add',
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: formSerialize,
                dataType: 'json',
                success: function(response) {
                    closeModal('loader-modal');
                    setModalContent('Create New Publisher', '<h5 class="no-margin">' + response['message'] + '</h5>', 'new-modal');
                    openModal(false, 'new-modal');

                    $.ajax({
                        url: '/search/publishers',
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        data: {},
                        dataType: 'json',
                        success: function(response) {
                            var list = '<option value="" selected disabled>Select a publisher...</option>';
                            var name = '';
                            var myThis = $('.publisher-dropdown');

                            for(var i = 0; i < response['data']['publishers'].length; i++) {
                                list += '<option value="' + response['data']['publishers'][i]['Publisher_ID'] + '">' + response['data']['publishers'][i]['Publisher_Name'] + '</option>';
                            }

                            myThis.html(list);

                            setTimeout(function() {
                                closeModal('new-modal');
                            }, 2000);
                        }
                    });

                    return false;
                }
            });

            return false;
        });

        return false;
    });

    $('[data-button="new-author-button"]').click(function() {
        setModalContent('Create New Author', '<form data-form="new-author-form"><div class="row"><div class="four columns"><div class="input-block"><label for="">Author\'s First Name:</label><input type="text" class="u-full-width" name="authorFirstName" placeholder="Author\'s First Name" required></div></div><div class="four columns"><div class="input-block"><label for="">Author\'s Middle Name:</label><input type="text" class="u-full-width" name="authorMiddleName" placeholder="Author\'s Middle Name"></div></div><div class="four columns"><div class="input-block"><label for="">Author\'s Last Name:</label><input type="text" class="u-full-width" name="authorLastName" placeholder="Author\'s Last Name" required></div></div></div><div class="input-block text-right"><input type="submit" class="btn btn-orange" value="Create Author"></div></form>', 'new-modal');
        openModal(true, 'new-modal');

        $('[data-form="new-author-form"]:first *:input[type!=hidden]:first').focus();

        $('[data-form="new-author-form"]').submit(function() {
            var formSerialize = $(this).serialize();

            openModal(false, 'loader-modal');

            $.ajax({
                url: '/panel/manage/authors/add',
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: formSerialize,
                dataType: 'json',
                success: function(response) {
                    closeModal('loader-modal');
                    setModalContent('Create New Author', '<h5 class="no-margin">' + response['message'] + '</h5>', 'new-modal');
                    openModal(false, 'new-modal');

                    $.ajax({
                        url: '/search/authors',
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        data: {},
                        dataType: 'json',
                        success: function(response) {
                            var list = '<option value="" selected disabled>Select an author...</option>';
                            var name = '';
                            var myThis = $('.author-dropdown');

                            for(var i = 0; i < response['data']['authors'].length; i++) {
                                if(response['data']['authors'][i]['Author_Middle_Name'].length > 1) {
                                    name = response['data']['authors'][i]['Author_First_Name'] + ' ' + response['data']['authors'][i]['Author_Middle_Name'].substr(0, 1) + '. ' + response['data']['authors'][i]['Author_Last_Name'];
                                } else {
                                    name = response['data']['authors'][i]['Author_First_Name'] + ' ' + response['data']['authors'][i]['Author_Last_Name'];
                                }

                                list += '<option value="' + response['data']['authors'][i]['Author_ID'] + '">' + name + '</option>';
                            }

                            myThis.html(list);

                            setTimeout(function() {
                                closeModal('new-modal');
                            }, 2000);
                        }
                    });

                    return false;
                }
            });

            return false;
        });

        return false;
    });

    $('[data-button="add-author-button"]').click(function() {
        $.ajax({
            url: '/search/authors',
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: {},
            dataType: 'json',
            success: function(response) {
                var list = '<div class="input-block"><div class="u-three-four-width"><select name="authors[]" class="author-dropdown u-full-width"><option value="" selected disabled>Select an author...</option>';
                var name = '';

                for(var i = 0; i < response['data']['authors'].length; i++) {
                    if(response['data']['authors'][i]['Author_Middle_Name'].length > 1) {
                        name = response['data']['authors'][i]['Author_First_Name'] + ' ' + response['data']['authors'][i]['Author_Middle_Name'].substr(0, 1) + '. ' + response['data']['authors'][i]['Author_Last_Name'];
                    } else {
                        name = response['data']['authors'][i]['Author_First_Name'] + ' ' + response['data']['authors'][i]['Author_Last_Name'];
                    }

                    list += '<option value="' + response['data']['authors'][i]['Author_ID'] + '">' + name + '</option>';
                }

                list += '</select></div><div class="u-one-four-width text-center"><a class="link link-sm" data-button="remove-author-button">Remove</a></div></div>';

                $('#authors-block').append(list);
            }
        });

        return false;
    });

    $(document).on('click', '[data-button="remove-author-button"]', function() {
        $(this).parent().parent().remove();
    });

    /*$(document).on('click', '.author-dropdown', function() {
        var myThis = $(this);

        $.ajax({
            url: '/search/authors',
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: {},
            dataType: 'json',
            success: function(response) {
                var list = '<option value="" selected disabled>Select an author...</option>';
                var name = '';

                for(var i = 0; i < response['data']['authors'].length; i++) {
                    if(response['data']['authors'][i]['Author_Middle_Name'].length > 1) {
                        name = response['data']['authors'][i]['Author_First_Name'] + ' ' + response['data']['authors'][i]['Author_Middle_Name'].substr(0, 1) + '. ' + response['data']['authors'][i]['Author_Last_Name'];
                    } else {
                        name = response['data']['authors'][i]['Author_First_Name'] + ' ' + response['data']['authors'][i]['Author_Last_Name'];
                    }

                    list += '<option value="' + response['data']['authors'][i]['Author_ID'] + '">' + name + '</option>';
                }

                myThis.html(list);
            }
        });

        return false;
    });*/
});

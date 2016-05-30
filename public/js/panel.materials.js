$(document).ready(function() {
    $('#materials-table').dataTable({
        aoColumnDefs: [
            { bSearchable: false, bSortable: false, aTargets: [3] }
        ]
    });

    $('[data-button="add-author-button"]').click(function() {
        var list = '<div class="input-block"><div class="u-three-four-width"><select name="authors[]" class="u-full-width"><option value="" selected disabled>Select an author...</option>';
        var name = '';
        var parsedAuthorsList = JSON.parse(atob(authorsList));

        for(var i = 0; i < parsedAuthorsList.length; i++) {
            if(parsedAuthorsList[i]['Author_Middle_Name'].length > 1) {
                name = parsedAuthorsList[i]['Author_First_Name'] + ' ' + substr(parsedAuthorsList[i]['Author_Middle_Name'], 0, 1) + '. ' + parsedAuthorsList[i]['Author_Last_Name'];
            } else {
                name = parsedAuthorsList[i]['Author_First_Name'] + ' ' + parsedAuthorsList[i]['Author_Last_Name'];
            }

            list += '<option value="' + parsedAuthorsList[i]['Author_ID'] + '">' + name + '</option>';
        }

        list += '</select></div><div class="u-one-four-width text-center"><a class="link link-sm" data-button="remove-author-button">Remove</a></div></div>';

        $('#authors-block').append(list);

        return false
    });

    $(document).on('click', '[data-button="remove-author-button"]', function() {
        $(this).parent().parent().remove();
    });
});
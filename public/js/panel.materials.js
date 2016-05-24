$(document).ready(function() {
    $('#materials-table').dataTable({
        aoColumnDefs: [
            { bSearchable: false, bSortable: false, aTargets: [3] }
        ]
    });

    $('[data-button="add-author-button"]').click(function() {
        var list = '<div class="input-block"><select name="authors[]" class="u-full-width"><option value="" selected disabled>Select an author...</option>';
        var name = '';

        authorsList = JSON.parse(atob(authorsList));

        for(var i = 0; i < authorsList.length; i++) {
            if(authorsList[i]['Author_Middle_Name'].length > 1) {
                name = authorsList[i]['Author_First_Name'] + ' ' + substr(authorsList[i]['Author_Middle_Name'], 0, 1) + '. ' + authorsList[i]['Author_Last_Name'];
            } else {
                name = authorsList[i]['Author_First_Name'] + ' ' + authorsList[i]['Author_Last_Name'];
            }

            list += '<option value="' + authorsList[i]['Author_ID'] + '">' + name + '</option>';
        }

        list += '</select></div>';

        $('#authors-block').append(list);

        return false;
    });
});
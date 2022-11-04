$(document).ready(function () {

    $(".book_edit").click(function () {
        toggleReadonly($(this).attr('bookid'))
    })

    $(".book_save").click(function () {
        saveBook($(this).attr('bookid'))
    })

    $(".book_delete").click(function () {
        deleteBook($(this).attr('bookid'))
    })

});

function toggleReadonly(bookId, saved) {
    if (arguments.length < 1) {
        //ReadOnly all strings
        bookId = 0;
    }
    if (arguments.length < 2) {
        saved = false;
    }

    let editButtonSelector;
    let saveButtonSelector;

    let arrayIds = [];
    $('form[id^="formForInline_"]').each(function (index) {
        if ($(this).attr('id') != "formForInline_" + bookId) {
            arrayIds.push($(this).attr('id').replace("formForInline_", ""))
        }
    });
    arrayIds.map(id => {

        editButtonSelector = '.book_edit[bookid="' + id + '"]';
        saveButtonSelector = '.book_save[bookid="' + id + '"]';

        if ($(editButtonSelector).hasClass('editing')) {
            $("#formForInline_" + id).children("input").attr('readonly', true);
            $(editButtonSelector).html('Edit inline').removeClass('editing');
            $(saveButtonSelector).hide();

            restoreOldValues(id);
        }
    })
document.querySelector
    editButtonSelector = '.book_edit[bookid="' + bookId + '"]';
    saveButtonSelector = '.book_save[bookid="' + bookId + '"]';

    if ($(editButtonSelector).hasClass('editing')) {
        $("#formForInline_" + bookId).children("input").attr('readonly', true);
        $(saveButtonSelector).hide();
        $(editButtonSelector).html('Edit inline').removeClass('editing');
        if (saved == false) {
            restoreOldValues(bookId);
        }
    } else {
        $("#formForInline_" + bookId).children("input").attr('readonly', false);
        $(saveButtonSelector).show();
        $(editButtonSelector).html('Cancel').addClass('editing');

        //save old values
        $("#formForInline_" + bookId).children('input').each((value, obj) => $(obj).attr('oldvalue', $(obj).val()));
    }
}

function saveBook(id) {

    //get data form form
    let postData = {};
    $("#formForInline_" + id).children('input').each((value, obj) => postData[$(obj).attr('name')] = $(obj).val());
    const jsonPostData = JSON.stringify(postData);

    $.ajax({
        url: "/public/index.php/api/book/update/" + id,
        dataType: "json",
        type: "PUT",
        async: true,
        data: jsonPostData,
        success: function (data) {
            toggleReadonly(id, true);
        },
        error: function (xhr, exception) {
            toggleReadonly(id);
        }
    });
}

function deleteBook(id) {
    toggleReadonly();
    $.ajax({
        url: "/public/index.php/api/book/delete/" + id,
        dataType: "json",
        type: "DELETE",
        async: true,
        data: {},
        success: function (data) {
            $("#formForInline_" + id).remove();
        },
        error: function (xhr, exception) {
        }
    });

}

function restoreOldValues(positionId) {
    $("#formForInline_" + positionId).children('input').each((value, obj) => {
        if ($(obj).attr('oldvalue')) {
            $(obj).val($(obj).attr('oldvalue')).attr('oldvalue', '');
        }
    });
}

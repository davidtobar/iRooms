$(function() {

    /*Modal Calendar*/
    $(".add-m").on("click", function(e) {
        e.preventDefault();
        $("#createMeeting .date-f").text($(this).attr('data-date'));
    });

    $('.tcell .cell-inner .holder').each(function() {
            if($(this).text() == ''){
                $(this).append('<h1 class="empty">0</h1>');
            }
    });

});
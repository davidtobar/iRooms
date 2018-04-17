$(function() {

    /*Modal Calendar*/
    $(".add-m").on("click", function(e) {
        e.preventDefault();
        var cell = $(this).parent(".tcell").attr('data-day');
        var data = moment(cell).format("dddd, MMMM D YYYY");

        $("#createMeeting .date-f").text(data);
    });

    $('.tcell .cell-inner .holder').each(function() {
            if($(this).text() == ''){
                $(this).append('<h1 class="empty">0</h1>');
            }
    });

});
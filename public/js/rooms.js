$(function() {

   $("#roomColor").spectrum({
       color: "rgb(115, 181, 35)",
       preferredFormat: "rgb",
       showInput: true,
       showPalette: true,
       palette: [["rgb(255, 0, 0)", "rgba(0, 255, 0, .5)", "rgb(0, 0, 255)"]]
   });

   $("#roomC").spectrum({
       preferredFormat: "rgb",
       showInput: true
   });

   /*Spinner*/
   $('.spinner .btn:first-of-type').on('click', function() {
      var btn = $(this);
      var input = btn.closest('.spinner').find('input');
      if (input.attr('max') == undefined || parseInt(input.val()) < parseInt(input.attr('max'))) {    
        input.val(parseInt(input.val(), 10) + 1);
      } else {
        btn.next("disabled", true);
      }
    });
    $('.spinner .btn:last-of-type').on('click', function() {
      var btn = $(this);
      var input = btn.closest('.spinner').find('input');
      if (input.attr('min') == undefined || parseInt(input.val()) > parseInt(input.attr('min'))) {    
        input.val(parseInt(input.val(), 10) - 1);
      } else {
        btn.prev("disabled", true);
      }
    });

    /*Modal*/
    var requiredCheckboxes = $('.modal-rooms .sec-radio :checkbox[required]');
      requiredCheckboxes.change(function(){
      if(requiredCheckboxes.is(':checked')) {
        requiredCheckboxes.removeAttr('required');
      }
      else {
        requiredCheckboxes.attr('required', 'required');
      }
    });

    /*Pagination*/
    $(".pagination li").each(function() {
      if ($(this).children().attr('rel') == "next" || $(this).children().text() == '»') {
        var next = $(this).children();
        next.html('<i class="mdi mdi-chevron-right"></i>');
      } else if ($(this).children().attr('rel') == "prev" || $(this).children().text() == '«') {
          var prev = $(this).children();
          prev.html('<i class="mdi mdi-chevron-left"></i>');
      }
            
    });

    /*hidden modal*/
    $("#editRoom").on("hidden.bs.modal", function(){
      $('#editRoom .meet').val("");
      $("#editRoom .modal-rooms .sec-radio :checkbox:checked").removeAttr("checked")
      $("#editRoom #opening_time option").removeAttr("selected");
      $("#editRoom #closing_time option").removeAttr("selected");
      $("#editRoom #status option").removeAttr("selected");
    });

    $("#editResource").on("hidden.bs.modal", function(){
      $('#editResource .meet').val("");
      $("#editResource .btn-ic.active").removeClass("active");
      $("#editResource #status option").removeAttr("selected");
    });

    $("#editUser").on("hidden.bs.modal", function(){
      $('#editUsers .meet').val("");
      $("#editUsers #user_role option").removeAttr("selected");
      $("#editUsers #user_status option").removeAttr("selected");
    });

});
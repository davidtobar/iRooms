@extends("core.user_dashboard") 

<!-- Page variables -->
@section("pageTitle", "Home")

<!-- Styles -->
@section("styles")
<link rel="stylesheet" href="{{ url('css/dashboard.css') }}">
@endsection

@section('content')

    <div class="inner-content-header">
        <div class="col-md-6">
            <h2>Month Schedule</h2>
            <p>You have <span><b>{{ $dataMeet }}</b> meetings</span> this month.</p>
        </div>
        <div class="col-md-6 quick-book">
            <a href="#" class="btn btn-success" data-toggle="modal" data-target="#createMeeting"><i class="mdi mdi-plus"></i> Quick Book</a>
        </div>
    </div>
    <div class="inner-content-body">
      <div class="row">
        <div class="col-md-12">
          <div id="calendar_month" class="full-height">
            <div class="section-rooms">
              <div class="total-rooms">
                <div class="list-rooms">
                  <p id="count">{{ $countRooms }}</p>
                  <p>Rooms</p>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
@endsection

@section('modal')
  @include('core.pages.schedule.user.create_meeting')
  @include('core.pages.search_results')
@endsection
<!-- Scripts -->
@section("scripts")

  <script type="text/javascript">
    $(document).ready(function () {
          $('#timepicker1').timepicker({
            showMeridian: false,
            showSeconds: true,

          });

          $('#timepicker2').timepicker({
            showMeridian: false,
            showSeconds: true,
          });

    });
  </script> 

  <script>
      $(function () {

        function sendMessage(type, message) {
          swal({ text: message, type: type }).then(function() {
                location.reload();
            },
            function (dismiss) {
                location.reload();
            });
        }


        // $("#quick-advanced").on('click', function(){
        //   var date_f = $("#date-format").text(); 

        //   console.log(date_f);

        //   $.get("{{ route('user-advanced-booking') }}?final=" + date_f,function(data) {
              
        //     });

        // });

        /*Create*/
        $("#createMeetings").on("submit", function (event) {
          event.preventDefault();

          var meeting_name = $('input[name="meeting_name"]').val();
          var meeting_date = $(".date-f").text();
          var meeting_room = $('#room option:selected').val();
          var meeting_layout = $('#layouts option:first').attr('selected','selected').val();
          var meeting_start_time = $('#start_time option:selected').val();
          var meeting_end_time = $('#end_time option:selected').val();
          var meeting_description = $('textarea[name="description"]').val();

          if (meeting_name) {
            axios.post("{{ route('user-meetings-create-post') }}", {
              name: meeting_name,
              date: meeting_date,
              room: meeting_room,       
              layout: meeting_layout,       
              start_time: meeting_start_time,
              end_time: meeting_end_time,
              description: meeting_description
          }).then(function (resp) {
              var data = resp.data.data;
              $('.meet').val("");
              sendMessage("success", "Meeting created");
          }).catch(function (err) {
              sendMessage("error", "An error has occurred, please verify all fields are complete and try again");
          });

          }

        });

        
        /*Auto*/
        $('#createMeeting').on('show.bs.modal', function (e) {
          
          $('#room').trigger("change");

          var date_f = $("#date-format").text();
          var final_date = moment(date_f).format("YYYY-MM-DD");
          $("#createMeeting .btn-advanced").attr("href","{{route('user-advanced-booking')}}?date="+final_date);
        });

        $('#createMeeting').on('hidden.bs.modal', function () {
            $('.section-danger').css("display","none");
            $("#createMeeting .btn-advanced").css("display", "block");
        });

        $('#room').on('change', function(e){
          
          $('#createMeeting').on('hidden.bs.modal', function () {
              // location.reload();
              $("#room option:first").attr("selected","selected");
              $("#room").val($("#room option:first").val());
          });

          var room_id = e.target.value;
          $.get("{{ route('user-layouts') }}?room_id=" + room_id,function(data) {
            $('#layouts').empty();

            $.each(data, function(index, roomsObj){
              $('#layouts').append('<option value="'+ roomsObj.layout_id +'">'+ roomsObj.layout_id +'</option>');
                  });
          });

          var $start = $('#date-format').text() || null;
          var $room = $('#room option:selected').val() || null;

          function hours(){

            console.log("hours");

            $.ajax({
              type: "post",
              url: "{{ route('user-hours') }}",
              headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              data:{
                room_id: room_id
              },
              success: function(data) {
                $('#start_time').html('');
                $('#end_time').html('');

                $.each(data, function(index, timeObj){
                  var time = moment(timeObj.hours, ["HH:mm"]).format("hh:mm A");
                  $('#start_time').append('<option value="'+ timeObj.hours +'">'+ time +'</option>');
                  $('#end_time').append('<option value="'+ timeObj.hours +'">'+ time +'</option>');
                });

                $('#start_time option:last-child').addClass("hidden");
                // $('#start_time option:last-child').attr("disabled",true);
                $('#end_time option:first-child').addClass("hidden");
                $('#start_time').val("");
                $('#end_time').val("");

                /*Hours*/

                if ($start && $room) {
                  firstOption();
                }

              }
            });

          }
          
          /*Hours*/
          
          function firstOption() {
            console.log("first");
            $.ajax({
              type: "post",
              url: "{{ route('user-first-create-option') }}",
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              data:{
                start: $start,
                room: $room
              },
              success: function(data) {
                $.each(data, function(index, timeObj){

                  var s_time = timeObj.start_time;

                  $("#start_time option").each(function() {
                    var time_val = $(this).val();
                    if(time_val >= s_time){
                      $(this).attr('disabled', 'disabled');
                      $('#start_time').val("");
                    }
                  });

                  $("#end_time option").each(function() {
                    var time_val = $(this).val();
                    if(time_val >= s_time){
                      $(this).attr('disabled', 'disabled');
                      $('#end_time').val("");
                    }
                  });

                });

                lastOption();

              }
            });
          }

          function lastOption() {
            console.log("last");
            $.ajax({
              type: "post",
              url: "{{ route('user-last-create-option') }}",
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              data:{
                start: $start,
                room: $room
              },
              success: function(data) {
                $.each(data, function(index, timeObj){
                  var e_time = timeObj.end_time;
                  $("#start_time option").each(function() {
                    var time_val = $(this).val();
                    if(time_val < e_time){
                      $(this).attr('disabled', 'disabled');
                      $('#start_time').val("");
                    }
                  });

                  $("#end_time option").each(function() {
                    var time_val = $(this).val();
                    if(time_val < e_time){
                     $(this).attr('disabled', 'disabled');
                     $('#end_time').val("");
                    }
                  });

                });

                sameOptions();

              }
            });
          }

          function sameOptions() {
            $.ajax({
              type: "post",
              url: "{{ route('user-same-options') }}",
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              data:{
                start: $start,
                room: $room
              },
              success: function(data) {
                $.each(data, function(index, timeObj){
                  var s_time = timeObj.start_time;
                  var e_time = timeObj.end_time;

                  $("#start_time option").each(function() {
                    var time_val = $(this).val();
                    if(time_val >= s_time && time_val < e_time){
                      $(this).attr('disabled', 'disabled');
                      $('#start_time').val("");
                    }
                  });

                  $("#end_time option").each(function() {
                    var time_val = $(this).val();
                    if(time_val >= s_time && time_val < e_time){
                      $(this).attr('disabled', 'disabled');
                      // $('#end_time').val("");
                    }
                  });

                });

                $('#start_time option:not([disabled]):first').attr("selected","true");

                if ($('#start_time option:not([disabled])').length == 1) {

                  $('#start_time').val('');
                  $('#start_time').attr("disabled",true);
                  $('#end_time').attr("disabled",true);
                  $('.section-danger').css("display","block");
                  $("#createMeeting .btn-success").attr("disabled",true);
                  $("#createMeeting .btn-advanced").attr("disabled",true);
                  $("#createMeeting .btn-advanced").css("display", "none");
                  $('#start_time').val('');
                  // $("#createMeeting .btn-advanced").removeAttr("href");
                }else{
                  $('#start_time').removeAttr("disabled");
                  $('#end_time').removeAttr("disabled");
                  $('.section-danger').css("display","none");

                  $("#createMeeting .btn-success").removeAttr("disabled");
                  $("#createMeeting .btn-advanced").removeAttr("disabled");
                  $("#createMeeting .btn-advanced").css("display", "block");
                  // $("#createMeeting .btn-advanced").attr("href","{{route('user-advanced-booking')}}");
                }

                /*Function*/
                $('#end_time').val("");

                  var start_option = $('#start_time').val();
                  var start_i = $('#start_time option:selected')[0].index;
                  
                  var enabled = [];
                  var array = [];
                  var aux;
                  var aux2 = 0;

                  $("#end_time option").each(function(e, index) {
                    var time_val = $(this).val();
                  if(time_val <= start_option){
                    $(this).addClass("hidden");    
                  }else{
                    $(this).removeClass("hidden");
                  }

                    
                  if (start_i == e) {

                    for (var i = start_i; i < $('#end_time option').length; i++) {  
                      if ($("#end_time option")[i].disabled && aux2 <= 0) {
                        aux = $("#end_time option")[i+1].index;
                        aux2+=1;
                      }
                    }


                    for (var j = start_i; j < $("#end_time option").length; j++) {
                      if(aux <= $("#end_time option")[j].index){
                        array.push($("#end_time option")[j]);
                      } else {
                        enabled.push($("#end_time option")[j]);
                      }
                    }

                    $("#end_time").html("");
                    $("#end_time").append(enabled);
                    $('#end_time').val("");

                  }

                });

                $('#end_time option').removeAttr('disabled');
                $('#end_time option').each(function () {
                  if ($(this).css('display') != 'none') {
                      $(this).prop("selected", true);
                      return false;
                  }
                });

              }
            });

          }

          /*principal*/
          hours();
              
        });

        var all_f = [];
        // var ctn = 0;
        $('#start_time').on('change', function(){
            $('#end_time').val("");
            var c_start = $("#start_time option").clone();

            // if (ctn <= 0) {
            //   $('#end_time').html("");
            //   $('#end_time').append(c_start);
            //   ctn += 1;
            // }

          $('#end_time').html("");
          $('#end_time').append(c_start);
          $('#end_time').append(all_f);

          $('#end_time').val("");

          var start_option = $(this).val();
          var start_i = $(this).find("option:selected")[0].index;
          
          var enabled = [];
          var array = [];
          var aux;
          var aux2 = 0;

          $("#end_time option").each(function(e, index) {
            var time_val = $(this).val();
            if(time_val <= start_option){
              $(this).addClass("hidden");      
            }else{
              $(this).removeClass("hidden");
            }
            
            if (start_i == e) {
              for (var i = start_i; i < $("#start_time option").length; i++) {    
                all_f.push($("#end_time option"));
                if ($("#start_time option")[i].disabled && aux2 <= 0) {
                  aux = $("#start_time option")[i+1].index;
                  aux2+=1;
                }
              }

              for (var j = start_i; j < $("#start_time option").length; j++) {
                if(aux <= $("#start_time option")[j].index){
                  array.push($("#end_time option")[j]);
                } else {
                  enabled.push($("#end_time option")[j]);
                }
              }

              $("#end_time").html("");
              $("#end_time").append(enabled);
              $('#end_time').val("");

            }

          });
          
          $('#end_time option').removeAttr('disabled');
          $('#end_time option').each(function () {
            if ($(this).css('display') != 'none') {
                $(this).prop("selected", true);
                return false;
            }
          });

        });

        /*Calendar*/
        var selectedEvent;
        $('#calendar_month').pagescalendar({

          events: [
            @foreach($meetings as $meeting)
              {
                id: '{{$meeting->id}}',
                title: '{{$meeting->meeting_name}}',
                class: '{{$meeting->color}}',
                start: '{{$meeting->start_date}}T{{$meeting->start_time}}',
                end: '{{$meeting->end_date}}T{{$meeting->end_time}}',
                room: '{{$meeting->room_name}}',
                description: '{{$meeting->description}}',
                user: '{{$meeting->user_name}}',
                user_id: '{{$meeting->user_id}}',
                status: '{{$meeting->status}}',
                role: '{{$meeting->role}}',
                auth: '{{ Auth::user()->id }}',
                other: {}
              },
            @endforeach
          ],
          view:"month"
        });

        /*Total Meetings*/
        $(".calendar .calendar-header .years .year").on("click", function(e){

          var month = $('#currentDate').attr('data-m');
          var year = e.target.attributes[2].value;

          $.ajax({
            type: "post",
            url: "{{ route('user-total') }}",
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data:{
              month: month,
              year: year
            },
            success: function(data) {
              $(".inner-content-header span b").text(data);
            }
          });

        });


        $(".calendar .options .months .month > a").on("click", function(e){
          var month = e.target.attributes[2].value;
          var year = $('#currentDate').attr('data-y');

          $.ajax({
            type: "post",
            url: "{{ route('user-total') }}",
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data:{
              month: month,
              year: year
            },
            success: function(data) {
              $(".inner-content-header span b").text(data);
            }
          });

        });

        $("[data-toggle=popover]").popover();


    });
  </script>

  <script src="{{ url('js/monthly-view.js') }}"></script>
@endsection

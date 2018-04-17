<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="icon" type="image/png" href="{{$login->favicon}}" />

  <title>Week Schedule :: {{$login->app_name}}</title>

  <!-- Styles -->
  <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('css/AdminLTE.css') }}" rel="stylesheet">
  <link href="{{ asset('css/jquery-ui.css') }}" rel="stylesheet">
  <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
  <link href="{{ asset('css/materialdesignicons.css') }}" rel="stylesheet">
  <link href="{{ asset('css/bootstrap-timepicker.min.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="{{ url('css/bootstrap-datepicker.min.css') }}">
  <link rel="stylesheet" href="{{ url('css/spectrum.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.11.4/sweetalert2.min.css" >
  <link href="https://fonts.googleapis.com/css?family=Heebo:300,400,500,700,800,900" rel="stylesheet">
  
  <link rel="stylesheet" href="{{ url('css/dashboard.css') }}">

</head>
<body class="hold-transition skin-blue sidebar-mini {{ Session::get('sidebarState') }}">
  <div class="wrapper">
    @include("components.navigation.header")
    @include("components.navigation.sidebar")

    <div class="content-wrapper section-w">

      <div class="inner-content-header">
          <div class="col-md-6">
              <h2>Week Schedule</h2>
              <p>You have <span><b></b> meetings</span> this week.</p>
          </div>
          <div class="col-md-6 quick-book">
              <a href="#" class="btn btn-success" data-toggle="modal" data-target="#createMeeting"><i class="mdi mdi-plus"></i> Quick Book</a>
          </div>
      </div>
      <div class="inner-content-body">
        <div class="row">
          <div class="col-md-12">
            <div id="calendar_week" class="full-height">
              <div class="section-rooms" id="room-week">
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

    </div>

    @include('core.pages.schedule.create_meeting')
    @include('core.pages.search_results')
  </div>
  
  <script src="{{ asset('js/jquery.min.js') }}"></script>
  <script src="{{ asset('js/bootstrap.min.js') }}"></script>
  <script src="{{ asset('js/adminlte.min.js') }}"></script>
  <script src="{{ asset('js/jquery-ui.js') }}"></script>
  <script src="{{ asset('js/bootstrap-timepicker.min.js') }}"></script>
    <script src="{{ url('js/bootstrap-datepicker.min.js') }}"></script>

  <script src="{{ url('js/app-dashboard.js') }}"></script>
  <script src="{{ url('js/app-custom-dashboard.js') }}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.17.0/axios.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.11.4/sweetalert2.min.js"></script>
  <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>

  <script src="{{ url('js/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>
  <script src="{{ url('js/interactjs/interact.min.js') }}"></script>
  <script src="{{ url('js/moment/moment-with-locales.min.js') }}"></script>

  <script type="text/javascript">
    var baseURL = {!! json_encode(url('/')) !!};
  </script>
  <script src="{{ url('js/pages.calendar.js') }}"></script>
  <!-- <script src="{{ url('js/calendar_month.js') }}"></script> -->
  <script src="{{ url('js/spectrum.js') }}"></script>

  <!-- Scripts -->

  <script>
   $('#sidebarToggle').on('click', function(e) {
       $.ajax({
           type: "post",
           url: "{{ url('savestate') }}",
           headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
           }
       });
   });
  </script>

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
              axios.post("{{ route('meetings-create-post') }}", {
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

            /*Validate initial*/
            if ($("#room option").length == 0) {
              $('.section-r').append("<div class='form-group col-md-12 section-danger' style='display: block !important;'><div class='alert alert-danger'>You must create a room to create new meetings, through this link: <a href='{{ route('room-list') }}' class='room-link'>Create new room</a></div></div>");
              $('#room').attr("disabled",true);
              $('#start_time').attr("disabled",true);
              $('#end_time').attr("disabled",true);
              $("#createMeeting .btn-advanced").css("display", "none");
              $("#createMeeting .btn-success").attr("disabled",true);
            }else{
              $('.section-r .section-danger').remove();
              $('#room').removeAttr("disabled");
              $('#start_time').removeAttr("disabled");
              $('#end_time').removeAttr("disabled");
              $("#createMeeting .btn-advanced").css("display", "block");
              $("#createMeeting .btn-success").removeAttr("disabled");
            }
            
            $('#room').trigger("change");

            var date_f = $("#date-format").text();
            var final_date = moment(date_f).format("YYYY-MM-DD");
            $("#createMeeting .btn-advanced").attr("href","{{route('advanced-booking')}}?date="+final_date);
          });

          $('#createMeeting').on('hidden.bs.modal', function () {
              // location.reload();
              $('.section-danger').css("display","none");
              $('.section-r .section-danger').remove();
              $("#createMeeting .btn-advanced").css("display", "block");
              $("#createMeeting .btn-success").removeAttr("disabled");
              
              $("#room option").attr("selected",false);
              $("#room option:first").attr("selected","selected");
              $("#room").val($("#room option:first").val());
          });

          
          $('#room').on('change', function(e){

            var room_id = e.target.value;
            var room_f = $(this);

            $.get("{{ route('layouts') }}?room_id=" + room_id,function(data) {
              $('#layouts').empty();

              $.each(data, function(index, roomsObj){
                $('#layouts').append('<option value="'+ roomsObj.layout_id +'">'+ roomsObj.layout_id +'</option>');
                    });
            });

            var $start = $('#date-format').text() || null;
            var $room = $('#room option:selected').val() || null;

            function hours(){

              $.ajax({
                type: "post",
                url: "{{ route('hours') }}",
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
              $.ajax({
                type: "post",
                url: "{{ route('first-create-option') }}",
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
              $.ajax({
                type: "post",
                url: "{{ route('last-create-option') }}",
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
                url: "{{ route('same-options') }}",
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
                    $("#createMeeting .btn-advanced").css("display","block");
                    // $("#createMeeting .btn-advanced").attr("href","{{route('advanced-booking')}}");
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
          $('#calendar_week').pagescalendar({

            events: [
              @foreach($meetings as $meeting)
                {
                  id: '{{$meeting->id}}',
                  title: '{{$meeting->meeting_name}}',
                  class: '{{$meeting->color}}',
                  start: '{{$meeting->start_date}}T{{$meeting->start_time}}',
                  end: '{{$meeting->end_date}}T{{$meeting->end_time}}',
                  room: '{{$meeting->room_name}}',
                  roomId: '{{$meeting->room}}',
                  description: '{{$meeting->description}}',
                  user: '{{$meeting->user_name}}',
                  status: '{{$meeting->status}}'
                },
              @endforeach
            ],
            resources: [
            @foreach($rooms_week as $rooms)
              { 
                id: '{{$rooms->id}}', 
                title: '{{$rooms->name}}',
                color: '{{$rooms->color}}',
                capacity: '{{$rooms->capacity}}',
                opening: '{{$rooms->opening_time}}',
                closing: '{{$rooms->closing_time}}'
              },
            @endforeach
            ],
            view:"week"
          });

          
          $("[data-toggle=popover]").popover();


      });
    </script>

    <script src="{{ url('js/week-view.js') }}"></script>

    <script>
      $(function () {

        /*Search*/
        var src = "{{ route('searchajax') }}";
        $("#search_text").autocomplete({
            source: function(request, response) {
              $.ajax({
                  type: "post",
                  url: src,
                  headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  },
                  dataType: "json",
                  data: {
                    term : request.term
                  },
                  success: function(data) {
                    response(data);

                    $(".ui-menu .ui-menu-item:first-child").addClass("selected");
                    
                    $(".ui-menu .ui-menu-item-wrapper").on("click", function(e){
                      $(".ui-menu .ui-menu-item:first-child").removeClass("selected");
                      $(e.target).parent('li').addClass('selected');
                    });

                  }
              });
            },
            // focus: function (event, ui) {
            //     $("#search_text").val(ui.item.label);
            //     $("#project-i").val(ui.item.id);
            //     return false;
            // },
            select: function (event, ui) {
                $("#search_text").val(ui.item.value);                   

                var meet_id = ui.item.id;
                var meet_start = ui.item.start_date;

                $.ajax({
                    type: "post",
                    url: "{{ route('searchdata') }}",
                    headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                      id: meet_id,
                      start: meet_start
                    },
                    success: function(data) {

                      if (data.length != 0) {
                        $("#searchResult").modal();
                        $("#searchResults #meet-name").text(data[0].meeting_name);
                        $("#searchResults #meet-autor").text(data[0].user_name);
                        $("#searchResults #meet-room").text(data[0].room_name);
                        $("#searchResults #meet-from").text(moment(data[0].start_date).format("ddd, DD MMM")+' / '+ moment(data[0].start_time, "HH:mm").format("hh:mm A"));
                        $("#searchResults #meet-to").text(moment(data[0].end_date).format("ddd, DD MMM")+' / '+ moment(data[0].end_time, "HH:mm").format("hh:mm A"));
                        $("#searchResults #meet-description").text(data[0].description);
                      }

                      $('#searchResult').on('hidden.bs.modal', function () {
                          $("#searchResults .search-option").text("");
                      });

                    }
                });

                return false;
            },
            minLength: 1,
           
        }).data("ui-autocomplete")._renderItem = function (ul, item) {
            if(item.start_date == undefined){
              return $("<li>")
              .data("ui-autocomplete-item", item)
              .append("<a>" + item.value + "</a>")
                .appendTo(ul);
            }else{
              return $("<li>")
              .data("ui-autocomplete-item", item)
              .append("<a data-at="+item.id+"> " + item.value + "<span>" + moment(item.start_date).format("DD MMM YYYY") + "</span></a>")
                .appendTo(ul);
            }
        };

        $("#search_text").keyup(function(e){
          if ($(this).val() == "") {
            $(".ui-menu .ui-menu-item").removeClass("selected");
          }

        });

        $("#search_text").bind("enterKey",function(e){

             var meet_id = $(".ui-menu .ui-menu-item.selected a").attr("data-at");
             var meet_start = $(".ui-menu .ui-menu-item.selected a span").text();

             var meet_startf = moment(meet_start).format("YYYY-MM-DD");

             $.ajax({
                 type: "post",
                 url: "{{ route('searchdata') }}",
                 headers: {
                   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                 },
                 data: {
                   id: meet_id,
                   start: meet_startf
                 },
                 success: function(data) {

                   if (data.length != 0) {
                     $("#searchResult").modal();
                     $("#searchResults #meet-name").text(data[0].meeting_name);
                     $("#searchResults #meet-autor").text(data[0].user_name);
                     $("#searchResults #meet-room").text(data[0].room_name);
                     $("#searchResults #meet-from").text(moment(data[0].start_date).format("ddd, DD MMM")+' / '+ moment(data[0].start_time, "HH:mm").format("hh:mm A"));
                     $("#searchResults #meet-to").text(moment(data[0].end_date).format("ddd, DD MMM")+' / '+ moment(data[0].end_time, "HH:mm").format("hh:mm A"));
                     $("#searchResults #meet-description").text(data[0].description);
                   }

                   $('#searchResult').on('hidden.bs.modal', function () {
                       $("#searchResults .search-option").text("");
                   });

                 }
             });

           });
        
        $("#search_text").keyup(function(e){
             if(e.keyCode == 13)
             {
                $(this).trigger("enterKey");
             }
        });

      });
    </script>

</body>
</html>


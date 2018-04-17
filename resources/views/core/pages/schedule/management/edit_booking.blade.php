@extends("core.manage_dashboard")

<!-- Page variables -->
@section("pageTitle", "Edit Booking")

<!-- Styles -->
@section("styles")
<link rel="stylesheet" href="{{ url('css/dashboard.css') }}">
@endsection

@section('content')
    <div class="inner-content-header">
        <div class="col-md-6">
            <h2>Book Meeting</h2>
        </div>
    </div>
    <div class="inner-content-body">
      <div class="col-md-12">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div><br />
        @endif
        <div class="section-advanced">
          <form id="advancedBooking">
            <div class="body-form">
              <h3>Edit booking</h3>
              <div class="row">
                <div class="col-md-5">
                  <div class="form-group group-advn princi-advn">             
                    <label for="inputName">Name</label>
                    <input id="meeting_name" class="form-control meet" name="meeting_name" required="" value="{{$meetings->name}}">
                  </div>
                  <div class="form-group group-advn princi-advn">
                    <label for="inputRoom">Room</label>
                    <select class="form-control meet" name="room" id="room" required>
                      <option value="">Select Room</option>
                      @foreach($rooms as $room)
                        <option value="{{ $room->id }}" {{ $selected == $room->id ? 'selected=true' : '' }}> {{ $room->name }} </option>
                      @endforeach
                    </select>
                  </div>
                  <div class="form-group group-advn">             
                    <label for="inputDescription">Description</label>
                    <textarea class="form-control meet" rows="3" placeholder="Enter ..." name="description" required>{{$meetings->description}}</textarea>
                  </div>
                  <div class="form-group group-advn">
                    <label for="inputResources">Resources</label>
                    @foreach($resources as $resource)
                    <div class="checkbox checkbox-primary checkbox-inline">
                        <label>
                            <input type="checkbox" value="{{ $resource->id }}" name="resources[]"
                            @foreach($resourceE as $res)
                              {{ $resource->id === $res->resources_id ? 'checked=true' : '' }}
                            @endforeach
                            > 
                            <i class="mdi {{ $resource->icon }}"></i>{{ $resource->name }}
                        </label>
                    </div>
                    @endforeach
                  </div>
                </div>
                <div class="col-md-offset-2 col-md-5">
                  <div class="form-group group-advn princi-advn">
                    <label for="inputTime">From</label>
                    <div class="sec-date">
                      <div class="input-group date">                  
                        <input type="text" class="form-control pull-right meet" name="startdate" id="startDate" required value="{{$meeting_start_date}}">
                        <div class="input-group-addon">
                          <i class="fa fa-calendar"></i>
                        </div>
                      </div>
                    </div>
                    <select class="form-control sel-time" name="start_time" id="start_time" required>
                      @foreach($select as $sel)
                        <option {{ $start == $sel->hours ? 'selected=true' : '' }} value="{{ $sel->hours }}"> {{ $sel->time }} </option>
                      @endforeach
                    </select>
                  </div>
                  <div class="form-group group-advn princi-advn">
                    <label for="inputTime">To</label>
                    <div class="sec-date">
                      <div class="input-group date">                  
                        <input type="text" class="form-control pull-right meet" id="endDate" name="enddate" required disabled="true" value="{{$meeting_end_date}}">
                        <div class="input-group-addon">
                          <i class="fa fa-calendar"></i>
                        </div>
                      </div>
                    </div>
                    <select class="form-control sel-time" name="end_time" id="end_time" required>
                      @foreach($select as $sel)
                        <option {{ $end == $sel->hours ? 'selected=true' : '' }} value="{{ $sel->hours }}"> {{ $sel->time }} </option>
                      @endforeach
                    </select>
                  </div>
                  <div class="form-group group-advn">             
                    <label for="inputNotify">Notify People</label>
                    <textarea class="form-control meet" rows="3" placeholder="guest@example.com, pastor@example.com" name="notify">{{$meetings->people_notify}}</textarea>
                  </div>
                  <div class="form-group group-advn">
                    <label for="inputRoomLayouts" id="room-lay">Room Layout</label>
                    <div id="sec-layout">
                    @foreach($layoutEdits as $layoutEdit)
                    <div class="radio-inline sec-radio">
                        <label class="radio radio-inline">
                          <div class="room-img">
                            <img src="{{ asset('img/') }}/{{$layoutEdit->img}}">
                          </div>
                          <input type="radio" name="roomlayout" value="{{$layoutEdit->layout_id}}" {{ $sel_layout == $layoutEdit->layout_id ? 'checked=true' : '' }}><span>{{$layoutEdit->name}}</span>
                        </label>
                    </div>
                    @endforeach
                  </div>
                  </div>
                </div>
              </div>
            </div> <!-- end body-form -->
            <div class="footer-form">
              <div class="row">
                <div class="col-md-offset-8 col-md-4 footer-links">
                  <button onclick="history.back();" class="btn btn-advanced btn-block"><i class="mdi mdi-arrow-left"></i> Back</button>
                  <button type="submit" class="btn btn-success btn-block"><i class="mdi mdi-plus"></i> Edit</button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
@endsection


@section('modal')
  @include('core.pages.search_results')
@endsection

<!-- Scripts -->
@section("scripts")
	<script type="text/javascript">
		$(document).ready(function () {

      var $start = $('input[name="startdate"]').val() || null;
      var $end = $('input[name="enddate"]').val() || null;
      var $room = $('#room option:selected').val() || null;

      function sameDate() {
        $.ajax({
            type: "post",
            url: "{{ route('manage-same-date') }}",
            headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data:{
              start: $start,
              end: $end,
              room: $room
            },
            success: function(data) {
              $.each(data, function(index, timeObj){

                var s_time = timeObj.start_time;

                $("#start_time option").each(function() {
                  var time_val = $(this).val();
                  if(time_val >= s_time){
                    $(this).attr('disabled', 'disabled');
                    // $('#start_time').val("");
                  }
                });

                $("#end_time option").each(function() {
                  var time_val = $(this).val();
                  if(time_val >= s_time){
                    $(this).attr('disabled', 'disabled');
                    // $('#end_time').val("");
                  }
                });

              });

              sameEnd();

            }
        });
      }

      function sameEnd() {
        $.ajax({
            type: "post",
            url: "{{ route('manage-same-end') }}",
            headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data:{
              end: $end,
              room: $room
            },
            success: function(data) {
              $.each(data, function(index, timeObj){

                var e_time = timeObj.end_time;

                $("#start_time option").each(function() {
                  var time_val = $(this).val();
                  if(time_val < e_time){
                    $(this).attr('disabled', 'disabled');
                    // $('#start_time').val("");
                  }
                });

                $("#end_time option").each(function() {
                  var time_val = $(this).val();
                  if(time_val < e_time){
                    $(this).attr('disabled', 'disabled');
                    // $('#end_time').val("");
                  }
                });

              });

              dateRange();

            }
        });
      }

      function dateRange() {
        $.ajax({
            type: "post",
            url: "{{ route('manage-date-range') }}",
            headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data:{
              start: $start,
              end: $end,
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
                    // $('#start_time').val("");
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

            }
        });
      }

      /*Validate Start Date*/

      function onlyS() {
        $.ajax({
            type: "post",
            url: "{{ route('manage-same-date') }}",
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
                    // $('#start_time').val("");
                  }
                });

              });

              onlyvalidateStart();
            }
        });
      }

      function onlyvalidateStart() {
        $.ajax({
            type: "post",
            url: "{{ route('manage-only-validate-start') }}",
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
                    // $('#start_time').val("");
                  }
                });

              });

              twice();
            }
        });
      }

      function twice() {
        $.ajax({
            type: "post",
            url: "{{ route('manage-date-range') }}",
            headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data:{
              start: $start,
              end: $end,
              room: $room
            },
            success: function(data) {
              $.each(data, function(index, timeObj){

                var s_time = timeObj.start_time;
                var e_time = timeObj.end_time;

                $("#start_time option").each(function() {
                  var time_val = $(this).val();
                  if(time_val >= s_time){
                    $(this).attr('disabled', 'disabled');
                    // $('#start_time').val("");
                  }
                });

                $("#end_time option").each(function() {
                  var time_val = $(this).val();
                  if(time_val < e_time){
                    $(this).attr('disabled', 'disabled');
                    // $('#end_time').val("");
                  }
                });

              });

              diferentS();
            }
        });
      }

      function diferentS() {
        $.ajax({
            type: "post",
            url: "{{ route('manage-diferent-start') }}",
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
                  }
                });

              });

              diferentE();
            }
        });
      }


      /*Validate End Date*/

      function diferentE() {
        $.ajax({
            type: "post",
            url: "{{ route('manage-diferent-end') }}",
            headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data:{
              end: $end,
              room: $room
            },
            success: function(data) {
              $.each(data, function(index, timeObj){

                var s_time = timeObj.start_time;
                var e_time = timeObj.end_time;

                $("#end_time option").each(function() {
                  var time_val = $(this).val();
                  if(time_val >= s_time && time_val < e_time){
                    $(this).attr('disabled', 'disabled');
                  }
                });

              });

              firstvalDif();
            }
        });
      }

      function firstvalDif() {
        $.ajax({
            type: "post",
            url: "{{ route('manage-first-val-dif') }}",
            headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data:{
              end: $end,
              room: $room
            },
            success: function(data) {
              $.each(data, function(index, timeObj){

                var s_time = timeObj.start_time;

                $("#end_time option").each(function() {
                  var time_val = $(this).val();
                  if(time_val >= s_time){
                    $(this).attr('disabled', 'disabled');
                    // $('#end_time').val("");
                  }
                });

              });

              secondvalDif();
            }
        });
      }

      function secondvalDif() {
        $.ajax({
            type: "post",
            url: "{{ route('manage-second-val-dif') }}",
            headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data:{
              end: $end,
              room: $room
            },
            success: function(data) {
              $.each(data, function(index, timeObj){

                var e_time = timeObj.end_time;

                $("#end_time option").each(function() {
                  var time_val = $(this).val();
                  if(time_val < e_time){
                    $(this).attr('disabled', 'disabled');
                    // $('#start_time').val("");
                  }
                });

              });
            }
        });
      }

      if ($start && $end && $room) {

        if ($start == $end) {

          $('#start_time option:last-child').addClass("hidden");
          $('#end_time option:first-child').addClass("hidden");
          // $('#end_time').val("");

          sameDate();

        }

        if ($start != $end) {

          onlyS();

        }  /*end if*/

      } /*end principal if*/


      $("#end_time").find('option').not(':selected').css("display","none");

      $('.input-group.date').datepicker({
      }).on('changeDate', function (e) {
          
          $("#room").trigger("change");

      }); /*end changeDate*/



      var start = new Date();
      var end = new Date(new Date().setYear(start.getFullYear()+1));

      $('#startDate').datepicker({
          format: "DD, MM dd yyyy",
          orientation: "bottom auto",
          todayHighlight: true,
          startDate: start,
          endDate: end
      }).on('changeDate', function(){
          $('#endDate').datepicker('setStartDate', new Date($(this).val()));
      }); 


    });
    </script>

    <script>
        $(function () {
            var referer=document.referrer;
            function sendMessage(type, message) {
              swal({ text: message, type: type }).then(function() {
                window.location.replace(referer);
              },
              function (dismiss) {
                window.location.replace(referer);
              });
            }

            var requiredCheckboxes = $(':checkbox[required]');
              requiredCheckboxes.change(function(){
              if(requiredCheckboxes.is(':checked')) {
                requiredCheckboxes.removeAttr('required');
              }
              else {
                requiredCheckboxes.attr('required', 'required');
              }
            });


            /*Format*/

            var all_f = [];

            $('#start_time').on('change', function(){
              $('#end_time').val("");
              var c_start = $("#start_time option").clone();

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
                    all_f = [];
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

            
            $("#advancedBooking").on("submit", function (event) {

              event.preventDefault();

              var meeting_name = $('input[name="meeting_name"]').val();
              var meeting_room = $('#room option:selected').val();
              var meeting_room_name = $('#room option:selected').text();
              var meeting_start_date = $('input[name="startdate"]').val();
              var meeting_start_time = $('#start_time option:selected').val();
              var meeting_end_date = $('input[name="enddate"]').val();
              var meeting_end_time = $('#end_time option:selected').val();
              var meeting_description = $('textarea[name="description"]').val();
              var meeting_notify = $('textarea[name="notify"]').val();
              var meeting_layout = $('input[name="roomlayout"]:checked').val();

              var val = [];
                $(':checkbox:checked').each(function(i){
                  val[i] = $(this).val();
              });
              
              var meeting_resource = val;

              var res = meeting_notify.split(",");

              var mome_start = moment(meeting_start_time, ["HH:mm"]).format("hh:mm A");
              var mome_end = moment(meeting_end_time, ["HH:mm"]).format("hh:mm A");

              if (meeting_name) {
                axios.post("{{ route('manage-update-booking',['id' => $meetings->id]) }}", {
                  name: meeting_name,
                  room: meeting_room,      
                  room_name: meeting_room_name, 
                  start_date: meeting_start_date,
                  start_time: meeting_start_time,
                  end_date: meeting_end_date,
                  end_time: meeting_end_time,
                  mom_start: mome_start,
                  mom_end: mome_end,
                  description: meeting_description,
                  notify: meeting_notify,
                  noti: res,
                  layout: meeting_layout,
                  resource: meeting_resource
                }).then(function (resp) {
                  var data = resp.data.data;

                  sendMessage("success", "Meeting updated");
                }).catch(function (err) {
                  sendMessage("error", "An error has occurred, please verify all fields are complete and try again");
                });

              } else {
                sendMessage("error", "An error has occurred, please verify all fields are complete and try again");
              }
            });


            $('#room').on('change', function(e){
              
              var room_id = e.target.value;

              $.get("{{ route('manage-room-layout-radio') }}?room_id=" + room_id,function(data) {
                  $('#sec-layout').empty();

                  $.each(data, function(index, roomsObj){
                    $('#sec-layout').append('<div class="radio-inline sec-radio"><label class="radio radio-inline"><div class="room-img"><img src="{{ asset("img") }}/'+ roomsObj.img +'"></div><input type="radio" name="roomlayout" value="'+ roomsObj.layout_id +'" id="roomlayout"><span>'+ roomsObj.name +'</span></label></div>');
                    if (index === 0) {
                      $(".sec-radio #roomlayout").prop('checked', true);
                    }
                  });
                  
                });

              var $start = $('#startDate').val() || null;
              var $room = $('#room option:selected').val() || null;

              function hours(){

                $.ajax({
                  type: "post",
                  url: "{{ route('manage-hours') }}",
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
                  url: "{{ route('manage-first-create-option') }}",
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
                  url: "{{ route('manage-last-create-option') }}",
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
                  url: "{{ route('manage-same-options') }}",
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

                      $('#start_time').attr("disabled",true);
                      $('#end_time').attr("disabled",true);
                      $('.section-danger').css("display","block");
                      $("#advancedBooking .btn-success").attr("disabled",true);
                    }else{
                      $('#start_time').removeAttr("disabled");
                      $('#end_time').removeAttr("disabled");

                      $('.section-danger').css("display","none");
                      $("#advancedBooking .btn-success").removeAttr("disabled");
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

            $('#startDate').change(function() {
                $('#endDate').val($(this).val());
            });


		});
	</script>
@endsection

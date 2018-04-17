@extends("core.user_dashboard") 

<!-- Page variables -->
@section("pageTitle", "Advanced Booking")

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
        <div class="section-advanced">
          <form id="advancedBooking">
            <div class="body-form">
              <h3>Advanced booking</h3>
              <div class="row">
                <div class="col-md-5">
                  <div class="form-group group-advn princi-advn">             
                    <label for="inputName">Name</label>
                    <input id="meeting_name" class="form-control meet" name="meeting_name" required="">
                  </div>
                  <div class="form-group group-advn princi-advn">
                    <label for="inputRoom">Room</label>
                    <select class="form-control meet" name="room" id="room" required>
                      <!-- <option selected="true" value="">Select Room</option> -->
                      @foreach($rooms as $room)
                        <option value="{{ $room->id }}"> {{ $room->name }} </option>
                      @endforeach
                    </select>
                  </div>
                  <div class="form-group group-advn">             
                    <label for="inputDescription">Description</label>
                    <textarea class="form-control meet" rows="3" placeholder="Enter ..." name="description" required></textarea>
                  </div>
                  <div class="form-group group-advn">
                    <label for="inputResources">Resources</label>
                    @foreach($resources as $resource)
                    <div class="checkbox checkbox-primary checkbox-inline">
                        <label>
                            <input type="checkbox" value="{{ $resource->id }}" name="resources[]" required /> <i class="mdi {{ $resource->icon }}"></i>{{ $resource->name }}
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
                        <input type="text" class="form-control pull-right meet meeting-dates" id="startDate" name="startdate" required>
                        <div class="input-group-addon">
                          <i class="fa fa-calendar"></i>
                        </div>
                      </div>
                    </div>
                    <select class="form-control sel-time" name="start_time" id="start_time" required>
                    </select>
                  </div>
                  <div class="form-group group-advn princi-advn">
                    <label for="inputTime">To</label>
                    <div class="sec-date">
                      <div class="input-group date">                  
                        <input type="text" class="form-control pull-right meet meeting-dates" id="endDate" name="enddate" disabled="true">
                        <div class="input-group-addon">
                          <i class="fa fa-calendar"></i>
                        </div>
                      </div>
                    </div>
                    <select class="form-control sel-time" name="end_time" id="end_time" required>
                    </select>
                  </div>
                  <div class="form-group section-danger">
                    <div class="alert alert-danger">This room is fully occupied on this date. Please choose another room.
                    </div>
                  </div>
                  <div class="form-group group-advn">             
                    <label for="inputNotify">Notify People</label>
                    <textarea class="form-control meet" rows="3" placeholder="guest@example.com, pastor@example.com" name="notify"></textarea>
                  </div>
                  <div class="form-group group-advn">
                    <label for="inputRoomLayouts" id="room-lay">Room Layout</label>
                    <div id="sec-layout"></div>
                  </div>
                </div>
              </div>
            </div> <!-- end body-form -->
            <div class="footer-form"> 
              <div class="row">
                <div class="col-md-offset-8 col-md-4 footer-links">
                  <button onclick="history.back();" class="btn btn-advanced btn-block"><i class="mdi mdi-arrow-left"></i> Back</button>
                  <button type="submit" class="btn btn-success btn-block"><i class="mdi mdi-plus"></i> Save</button>
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

      var date = location.search.split('date=')[1];
      var final_date = moment(date).format("dddd, MMMM D YYYY");
      $("#startDate").val(final_date);
      $("#endDate").val(final_date);

      $('.input-group.date').datepicker({
      }).on('changeDate', function (e) {
        $("#room").trigger("change");
      });

      
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

      // $('#startDate').val("Wednesday, March 28 2018");
      // $('#endDate').val("Wednesday, March 28 2018");
      // var mom = moment(start).format("dddd, MMMM D YYYY");

      // $('#startDate').val(mom);
      // $('#endDate').val(mom);

      // $('#endDate').datepicker({
      //     format: "DD, MM dd yyyy",
      //     orientation: "bottom auto",
      //     todayHighlight: true,
      //     startDate: start,
      //     endDate: end
      // }).on('changeDate', function(){
      //     $('#startDate').datepicker('setEndDate', new Date($(this).val()));
      // });

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
              var meeting_end_date = $('input[name="startdate"]').val();
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
                axios.post("{{ route('user-advanced-create') }}", {
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

                  $('.meet').val("");
                  $('#sec-layout').empty();
                  $(':checkbox:checked').prop('checked', false);
                  sendMessage("success", "Meeting created");
                }).catch(function (err) {
                  sendMessage("error", "An error has occurred, please verify all fields are complete and try again");
                });
              }
            });


            $('#room').on('change', function(e){
              
              // $('#createMeeting').on('hidden.bs.modal', function () {
              //     location.reload();
              // });

              var room_id = e.target.value;

              $.get("{{ route('user-room-layout-radio') }}?room_id=" + room_id,function(data) {
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

            if($('#startDate').val() != ""){
              $('#room').trigger("change");
              
            }

    });
  </script>
@endsection

@extends("core.dashboard")

<!-- Page variables -->
@section("pageTitle", "Rooms Manager")

<!-- Styles -->
@section("styles")
<link rel="stylesheet" href="{{ url('css/dashboard.css') }}">
@endsection

@section('content')
    <div class="inner-content-header">
        <div class="col-md-6">
            <h2>Rooms Manager</h2>
        </div>
        <div class="col-md-6 quick-book">
            <a href="#" class="btn btn-success" data-toggle="modal" data-target="#createRoom"><i class="mdi mdi-plus"></i> Add Room</a>
        </div>
    </div>
    <div class="inner-content-body">
      <div class="col-md-12">
        <div class="section-advanced" id="section-meet">
            <table class="table table-bordered has-action">
                <thead>
                    <tr>
                        <th class="col-md-3">Name</th>
                        <th class="col-md-3">Color</th>
                        <th class="col-md-2">Capacity</th>
                        <th class="col-md-2">Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                  @if($rooms->count() > 0)
                  @foreach($rooms as $room)
                  <tr id="tr_{{$room->id}}">
                    <td><label><input type="checkbox" name="check" class="sub_chk" data-id="{{$room->id}}"> <span class="label-text">{{$room->name}}</span></label></td>
                    <td class="td-color"><span style="background-color: rgba({{$room->color}}, .2)"></span></td>
                    <td>{{$room->capacity}}</td>
                    <td>{{ $room->status == 0 ? 'Disable' : 'Active' }}</td>
                    <td>
                      <a href="#" class="btn btn-action btn-room" data-toggle="modal" data-target="#editRoom" data-community="{{ json_encode($room) }}"><i class="mdi mdi-pencil"></i></a>
                      <a href="{{ route('deleteRoom', ['id' => $room->id]) }}" class="btn btn-action btn-del"><i class="mdi mdi-delete"></i></a>
                    </td>
                  </tr>
                  @endforeach
                  @endif
                </tbody>
            </table>
        </div>
      </div>
      <div class="col-md-12">
        <div class="meeting-page">
          {{ $rooms->links() }}
        </div>
      </div>
      <div class="col-md-12">
        <div class="section-delete">
          <button href="#" class="btn btn-del-selected delete_all" data-url="{{ route('deleteAllRoom') }}"><i class="mdi mdi-delete"></i> Delete selected</button>
        </div>
      </div>
    </div>
@endsection

@section('modal')
  @include('core.pages.manage.create_rooms')
  @include('core.pages.manage.edit_rooms')
  @include('core.pages.search_results')
@endsection

<!-- Scripts -->
@section("scripts")

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

      $('#opening_time option:last-child').addClass("hidden");
      $('#opening_time option:last-child').attr("disabled",true);
      $('#opening_time option:nth-child(96)').addClass("hidden");
      $('#closing_time option:first-child').addClass("hidden");
      $('#closing_time option:last-child').addClass("hidden");
      $('#closing_time').val($('#closing_time option:nth-child(2)').val());


      /*Create*/
      $("#createRooms").on("submit", function (event) {
        event.preventDefault();

        var room_name = $('input[name="room_name"]').val();
        var room_capacity = $('#roomCapacity').val();
        var room_opening = $('#opening_time option:selected').val();
        var room_closing = $('#closing_time option:selected').val();
        var room_status = $('#status option:selected').val();
        //color
        var r_color = $('#roomColor').val();
        var pos = r_color.indexOf("(") + 1;
        var room_color = r_color.slice(pos, r_color.lastIndexOf(')'));

        var val = [];
          $(".modal-rooms .sec-radio :checkbox:checked").each(function(i){
            val[i] = $(this).val();
        });

        var room_layout = val;

        if (room_name) {
          axios.post("{{ route('create-room') }}", {
            name: room_name,
            capacity: room_capacity,
            color: room_color,       
            layout: room_layout,
            opening: room_opening,
            closing: room_closing,
            status: room_status
        }).then(function (resp) {
            var data = resp.data.data;
            $('.meet').val("");
            $(".modal-rooms .sec-radio :checkbox:checked").prop('checked', false);
            $("#opening_time option:first-child").attr("selected","selected");
            $("#closing_time option:first-child").attr("selected","selected");
            sendMessage("success", "Room created");
            $('#createRoom').modal('hide');
        }).catch(function (err) {
            sendMessage("error", "An error has occurred, please verify all fields are complete and try again");
        });

        }

      });


      /*Edit*/

      $(".btn-room").on("click", function (event) {
        event.preventDefault();

        var att = $(this).attr("data-community");
        var parse = jQuery.parseJSON(att);

        var open = $("#editRooms #opening_time option");
        var close = $("#editRooms #closing_time option");
        var status = $("#editRooms #status option");
        var id_room = $("#editRooms .title-f span");

        var parse_lay = parse.layout.split(',');
        var layout = $("input[name='roomlayout']");

        id_room.text(parse.id);
        $("#editRooms #room_name").val(parse.name);
        $("#editRooms #roomC").val("rgb("+parse.color+")");
        $("#editRooms #sec-room .sp-preview-inner").css("background-color","rgba("+parse.color+", .2)");
        $("#editRooms #roomCapacity").val(parse.capacity);


        $(open).each(function() {
          if (parse.opening_time == $(this).val()) {
            $(this).attr("selected","selected");
          }
        });

        $(close).each(function() {
          if (parse.closing_time == $(this).val()) {
            $(this).attr("selected","selected");
          }
        });

        $(status).each(function() {
          if (parse.status == $(this).val()) {
            $(this).attr("selected","selected");
          }
        });

        for ($i = 0; $i < parse_lay.length; $i++) {
          console.log(parse_lay[$i]);
          $(layout).each(function() {
            if (parse_lay[$i] == $(this).val()) {
              $(this).attr("checked","checked");
            }
          });
        };

      });

      $("#editRooms").on("submit", function (event) {
        event.preventDefault();

        var room_name = $('#editRooms input[name="room_name"]').val();
        var room_capacity = $('#editRooms #roomCapacity').val();
        var room_opening = $('#editRooms #opening_time option:selected').val();
        var room_closing = $('#editRooms #closing_time option:selected').val();
        var room_status = $('#editRooms #status option:selected').val();
        var room_id = $("#editRooms .title-f span").text();
        //color
        var r_color = $('#editRooms #roomC').val();
        var pos = r_color.indexOf("(") + 1;
        var room_color = r_color.slice(pos, r_color.lastIndexOf(')'));

        var val = [];
          $("#editRooms .modal-rooms .sec-radio :checkbox:checked").each(function(i){
            val[i] = $(this).val();
        });

        var room_layout = val;

        if (room_name) {
          axios.post("{{ route('update-room') }}", {
            id_r: room_id,
            name: room_name,
            capacity: room_capacity,
            color: room_color,       
            layout: room_layout,
            opening: room_opening,
            closing: room_closing,
            status: room_status
        }).then(function (resp) {
            var data = resp.data.data;
            $('#editRoom .meet').val("");
            $("#editRoom .modal-rooms .sec-radio :checkbox:checked").removeAttr("checked")
            $("#editRoom #opening_time option").removeAttr("selected");
            $("#editRoom #closing_time option").removeAttr("selected");
            sendMessage("success", "Room updated");
            $('#editRoom').modal('hide');
        }).catch(function (err) {
            sendMessage("error", "An error has occurred, please verify all fields are complete and try again");
        });

        }

      });


      /*Remove*/
      $('.btn-del').on('click', function(event){
        event.preventDefault();
        var url =  $(this).attr('href');

        swal({   
          title: "Are you sure?",
          text: "Once deleted, you will not be able to recover this Room.",    
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it',
          cancelButtonText: 'No, cancel',
          confirmButtonClass: 'btn btn-success',
          cancelButtonClass: 'btn btn-danger',
          buttonsStyling: false,
          reverseButtons: true
        }).then(function () {
          $.ajax({
            url: url,
            type: 'DELETE',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function (data) {
              if (data['success']) {
                $("#" + data['tr']).slideUp("slow");
                swal({ text: 'Room deleted', type: 'success' })
              } else if (data['error']) {
                sendMessage("error", data['error']);
              } else {
                sendMessage("error", "Something went wrong.");
              }
            },
            error: function (data) {
              sendMessage("error", "Something went wrong.");
            }
          });
                  
        }).catch(swal.noop)
      });


      $('.delete_all').on('click', function(e) {
        var allMeets = [];  
        $(".sub_chk:checked").each(function() {  
          allMeets.push($(this).attr('data-id'));
        });  

        if(allMeets.length <=0){  
          swal({ text: 'Please select row.', type: 'error' })
        }else {  

          var urlAll =  $(this).data('url');
          var selected_val = allMeets.join(",");

          swal({   
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover this meetings.",    
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it',
            cancelButtonText: 'No, cancel',
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-danger',
            buttonsStyling: false,
            reverseButtons: true
          }).then(function () {
            $.ajax({
              url: urlAll,
              type: 'DELETE',
              headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
              data: 'ids='+selected_val,
              success: function (data) {
                if (data['success']) {
                  $(".sub_chk:checked").each(function() {  
                      $(this).parents("tr").remove();
                  });
                  swal({ text: 'Rooms deleted successfully.', type: 'success' })
                } else if (data['error']) {
                  sendMessage("error", data['error']);
                } else {
                  sendMessage("error", "Something went wrong.");
                }
              },
              error: function (data) {
                sendMessage("error", "Something went wrong.");
              }
            });

            $.each(allMeets, function(index,value) {
                $('table tr').filter("[data-row-id='" + value + "']").remove();
            });
                    
          }).catch(swal.noop)
        } 
      });


		});
	</script>
  <script src="{{ url('js/rooms.js') }}"></script>
@endsection

@extends("core.manage_dashboard")

<!-- Page variables -->
@section("pageTitle", "My meetings")

<!-- Styles -->
@section("styles")
<link rel="stylesheet" href="{{ url('css/dashboard.css') }}">
@endsection

@section('content')
    <div class="inner-content-header">
        <div class="col-md-6">
            <h2>My meetings</h2>
        </div>
        <div class="col-md-6 quick-book">
            <a href="{{route('advanced-booking')}}" class="btn btn-success"><i class="mdi mdi-plus"></i> Quick Book</a>
        </div>
    </div>
    <div class="inner-content-body">
      <div class="col-md-12">
        <div class="section-advanced" id="section-meet">
            <table class="table table-bordered has-action">
                <thead>
                    <tr>
                        <th class="col-md-3">Name</th>
                        <th class="col-md-2">Date</th>
                        <th>Duration</th>
                        <th class="col-md-2">Resources</th>
                        <th class="col-md-2">Room</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                  @if($meetDetail->count() > 0)
                  @foreach($meetDetail as $detail)
                    <tr id="tr_{{$detail->id}}">
                      <td><label><input type="checkbox" name="check" class="sub_chk" data-id="{{$detail->id}}"> <span class="label-text">{{$detail->meeting_name}}</span></label></td>
                      <td class="td-date">{{$detail->startD}} {{$detail->time}}</td>
                      <td>{{$detail->hour}}.{{$detail->minute}} hrs</td>
                      <td class="meet-i"><span>{{$detail->icon}}</span></td>
                      <td class="td-rooms"><span style="color: rgb({{$detail->color}})">{{$detail->room_name}}</span></td>
                      <td>
                        <a href="{{ route('edit-booking', ['id' => $detail->id]) }}" class="btn btn-action"><i class="mdi mdi-pencil"></i></a>
                        <a href="{{ route('manage-deleteMeeting', ['id' => $detail->id]) }}" class="btn btn-action btn-del"><i class="mdi mdi-delete"></i></a>
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
          {{ $meetDetail->links() }}
        </div>
      </div>
      <div class="col-md-12">
        <div class="section-delete">
          <button href="#" class="btn btn-del-selected delete_all" data-url="{{ route('manage-deleteAll') }}"><i class="mdi mdi-delete"></i> Delete selected</button>
        </div>
      </div>
    </div>
@endsection

@section('modal')
  @include('core.pages.search_results')
@endsection

<!-- Scripts -->
@section("scripts")

  <script>
    $(function () {

      function sendMessage(type, message) {
        swal({ text: message, type: type })
      }

      $(".meet-i").each(function() {
        if ($(this).text() != '') {
          var section = $(this);
          var span = $(this).children('span');
          var myArr = span.text();
          var result = myArr.split(',');

          $.each(result, function(index,value) {
            span.remove();
            section.append("<i class='mdi " + value + "'></i>");
          });
        }else{
          var span = $(this).children('span');

          span.append("<i class='mdi mdi-cancel'></i>");
        }
      });

      $(".pagination li").each(function() {
        if ($(this).children().attr('rel') == "next" || $(this).children().text() == '»') {
          var next = $(this).children();
          next.html('<i class="mdi mdi-chevron-right"></i>');
        } else if ($(this).children().attr('rel') == "prev" || $(this).children().text() == '«') {
            var prev = $(this).children();
            prev.html('<i class="mdi mdi-chevron-left"></i>');
        }
              
      });

      $('.btn-del').on('click', function(event){
        event.preventDefault();
        var url =  $(this).attr('href');

        swal({   
          title: "Are you sure?",
          text: "Once deleted, you will not be able to recover this meeting.",    
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
                sendMessage("success", "Meeting deleted");
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
          sendMessage("error", "Please select row.");
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
                  sendMessage("success", "Meetings deleted successfully.");
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
@endsection

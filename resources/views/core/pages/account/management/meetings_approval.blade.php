@extends("core.manage_dashboard")

<!-- Page variables -->
@section("pageTitle", "Meetings Approval")

<!-- Styles -->
@section("styles")
<link rel="stylesheet" href="{{ url('css/dashboard.css') }}">
@endsection

@section('content')
    <div class="inner-content-header">
        <div class="col-md-6">
            <h2>Meetings Approval</h2>
        </div>
        <!-- <div class="col-md-6 quick-book">
            <a href="{{route('advanced-booking')}}" class="btn btn-success"><i class="mdi mdi-plus"></i> Quick Book</a>
        </div> -->
    </div>
    <div class="inner-content-body">
      <div class="col-md-12">
        <div class="section-advanced" id="section-meet">
            <table class="table table-bordered has-action">
                <thead>
                    <tr>
                        <th class="col-md-2">Name</th>
                        <th class="col-md-2">Date</th>
                        <th>Duration</th>
                        <th class="col-md-2">User</th>
                        <th class="col-md-2">Room</th>
                        <th>Approval</th>
                    </tr>
                </thead>
                <tbody>
                  @if($meetDetail->count() > 0)
                  @foreach($meetDetail as $detail)
                    <tr id="tr_{{$detail->id}}">
                      <td><label><span class="label-text">{{$detail->meeting_name}}</span></label></td>
                      <td class="td-date">{{$detail->startD}} {{$detail->time}}</td>
                      <td>{{$detail->hour}}.{{$detail->minute}} hrs</td>
                      <td>{{$detail->user_name}}</td>
                      <td class="td-rooms"><span style="color: rgb({{$detail->color}})">{{$detail->room_name}}</span></td>
                      <td class="td-roles">
                        <form class="changeRole">
                          <select class="form-control sel-role" name="role" data-user="{{$detail->id}}">
                            <option value="2" {{ $detail->status == 2 ? 'selected=true' : '' }}> Disapproved</option>
                            <option value="1" {{ $detail->status == 1 ? 'selected=true' : '' }}> Approved</option>
                          </select>
                          <button type="submit" class="btn btn-advanced btn-role">Change</button>
                        </form>
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
        swal({ text: message, type: type }).then(function() {
                location.reload();
            },
            function (dismiss) {
                location.reload();
            });
      }

      $(".pagination li").each(function() {
        if ($(this).children().attr('rel') == "next" || $(this).children().text() == '»') {
          var next = $(this).children();
          next.html('<i class="mdi mdi-chevron-right"></i>');
        } else if ($(this).children().attr('rel') == "prev" || $(this).children().text() == '«') {
            var prev = $(this).children();
            prev.html('<i class="mdi mdi-chevron-left"></i>');
        }
              
      });

      $(".changeRole").on("submit", function (event) {

        event.preventDefault();

        var meeting_status = event.target[0].value;
        var meeting_id = event.target[0].dataset.user;
        
        if (meeting_status) {
          axios.post("{{ route('manage-update-status') }}", {
            meeting: meeting_id,
            status: meeting_status
        }).then(function (resp) {
            var data = resp.data.data;
            if (data.status == 1) {
              sendMessage("success", "Approved meeting"); 
            } else {
              sendMessage("success", "Disapproved meeting"); 
            }
        }).catch(function (err) {
            sendMessage("error", "An error has occurred, try again");
        });

        }

      });

		});
	</script>
@endsection

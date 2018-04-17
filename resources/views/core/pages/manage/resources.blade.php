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
            <h2>Resources Manager</h2>
        </div>
        <div class="col-md-6 quick-book">
            <a href="#" class="btn btn-success" data-toggle="modal" data-target="#createResource"><i class="mdi mdi-plus"></i> Add Resource</a>
        </div>
    </div>
    <div class="inner-content-body">
      <div class="col-md-12">
        <div class="section-advanced" id="section-meet">
            <table class="table table-bordered has-action">
                <thead>
                    <tr>
                        <th class="col-md-4">Name</th>
                        <th class="col-md-4">Description</th>
                        <th class="col-md-2">Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                  @if($resources->count() > 0)
                  @foreach($resources as $resource)
                  <tr id="tr_{{$resource->id}}">
                    <td><label><input type="checkbox" name="check" class="sub_chk" data-id="{{$resource->id}}"> <span class="label-text">{{$resource->name}}</span></label></td>
                    <td><span>{{$resource->description}}</span></td>
                    <td>{{ $resource->status == 0 ? 'Disable' : 'Active' }}</td>
                    <td>
                      <a href="#" class="btn btn-action btn-room" data-toggle="modal" data-target="#editResource" data-community="{{ json_encode($resource) }}"><i class="mdi mdi-pencil"></i></a>
                      <a href="{{ route('deleteResource', ['id' => $resource->id]) }}" class="btn btn-action btn-del"><i class="mdi mdi-delete"></i></a>
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
          {{ $resources->links() }}
        </div>
      </div>
      <div class="col-md-12">
        <div class="section-delete">
          <button href="#" class="btn btn-del-selected delete_all" data-url="{{ route('deleteAllRes') }}"><i class="mdi mdi-delete"></i> Delete selected</button>
        </div>
      </div>
    </div>
@endsection

@section('modal')
  @include('core.pages.manage.create_resource')
  @include('core.pages.manage.edit_resource')
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

      /*Create*/
      $("#createResources").on("submit", function (event) {
        event.preventDefault();

        var resource_name = $('input[name="resource_name"]').val();
        var resource_description = $('textarea[name="description"]').val();
        var resource_icon= $(".btn-ic.active input").val();
        var resource_status = $('#status option:selected').val();

        if (resource_name) {
          axios.post("{{ route('create-resource') }}", {
            name: resource_name,
            description: resource_description,
            icon: resource_icon,       
            status: resource_status
        }).then(function (resp) {
            var data = resp.data.data;
            $('.meet').val("");
            sendMessage("success", "Resource created");
            $('#createResource').modal('hide');
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

        var id_resource = $("#editResources .title-f span");
        var status = $("#editResources #status option");
        var icon = $("input[name='icons']");


        id_resource.text(parse.id);
        $("#editResources #resource_name").val(parse.name);
        $("#editResources #description").val(parse.description);
        $("#editResources #roomCapacity").val(parse.capacity);

        $(status).each(function() {
          if (parse.status == $(this).val()) {
            $(this).attr("selected","selected");
          }
        });

        $(icon).each(function() {
          if (parse.icon == $(this).val()) {
            $(this).parent().addClass('active');
          }
        });

      });

      $("#editResources").on("submit", function (event) {
        event.preventDefault();

        var resource_name = $('#editResources input[name="resource_name"]').val();
        var resource_description = $('#editResources textarea[name="description"]').val();
        var resource_icon= $("#editResources .btn-ic.active input").val();
        var resource_status = $('#editResources #status option:selected').val();
        var resource_id = $("#editResources .title-f span").text();

        if (resource_name) {
          axios.post("{{ route('update-resource') }}", {
            id_r: resource_id,
            name: resource_name,
            description: resource_description,
            icon: resource_icon,    
            status: resource_status
        }).then(function (resp) {
            var data = resp.data.data;
            $('.meet').val("");
            $("#editResource .btn-ic.active").removeClass("active");
            sendMessage("success", "Resource updated");
            $('#editResource').modal('hide');
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
          text: "Once deleted, you will not be able to recover this resource.",    
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
                swal({ text: 'Resource deleted', type: 'success' })
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
                  swal({ text: 'Resources deleted successfully.', type: 'success' })
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

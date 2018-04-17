@extends("core.dashboard")

<!-- Page variables -->
@section("pageTitle", "Users Manager")

<!-- Styles -->
@section("styles")
<link rel="stylesheet" href="{{ url('css/dashboard.css') }}">
@endsection

@section('content')
    <div class="inner-content-header">
        <div class="col-md-6">
            <h2>Users Manager</h2>
        </div>
        <div class="col-md-6 quick-book">
            <a href="#" class="btn btn-success" data-toggle="modal" data-target="#createUser"><i class="mdi mdi-plus"></i> Add User</a>
        </div>
    </div>
    <div class="inner-content-body">
      <div class="col-md-12">
        <div class="section-advanced" id="section-meet">
            <table class="table table-bordered has-action">
                <thead>
                    <tr>
                        <th class="col-md-2">Name</th>
                        <th class="col-md-2">Email</th>
                        <th class="col-md-2">Phone</th>
                        <th>Status</th>
                        <th class="col-md-3">Role</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                  @if($users->count() > 0)
                  @foreach($users as $user)
                  <tr id="tr_{{$user->id}}">
                    @foreach($user_auth as $userA)
                    <td><label><input type="checkbox" name="check" class="sub_chk" data-id="{{$user->id}}"> <span class="label-text {{ $userA->id == $user->id ? 'label-auth' : '' }}">{{$user->name}}</span></label></td>
                    <td class="td-user-email">{{$user->email}}</td>
                    <td>{{$user->phone}}</td>
                    <td>{{ $user->status == 0 ? 'Disable' : 'Active' }}</td>
                    <td class="td-roles">
                      <form class="changeRole {{ $userA->id == $user->id ? 'sel-auth' : '' }}">
                        <select class="form-control sel-role" name="role" data-user="{{$user->id}}">
                          <option value="0" {{ $user->role == 0 ? 'selected=true' : '' }}> User</option>
                          <option value="1" {{ $user->role == 1 ? 'selected=true' : '' }}> Manager</option>
                          <option value="2" {{ $user->role == 2 ? 'selected=true' : '' }}> Administrator</option>
                        </select>
                        <button type="submit" class="btn btn-advanced btn-role">Change Role</button>
                      </form>
                    </td>
                    <td>
                      <a href="#" class="btn btn-action btn-user" data-toggle="modal" data-target="#editUser" data-community="{{ json_encode($user) }}"><i class="mdi mdi-pencil"></i></a>
                      <a href="{{ route('deleteUser', ['id' => $user->id]) }}" class="btn btn-action btn-del {{ $userA->id == $user->id ? 'auth' : '' }}"><i class="mdi mdi-delete"></i></a>
                      @endforeach
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
          {{ $users->links() }}
        </div>
      </div>
      <div class="col-md-12">
        <div class="section-delete">
          <button href="#" class="btn btn-del-selected delete_all" data-url="{{ route('deleteAllUser') }}"><i class="mdi mdi-delete"></i> Delete selected</button>
        </div>
      </div>
    </div>
@endsection

@section('modal')
  @include('core.pages.manage.create_user')
  @include('core.pages.manage.edit_users')
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

      $(".changeRole").on("submit", function (event) {

        event.preventDefault();

        var user_role = event.target[0].value;
        var user_id = event.target[0].dataset.user;
        
        if (user_role) {
          axios.post("{{ route('update-role') }}", {
            user: user_id,
            role: user_role
        }).then(function (resp) {
            var data = resp.data.data;
            sendMessage("success", "Role updated");
        }).catch(function (err) {
            sendMessage("error", "An error has occurred, try again");
        });

        }

      });


      /*Create*/
      $("#createUsers").on("submit", function (event) {
        event.preventDefault();

        var full_name = $('input[name="full_name"]').val();
        var username = $('input[name="username"]').val();
        var user_phone = $('input[name="user_phone"]').val();
        var user_email = $('input[name="user_email"]').val();
        var user_pass = $('input[name="user_password"]').val();
        var user_role = $('#user_role option:selected').val();
        var user_status = $('#user_status option:selected').val();

        if (full_name) {
          axios.post("{{ route('create-user') }}", {
            fullname: full_name,
            usern: username,
            phone: user_phone,       
            email: user_email,
            password: user_pass,
            role: user_role,
            status: user_status
        }).then(function (resp) {
            var data = resp.data.data;
            sendMessage("success", "User created");
        }).catch(function (err) {
            sendMessage("error", "An error has occurred, please verify that your email is not repeated and all fields are complete.");
        });

        }

      });

      /*Edit*/

      $(".btn-user").on("click", function (event) {
        event.preventDefault();

        var att = $(this).attr("data-community");
        var parse = jQuery.parseJSON(att);

        var id_user = $("#editUsers .title-f span");
        var status = $("#editUsers #user_status option");
        var role = $("#editUsers #user_role option");


        id_user.text(parse.id);
        $("#editUsers #full_name").val(parse.name);
        $("#editUsers #username").val(parse.username);
        $("#editUsers #user_phone").val(parse.phone);
        $("#editUsers #user_email").val(parse.email);

        $(status).each(function() {
          if (parse.status == $(this).val()) {
            $(this).attr("selected","selected");
          }
        });

        $(role).each(function() {
          if (parse.role == $(this).val()) {
            $(this).attr("selected","selected");
          }
        });

      });

      $("#editUsers").on("submit", function (event) {
        event.preventDefault();

        var full_name = $('#editUsers input[name="full_name"]').val();
        var username = $('#editUsers input[name="username"]').val();
        var user_phone = $('#editUsers input[name="user_phone"]').val();
        var user_email = $('#editUsers input[name="user_email"]').val();
        var user_pass = $('#editUsers input[name="user_password"]').val();
        var user_role = $('#editUsers #user_role option:selected').val();
        var user_status = $('#editUsers #user_status option:selected').val();
        var user_id = $("#editUsers .title-f span").text();

        if (full_name) {
          axios.post("{{ route('update-user') }}", {
            id_u: user_id,
            fullname: full_name,
            usern: username,
            phone: user_phone,       
            email: user_email,
            password: user_pass,
            role: user_role,
            status: user_status
        }).then(function (resp) {
            var data = resp.data.data;
            sendMessage("success", "User updated");
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
          text: "Once deleted, you will not be able to recover this user.",    
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
                swal({ text: 'User deleted', type: 'success' })
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
            text: "Once deleted, you will not be able to recover this users.",    
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
                  swal({ text: 'Users deleted successfully.', type: 'success' })
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

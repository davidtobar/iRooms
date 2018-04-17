@extends("core.user_dashboard")

<!-- Page variables -->
@section("pageTitle", "My Account")

<!-- Styles -->
@section("styles")
<link rel="stylesheet" href="{{ url('css/dashboard.css') }}">
@endsection

@section('content')
    <div class="inner-content-header">
        <div class="col-md-6">
            <h2>My Account</h2>
        </div>
    </div>
    <div class="inner-content-body">
      <div class="col-md-12">
        <div class="section-advanced">
            <div class="body-form">
              <div class="row">
                <div class="col-md-6 edit-profile">
                  <h3>Edit your profile</h3>
                  <form id="myProfile">
                    <div class="form-group group-advn princi-advn">  
                      <label for="inputName">Full Name</label>
                      <input id="profile_name" class="form-control meet" name="profile_name" required value="{{ $name }}">
                    </div>
                    <div class="form-group group-advn princi-advn">  
                      <label for="inputName">Telephone</label>
                      <input id="profile_phone" class="form-control meet" name="profile_phone" required value="{{ $telephone }}">
                    </div>
                    <div class="form-group group-advn princi-advn">  
                      <label for="inputName">Email</label>
                      <input id="profile_email" class="form-control meet" name="profile_email" required value="{{ $email }}">
                    </div>
                    <div class="form-group subm">  
                      <button type="submit" class="btn btn-advanced btn-block">Save changes</button>
                    </div>
                  </form>
                </div>
                <div class="col-md-6 edit-profile" id="change-pass">
                  <h3>Change your password</h3>
                  @if (session('error'))
                      <div class="alert alert-danger">
                        {{ session('error') }}
                      </div>
                  @endif
                  @if (session('success'))
                    <div class="alert alert-success">
                      {{ session('success') }}
                    </div>
                  @endif
                  <form id="updatePass" method="POST" action="{{ route('user-changePassword') }}">
                    {{ csrf_field() }}
                    <div class="group-advn princi-advn form-group{{ $errors->has('current-password') ? ' has-error' : '' }}">
                      <label for="new-password">Old Password</label>
                      <input id="current-password" type="password" class="form-control" name="current-password" required>
                        @if ($errors->has('current-password'))
                          <span class="help-block">
                              <strong>{{ $errors->first('current-password') }}</strong>
                          </span>
                        @endif
                    </div>
 
                    <div class="group-advn princi-advn form-group{{ $errors->has('new-password') ? ' has-error' : '' }}">
                      <label for="new-password">New Password</label>
                      <input id="new-password" type="password" class="form-control" name="new-password" required>
                        @if ($errors->has('new-password'))
                          <span class="help-block">
                            <strong>{{ $errors->first('new-password') }}</strong>
                          </span>
                        @endif
                    </div>
 
                    <div class="group-advn princi-advn form-group">
                        <label for="new-password-confirm">Confirm New Password</label>
                        <input id="new-password-confirm" type="password" class="form-control" name="new-password_confirmation" required>
                    </div>

                    <div class="form-group subm">  
                      <button type="submit" class="btn btn-advanced btn-block">Update password</button>
                    </div>

                  </form>
                </div>
              </div>
            </div> <!-- end body-form -->
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

            
            $("#myProfile").on("submit", function (event) {

              event.preventDefault();

              var profile_name = $('input[name="profile_name"]').val();
              var profile_phone = $('input[name="profile_phone"]').val();
              var profile_email = $('input[name="profile_email"]').val();


              if (profile_name) {
                axios.post("{{ route('user-edit-profile') }}", {
                  name: profile_name,
                  phone: profile_phone,       
                  email: profile_email
                }).then(function (resp) {
                  var data = resp.data.data;

                  sendMessage("success", "Profile updated");
                }).catch(function (err) {
                  sendMessage("error", "An error has occurred, please verify all fields are complete and try again");
                });

              } else {

              }
            });


		});
	</script>
@endsection

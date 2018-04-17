@extends("core.dashboard")

<!-- Page variables -->
@section("pageTitle", "Settings")

<!-- Styles -->
@section("styles")
<link rel="stylesheet" href="{{ url('css/dashboard.css') }}">
@endsection

@section('content')
    <div class="inner-content-header">
        <div class="col-md-6">
            <h2>Settings</h2>
        </div>
    </div>
    <div class="inner-content-body">
      <div class="col-md-12">
        <!-- Login settings -->
        <div class="section-advanced settings-adv">
          <form id="mainSetting">
            <div class="body-form">
              <h3>Main Settings</h3>
              <div class="row">
                <div class="col-md-5">
                  <div class="form-group group-advn princi-advn">
                    <label for="inputHost">App Name</label>
                    <input id="main_name" class="form-control meet" name="main_name" required="" value="{{$main_name}}">
                  </div>
                </div>
                <div class="col-md-offset-2 col-md-5">
                  <div class="form-group group-advn princi-advn">
                    <label for="inputHost">Logo Url</label>
                    <input id="main_logo" class="form-control meet" name="main_logo" required="" value="{{$main_logo}}">
                  </div>
                </div>
                <div class="col-md-5">
                  <div class="form-group group-advn">             
                    <label for="inputPort">Description</label>
                    <textarea id="main_descrip" class="form-control meet" rows="3" name="main_descrip" required>{{$main_descrip}}</textarea>
                  </div>
                </div>
                <div class="col-md-offset-2 col-md-5">
                  <div class="form-group group-advn princi-advn">
                    <label for="inputHost">Favicon Url</label>
                    <input id="main_favicon" class="form-control meet" name="main_favicon" required="" value="{{$main_favicon}}">
                  </div>
                </div>
              </div>
            </div> <!-- end body-form -->
            <div class="footer-form">
              <div class="row">
                <div class="col-md-offset-8 col-md-4 footer-links">
                  <button type="submit" class="btn btn-success btn-block"><i class="mdi mdi-plus"></i> Save</button>
                </div>
              </div>
            </div>
          </form>
        </div>

        <div class="section-advanced">
          <form id="mailSettings">
            <div class="body-form">
              <h3>Mail Settings</h3>
              <div class="row">
                <div class="col-md-5">
                  <div class="form-group group-advn princi-advn">
                    <label for="inputHost">Mail Host</label>
                    <input id="mail_host" class="form-control meet" name="mail_host" required="" value="{{ $host }}">
                  </div>
                  <div class="form-group group-advn princi-advn">             
                    <label for="inputPort">Mail Port</label>
                    <input id="mail_port" class="form-control meet" name="mail_port" required="" value="{{ $port }}">
                  </div>
                  <div class="form-group group-advn princi-advn">             
                    <label for="inputName">From Name</label>
                    <input id="from_name" class="form-control meet" name="from_name" required value="{{ $name }}">
                  </div>
                </div>
                <div class="col-md-offset-2 col-md-5">
                  <div class="form-group group-advn princi-advn">             
                    <label for="inputEmail">Smtp Email</label>
                    <input type="email" id="smtp_email" class="form-control meet" name="smtp_email" required="" value="{{ $email }}">
                  </div>
                  <div class="form-group group-advn princi-advn">             
                    <label for="inputPass">Password</label>
                    <input id="email_password" class="form-control meet" name="email_password" type="password" required value="{{ $pass }}">
                  </div>
                </div>
              </div>
            </div> <!-- end body-form -->
            <div class="footer-form">
              <div class="row">
                <div class="col-md-offset-8 col-md-4 footer-links">
                  <button type="submit" class="btn btn-success btn-block"><i class="mdi mdi-plus"></i> Save</button>
                </div>
              </div>
            </div>
          </form>
        </div>

      </div>
    </div>
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

            
      $("#mailSettings").on("submit", function (event) {

        event.preventDefault();

        var mail_host = $('input[name="mail_host"]').val();
        var mail_port = $('input[name="mail_port"]').val();
        var from_name = $('input[name="from_name"]').val();
        var smtp_email = $('input[name="smtp_email"]').val();
        var email_password = $('input[name="email_password"]').val();

        if (smtp_email) {
          axios.post("{{ route('create-settings') }}", {
            host: mail_host,
            port: mail_port,       
            name: from_name,
            email: smtp_email,
            pass: email_password,
          }).then(function (resp) {
            var data = resp.data.data;
            sendMessage("success", "Saved email settings");
          }).catch(function (err) {
            sendMessage("error", "An error has occurred, please verify all fields are complete and try again");
          });

        }            
      });


      $("#mainSetting").on("submit", function (event) {

        event.preventDefault();

        var main_logo = $('input[name="main_logo"]').val();
        var main_descrip = $('textarea[name="main_descrip"]').val();
        var main_name = $('input[name="main_name"]').val();
        var main_favicon = $('input[name="main_favicon"]').val();

        if (main_logo) {
          axios.post("{{ route('main-create') }}", {
            logo: main_logo,
            descrip: main_descrip,
            name: main_name,
            favicon: main_favicon
          }).then(function (resp) {
            var data = resp.data.data;
            sendMessage("success", "Saved main settings");
          }).catch(function (err) {
            sendMessage("error", "An error has occurred, please verify all fields are complete and try again");
          });

        }            
      });


    });
  </script>

@endsection

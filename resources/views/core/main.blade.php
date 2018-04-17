<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="icon" type="image/png" href="{{$login->favicon}}" />
  @yield("metas")

  <title>@yield("pageTitle") :: {{$login->app_name}}</title>

  <!-- Styles -->
  <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Heebo:300,400,500,700,800,900" rel="stylesheet">
  @yield("styles")

</head>
<body>
  <div id="app">
      <div class="container">
          <div class="row">
            <div class="col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">
              <div class="header">
                <!-- <h2>Welcome to iRooms</h2> -->
                <!-- <img src="{{ asset('img/' . $login->logo) }}" alt="logo iRooms"> -->
                <img src="{{$login->logo}}" alt="logo iRooms">
                <p>{{$login->description}}</p>
              </div>
            </div>

            @yield('content')
            
          </div>
        </div>

  </div>

  <script src="{{ asset('js/jquery.min.js') }}"></script>
  <script src="{{ asset('js/bootstrap.min.js') }}"></script>
  @yield("scripts")
</body>
</html>
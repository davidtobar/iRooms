@extends("core.main")

<!-- Page Title -->
@section("pageTitle", "Password Reset")

<!-- Styles -->
@section("styles")
<link rel="stylesheet" href="{{ url('css/home.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">
            <div class="panel panel-default login-form">
                <div class="panel-heading form-header">Password Reset</div>

                <div class="panel-body form-body">
                    <form class="form-horizontal" method="POST" action="{{ route('password.request') }}">
                        {{ csrf_field() }}

                        <div class="col-md-10 col-md-offset-1">
                            <input type="hidden" name="token" value="{{ $token }}">
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">

                            <div class="col-md-10 col-md-offset-1 input-s">
                                <input id="email" type="email" class="form-control" name="email" value="{{ $email or old('email') }}" required autofocus placeholder="you@example.com" >

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">

                            <div class="col-md-10 col-md-offset-1 input-s">
                                <input id="password" type="password" class="form-control" name="password" placeholder="Password"required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">

                            <div class="col-md-10 col-md-offset-1 input-s">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required placeholder="Confirm Password">

                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-3 but">
                                <button type="submit" class="btn btn-primary">
                                    Password Reset
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<!-- Scripts -->
@section("scripts")
    <script src="{{ asset('js/app.js') }}"></script>
@endsection

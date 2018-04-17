@extends("core.main")

<!-- Page Title -->
@section("pageTitle", "Password Reset")

<!-- Styles -->
@section("styles")
<link rel="stylesheet" href="{{ url('css/home.css') }}">

@section('content')
<div class="container">
    <div class="row">
        <div class="col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">
            <div class="panel panel-default login-form">
                <div class="panel-heading form-header">Password Reset</div>

                <div class="col-md-10 col-md-offset-1" id="reset-txt">
                    <p>To reset your password, enter the email address you use to login.</p>
                </div>

                <div class="panel-body form-body">
                    <div class="col-md-10 col-md-offset-1">
                        @if (session('status'))
                            <div class="alert alert-success success">
                                {{ session('status') }}
                            </div>
                        @endif
                    </div>

                    <form class="form-horizontal" method="POST" action="{{ route('password.email') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">

                            <div class="col-md-10 col-md-offset-1 input-s">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="you@example.com" required>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-3 but">
                                <button type="submit" class="btn btn-primary">
                                    Request
                                </button>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-offset-6 col-md-6 help">
                                <a class="btn btn-link" href="{{ route('login') }}">
                                    Back to Login
                                </a>
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

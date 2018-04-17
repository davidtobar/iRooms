
@extends("core.main")

<!-- Page Title -->
@section("pageTitle", "Home")

<!-- Styles -->
@section("styles")
<link rel="stylesheet" href="{{ url('css/home.css') }}">
@endsection

<!-- Content -->
@section("content")
        <div class="col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">
            <div class="panel panel-default login-form">
                <div class="panel-heading form-header">Login to your account</div>

                <div class="panel-body form-body">
                    <form class="form-horizontal" method="POST" action="{{ route('login-post') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">

                            <div class="col-md-10 col-md-offset-1 input-s">
                                <input id="email" type="text" class="form-control" name="email" value="{{ old('email') }}" placeholder="Username" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">

                            <div class="col-md-10 col-md-offset-1 input-s">
                                <input id="password" type="password" class="form-control" name="password" placeholder="Password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-3 but">
                                <button type="submit" class="btn btn-primary">
                                    Sign in
                                </button>
                            </div>
                        </div>

                        <div class="form-group">
                        	<div class="col-md-6 help">
                        		<div class="checkbox">
                        		    <label>
                        		        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                        		    </label>
                        		</div>
                        	</div>
                            <div class="col-md-6 help">
                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    Forgot password?
                                </a>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>

@endsection
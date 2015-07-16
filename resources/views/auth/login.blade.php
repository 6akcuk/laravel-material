@extends('app')

@section('css')
  <link rel="stylesheet" href="css/auth.css">
@stop

@section('content')
  <div class="container">
    <div class="col-lg-6">
      <form class="form-horizontal" method="POST" action="{{ url('/auth/login') }}">
        <fieldset>
          {!! csrf_field() !!}

          <legend>Login</legend>

          @if (count($errors) > 0)
            <div class="alert alert-danger">
              <strong>Whoops!</strong> There were some problems with your input.<br><br>
              <ul>
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <div class="form-group">
            <label for="email" class="control-label col-sm-3">Email</label>
            <div class="col-sm-6">
              <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}">
            </div>
          </div>

          <div class="form-group">
            <label for="password" class="control-label col-sm-3">Password</label>
            <div class="col-sm-6">
              <input type="password" id="password" name="password" class="form-control" id="password">
            </div>
          </div>

          <div class="form-group">
            <div class="col-sm-offset-3 col-sm-6">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="remember"> Remember Me
                </label>
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="col-sm-offset-3 col-sm-6">
              <button class="btn btn-primary" type="submit">Login</button>

              <a class="btn btn-link" href="{{ url('/password/email') }}">Forgot Password?</a>
            </div>
          </div>
        </fieldset>
      </div>
    </div>
  </div>
@stop

@section('javascript')
  <script type="text/javascript" src="js/auth.js"></script>
@stop
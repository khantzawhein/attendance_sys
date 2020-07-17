<!DOCTYPE html>
<html>
    <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <title>Attendance System | Log in</title>
      <!-- Tell the browser to be responsive to screen width -->
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" href="{{asset('css/app.css')}}">
        <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">

      <!-- Google Font: Source Sans Pro -->
      <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    </head>
    <body class="hold-transition login-page">
    <div id="app"></div>
    <div class="login-box">
      <div class="login-logo">
        <a href="/"><b>DUCS </b>Attendance System</a>
      </div>
      <!-- /.login-logo -->
      <div class="card">
        <div class="card-body login-card-body">
            @if(session()->has('registered'))
                <div class="alert alert-success">
                    {{session('registered')}}
                </div>
            @endif
          <p class="login-box-msg">Sign in to the system to continue</p>
          <form action="{{ route('login') }}" method="post">
              @csrf
            <div class="input-group mb-3">
              <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email" required value="{{old('email')}}">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-envelope"></span>
                </div>
              </div>
             @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
            </div>
            <div class="input-group mb-3">
              <input name="password" type="password" class="form-control @error('password') is-invalid @enderror" required placeholder="Password">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-lock"></span>
                </div>
              </div>
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="row">
              <div class="col-8">
                <div class="icheck-primary">
                  <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                  <label for="remember">
                    Remember Me
                  </label>
                </div>
              </div>
              <!-- /.col -->
              <div class="col-4">
                <button type="submit" class="btn btn-primary btn-block">Sign In</button>
              </div>
              <!-- /.col -->
            </div>
          </form>

          <p class="mb-1">
            <a href="{{ route('password.request') }}">Forgot password?</a>
          </p>
          <p class="mb-0">
            New student? <a href="{{ route('register') }}" class="text-center">Register here.</a>
          </p>
        </div>
        <!-- /.login-card-body -->
      </div>
    </div>
    <!-- /.login-box -->
    <script src="{{asset('js/app.js')}}"></script>

</body>
</html>

<!DOCTYPE html>
<html>
    <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <title>Attendance System | Reset Password</title>
      <!-- Tell the browser to be responsive to screen width -->
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" href="{{asset('css/app.css')}}">

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
                <p class="login-box-msg">You are only one step a way from your new password, recover your password now.</p>
                <form action="{{ route('password.update') }}" method="post">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <div class="input-group mb-3">
                      <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email" required autocomplete="email" value="{{ $email ?? old('email') }}">
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
                      <input type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" name="password" required autofocus autocomplete="new_password">
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
                    <div class="input-group mb-3">
                      <input type="password" class="form-control" placeholder="Confirm Password" name="password_confirmation" autocomplete="new_password">
                      <div class="input-group-append">
                        <div class="input-group-text">
                          <span class="fas fa-lock"></span>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block">Reset password</button>
                      </div>
                      <!-- /.col -->
                    </div>
                </form>
            <!-- /.login-card-body -->
            </div>
          </div>
        </div>
        <!-- /.login-box -->
        <script src="{{asset('js/app.js')}}"></script>
    </body>
</html>

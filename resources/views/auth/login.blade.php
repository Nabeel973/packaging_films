<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>International Packaging Films Ltd | Log In</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset("plugins/fontawesome-free/css/all.min.css")}}">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{asset("plugins/icheck-bootstrap/icheck-bootstrap.min.css")}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset("dist/css/adminlte.min.css")}}">

  <style>
    body {
      background:  url('{{ asset('dist/img/LP 1_2.jpg') }}') no-repeat center center fixed;
      background-size: cover;
    }

    .card {
      background-color: rgba(255, 255, 255, 0.6); /* White with 80% opacity */
    }
    .login-card-body {
      background-color: transparent; /* Ensure inner card body is transparent */
    }
   
  </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <h3 class="text-black font-weight-bold">International Packaging Films Ltd</h3>
  </div>
  <!-- /.login-logo -->
  <div class="card card-md">
    <div class="card-body login-card-body">
      <h3 class="login-box-msg font-weight-bold">SIGN IN</h3>
      <x-auth-session-status class="mb-4 text-center" :status="session('status')" />
      <form method="POST" action="{{ route('login') }}" id="quickForm">
        @csrf        
        <div class="input-group mb-3 form-group">
          <input type="email" class="form-control" name="email" placeholder="Email" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
          {{-- @if ($errors->has('email'))
            <span class="text-danger">{{ $errors->first('email') }}</span>
          @endif --}}
        </div>
       
        <div class="input-group mb-3 form-group">
          <input type="password" class="form-control" name="password" placeholder="Password" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
          {{-- @if ($errors->has('password'))
          <span class="text-danger">{{ $errors->first('password') }}</span>
        @endif --}}
        </div>
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" name="remember" id="remember" class="checkbox">
              <label for="remember">
                Remember Me
              </label>
            </div>
          </div>
        </div>
        <div class="row mt-2">
            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
        </div>
      </form>

      <p class="mt-2 text-center">
        <a href="{{ route('password.request') }}" class="font-weight-bold">Forgot Password</a>
      </p>
    </div>
  </div>
</div>

<!-- jQuery -->
<script src="{{asset("plugins/jquery/jquery.min.js")}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset("plugins/bootstrap/js/bootstrap.bundle.min.js")}}"></script>
<!-- AdminLTE App -->
<script src="{{asset("dist/js/adminlte.min.js")}}"></script>
<!-- jquery-validation -->
<script src="{{asset("plugins/jquery-validation/jquery.validate.min.js")}}"></script>
<script src="{{asset("plugins/jquery-validation/additional-methods.min.js")}}"></script>
<script>
  $(function () {
    $.validator.setDefaults({
      submitHandler: function(form) {
        form.submit(); // Submit the form
      }
    });
    $('#quickForm').validate({
      rules: {
        email: {
          required: true,
          email: true,
        },
        password: {
          required: true,
          minlength: 8
        }
      },
      messages: {
        email: {
          required: "Please enter a email address",
          email: "Please enter a valid email address"
        },
        password: {
          required: "Please provide a password",
          minlength: "Your password must be at least 8 characters long"
        },
        //terms: "Please accept our terms"
      },
      errorElement: 'span',
      errorPlacement: function (error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
      },
      highlight: function (element, errorClass, validClass) {
        $(element).addClass('is-invalid');
      },
      unhighlight: function (element, errorClass, validClass) {
        $(element).removeClass('is-invalid');
      }
    });
  });
  </script>
</body>
</html>

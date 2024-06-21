<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Recover Password</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="../../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <h3>International Packaging Films Ltd</h3>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">You are only one step a way from your new password, reset your password now.</p>
      <x-auth-session-status class="mb-4 text-center" :status="session('status')" />
      <form method="POST" action="{{ route('password.store') }}" id="quickForm">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">
    
        <div class="input-group mb-3 form-group">
          <input type="password" class="form-control" placeholder="Password" name="password" id="password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>

        <div class="input-group mb-3 form-group">
          <input type="password" class="form-control" placeholder="Confirm Password" name="password_confirmation">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Change password</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

      <p class="mt-3 mb-1  text-center">
        <a href="{{ route('login') }}">
          <i class="fas fa-arrow-left"></i> Back to Login
        </a>
      </p>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>
<!-- jquery-validation -->
<script src="../../plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="../../plugins/jquery-validation/additional-methods.min.js"></script>
<script>
  $(function () {
    $.validator.setDefaults({
      submitHandler: function(form) {
        form.submit(); // Submit the form
      }
    });
    $('#quickForm').validate({
      rules: {
        password: {
          required: true,
          minlength: 8
        },
        password_confirmation: {
          required: true,
          minlength: 8,
          equalTo: "#password"
        }
      },
      messages: {

        password: {
          required: "Please provide a password",
          minlength: "Your password must be at least 8 characters long"
        },
        password_confirmation: {
          required: "Please confirm your password",
          minlength: "Your password must be at least 8 characters long",
          equalTo: "Passwords do not match"
        }
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

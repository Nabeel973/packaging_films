@extends('admin.app')

@section('content-header')
<h1>Dashboard {{ session('change_password') }}</h1>
@endsection

@section('content')
<div class="row">
    <!-- Your content here -->
</div>
<!-- /.row -->

<!-- Password Reset Modal -->
<div class="modal fade" id="reset-password-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header justify-content-center">
                <h4 class="modal-title">Reset Password</h4>
            </div>
            <div class="modal-body">
              <form id="quickForm">
                @csrf
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

              </form>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="submit" class="btn btn-primary" onclick="submitResetPassword()">Reset Password</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
@endsection

@section('scripts')
<!-- Ensure the correct path to jquery-validation plugin -->
<script src="{{ asset('plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('plugins/jquery-validation/additional-methods.min.js') }}"></script>

<script src="../../plugins/toastr/toastr.min.js"></script>

<script>
  $(document).ready(function() {
      // Check session value and show modal if needed
      @if(session('change_password') == 0)
          $('#reset-password-modal').modal({
              backdrop: 'static',
              keyboard: false
          });
          $('#reset-password-modal').modal('show');
      @endif

      // jQuery validation for the form
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

  function submitResetPassword() {
      if ($('#quickForm').valid()) {  // Check if the form is valid
          const formData = $('#quickForm').serialize();

          $.ajax({
              url: '{{ route('user_password.reset') }}', // Adjust the route to your password reset endpoint
              type: 'POST',
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              data: formData,
              success: function(response) {
                  if (response.success) {
                      $('#reset-password-modal').modal('hide');
                     
                      toastr.success('Password Reset Successfully')
                      // Optionally, refresh the page or redirect
                  } else {
                      //$('#password-error').text(response.message).removeClass('d-none');
                      toastr.error(response.message);
                  }
              },
              error: function(xhr) {
                  let errorMessage = 'An error occurred. Please try again.';
                  if (xhr.responseJSON && xhr.responseJSON.message) {
                      errorMessage = xhr.responseJSON.message;
                  }
                  toastr.error(errorMessage);
              }
          });
      }
  }
</script>

@endsection

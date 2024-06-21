@extends('admin.app')

@section('content-header')
  <h1>Edit User</h1>
@endsection

@section('content')

  <!-- Main row -->
  <div class="card">
    <div class="card-body">
      <x-auth-session-status class="mb-4 text-center" :status="session('status')" />
        <form id="quickForm" method="post" 
        action="{{route('user.update', $user->id)}}"
        >
          @csrf
          @method('PUT')
          <div class="row mb-2">
            <div class="col-md-6">
              <div class="form-group">
                <label for="exampleInputName">Enter Name</label>
                <input type="text" name="name" class="form-control" id="exampleInputName" placeholder="Enter Name" value="{{ old('name', $user->name) }}">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="exampleInputEmail1">Email Address</label>
                <input type="email" name="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email" value="{{ old('email', $user->email) }}" aria-describedby="exampleInputEmail1-error" aria-invalid="true">
              </div>
            </div>
          </div>
          <div class="row mb-2">
            <div class="col-md-6">
              <div class="form-group">
                <label for="password">Enter Password</label>
                <input type="password" name="password" class="form-control" id="password" placeholder="Enter Password">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="exampleInputConfirmPassword">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control" id="exampleInputConfirmPassword" placeholder="Confirm Password">
              </div>
            </div>
          </div>
          <div class="row mb-2">
            <div class="col-md-6">
              <div class="form-group">
                <label>Select Role</label>
                <select class="role form-control" id="exampleInputRole" name="role">
                </select>
              </div>
            </div> 
          </div>
          <div class="row justify-content-center mt-2">
            <button type="submit" class="btn btn-primary btn-lg">Update User</button>
          </div>
        </form>
    </div>
  </div>
@endsection


@section('scripts')
<!-- jquery-validation -->
<script src="{{asset("plugins/jquery-validation/jquery.validate.min.js")}}"></script>
<script src="{{asset("plugins/jquery-validation/additional-methods.min.js")}}"></script>
<!-- Select2 -->
<script src="{{asset("plugins/select2/js/select2.full.min.js")}}"></script>

<script>
  $(document).ready(function() {
  
    var roles = {!! json_encode($roles) !!};
    var user = {!! json_encode($user) !!};
  
    $(".role").select2({
      placeholder: "Select Role",
      data: roles.map(function(role) {
        return { id: role.id, text: role.name };
      })
    });
  
    $(".role").val(user.role_id).trigger('change');
  
    $.validator.setDefaults({
      submitHandler: function(form) {
        form.submit(); // Submit the form
      }
    });
  
    $('#quickForm').validate({
      rules: {
        name: {
          required: true,
        },
        email: {
          required: true,
          email: true,
        },
        password: {
          minlength: 8
        },
        password_confirmation: {
          minlength: 8,
          equalTo: "#password"
        },
        role: {
          required: true,
        }
      },
      messages: {
        name: {
          required: "Please enter a name",
        },
        email: {
          required: "Please enter an email address",
          email: "Please enter a valid email address"
        },
        password: {
          minlength: "Your password must be at least 8 characters long"
        },
        password_confirmation: {
          minlength: "Your password must be at least 8 characters long",
          equalTo: "Passwords do not match"
        },
        role: {
          required: "Select a Role",
        },
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
  
@endsection


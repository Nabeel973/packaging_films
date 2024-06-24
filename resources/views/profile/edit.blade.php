@extends('admin.app')

@section('content-header')
  <h1>Edit Profile</h1>
@endsection

@section('content')

  <!-- Main row -->
  <div class="card">
    <div class="card-body">
      <x-auth-session-status class="mb-4 text-center" :status="session('status')" />
        <div class="row">
                       <!-- Image Section -->
            <div class="col-md-4">
                <div class="text-center">
                    <img src="{{ url('storage/' . $user->image) }}" class="rounded-circle img-fluid" alt="Profile Image" height="500px" width="500px">
                    {{-- <img src="{{ url($user->image) }}" class="rounded-circle img-fluid" alt="Profile Image"> --}}
                </div>
            </div>
            <!-- Form Details Section -->
            <div class="col-md-8">
                <h2>Profile Details</h2>
                <form id="quickForm" action="{{route('profile.update')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="form-group">
                        <label for="fullName">Full Name</label>
                        <input type="text" class="form-control" id="fullName" name="name" placeholder="Enter your full name" required value="{{ old('name', $user->name) }}">
                    </div>
                    <div class="form-group">
                        <label for="email">Email address</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required value="{{ old('email', $user->email) }}">
                    </div>
                    {{-- <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" class="form-control" id="phone" name="phone" placeholder="Enter your phone number" required>
                    </div> --}}
                    <div class="form-group">
                        <label for="image">Profile Image</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/jpeg,image/png">
                      </div>
                    {{-- <button type="submit" class="btn btn-primary mt-3">Save</button> --}}
                    <button type="submit" name="action" value="update" class="btn btn-primary btn-lg mx-2">
                        <i class="fas fa-save"></i> Save
                      </button>
                </form>
            </div>
        </div>
        </div>
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
     $(document).ready(function () {
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
                    phone: {
                        required: true,
                    },
                    image: {
                        //required: true,
                        accept: "image/*",
                        filesize: 1048576 // 1 MB in bytes
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
                    phone: {
                        required: "Please enter a phone number",
                    },
                    image: {
                        //required: "Please upload a profile image",
                        accept: "Only image files are allowed",
                        filesize: "Image size must be less than 1MB"
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

            // Custom validator for file size
            $.validator.addMethod('filesize', function (value, element, param) {
                if (element.files.length == 0) {
                    return true; // No file selected, so pass validation
                }
                return this.optional(element) || (element.files[0].size <= param);
            }, 'File size must be less than 1MB');
        });
</script>
  
@endsection


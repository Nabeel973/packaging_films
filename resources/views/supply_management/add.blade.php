@extends('admin.app')

@section('content-header')
  <h1>Add New Supplier</h1>
@endsection

@section('content')

  <!-- Main row -->
  <div class="card">
    <div class="card-body">
      <x-auth-session-status class="mb-4 text-center" :status="session('status')" />
        <form id="quickForm" method="post" action="{{route('supplier.submit')}}">
          @csrf
          <div class="row mb-2 justify-content-center">
            <div class="col-md-6">
              <div class="form-group">
                <label for="exampleInputEmail1">Enter Name</label>
                <input type="text" name="name" class="form-control" id="exampleInputName" placeholder="Enter Name">
              </div>
            </div>
          </div>
          <div class="row justify-content-center mt-2">
            <button type="submit" class="btn btn-primary btn-lg">Create New Supplier</button>
          </div>
      
        </form>
    </div>
  </div>
@endsection

@section('scripts')
<!-- jquery-validation -->
<script src="{{asset("plugins/jquery-validation/jquery.validate.min.js")}}"></script>
<script src="{{asset("plugins/jquery-validation/additional-methods.min.js")}}"></script>

<script>

  $(document).ready(function() {
  
  
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
        },
        messages: {
          name: {
            required: "Please enter a name",
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


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
            <button type="submit" name="action" value="update" class="btn btn-warning btn-lg mx-2" id="submit-button">
              <i class="fas fa-save pr-2"></i> Create New Supplier
            </button>
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
          $('#submit-button').prop('disabled', true);
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
          $('#submit-button').prop('disabled', false);

        },
        unhighlight: function (element, errorClass, validClass) {
          $(element).removeClass('is-invalid');
        }
      });

      $('#quickForm').on('invalid-form.validate', function() {
        $('#submit-button').prop('disabled', false);
      });

  });

  
  </script>
@endsection


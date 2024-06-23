@extends('admin.app')

@section('content-header')
  <h1>Add New LC Request</h1>
@endsection

@section('content')

  <!-- Main row -->
  <div class="card">
    <div class="card-body">
      <x-auth-session-status class="mb-4 text-center" :status="session('status')" />
        <form id="quickForm" method="post" action="{{route('lc_request.submit')}}" enctype="multipart/form-data">
          @csrf
          <div class="row mb-2">
            <div class="col-md-6">
              <div class="form-group">
                <label for="shipmentName">Shipment Name*</label>
                <input type="text" name="shipment_name" class="form-control" id="shipmentName" placeholder="Enter Shipment Name">
              </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Select Supplier*</label>
                    <select class="supplier form-control" id="supplier" name="supplier">
                    </select>
                    
                </div>
          </div>
          </div>
          <div class="row mb-2">
            <div class="col-md-6">
              <div class="form-group">
                <label for="itemName">Item Name</label>
                <input type="text" name="item_name" class="form-control " id="item_name" placeholder="Enter Item Name">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="itemQuantity">Item Quantity</label>
                <input type="number" name="item_quantity" class="form-control" id="item_quantity" placeholder="Enter Quantity">
              </div>
            </div>
          </div>
          <div class="row mb-2">
            <div class="col-md-6">
              <div class="form-group">
                <label>Payment Terms*</label>
                <input type="text" name="payment_terms" class="form-control" id="payment_terms" placeholder="Payment Terms">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Attach Performa Invoice*</label>
                <input type="file" class="form-control" id="performa_invoice" name="performa_invoice">
              </div>
            </div>
          </div>
          <div class="row mb-2">
            <div class="col-md-6">
              <div class="form-group">
                <label for="otherDocuments">Other Document</label>
                <input type="file" class="form-control" id="other_document" name="other_document">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-check mt-4">
                <input class="form-check-input" type="checkbox" name="draft_required">
                <label class="form-check-label">LC Draft Required</label>
              </div>
            </div>
          </div>
          <div class="row justify-content-center mt-2">
            <button type="submit" class="btn btn-primary btn-lg">Generate New Request</button>
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
      var supplier_names = {!! json_encode($supplier_names) !!};

      $(".supplier").select2({
        placeholder: "Select Supplier",
        data: supplier_names.map(function(supplier) {
            return { id: supplier.id, text: supplier.name };
        }),
        //allowClear: true // Add this line to allow clearing the selection
      });

      $.validator.setDefaults({
        submitHandler: function(form) {
          form.submit(); // Submit the form
        }
      });
      
      $('#quickForm').validate({
        rules: {
          shipment_name: {
            required: true,
          },
          supplier: {
            required: true,
          },
          payment_terms: {
            required: true,
          },
          performa_invoice: {
            required: true,
            extension: "pdf|doc|docx|png|jpg|jpeg",
          },
          other_document: {
            extension: "pdf|doc|docx|png|jpg|jpeg",
          },
        },
        messages: {
          shipment_name: {
            required: "Please enter a shipment name",
          },
          supplier: {
            required: "Please select a supplier",
          },
          payment_terms: {
            required: "Please enter payment terms",
          },
          performa_invoice: {
            required: "Please attach a performa invoice",
            extension: "Please upload a valid PDF, DOC, or DOCX file",
          },
          other_document: {
            extension: "Please upload a valid PDF,DOC,PNG,JPEG,JPG, or DOCX file",
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

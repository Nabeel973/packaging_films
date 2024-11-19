@extends('admin.app')

@section('content-header')
  <h1>Add New Import Request</h1>
@endsection

@section('content')

  <!-- Main row -->
  <div class="card">
    <div class="card-body">
      <x-auth-session-status class="mb-4 text-center" :status="session('status')" />
        <form id="quickForm" method="post" action="{{route('import_request.submit')}}" enctype="multipart/form-data">
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
                <label for="itemName">Item Name*</label>
                <input type="text" name="item_name" class="form-control " id="item_name" placeholder="Enter Item Name">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="itemQuantity">Item Quantity*</label>
                <input type="number" name="item_quantity" class="form-control" id="item_quantity" placeholder="Enter Quantity">
              </div>
            </div>
          </div>
          
          <div class="row mb-2">
            <div class="col-md-6">
              <div class="form-group">
                <label for="amount">Amount</label>
                <input type="number" name="amount" class="form-control" id="amount" placeholder="Enter Amount">
              </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Select Currency</label>
                    <select class="currency form-control" id="currency" name="currency">
                    </select>
                    
                </div>
            </div>
          </div>
          <div class="row mb-4">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Request Type*</label>
                    <select class="request_type_id form-control" id="request_type_id" name="request_type_id">
                    </select>
                    
                </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                  <label>Select Company*</label>
                  <select class="company form-control" id="company" name="company_id">
                  </select>
                  
              </div>
          </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Add Comments</label>
                <textarea id="comments" cols="8" class="form-control" maxlength="1000" name="comments"></textarea>
              </div>
            </div>
          </div>
         
            @php
                $documents = [
                    'invoice' => 'Invoice',
                    'shipping_document' => 'Shipping Document',
                    'paid_gd' => 'Duty Paid GDs'
                ];
                 $title = 'Add Documents';
                 $layout = 'vertical';
                 $disabled = false
            @endphp
            @include('components.document-upload', ['documents' => $documents,'layout' => $layout, 'disable' => $disabled])

          <div class="row justify-content-center mt-2">
        
            <button type="submit" name="action" class="btn btn-warning btn-lg mx-2" id="submit-button">
              <i class="fas fa-save pr-2"> </i> Generate New Request
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
  <!-- Select2 -->
  <script src="{{asset("plugins/select2/js/select2.full.min.js")}}"></script>
  <script>
    $(document).ready(function() {

      var supplier_names = {!! json_encode($supplier_names) !!};
      var companies = {!! json_encode($companies) !!};
      var payments = {!! json_encode($payments) !!};
      var request_types = {!! json_encode($request_types) !!};

      $(".supplier").select2({
        placeholder: "Select Supplier",
        data: supplier_names.map(function(supplier) {
            return { id: supplier.id, text: supplier.name };
        }),
        width: '100%',
        dropdownAutoWidth: true,
        //allowClear: true // Add this line to allow clearing the selection
      });

      $(".supplier").val('').trigger('change');

      var currency_names = {!! json_encode($currencies) !!};

      $(".currency").select2({
        placeholder: "Select Currency",
        data: currency_names.map(function(currency) {
            return { id: currency.id, text: currency.name };
        }),
        width: '100%',
        dropdownAutoWidth: true,
        //allowClear: true // Add this line to allow clearing the selection
      });

      $(".currency").val('').trigger('change');


      $(".payment_id").select2({
        placeholder: "Select Payment",
        data: payments.map(function(payment) {
            return { id: payment.id, text: payment.name };
        }),
        width: '100%',
        dropdownAutoWidth: true,
        //allowClear: true // Add this line to allow clearing the selection
      });

      $(".payment_id").val('').trigger('change');


      $(".request_type_id").select2({
        placeholder: "Request Type",
        data: request_types.map(function(request_type) {
            return { id: request_type.id, text: request_type.name };
        }),
        width: '100%',
        dropdownAutoWidth: true,
        //allowClear: true // Add this line to allow clearing the selection
      });

      $(".request_type_id").val('').trigger('change');


      $(".company").select2({
        placeholder: "Select Company",
        data: companies.map(function(company) {
            return { id: company.id, text: company.name };
        }),
        width: '100%',
        dropdownAutoWidth: true,
        //allowClear: true // Add this line to allow clearing the selection
      });

      $(".company").val('').trigger('change');

      $.validator.setDefaults({
        submitHandler: function(form) {
          $('#submit-button').prop('disabled', true);
          // $('#loader').show();
          form.submit(); // Submit the form
        }
      });
      


      $('#quickForm').validate({
        rules: {
          shipment_name: {
            required: true,
          },
          request_type_id: {
            required: true,
          },
          company_id: {
            required: true,
          },
          item_name: {
            required: true,
          },
          item_quantity: {
            required: true,
          },
          supplier: {
            required: true,
          },
          invoice: {
            extension: "pdf|doc|docx|png|jpg|jpeg",
          },
          shipping_document: {
            extension: "pdf|doc|docx|png|jpg|jpeg",
          },
          paid_gd: {
            extension: "pdf|doc|docx|png|jpg|jpeg",
          },
         
        },
        messages: {
          shipment_name: {
            required: "Please enter a shipment name",
          },
          request_type_id: {
            required: "Please select a request type",
          },
          item_name: {
            required: "Please enter a item name",
          },
          item_quantity: {
            required: "Please enter a item name",
          },
          company_id: {
            required: "Please select a company",
          },
          supplier: {
            required: "Please select a supplier",
          },

        invoice: {
            extension: "Please upload a valid PDF, DOC, or DOCX file",
          },
          shipping_document: {
            extension: "Please upload a valid PDF, DOC, or DOCX file",
          },
          paid_gd: {
            extension: "Please upload a valid PDF, DOC, or DOCX file",
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
          // $('#loader').hide();
        },
        unhighlight: function (element, errorClass, validClass) {
          $(element).removeClass('is-invalid');
        }
      });

      $('#quickForm').on('invalid-form.validate', function() {
        $('#submit-button').prop('disabled', false);
        // $('#loader').hide();
      });

    });
  </script>
@endsection

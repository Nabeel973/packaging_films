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
              <div class="form-check mt-4">
                <input class="form-check-input" type="checkbox" name="draft_required">
                <label class="form-check-label">LC Draft Required</label>
              </div>
            </div>
          </div>
          <div class="row mt-4">
            <div class="col-md-6">
              <div id="dynamic-form">
                <div class="row" id="document-row-1">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="otherDocuments">Other Document</label>
                            <input type="file" class="form-control" id="document1" name="document1">
                        </div>
                    </div>
                    <div class="col-md-1 d-flex align-items-center mt-2">
                      <button type="button" class="btn btn-success" id="add-field"><i class="fas fa-plus"></i></button>
                    </div>
                    <div class="col-md-1 d-flex align-items-center">
                        <i class="delete-icon" style="display:none;">&#10006;</i>
                    </div>
                   
                </div>
            </div>
              {{-- <button type="button" class="btn btn-primary mt-3" id="add-field">Add Document</button> --}}
            </div>
          </div>
          <div class="row justify-content-center mt-2">
            {{-- <button type="submit" class="btn btn-primary btn-lg">Generate New Request</button> --}}
            <button type="submit" name="action" value="update" class="btn btn-primary btn-lg mx-2">
              <i class="fas fa-save"></i> Generate New Request
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

    let fieldCount = 1;
    const maxFields = 5;

    // Function to check if the last input is filled
    function checkLastInput() {
        return $('#dynamic-form input[type="file"]').last().val() !== "";
    }

    // Enable the Add button only if the last input is filled and max fields not reached
    function toggleAddButton() {
        if (checkLastInput() && fieldCount < maxFields) {
            $('#add-field').prop('disabled', false);
        } else {
            $('#add-field').prop('disabled', true);
        }
    }

    // Initial check
    toggleAddButton();

    // Event to add new field
    $('#add-field').on('click', function() {
        if (fieldCount < maxFields) {
            fieldCount++;
            const newRow = `
                <div class="row" id="document-row-${fieldCount}">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="otherDocuments">Other Document</label>
                            <input type="file" class="form-control" id="document${fieldCount}" name="document${fieldCount}">
                        </div>
                    </div>
                    <div class="col-md-2 d-flex align-items-center">
                        <button type="button" class="btn btn-danger delete-row mt-2"><i class="fa fa-trash" aria-hidden="true"></i></button>
                    </div>
                </div>
            `;
            $('#dynamic-form').append(newRow);
            toggleAddButton();
        }
    });

    // Event to handle file input change
    $(document).on('change', 'input[type="file"]', function() {
        toggleAddButton();
    });

    // Event to delete a row
    $(document).on('click', '.delete-row', function() {
        $(this).closest('.row').remove();
        fieldCount--;
        toggleAddButton();
    });
    });
  </script>
@endsection

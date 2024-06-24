@extends('admin.app')

@section('content-header')
  <h1>Edit LC Request</h1>
@endsection

@section('content')

  <!-- Main row -->
  <div class="card">
    <div class="card-body">
      <x-auth-session-status class="mb-4 text-center" :status="session('status')" />
        <form id="quickForm" method="post" action="{{ route('lc_request.update', $lcRequest->id) }}" enctype="multipart/form-data">
          @csrf
          @method('PUT')
          <div class="row mb-2">
            <div class="col-md-6">
              <div class="form-group">
                <label for="shipmentName">Shipment Name*</label>
                <input type="text" name="shipment_name" class="form-control" id="shipmentName" placeholder="Enter Shipment Name" value="{{ $lcRequest->shipment_name }}" disabled="{{$disable}}">
              </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Select Supplier*</label>
                    <select class="supplier form-control" id="supplier" name="supplier" disabled="{{$disable}}">
                    </select>
                </div>
          </div>
          </div>
          <div class="row mb-2">
            <div class="col-md-6">
              <div class="form-group">
                <label for="itemName">Item Name</label>
                <input type="text" name="item_name" class="form-control " id="item_name" placeholder="Enter Item Name" value="{{ $lcRequest->item_name }}" disabled="{{$disable}}">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="itemQuantity">Item Quantity</label>
                <input type="number" name="item_quantity" class="form-control" id="item_quantity" placeholder="Enter Quantity" value="{{ $lcRequest->quantity }}" disabled="{{$disable}}">
              </div>
            </div>
          </div>
          <div class="row mb-2">
            <div class="col-md-6">
              <div class="form-group">
                <label>Payment Terms*</label>
                <input type="text" name="payment_terms" class="form-control" id="payment_terms" placeholder="Payment Terms" value="{{ $lcRequest->payment_terms }}" disabled="{{$disable}}">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Attach Performa Invoice*</label>
                <input type="file" class="form-control" id="performa_invoice" name="performa_invoice" disabled="{{$disable}}">
                <p>{{ $lcRequest->performa_invoice }}</p>
              </div>
            </div>
          </div>
          <div class="row mb-2">
            <div class="col-md-6">
              <div class="form-group">
                <label for="otherDocuments">Other Document</label>
                <input type="file" class="form-control" id="other_document" name="other_document" disabled="{{$disable}}">
                <p>{{ $lcRequest->other_document }}</p>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" name="draft_required" {{ $lcRequest->draft_required ? 'checked' : '' }} disabled="{{$disable}}">
                <label class="form-check-label">LC Draft Required</label>
              </div>
            </div>
          </div>
          {{-- <div class="row justify-content-center mt-2">
            <button type="submit" name="action" value="reject" class="btn btn-danger btn-lg mx-2">Reject</button>
            <button type="submit" name="action" value="update" class="btn btn-primary btn-lg mx-2">Update</button>
            <button type="submit" name="action" value="approve" class="btn btn-success btn-lg mx-2">Approve</button>
          </div> --}}
          <div class="row justify-content-center mt-2">
            @if(session('role_id') == 1 || (session('role_id') == 2 && $lcRequest->status_id  == 1))
                <button type="button" class="btn btn-danger btn-lg mx-2" id="reject">
                <i class="fas fa-times"></i> Reject
                </button>
            @endif    
            @if(session('role_id') == 1 || (session('role_id') == 5 && $lcRequest->status_id  == 3))
                <button type="submit" name="action" value="update" class="btn btn-primary btn-lg mx-2">
                <i class="fas fa-save"></i> Update
                </button>
            @endif    
            
            @if(session('role_id') == 1 || (session('role_id') == 2 && $lcRequest->status_id  == 1))
                <button type="submit" name="action" value="approve" class="btn btn-success btn-lg mx-2">
                <i class="fas fa-check"></i> Approve
                </button>
            @endif    
          </div>
        </form>
    </div>
  </div>


  <div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="cancelModalLabel">Reject Reason</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="cancelReasonForm">
            @csrf
            <div class="form-group">
              <label for="cancelReasonTextarea">Enter Reason*</label>
              <textarea class="form-control" id="cancelReasonTextarea" name="cancel_reason" rows="3" required></textarea>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" id="submitCancelReason" class="btn btn-primary">Submit</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  <!-- jquery-validation -->
  <script src="{{ asset('plugins/jquery-validation/jquery.validate.min.js') }}"></script>
  <script src="{{ asset('plugins/jquery-validation/additional-methods.min.js') }}"></script>
  <!-- Select2 -->
  <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
  <script src="../../plugins/toastr/toastr.min.js"></script>

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

      $(".supplier").val({{ $lcRequest->supplier_id }}).trigger('change');

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
            extension: "Please upload a valid PDF, DOC, DOCX, PNG, JPG, or JPEG file",
          },
          other_document: {
            extension: "Please upload a valid PDF, DOC, DOCX, PNG, JPG, or JPEG file",
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

      $('#reject').click(function() {
        $('#cancelModal').modal('show');
      });

      $('#submitCancelReason').click(function() {
        if ($('#cancelReasonTextarea').val() !== '') {
            var reason = $('#cancelReasonTextarea').val();
            var lc_request_id = {{$lcRequest->id}};

            $.ajax({
            url: '{{ route("lc_request.reject-reason") }}', // Change to your route
            method: 'POST',
            data: {
                reason: reason,
                lc_request_id: lc_request_id,
            },
            success: function(response) {
                // Handle success response
                $('#cancelModal').modal('hide');
            },
            error: function(xhr, status, error) {
                // Handle error
                toastr.error(xhr.responseText);
            }
            });
          
        } else {
          // Show error message or handle validation
          toastr.error('Please provide a reject reason');
        }
      });


    });
  </script>
@endsection

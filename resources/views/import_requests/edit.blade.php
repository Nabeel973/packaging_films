@extends('admin.app')

@section('content-header')
  <h1>Edit Import Request
@endsection

@section('content')

  <!-- Main row -->
  <div class="card">
    <div class="card-body">
      <x-auth-session-status class="mb-4 text-center" :status="session('status')" />
        <div class="font-medium text-sm text-black bg-warning p-2 border rounded-md text-center mb-2">
            Import Request Status : <b>{{$importRequest->status->name}}</b>
        </div>
        <form id="quickForm" method="post" action="{{ route('import_request.update', $importRequest->id) }}" enctype="multipart/form-data">
          @csrf
          @method('PUT')
          <div class="row mb-2">
            <div class="col-md-6">
              <div class="form-group">
                <label for="shipmentName">Shipment Name*</label>
                <input type="text" name="shipment_name" class="form-control" id="shipmentName" placeholder="Enter Shipment Name" value="{{ $importRequest->shipment_name }}" {{ $disable ? 'disabled' : '' }}>
              </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Select Supplier*</label>
                    <select class="supplier form-control" id="supplier" name="supplier" {{ $disable ? 'disabled' : '' }}>
                    </select>
                </div>
          </div>
          </div>
          <div class="row mb-2">
            <div class="col-md-6">
              <div class="form-group">
                <label for="itemName">Item Name*</label>
                <input type="text" name="item_name" class="form-control " id="item_name" placeholder="Enter Item Name" value="{{ $importRequest->item_name }}" {{ $disable ? 'disabled' : '' }}>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="itemQuantity">Item Quantity*</label>
                <input type="number" name="item_quantity" class="form-control" id="item_quantity" placeholder="Enter Quantity" value="{{ $importRequest->quantity }}" {{ $disable ? 'disabled' : '' }}>
              </div>
            </div>
          </div>
          <div class="row mb-2">
            <div class="col-md-6">
              <div class="form-group">
                <label for="amount">Amount*</label>
                <input type="text" name="amount" class="form-control" id="amount" placeholder="Enter Amount" value="{{ $importRequest->amount }}" {{ $disable ? 'disabled' : '' }}>
              </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Select Currency*</label>
                    <select class="currency form-control" id="currency" name="currency" {{ $disable ? 'disabled' : '' }}>
                    </select>
                    
                </div>
            </div>
          </div>
    
          <div class="row mb-4">
            
            <div class="col-md-6">
                <div class="form-group">
                    <label>Select Request Type*</label>
                    <select class="request_type_id form-control" id="request_type_id" name="request_type_id" {{ $disable ? 'disabled' : '' }}>
                    </select>
                    
                </div>
            </div>

              <div class="col-md-6">
                <div class="form-group">
                    <label>Select Company*</label>
                    <select class="company form-control" id="company" name="company_id" {{ $disable ? 'disabled' : '' }}>
                    </select>
                    
                </div>
            </div>
              
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Add Comments</label>
                <span class="text-warning pl-4">Note: This part should not be updated for rejection</span>
                <textarea id="comments" cols="8" class="form-control" maxlength="1000" name="comments">{{ $importRequest->comments}} </textarea>
              </div>
            </div>
            @if(in_array($importRequest->status_id,[3,5]))
            <div class="col-md-6">
              <div class="form-group">
                <label>Rejected Reason</label>
                <textarea class="form-control" id="cancelReasonTextarea" name="reason" rows="3" disabled="true">{{ $importRequest->reason_code }}</textarea>
              </div>
            </div>
          @endif
          </div>
         
          {{-- View Documents Start --}}
            @php
                $documents = [
                    'invoice' => 'Invoice',
                    'shipping_document' => 'Shipping Document',
                    'duty_paid_gd' => 'Duty Paid GDs'
                ];
                 $title = 'View Documents';
                 $layout = 'vertical';
                $disabled = false
                
            @endphp
            @include('components.document-upload', ['documents' => $documents , 'model' => $importRequest, 'layout' => $layout, 'disable' => $disabled])
           {{-- View Documents End --}}

          {{-- Bank Documents Start --}}
          @if($importRequest->documents && $importRequest->documents->bank_document)
  
            @include('components.single-document-view', [
                'title' => 'View Bank Documents',
                'fields' => [
                    [
                        'type' => 'text',
                        'label' => 'Bank Name',
                        'id' => 'bank_name',
                        'name' => 'bank_name',
                        'value' => $importRequest->documents->bank_name,
                        'disabled' => true
                    ],
                    [
                        'type' => 'file',
                        'label' => 'Bank Document',
                        'id' => 'bank_document',
                        'name' => 'bank_document',
                        'url' => asset('storage/' . $importRequest->documents->bank_document),
                        'disabled' => true
                    ]
                ]
            ])
          @endif

          @if ($importRequest->status_id > 7)
            @php
              $supporting_documents = [
                  'payment_support' => 'Payment Support',
                  'bank_endorsed_document' => 'Bank Endorsed Documents',
                  'fi_number_screenshot' => 'FI Number Screenshot'
              ];
              $title = 'View Supporting Documents';
              $layout = 'vertical';
              $disabled = true
            @endphp
            @include('components.document-upload', ['documents' => $supporting_documents , 'model' => $importRequest, 'layout' => $layout,'title' => $title , 'disable' => $disabled])
          @endif

          <div class="row justify-content-center mt-2">
            @if((in_array(session('role_id'), [1, 3]) && in_array($importRequest->status_id, [1, 4])) || 
                (in_array(session('role_id'), [1, 4]) && in_array($importRequest->status_id, [2])))
                <button type="button" class="btn btn-danger btn-lg mx-2" id="reject" data-toggle="modal" data-target="#rejectReasonModal">
                    <i class="fas fa-times"></i> Reject
                </button>
            @endif

            @if(in_array(session('role_id'),[1,5]) && in_array($importRequest->status_id,[1,3,4,5,8]))
                <button type="submit" name="action" value="update" class="btn btn-warning btn-lg mx-2" id="submit-button">
                  <i class="fas fa-save mr-2"></i> Update
                </button>
            @endif    
            
            @if(in_array(session('role_id'),[1,3]) && in_array($importRequest->status_id,[1,4]) )
                <button type="submit" name="action" value="approve" class="btn btn-success btn-lg mx-2">
                  <i class="fas fa-check"></i> Approve
                </button>
            @endif 

            @if((in_array(session('role_id'),[1,4]) && in_array($importRequest->status_id,[2,6])))
                <button type="button" class="btn btn-success btn-lg mx-2" id="apply_for_bank" data-toggle="modal" data-target="#documentModal">
                  <i class="fas fa-check"></i> Apply For Bank
                </button>
            @endif   

            @if((in_array(session('role_id'),[1,4]) &&  in_array($importRequest->status_id,[7])))
                <button type="button" class="btn btn-success btn-lg mx-2" id="apply_for_transit">
                  <i class="fas fa-check"></i>  Apply For Transmit
                </button> 
            @endif    

            {{-- @if((session('role_id') == 5 && $importRequest->status_id == 8))
                <button type="submit" class="btn btn-success btn-lg mx-2"  name="action" value="transmit">
                  <i class="fas fa-check"></i> Ready for Transmit
                </button>
            @endif   --}}

          </div>
        </form>
    </div>
  </div>

@include('components.reject-reason-modal', [
    'id' => 'rejectReasonModal',
    'title' => 'Reject Reason',
    'formId' => 'cancelReasonForm',
    'formAction' => route('import_request.reject-reason'),
    'method' => 'POST',
    'hiddenFields' => ['import_request_id' => $importRequest->id],
    'textareaId' => 'cancelReasonTextarea',
    'textareaLabel' => 'Enter Reason*',
    'textareaName' => 'reason',
    'submitButtonId' => 'submitCancelReason',
    'submitButtonText' => 'Submit'
])

@include('components.document-modal', [
    'id' => 'documentModal',
    'title' => 'Upload Document',
    'formId' => 'uploadDocumentForm',
    'formAction' => route('import_request.apply_for_bank'),
    'method' => 'POST',
    'hiddenFields' => [
        'import_request_id' => $importRequest->id
    ],
    'fields' => [
        [
            'type' => 'text',
            'name' => 'bank_name',
            'id' => 'bank_name',
            'label' => 'Enter Bank Name*'
        ],
        [
            'type' => 'file',
            'name' => 'bank_document',
            'id' => 'bank_document',
            'label' => 'Upload Document*'
        ]
    ],
    'submitButtonId' => 'submitDocument',
    'submitButtonText' => 'Submit'
])


@include('components.document-modal', [
    'id' => 'transitModal',
    'title' => 'Apply For Transmit',
    'formId' => 'transitDocumentForm',
    'formAction' => route('import_request.apply_for_transit'),
    'method' => 'POST',
    'hiddenFields' => [
        'import_request_id' => $importRequest->id
    ],
    'fields' => [
        [
            'type' => 'file',
            'name' => 'payment_support',
            'id' => 'payment_support',
            'label' => 'Payment Support'
        ],
        [
            'type' => 'file',
            'name' => 'bank_endorsed_document',
            'id' => 'bank_endorsed_document',
            'label' => 'Bank Endorsed Documents'
        ],
        [
            'type' => 'file',
            'name' => 'fi_number_screenshot',
            'id' => 'fi_number_screenshot',
            'label' => 'FI Number Screenshot'
        ],
        [
            'type' => 'checkbox',
            'name' => 'request_completed',
            'id' => 'request_completed',
            'label' => 'Request Completed*'
        ]
    ],
    'submitButtonId' => 'submitDocument',
    'submitButtonText' => 'Submit'
])

@endsection

@section('scripts')
  <!-- jquery-validation -->
  <script src="{{ asset('plugins/jquery-validation/jquery.validate.min.js') }}"></script>
  <script src="{{ asset('plugins/jquery-validation/additional-methods.min.js') }}"></script>
  <!-- Select2 -->
  <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
  <script src="../../plugins/toastr/toastr.min.js"></script>
  <!-- InputMask -->
  <script src="{{ asset('plugins/moment/moment.min.js')}}"></script>
  <script src="{{ asset('plugins/inputmask/jquery.inputmask.min.js') }}"></script>
  <!-- date-range-picker -->
  <script src="{{ asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
 <!-- Tempusdominus Bootstrap 4 -->
  <script src="{{ asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>

  <script>
    $(document).ready(function() {

      var supplier_names = {!! json_encode($supplier_names) !!};
      var currencies = {!! json_encode($currencies) !!};
      var companies = {!! json_encode($companies) !!};
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

      $(".supplier").val({{ $importRequest->supplier_id }}).trigger('change');

      $(".currency").select2({
        placeholder: "Select Currency",
        data: currencies.map(function(currency) {
            return { id: currency.id, text: currency.name };
        }),
        width: '100%',
        dropdownAutoWidth: true,
        //allowClear: true // Add this line to allow clearing the selection
      });

      $(".currency").val({{ $importRequest->currency_id }}).trigger('change');

      $(".company").select2({
        placeholder: "Select Company",
        data: companies.map(function(company) {
            return { id: company.id, text: company.name };
        }),
        width: '100%',
        dropdownAutoWidth: true,
        //allowClear: true // Add this line to allow clearing the selection
      });

      $(".company").val({{ $importRequest->company_id }}).trigger('change');
      
      $(".request_type_id").select2({
        placeholder: "Select Request Type",
        data: request_types.map(function(request_type) {
            return { id: request_type.id, text: request_type.name };
        }),
        width: '100%',
        dropdownAutoWidth: true,
        //allowClear: true // Add this line to allow clearing the selection
      });

      $(".request_type_id").val({{ $importRequest->request_type_id }}).trigger('change');

      $.validator.setDefaults({
        submitHandler: function(form) {
          $('.btn').prop('disabled', true);
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
            payment_id: {
                required: true,
            },
            performa_invoice: {
                extension: "pdf|doc|docx|png|jpg|jpeg",
            },
            other_document: {
                extension: "pdf|doc|docx|png|jpg|jpeg",
            },
            lc_opening_date: {
                required: true,
            }
        },
        messages: {
            shipment_name: {
                required: "Please enter a shipment name",
            },
            supplier: {
                required: "Please select a supplier",
            },
            payment_id: {
                required: "Please select payment terms",
            },
            performa_invoice: {
                extension: "Please upload a valid PDF, DOC, DOCX, PNG, JPG, or JPEG file",
            },
            other_document: {
                extension: "Please upload a valid PDF, DOC, DOCX, PNG, JPG, or JPEG file",
            },
            lc_opening_date: {
              required: "Please select a date."
            }
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
            $('.btn').prop('disabled', false);
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        }
    });

      $('#cancelReasonForm').validate({
        rules: {
          reason: {
            required: true,
          },
        },
        messages: {
          reason: {
            required: "Reason is required",
          },
         
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
          error.addClass('invalid-feedback');
          element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
          $(element).addClass('is-invalid');
          $('.btn').prop('disabled', false);
        },
        unhighlight: function (element, errorClass, validClass) {
          $(element).removeClass('is-invalid');
        }
      });

      $('#uploadDocumentForm').validate({
        rules: {
          bank_name: {
            required: true,
          },
          bank_document: {
            required: true,
          }
        },
        messages: {
          bank_name: {
            required: "Bank name is required",
          },
          bank_document: {
            required: "Document is required",
          }
         
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
          error.addClass('invalid-feedback');
          element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
          $(element).addClass('is-invalid');
          $('.btn').prop('disabled', false);
        },
        unhighlight: function (element, errorClass, validClass) {
          $(element).removeClass('is-invalid');
        }
      });

      $('#transitDocumentForm').validate({
        rules: {
          request_completed: {
            required: true,
          }
        },
        messages: {
          request_completed: {
            required: "Checkbox is Required",
          }
         
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
          error.addClass('invalid-feedback');
          element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
          $(element).addClass('is-invalid');
          $('.btn').prop('disabled', false);
        },
        unhighlight: function (element, errorClass, validClass) {
          $(element).removeClass('is-invalid');
        }
      });

      $('#reject').click(function() {
        $('#cancelModal').modal('show');
      });

      $('#apply_for_bank').click(function() {
        $('#documentModal').modal('show');
      });
      
      $('#apply_for_transit').click(function() {
        $('#transitModal').modal('show');
      });

      $('#quickFom rm, #transitForm, #uploadDocumentForm, #cancelReasonForm').on('invalid-form.validate', function() {
          $('.btn').prop('disabled', false);
      });

      $('#datepicker').datetimepicker({
        format: 'YYYY-MM-DD' // Use the correct format for your date
      });

      $('#datepicker').on("change", function(e) {
          console.log($('#lc_opening_date').val());
    });




    });
  </script>
@endsection

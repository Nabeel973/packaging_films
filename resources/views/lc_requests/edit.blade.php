@extends('admin.app')

@section('content-header')
  <h1>Edit LC Request
@endsection

@section('content')

  <!-- Main row -->
  <div class="card">
    <div class="card-body">
      <x-auth-session-status class="mb-4 text-center" :status="session('status')" />
        <div class="font-medium text-sm text-black bg-warning p-2 border rounded-md text-center mb-2">
            LC Request Status : <b>{{$lcRequest->status->name}}</b>
        </div>
        <form id="quickForm" method="post" action="{{ route('lc_request.update', $lcRequest->id) }}" enctype="multipart/form-data">
          @csrf
          @method('PUT')
          <div class="row mb-2">
            <div class="col-md-6">
              <div class="form-group">
                <label for="shipmentName">Shipment Name*</label>
                <input type="text" name="shipment_name" class="form-control" id="shipmentName" placeholder="Enter Shipment Name" value="{{ $lcRequest->shipment_name }}" {{ $disable ? 'disabled' : '' }}>
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
                <label for="itemName">Item Name</label>
                <input type="text" name="item_name" class="form-control " id="item_name" placeholder="Enter Item Name" value="{{ $lcRequest->item_name }}" {{ $disable ? 'disabled' : '' }}>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="itemQuantity">Item Quantity</label>
                <input type="number" name="item_quantity" class="form-control" id="item_quantity" placeholder="Enter Quantity" value="{{ $lcRequest->quantity }}" {{ $disable ? 'disabled' : '' }}>
              </div>
            </div>
          </div>
          <div class="row mb-2">
            <div class="col-md-6">
              <div class="form-group">
                <label>Payment Terms*</label>
                {{-- <input type="text" name="payment_terms" class="form-control" id="payment_terms" placeholder="Payment Terms" value="{{ $lcRequest->payment_terms }}" {{ $disable ? 'disabled' : '' }}> --}}
                <select class="payment_id form-control" id="payment_id" name="payment_id" {{ $disable ? 'disabled' : '' }}>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Attach Performa Invoice*</label>
                <input type="file" class="form-control" id="performa_invoice" name="performa_invoice" {{ $disable ? 'disabled' : '' }}>
                {{-- <p>{{ $lcRequest->performa_invoice }}</p> --}}
                @if ($lcRequest->documents && $lcRequest->documents->performa_invoice)
                  <a href="{{ asset('storage/'.$lcRequest->documents->performa_invoice) }}" class="btn btn-success mt-2" download>
                    <i class="fas fa-download"></i> Download
                  </a>
                @endif
              </div>
            </div>
          </div>

          <div class="row mb-2">
            <div class="col-md-6">
              <div class="form-group">
                <label for="amount">Amount*</label>
                <input type="text" name="amount" class="form-control" id="amount" placeholder="Enter Amount" value="{{ $lcRequest->amount }}" {{ $disable ? 'disabled' : '' }}>
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
                <div class="form-check mt-4">
                  <input class="form-check-input" type="checkbox" name="draft_required" {{ $lcRequest->draft_required ? 'checked' : '' }} {{ $disable ? 'disabled' : '' }}>
                  <label class="form-check-label">LC Draft Required</label>
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
          <div class="row mb-4">
            @if(($lcRequest->opening_deadline != null) || ($lcRequest->opening_deadline == null && Auth::user()->role_id == 3))
              <div class="col-md-6">
                <div class="form-group">
                  <label>LC Opening Date:</label>
                    <div class="input-group date" id="datepicker" data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input" data-target="#datepicker" id="lc_opening_date" name="lc_opening_date" value="{{ $lcRequest->opening_deadline }}"   
                          @if(Auth::user()->role_id != 3 || ($lcRequest->opening_deadline != null && Auth::user()->role_id == 3)) 
                            disabled
                          @endif/>
                        
                        <div class="input-group-append" data-target="#datepicker" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
              </div>
            @endif  
            @if(in_array($lcRequest->status_id,[3,5]))
              <div class="col-md-6">
                <div class="form-group">
                  <label>Rejected Reason</label>
                  <textarea class="form-control" id="cancelReasonTextarea" name="reason" rows="3" disabled="true">{{ $lcRequest->reason_code }}</textarea>
                </div>
              </div>
            @endif
              
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Add Comments</label>
                <span class="text-warning pl-4">Note: This part should not be updated for rejection</span>
                <textarea id="comments" cols="8" class="form-control" maxlength="1000" name="comments">{{ $lcRequest->comments}} </textarea>
              </div>
            </div>
          </div>
         
          {{-- View Documents Start --}}
          <div class="row mt-2">
            <div class="col-12">
              <div class="card card-warning collapsed-card">
                <div class="card-header">
                  <h3 class="card-title">Other Supporting Documents</h3>
  
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                    </button>
                  </div>
                  <!-- /.card-tools -->
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-4">
                     <div class="form-group">
                        <label for="otherDocuments">Document1</label>
                        <input type="file" class="form-control" id="document_1" name="document_1">
                        @if ($lcRequest->documents && $lcRequest->documents->document_1)
                          <a href="{{ asset('storage/'.$lcRequest->documents->document_1) }}" class="btn btn-success mt-2" download>
                            <i class="fas fa-download"></i> Download
                          </a>
                        @endif
                     </div>
            
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                         <label for="otherDocuments">Document2</label>
                         <input type="file" class="form-control" id="document_2" name="document_2">
                          @if ($lcRequest->documents && $lcRequest->documents->document_2)
                            <a href="{{ asset('storage/'.$lcRequest->documents->document_2) }}" class="btn btn-success mt-2" download>
                              <i class="fas fa-download"></i> Download
                            </a>
                          @endif
                      </div>
             
                     </div>
                     <div class="col-md-4">
                      <div class="form-group">
                         <label for="otherDocuments">Document3</label>
                         <input type="file" class="form-control" id="document_3" name="document_3">
                         @if ($lcRequest->documents && $lcRequest->documents->document_3)
                         <a href="{{ asset('storage/'.$lcRequest->documents->document_3) }}" class="btn btn-success mt-2" download>
                           <i class="fas fa-download"></i> Download
                         </a>
                       @endif
                      </div>
             
                     </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4">
                     <div class="form-group">
                        <label for="otherDocuments">Document4</label>
                        <input type="file" class="form-control" id="document_4" name="document_4">
                        @if ($lcRequest->documents && $lcRequest->documents->document_4)
                          <a href="{{ asset('storage/'.$lcRequest->documents->document_4) }}" class="btn btn-success mt-2" download>
                            <i class="fas fa-download"></i> Download
                          </a>
                        @endif
                     </div>
            
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                         <label for="otherDocuments">Document5</label>
                         <input type="file" class="form-control" id="document_5" name="document_5">
                         @if ($lcRequest->documents && $lcRequest->documents->document_5)
                         <a href="{{ asset('storage/'.$lcRequest->documents->document_5) }}" class="btn btn-success mt-2" download>
                           <i class="fas fa-download"></i> Download
                         </a>
                        @endif
                      </div>
             
                     </div>
                  </div>
                </div>
                <!-- /.card-body -->
              </div>
              <!-- /.card -->
            </div>
          </div>
           {{-- View Documents End --}}

          {{-- Bank Documents Start --}}
          @if($lcRequest->documents && $lcRequest->documents->bank_document)
          <div class="row mt-2">
            <div class="col-12">
              <div class="card card-warning collapsed-card">
                <div class="card-header">
                  <h3 class="card-title">View Bank Documents</h3>
  
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                    </button>
                  </div>
                  <!-- /.card-tools -->
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-6">
                     <div class="form-group">
                        <label for="otherDocuments">Bank Name</label>
                        <input type="text" class="form-control" value="{{$lcRequest->documents->bank_name}}" disabled="true">
                       
                     </div>
            
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                         <label for="otherDocuments">Bank Document</label>
                         <input type="file" class="form-control" disabled="true">
                          @if ($lcRequest->documents && $lcRequest->documents->bank_document)
                            <a href="{{ asset('storage/'.$lcRequest->documents->bank_document) }}" class="btn btn-success mt-2" download>
                              <i class="fas fa-download"></i> Download
                            </a>
                          @endif
                      </div>
             
                     </div>
              
                  </div>
                 
                </div>
                <!-- /.card-body -->
              </div>
              <!-- /.card -->
            </div>
          </div>
          @endif

          {{-- Bank Document End --}}

           {{-- Bank Documents Start --}}
           @if($lcRequest->documents && $lcRequest->documents->transmited_lc_document)
           <div class="row mt-2">
             <div class="col-12">
               <div class="card card-warning collapsed-card">
                 <div class="card-header">
                   <h3 class="card-title">View Transmited LC Documents</h3>
   
                   <div class="card-tools">
                     <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                     </button>
                   </div>
                   <!-- /.card-tools -->
                 </div>
                 <!-- /.card-header -->
                 <div class="card-body">
                   <div class="row">
                     <div class="col-md-6">
                      <div class="form-group">
                         <label for="otherDocuments">Transmited LC Number</label>
                         <input type="text" class="form-control" value="{{$lcRequest->documents->transmited_lc_number}}" disabled="true">
                        
                      </div>
             
                     </div>
                     <div class="col-md-6">
                       <div class="form-group">
                          <label for="otherDocuments">Transmited LC Document</label>
                          <input type="file" class="form-control" disabled="true">
                           @if ($lcRequest->documents && $lcRequest->documents->transmited_lc_document)
                             <a href="{{ asset('storage/'.$lcRequest->documents->transmited_lc_document) }}" class="btn btn-success mt-2" download>
                               <i class="fas fa-download"></i> Download
                             </a>
                           @endif
                       </div>
              
                      </div>
               
                   </div>
                  
                 </div>
                 <!-- /.card-body -->
               </div>
               <!-- /.card -->
             </div>
           </div>
           @endif
 
           {{-- Bank Document End --}}

          <div class="row justify-content-center mt-2">
            @if((in_array(session('role_id'),[1,3]) && in_array($lcRequest->status_id,[1,4])) || (in_array(session('role_id'),[1,4]) && in_array($lcRequest->status_id,[2,6]) ))
                <button type="button" class="btn btn-danger btn-lg mx-2" id="reject">
                  <i class="fas fa-times"></i> Reject
                </button>
            @endif    
            @if(in_array(session('role_id'),[1,5]) && in_array($lcRequest->status_id,[1,3,4,5]))
                <button type="submit" name="action" value="update" class="btn btn-warning btn-lg mx-2" id="submit-button">
                  <i class="fas fa-save mr-2"></i> Update
                </button>
            @endif    
            
            @if(in_array(session('role_id'),[1,3]) && in_array($lcRequest->status_id,[1,4]) )
                <button type="submit" name="action" value="approve" class="btn btn-success btn-lg mx-2">
                  <i class="fas fa-check"></i> Approve
                </button>
            @endif 

            @if((in_array(session('role_id'),[1,4]) && in_array($lcRequest->status_id,[2,6])))
                <button type="button" class="btn btn-success btn-lg mx-2" id="apply_for_bank">
                  <i class="fas fa-check"></i> Apply For Bank
                </button>
            @endif   

            @if((in_array(session('role_id'),[1,4]) &&  in_array($lcRequest->status_id,[7,9])))
              @if($lcRequest->draft_required == 1 && $lcRequest->status_id == 7) 
                <button type="submit"  name="action" value="next" class="btn btn-success btn-lg mx-2" id="apply_for_bank"> 
                  <i class="fas fa-check"></i> Move To Draft Review

                </button>
              @else
                <button type="button" class="btn btn-success btn-lg mx-2" id="apply_for_transit">
                  <i class="fas fa-check"></i>  Apply For Transmit
                </button>
              @endif    
            @endif    

            @if((session('role_id') == 5 && $lcRequest->status_id == 8))
                <button type="submit" class="btn btn-success btn-lg mx-2"  name="action" value="transmit">
                  <i class="fas fa-check"></i> Ready for Transmit
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
        <form id="cancelReasonForm" method="post" action="{{ route('lc_request.reject-reason') }}">
          @csrf
          @method('post')
          <div class="modal-body">
         
            <input type="hidden" name="lc_request_id" id="lc_request_id" value="{{ $lcRequest->id }}">
            <div class="form-group">
              <label for="cancelReasonTextarea">Enter Reason*</label>
              <textarea class="form-control" id="cancelReasonTextarea" name="reason" rows="3" required></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" id="submitCancelReason" class="btn btn-primary">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" id="documentModal" tabindex="-1" role="dialog" aria-labelledby="documentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="documentModalLabel">Upload Document</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="uploadDocumentForm" method="post" action="{{ route('lc_request.apply_for_bank') }}" enctype="multipart/form-data">
          @csrf
          @method('post')
          <div class="modal-body">
         
            <input type="hidden" name="lc_request_id" id="lc_request_id" value="{{ $lcRequest->id }}">
            <div class="form-group">
              <label for="cancelReasonTextarea">Enter Bank Name*</label>
              <input type="text" name="bank_name" id="bank_name" class="form-control">
            </div>
            <div class="form-group">
              <label>Upload Document*</label>
              <input type="file" class="form-control" id="bank_document" name="bank_document">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" id="submitCancelReason" class="btn btn-primary">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" id="transitModal" tabindex="-1" role="dialog" aria-labelledby="transitModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="transit">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="transitModalLabel">Upload Document</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="transitForm" method="post" action="{{ route('lc_request.apply_for_transit') }}" enctype="multipart/form-data">
          @csrf
          @method('post')
          <div class="modal-body">
         
            <input type="hidden" name="lc_request_id" id="lc_request_id" value="{{ $lcRequest->id }}">
            <div class="form-group">
              <label for="cancelReasonTextarea">Enter LC Number*</label>
              <input type="text" name="lc_number" id="lc_number" class="form-control">
            </div>
            <div class="form-group">
              <label>Upload Transmitted LC Copy*</label>
              <input type="file" class="form-control" id="transmited_lc_document" name="transmited_lc_document">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" id="submitCancelReason" class="btn btn-primary">Submit</button>
          </div>
        </form>
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
      var payments = {!! json_encode($payments) !!};

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

      $(".currency").select2({
        placeholder: "Select Currency",
        data: currencies.map(function(currency) {
            return { id: currency.id, text: currency.name };
        }),
        width: '100%',
        dropdownAutoWidth: true,
        //allowClear: true // Add this line to allow clearing the selection
      });

      $(".currency").val({{ $lcRequest->currency_id }}).trigger('change');

      $(".company").select2({
        placeholder: "Select Company",
        data: companies.map(function(company) {
            return { id: company.id, text: company.name };
        }),
        width: '100%',
        dropdownAutoWidth: true,
        //allowClear: true // Add this line to allow clearing the selection
      });

      $(".company").val({{ $lcRequest->company_id }}).trigger('change');

      $(".payment_id").select2({
        placeholder: "Select Payment Terms",
        data: payments.map(function(payment) {
            return { id: payment.id, text: payment.name };
        }),
        width: '100%',
        dropdownAutoWidth: true,
        //allowClear: true // Add this line to allow clearing the selection
      });

      $(".payment_id").val({{ $lcRequest->payment_id }}).trigger('change');


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

      $('#transitForm').validate({
        rules: {
          lc_number: {
            required: true,
          },
          transmited_lc_document: {
            required: true,
          }
        },
        messages: {
          lc_number: {
            required: "LC Number is required",
          },
          transmited_lc_document: {
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

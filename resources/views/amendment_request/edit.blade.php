@extends('admin.app')

@section('content-header')
  <h1>Edit Amendment Request
@endsection

@section('content')

  <!-- Main row -->
  <div class="card">
    <div class="card-body">
      <x-auth-session-status class="mb-4 text-center" :status="session('status')" />
        <div class="font-medium text-sm text-black bg-warning p-2 border rounded-md text-center mb-2">
            Amendment Request Status : <b>{{$amendmentLcRequest->status->name}}</b>
        </div>
        <form id="quickForm" method="post" action="{{ route('lc_request.update', $amendmentLcRequest->id) }}" enctype="multipart/form-data">
          @csrf
          @method('PUT')
          <div class="row mb-2">
            <div class="col-md-6">
              <div class="form-group">
                 <label for="shipmentName">Details of Amendment*</label>
                {{-- <input type="text" name="details" class="form-control" id="details" placeholder="Enter Details"> --}}
                <textarea name="details" id="details" cols="5" rows="3" class="form-control" {{ $disable ? 'disabled' : '' }} > {{ $amendmentLcRequest->details }} </textarea>
              </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Revised Performa Invoice</label>
                    <input type="file" class="form-control" id="performa_invoice" name="performa_invoice" {{ $disable ? 'disabled' : '' }}>
                    @if ($amendmentLcRequest->performa_invoice)
                        <a href="{{ asset('storage/'.$amendmentLcRequest->performa_invoice) }}" class="btn btn-success mt-2" download>
                            <i class="fas fa-download"></i> Download
                        </a>
                    @endif
                </div>
          </div>
          </div>
         
          {{-- View Documents Start --}}
          <div class="row mt-2">
            <div class="col-12">
              <div class="card card-warning collapsed-card">
                <div class="card-header">
                  <h3 class="card-title">View/Update Documents</h3>
  
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
                        @if ($amendmentLcRequest->document_1)
                          <a href="{{ asset('storage/'.$amendmentLcRequest->document_1) }}" class="btn btn-success mt-2" download>
                            <i class="fas fa-download"></i> Download
                          </a>
                        @endif
                     </div>
            
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                         <label for="otherDocuments">Document2</label>
                         <input type="file" class="form-control" id="document_2" name="document_2">
                          @if ($amendmentLcRequest->document_2)
                            <a href="{{ asset('storage/'.$amendmentLcRequest->document_2) }}" class="btn btn-success mt-2" download>
                              <i class="fas fa-download"></i> Download
                            </a>
                          @endif
                      </div>
             
                     </div>
                     <div class="col-md-4">
                      <div class="form-group">
                         <label for="otherDocuments">Document3</label>
                         <input type="file" class="form-control" id="document_3" name="document_3">
                         @if ($amendmentLcRequest->document_3)
                         <a href="{{ asset('storage/'.$amendmentLcRequest->document_3) }}" class="btn btn-success mt-2" download>
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
                        @if ($amendmentLcRequest->document_4)
                          <a href="{{ asset('storage/'.$amendmentLcRequest->document_4) }}" class="btn btn-success mt-2" download>
                            <i class="fas fa-download"></i> Download
                          </a>
                        @endif
                     </div>
            
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                         <label for="otherDocuments">Document5</label>
                         <input type="file" class="form-control" id="document_5" name="document_5">
                         @if ($amendmentLcRequest->document_5)
                         <a href="{{ asset('storage/'.$amendmentLcRequest->document_5) }}" class="btn btn-success mt-2" download>
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
          @if($amendmentLcRequest->bank_document)
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
                        <input type="text" class="form-control" value="{{$amendmentLcRequest->bank_name}}" disabled="true">
                       
                     </div>
            
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                         <label for="otherDocuments">Bank Document</label>
                         <input type="file" class="form-control" disabled="true">
                          @if ($amendmentLcRequest->bank_document)
                            <a href="{{ asset('storage/'.$amendmentLcRequest->bank_document) }}" class="btn btn-success mt-2" download>
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
           @if($amendmentLcRequest->transmited_lc_document)
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
                         <input type="text" class="form-control" value="{{$amendmentLcRequest->transmited_lc_number}}" disabled="true">
                        
                      </div>
             
                     </div>
                     <div class="col-md-6">
                       <div class="form-group">
                          <label for="otherDocuments">Transmited LC Document</label>
                          <input type="file" class="form-control" disabled="true">
                           @if ($amendmentLcRequest->transmited_lc_document)
                             <a href="{{ asset('storage/'.$amendmentLcRequest->transmited_lc_document) }}" class="btn btn-success mt-2" download>
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

            @if(in_array(session('role_id'),[1,5]) && in_array($amendmentLcRequest->status_id,[3,5])))
                <button type="submit" name="action" value="update" class="btn btn-warning btn-lg mx-2" id="submit-button">
                <i class="fas fa-save mr-2"></i> Update
                </button>
            @endif    
  
            @if((in_array(session('role_id'),[1,4]) && $amendmentLcRequest->status_id == 11))
                <button type="button" class="btn btn-success btn-lg mx-2" id="apply_for_bank">
                <i class="fas fa-check"></i> Apply For Bank
                </button>
            @endif   

            @if(in_array(session('role_id'),[1,4]) &&  $amendmentLcRequest->status_id == 7)
                <button type="button" class="btn btn-success btn-lg mx-2" id="apply_for_transit">
                  <i class="fas fa-check"></i>  Apply For Transmit
                </button>  
            @endif 

          </div>
        </form>
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
        <form id="uploadDocumentForm" method="post" action="{{ route('amendment_request.apply_for_bank') }}" enctype="multipart/form-data">
          @csrf
          @method('post')
          <div class="modal-body">
         
            <input type="hidden" name="amendment_lc_request_id" id="amendment_lc_request_id" value="{{ $amendmentLcRequest->id }}">
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
        <form id="transitForm" method="post" action="{{ route('amendment_request.apply_for_transit') }}" enctype="multipart/form-data">
          @csrf
          @method('post')
          <div class="modal-body">
         
            <input type="hidden" name="amendment_lc_request_id" id="amendment_lc_request_id" value="{{ $amendmentLcRequest->id }}">
            <div class="form-group">
              <label for="cancelReasonTextarea">Enter LC Number*</label>
              <input type="text" name="lc_number" id="lc_number" class="form-control">
            </div>
            <div class="form-group">
              <label>Upload Amednment Copy*</label>
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
  <script src="{{ asset('/plugins/toastr/toastr.min.js')}}"></script>

  <script>
    $(document).ready(function() {

      $.validator.setDefaults({
        submitHandler: function(form) {
          $('.btn').prop('disabled', true);
          form.submit(); // Submit the form
        }
      });
      
      $('#quickForm').validate({
        rules: {
          details: {
            required: true,
          },
          performa_invoice: {
            extension: "pdf|doc|docx|png|jpg|jpeg",
          },
          document_1: {
            extension: "pdf|doc|docx|png|jpg|jpeg",
          },
          document_2: {
            extension: "pdf|doc|docx|png|jpg|jpeg",
          },
          document_3: {
            extension: "pdf|doc|docx|png|jpg|jpeg",
          },
          document_4: {
            extension: "pdf|doc|docx|png|jpg|jpeg",
          },
          document_5: {
            extension: "pdf|doc|docx|png|jpg|jpeg",
          },
        },
        messages: {
          details: {
            required: "Please enter detail",
          },
          performa_invoice: {
            extension: "Please upload a valid PDF, DOC, or DOCX file",
          },
          document_1: {
            extension: "Please upload a valid PDF,DOC,PNG,JPEG,JPG, or DOCX file",
          },
          document_2: {
            extension: "Please upload a valid PDF,DOC,PNG,JPEG,JPG, or DOCX file",
          },
          document_3: {
            extension: "Please upload a valid PDF,DOC,PNG,JPEG,JPG, or DOCX file",
          },
          document_4: {
            extension: "Please upload a valid PDF,DOC,PNG,JPEG,JPG, or DOCX file",
          },
          document_5: {
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
          $('.btn').prop('disabled', false);
        },
        unhighlight: function (element, errorClass, validClass) {
          $(element).removeClass('is-invalid');
        }
      });

    //   $('#cancelReasonForm').validate({
    //     rules: {
    //       reason: {
    //         required: true,
    //       },
    //     },
    //     messages: {
    //       reason: {
    //         required: "Reason is required",
    //       },
         
    //     },
    //     errorElement: 'span',
    //     errorPlacement: function (error, element) {
    //       error.addClass('invalid-feedback');
    //       element.closest('.form-group').append(error);
    //     },
    //     highlight: function (element, errorClass, validClass) {
    //       $(element).addClass('is-invalid');
    //       $('.btn').prop('disabled', false);
    //     },
    //     unhighlight: function (element, errorClass, validClass) {
    //       $(element).removeClass('is-invalid');
    //     }
    //   });

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

    //   $('#reject').click(function() {
    //     $('#cancelModal').modal('show');
    //   });

      $('#apply_for_bank').click(function() {
        $('#documentModal').modal('show');
      });
      
      $('#apply_for_transit').click(function() {
        $('#transitModal').modal('show');
      });

      $('#quickFom rm, #transitForm, #uploadDocumentForm, #cancelReasonForm').on('invalid-form.validate', function() {
          $('.btn').prop('disabled', false);
      });

    });
  </script>
@endsection

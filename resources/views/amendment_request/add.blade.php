@extends('admin.app')

@section('content-header')
  <h1>Add New LC Amendment Request</h1>
@endsection

@section('content')

  <!-- Main row -->
  <div class="card">
    <div class="card-body">
      <x-auth-session-status class="mb-4 text-center" :status="session('status')" />
        <form id="quickForm" method="post" action="{{route('amendment_request.submit', $id)}}" enctype="multipart/form-data">
          @csrf
          <div class="row mb-2">
            <div class="col-md-6">
              <div class="form-group">
                <label for="shipmentName">Details of Amendment*</label>
                {{-- <input type="text" name="details" class="form-control" id="details" placeholder="Enter Details"> --}}
                <textarea name="details" id="details" cols="5" rows="3" class="form-control" ></textarea>
              </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="otherDocuments">Attech Revised Performa Invoice</label>
                    <input type="file" class="form-control" id="performa_invoice" name="performa_invoice">
                </div>
          </div>
          </div>
         
          <div class="row mt-2">
            <div class="col-12">
              <div class="card card-warning collapsed-card">
                <div class="card-header">
                  <h3 class="card-title">Add Documents</h3>
  
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
                     </div>
            
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                         <label for="otherDocuments">Document2</label>
                         <input type="file" class="form-control" id="document_2" name="document_2">
                      </div>
             
                     </div>
                     <div class="col-md-4">
                      <div class="form-group">
                         <label for="otherDocuments">Document3</label>
                         <input type="file" class="form-control" id="document_3" name="document_3">
                      </div>
             
                     </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4">
                     <div class="form-group">
                        <label for="otherDocuments">Document4</label>
                        <input type="file" class="form-control" id="document_4" name="document_4">
                     </div>
            
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                         <label for="otherDocuments">Document5</label>
                         <input type="file" class="form-control" id="document_5" name="document_5">
                      </div>
             
                     </div>
                  </div>
                </div>
                <!-- /.card-body -->
              </div>
              <!-- /.card -->
            </div>
          </div>
          <div class="row justify-content-center mt-2">
            {{-- <button type="submit" class="btn btn-primary btn-lg">Generate New Request</button> --}}
            <button type="submit" name="action" value="update" class="btn btn-warning btn-lg mx-2" id="submit-button">
              <i class="fas fa-save pr-2"> </i> Generate Amendment Request
            </button>
            {{-- <div id="loader" style="display:none;">
              <img src="{{ asset('images/loader.gif') }}" alt="Loading...">
            </div> --}}
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
    
      $.validator.setDefaults({
        submitHandler: function(form) {
          $('#submit-button').prop('disabled', true);
          // $('#loader').show();
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

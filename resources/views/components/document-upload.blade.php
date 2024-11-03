<div class="card card-warning collapsed-card">
  <div class="card-header">
      <h3 class="card-title">Add Documents</h3>
      <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-plus"></i>
          </button>
      </div>
  </div>
  <div class="card-body">
      <div class="row">
          @foreach ($documents as $docKey => $docLabel)
              <div class="col-md-4">
                  <div class="form-group">
                      <label for="{{ $docKey }}">{{ $docLabel }}</label>
                      <input type="file" class="form-control file-input" id="{{ $docKey }}" name="{{ $docKey }}">
                      <small class="error-message text-danger"></small>
                  </div>
              </div>
          @endforeach
      </div>
  </div>
</div>

{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function() {
      const maxFileSize = 2 * 1024 * 1024; // 2 MB in bytes
      const allowedFileTypes = ['application/pdf', 'image/jpeg', 'image/png'];

      // Event listener for file input change
      $('.file-input').on('change', function() {
          const file = this.files[0];
          const $errorContainer = $(this).closest('.form-group').find('.error-message');
          const $inputField = $(this);

          // Reset any previous errors
          $errorContainer.text('');
          $inputField.removeClass('is-invalid');

          if (file) {
              // Check file type
              if (!allowedFileTypes.includes(file.type)) {
                  $errorContainer.text('Invalid file type. Only PDF, JPEG, and PNG are allowed.');
                  $inputField.addClass('is-invalid');
                  return;
              }

              // Check file size
              if (file.size > maxFileSize) {
                  $errorContainer.text('File size exceeds 2 MB limit.');
                  $inputField.addClass('is-invalid');
                  return;
              }
          }
      });
  });
</script> --}}

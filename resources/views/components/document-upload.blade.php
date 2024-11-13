{{-- <div class="card card-warning collapsed-card">
  <div class="card-header">
      <h3 class="card-title">{{$title}}</h3>
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
                
                      @if (isset($model) && $model->documents && data_get($model->documents, $docKey))
                      <a href="{{ asset('storage/' . data_get($model->documents, $docKey)) }}" class="btn btn-success mt-2" download>
                          <i class="fas fa-download"></i> Download
                      </a>
                  @endif
                      <small class="error-message text-danger"></small>
                  </div>
              </div>
          @endforeach
      </div>
  </div>
</div> --}}
<div class="card card-warning collapsed-card">
    <div class="card-header">
        <h3 class="card-title">{{ $title }}</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-plus"></i>
            </button>
        </div>
    </div>
    
    <div class="card-body {{ $layout == 'horizontal' ? 'horizontal-layout' : 'vertical-layout' }}">
        <div class="row">
            @foreach ($documents as $docKey => $docLabel)
                <div class="{{ $layout === 'horizontal' ? 'col-md-12 d-flex align-items-center' : 'col-md-4' }}">
                    <div class="form-group">
                        <label for="{{ $docKey }}">{{ $docLabel }}</label>
                        <input type="file" class="form-control file-input {{ $layout === 'horizontal' ? 'ml-2' : '' }}" 
                               id="{{ $docKey }}" name="{{ $docKey }}">
                  
                        @if (isset($model) && $model->documents && data_get($model->documents, $docKey))
                        <a href="{{ asset('storage/' . data_get($model->documents, $docKey)) }}" 
                           class="btn btn-success mt-2" download>
                            <i class="fas fa-download"></i> Download
                        </a>
                        @endif
                        <small class="error-message text-danger"></small>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
  </div>
  
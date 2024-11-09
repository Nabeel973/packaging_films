<div class="card card-warning collapsed-card">
    <div class="card-header">
        <h3 class="card-title">{{ $title ?? 'View Documents' }}</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-plus"></i>
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            @foreach ($fields as $field)
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="{{ $field['id'] }}">{{ $field['label'] }}</label>
                        
                        @if ($field['type'] === 'text')
                            <!-- Text input field -->
                            <input 
                                type="text" 
                                class="form-control" 
                                id="{{ $field['id'] }}" 
                                name="{{ $field['name'] }}" 
                                value="{{ $field['value'] ?? '' }}" 
                                {{ $field['disabled'] ? 'disabled' : '' }}>
                        @elseif ($field['type'] === 'file')
                            <!-- File input field with download option -->
                            <input 
                                type="file" 
                                class="form-control file-input" 
                                id="{{ $field['id'] }}" 
                                name="{{ $field['name'] }}" 
                                {{ $field['disabled'] ? 'disabled' : '' }}>
                            
                            @if (isset($field['url']))
                                <!-- Download link for existing file -->
                                <a href="{{ $field['url'] }}" class="btn btn-success mt-2" download>
                                    <i class="fas fa-download"></i> Download
                                </a>
                            @endif
                        @endif

                        <small class="error-message text-danger"></small>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- resources/views/components/modal.blade.php -->
<div class="modal fade" id="{{ $id }}" tabindex="-1" role="dialog" aria-labelledby="{{ $id }}Label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="{{ $id }}Label">{{ $title }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="{{ $formId }}" method="POST" action="{{ $formAction }}">
                @csrf
                @method($method ?? 'POST')
                
                <div class="modal-body">
                    @foreach ($hiddenFields as $name => $value)
                        <input type="hidden" name="{{ $name }}" value="{{ $value }}">
                    @endforeach
                    
                    <div class="form-group">
                        <label for="{{ $textareaId }}">{{ $textareaLabel }}</label>
                        <textarea class="form-control" id="{{ $textareaId }}" name="{{ $textareaName }}" rows="3" required></textarea>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" id="{{ $submitButtonId }}" class="btn btn-primary">{{ $submitButtonText }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

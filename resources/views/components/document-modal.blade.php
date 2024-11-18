<!-- resources/views/components/document-modal.blade.php -->
@props([
    'id' => 'documentModal',
    'title' => 'Upload Document',
    'formId' => 'uploadDocumentForm',
    'formAction' => route('lc_request.apply_for_bank'),
    'method' => 'POST',
    'hiddenFields' => [],
    'fields' => [],
    'submitButtonId' => 'submitDocument',
    'submitButtonText' => 'Submit',
])

<div class="modal fade" id="{{ $id }}" tabindex="-1" role="dialog" aria-labelledby="{{ $id }}Label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="{{ $id }}Label">{{ $title }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="{{ $formId }}" method="post" action="{{ $formAction }}" enctype="multipart/form-data">
                @csrf
                @method($method)

                <div class="modal-body">
                    @foreach ($hiddenFields as $name => $value)
                        <input type="hidden" name="{{ $name }}" value="{{ $value }}">
                    @endforeach
                    
                    @foreach ($fields as $field)
                        <div class="form-group {{ $field['type'] === 'checkbox' ? 'd-flex align-items-center' : '' }}">
                            @if ($field['type'] === 'checkbox')
                                <!-- Checkbox with small size and left alignment -->
                                <input type="checkbox" name="{{ $field['name'] }}" id="{{ $field['id'] }}" 
                                    class="form-check-small mr-2">
                                <label for="{{ $field['id'] }}" class="form-check-label">{{ $field['label'] }}</label>
                            @else
                                <!-- Default input fields -->
                                <label for="{{ $field['id'] }}">{{ $field['label'] }}</label>
                                <input type="{{ $field['type'] }}" name="{{ $field['name'] }}" id="{{ $field['id'] }}" 
                                    class="form-control">
                            @endif
                        </div>
                    @endforeach

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" id="{{ $submitButtonId }}" class="btn btn-primary">{{ $submitButtonText }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
  .form-check-small {
    width: 16px;
    height: 16px;
    margin-right: 8px;
}

.form-check-label {
    margin-bottom: 0;
    font-size: 0.9rem;
}


</style>
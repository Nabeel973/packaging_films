@props(['status','errors'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-medium text-sm text-white bg-success p-2 border rounded-md']) }}>
        {{ $status }}
    </div>
@endif

@if ($errors->any())
<div class="alert alert-danger text-left">
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
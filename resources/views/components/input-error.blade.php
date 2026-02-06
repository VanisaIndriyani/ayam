@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'text-danger small mb-1 ps-1']) }}>
        @foreach ((array) $messages as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
@endif

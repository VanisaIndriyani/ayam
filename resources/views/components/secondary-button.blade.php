<button
    {{ $attributes->merge([
        'type' => 'button',
        'class' =>
            'btn btn-outline-secondary d-inline-flex align-items-center px-4 py-2 
             text-uppercase fw-semibold text-xs shadow-sm
             bg-white border rounded 
             transition-all disabled:opacity-50'
    ]) }}
>
    {{ $slot }}
</button>

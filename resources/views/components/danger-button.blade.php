<button 
    {{ $attributes->merge([
        'type' => 'submit', 
        'class' => 'btn btn-danger fw-semibold text-uppercase px-4 py-2 shadow-sm'
    ]) }}>
    {{ $slot }}
</button>

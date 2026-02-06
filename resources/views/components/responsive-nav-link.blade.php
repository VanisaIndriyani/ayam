@props(['active' => false])

@php
    $classes = $active
        ? 'd-block w-100 ps-3 pe-4 py-2 border-start border-3 border-primary bg-light fw-semibold text-primary'
        : 'd-block w-100 ps-3 pe-4 py-2 border-start border-3 border-transparent text-secondary fw-medium
            hover-bg-light hover-border-start';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>

<style>
    /* Hover effect upgrade */
    a.hover-bg-light:hover {
        background-color: #f8f9fa !important;
        color: #0d6efd !important;
    }

    a.hover-border-start:hover {
        border-left-color: #ced4da !important;
    }
</style>

@props(['active'])

@php
$classes = ($active ?? false)
    ? 'nav-link fw-semibold text-primary border-bottom border-2 border-primary'
    : 'nav-link text-secondary';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>

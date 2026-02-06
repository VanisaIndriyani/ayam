@props([
    'align' => 'right',   // left, right, center
    'width' => '200px',   // bisa custom misal: 180px, 240px
])

@php
    // Alignment bootstraps class
    $alignmentClass = match($align) {
        'left'   => 'dropdown-menu-start',
        'center' => 'dropdown-menu-center',
        default  => 'dropdown-menu-end',
    };
@endphp

<div class="dropdown">
    {{-- Trigger --}}
    <div data-bs-toggle="dropdown" aria-expanded="false" style="cursor:pointer;">
        {{ $trigger }}
    </div>

    {{-- Dropdown Content --}}
    <div class="dropdown-menu shadow {{ $alignmentClass }}" style="min-width: {{ $width }};">
        {{ $content }}
    </div>
</div>

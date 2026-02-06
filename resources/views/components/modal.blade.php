@props([
    'name',
    'show' => false,
    'maxWidth' => '2xl'
])

@php
$maxWidthClass = [
    'sm'  => 'modal-sm',
    'md'  => '',            // default modal
    'lg'  => 'modal-lg',
    'xl'  => 'modal-xl',
    '2xl' => 'modal-xl',    // Bootstrap doesn’t support 2xl, mapped to xl
][$maxWidth];
@endphp

<div 
    x-data="{ show: @js($show) }"
    x-init="$watch('show', value => {
        if (value) {
            let modalEl = document.getElementById('{{ $name }}');
            let modal = new bootstrap.Modal(modalEl);
            modal.show();
        }
    })"
    x-on:open-modal.window="$event.detail == '{{ $name }}' ? show = true : null"
    x-on:close-modal.window="$event.detail == '{{ $name }}' ? show = false : null"
>
    <!-- Bootstrap Modal -->
    <div class="modal fade" id="{{ $name }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog {{ $maxWidthClass }} modal-dialog-centered">
            <div class="modal-content shadow-lg">

                {{ $slot }}

            </div>
        </div>
    </div>
</div>

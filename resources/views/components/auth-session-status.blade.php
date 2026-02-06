@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'alert alert-success small mb-3']) }}>
        <i class="bi bi-check-circle me-1"></i>
        {{ $status }}
    </div>
@endif

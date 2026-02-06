@extends('layouts.admin')

@section('title', 'Manajemen Pesanan')

@section('content')

<div class="container-fluid">

    {{-- ===================== PAGE HEADER ===================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0">
            <i class="bi bi-bag-check-fill me-2 text-primary"></i> Manajemen Pesanan
        </h3>
    </div>

    {{-- ===================== FILTER CARD ===================== --}}
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body">

            <form method="GET">
                <div class="row g-3 align-items-end">

                    {{-- SEARCH --}}
                    <div class="col-md-5">
                        <label class="form-label fw-semibold">Cari Pesanan</label>
                        <input
                            type="text"
                            name="search"
                            class="form-control rounded-3"
                            placeholder="Cari kode pesanan, email, atau nama user..."
                            value="{{ request('search') }}"
                        >
                    </div>

                    {{-- STATUS ORDER --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Status Pesanan</label>
                        <select name="status" class="form-select rounded-3">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status')=='pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ request('status')=='processing' ? 'selected' : '' }}>Processing</option>
                            <option value="packed" {{ request('status')=='packed' ? 'selected' : '' }}>Packed</option>
                            <option value="shipped" {{ request('status')=='shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="completed" {{ request('status')=='completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ request('status')=='cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <button class="btn btn-primary w-100 rounded-3 fw-bold">
                            <i class="bi bi-search me-1"></i> Filter
                        </button>
                    </div>

                </div>
            </form>

        </div>
    </div>

    {{-- ===================== TABLE CARD ===================== --}}
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-0">

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4">Kode</th>
                            <th>User</th>
                            <th>Total</th>
                            <th>Status Pesanan</th>
                            <th>Status Pembayaran</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($orders as $order)

                        @php
                            $payment = $order->payments->first();
                            $trxStatus = $payment->transaction_status ?? null;

                            if ($trxStatus === '00') {
                                $paymentLabel = 'PAID';
                                $paymentClass = 'paid';
                            } elseif ($trxStatus === '01') {
                                $paymentLabel = 'PENDING';
                                $paymentClass = 'pending';
                            } else {
                                $paymentLabel = 'FAILED';
                                $paymentClass = 'failed';
                            }
                        @endphp

                        <tr class="border-bottom">

                            {{-- CODE --}}
                            <td class="fw-bold px-4">{{ $order->code }}</td>

                            {{-- USER --}}
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <img
                                        src="https://ui-avatars.com/api/?name={{ urlencode($order->user->name) }}&background=0D6EFD&color=fff"
                                        class="rounded-circle shadow-sm"
                                        width="40" height="40"
                                    >
                                    <div class="lh-sm">
                                        <div class="fw-semibold">{{ $order->user->name }}</div>
                                        <small class="text-muted">{{ $order->user->email }}</small>
                                    </div>
                                </div>
                            </td>

                            {{-- TOTAL --}}
                            <td class="fw-bold text-success">
                                Rp {{ number_format($order->grand_total) }}
                            </td>

                            {{-- ORDER STATUS --}}
                            <td>
                                <span class="badge status-badge {{ $order->status }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>

                            {{-- PAYMENT STATUS (FROM DUITKU) --}}
                            <td>
                                <span class="badge payment-badge {{ $paymentClass }}">
                                    {{ $paymentLabel }}
                                </span>
                            </td>

                            {{-- ACTION --}}
                            <td class="text-end pe-4">
                                <a href="{{ route('admin.orders.show', $order->id) }}"
                                   class="btn btn-sm btn-primary rounded-3 px-3 shadow-sm">
                                    <i class="bi bi-eye-fill"></i> Detail
                                </a>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                Tidak ada pesanan ditemukan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>
    </div>

    <div class="mt-3">
        {{ $orders->links() }}
    </div>

</div>

{{-- ===================== STYLE ===================== --}}
<style>
.status-badge,
.payment-badge {
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 0.8rem;
    font-weight: 600;
}

/* ORDER */
.status-badge.pending { background:#ffc107; color:#000; }
.status-badge.processing { background:#0d6efd; color:#fff; }
.status-badge.packed { background:#6610f2; color:#fff; }
.status-badge.shipped { background:#0dcaf0; color:#000; }
.status-badge.completed { background:#198754; color:#fff; }
.status-badge.cancelled { background:#6c757d; color:#fff; }

/* PAYMENT */
.payment-badge.pending { background:#ffc107; color:#000; }
.payment-badge.paid { background:#198754; color:#fff; }
.payment-badge.failed { background:#dc3545; color:#fff; }
</style>

@endsection

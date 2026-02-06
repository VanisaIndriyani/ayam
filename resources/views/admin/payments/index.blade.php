@extends('layouts.admin')

@section('title', 'Dashboard Pembayaran')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">Pembayaran Duitku</h3>
    </div>

    {{-- FILTER --}}
    <form class="row g-3 mb-4" method="GET">
        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">Semua Status</option>
                <option value="00" {{ request('status') === '00' ? 'selected' : '' }}>Paid</option>
                <option value="01" {{ request('status') === '01' ? 'selected' : '' }}>Pending</option>
                <option value="FAILED" {{ request('status') === 'FAILED' ? 'selected' : '' }}>Failed</option>
            </select>
        </div>

        <div class="col-md-2">
            <button class="btn btn-primary w-100">Filter</button>
        </div>
    </form>

    {{-- TABLE --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">

            <div class="table-responsive">
                <table class="table table-striped align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Kode Pesanan</th>
                            <th>Provider</th>
                            <th>Status</th>
                            <th>Jumlah</th>
                            <th>Tanggal</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($payments as $p)
                        <tr>
                            <td>{{ $p->order->code }}</td>

                            <td class="text-uppercase">
                                {{ $p->provider ?? 'duitku' }}
                            </td>

                            <td>
                                @php
                                    $status = $p->transaction_status;
                                @endphp

                                <span class="badge
                                    @if($status === '00') bg-success
                                    @elseif($status === '01') bg-warning text-dark
                                    @else bg-danger
                                    @endif
                                ">
                                    @if($status === '00')
                                        PAID
                                    @elseif($status === '01')
                                        PENDING
                                    @else
                                        FAILED
                                    @endif
                                </span>
                            </td>

                            <td>Rp {{ number_format($p->gross_amount) }}</td>

                            <td>{{ $p->created_at->format('d M Y H:i') }}</td>

                            <td>
                                <a href="{{ route('admin.orders.show', $p->order_id) }}"
                                   class="btn btn-sm btn-primary">
                                   Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                Tidak ada data pembayaran
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>
    </div>

    <div class="mt-3">
        {{ $payments->withQueryString()->links() }}
    </div>

</div>
@endsection

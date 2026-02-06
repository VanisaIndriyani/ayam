@extends('layouts.admin')

@section('title', 'Detail Pesanan')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">Detail Pesanan #{{ $order->code }}</h3>

        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>


    <div class="row g-4">

        {{-- ========================== KOLOM KIRI ========================== --}}
        <div class="col-lg-8">

            {{-- Informasi Pesanan --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">

                    <h5 class="fw-bold mb-3">Informasi Pesanan</h5>

                    <div class="row g-3">

                        <div class="col-md-6">
                            <p class="mb-1 text-muted">Tanggal Order</p>
                            <p class="fw-semibold">
                                {{ $order->ordered_at ? date('d M Y H:i', strtotime($order->ordered_at)) : '-' }}
                            </p>
                        </div>

                        <div class="col-md-6">
                            <p class="mb-1 text-muted">Status Pesanan</p>
                            <span class="badge px-3 py-2 fs-6
                                @class([
                                    'bg-warning text-dark' => $order->status == 'pending',
                                    'bg-primary'           => $order->status == 'processing',
                                    'bg-info text-dark'    => $order->status == 'shipped',
                                    'bg-success'           => $order->status == 'completed',
                                    'bg-secondary'         => $order->status == 'cancelled',
                                ])
                            ">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>

                        <div class="col-md-6">
                            <p class="mb-1 text-muted">Nama Penerima</p>
                            <p class="fw-semibold">{{ $order->shipping_name }}</p>
                        </div>

                        <div class="col-md-6">
                            <p class="mb-1 text-muted">No HP</p>
                            <p class="fw-semibold">{{ $order->shipping_phone }}</p>
                        </div>

                        <div class="col-md-12">
                            <p class="mb-1 text-muted">Alamat</p>
                            <p class="fw-semibold">{{ $order->shipping_address }}</p>
                        </div>

                        <div class="col-md-6">
                            <p class="mb-1 text-muted">Kota</p>
                            <p class="fw-semibold">{{ $order->shipping_city }}</p>
                        </div>

                        <div class="col-md-6">
                            <p class="mb-1 text-muted">Kurir</p>
                            <p class="fw-semibold">
                                {{ strtoupper($order->courier) }} - {{ strtoupper($order->service) }}
                            </p>
                        </div>

                        @if ($order->tracking_number)
                        <div class="col-md-6">
                            <p class="mb-1 text-muted">Nomor Resi</p>
                            <p class="fw-semibold">{{ $order->tracking_number }}</p>
                        </div>
                        @endif

                    </div>
                </div>
            </div>


            {{-- Produk --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Produk Dalam Pesanan</h5>

                    <div class="table-responsive">
                        <table class="table table-borderless align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Produk</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($order->items as $item)
                                <tr class="border-bottom">
                                    <td>{{ $item->product_name }}</td>
                                    <td class="text-center fw-semibold">x{{ $item->quantity }}</td>
                                    <td class="text-end fw-semibold">
                                        Rp {{ number_format($item->subtotal) }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between fw-bold fs-5">
                        <span>Total Bayar</span>
                        <span>Rp {{ number_format($order->grand_total) }}</span>
                    </div>

                </div>
            </div>


            {{-- Riwayat Status (Timeline) --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">

                    <h5 class="fw-bold mb-3">Riwayat Status</h5>

                    @if($order->statusHistories->count() == 0)
                        <p class="text-muted">Belum ada riwayat status.</p>
                    @else

                        <ul class="timeline list-unstyled mb-0">

                            @foreach ($order->statusHistories as $history)
                            
                            <li class="mb-4 position-relative ps-4">

                                <span class="position-absolute top-0 start-0 translate-middle p-2 bg-primary rounded-circle"></span>

                                <div class="fw-semibold">
                                    {{ ucfirst($history->status_from) }} → {{ ucfirst($history->status_to) }}
                                </div>

                                <div class="text-muted small">
                                    {{ date('d M Y H:i', strtotime($history->created_at)) }}
                                    | oleh: {{ $history->user->name ?? '-' }}
                                </div>

                                @if($history->note)
                                <div class="mt-1 small text-dark fst-italic">
                                    Catatan: {{ $history->note }}
                                </div>
                                @endif

                            </li>

                            @endforeach

                        </ul>

                    @endif

                </div>
            </div>

        </div>



        {{-- ========================== KOLOM KANAN ========================== --}}
        <div class="col-lg-4">

            {{-- Update status --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">

                    <h5 class="fw-bold mb-3">Update Status Pesanan</h5>

                    <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                @foreach (['pending','processing','packed','shipped','completed','cancelled'] as $status)
                                    <option value="{{ $status }}"
                                        {{ $order->status == $status ? 'selected' : '' }}>
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Catatan (Opsional)</label>
                            <textarea name="note" class="form-control" rows="3"></textarea>
                        </div>

                        <button class="btn btn-primary w-100">
                            Update Status
                        </button>
                    </form>

                </div>
            </div>


            {{-- Update Resi --}}
            <div class="card shadow-sm border-0">
                <div class="card-body">

                    <h5 class="fw-bold mb-3">Update Nomor Resi</h5>

                    <form action="{{ route('admin.orders.updateResi', $order->id) }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Nomor Resi</label>
                            <input type="text" name="tracking_number" class="form-control"
                                value="{{ $order->tracking_number }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tracking URL</label>
                            <input type="text" name="tracking_url" class="form-control"
                                value="{{ $order->tracking_url }}">
                        </div>

                        <button class="btn btn-purple btn-dark w-100">
                            Update Resi
                        </button>

                    </form>

                </div>
            </div>

        </div>

    </div>

</div>

@endsection

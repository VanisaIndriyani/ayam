@extends('layouts.admin')

@section('title', 'Laporan Transaksi')

@section('content')

<h3 class="fw-bold mb-4">Laporan Transaksi</h3>

{{-- Filter --}}
<div class="card mb-4 shadow-sm">
    <div class="card-body">

        <form class="row g-3" method="GET">
            <div class="col-md-4">
                <label class="form-label">Tanggal Mulai</label>
                <input type="date" name="from" class="form-control"
                       value="{{ $from }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Tanggal Akhir</label>
                <input type="date" name="to" class="form-control"
                       value="{{ $to }}">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button class="btn btn-primary w-100">
                    <i class="bi bi-filter me-2"></i> Filter
                </button>
            </div>
        </form>

    </div>
</div>


{{-- Summary --}}
<div class="row g-4 mb-4">

    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <h6>Total Transaksi</h6>
                <h4 class="fw-bold">{{ $summary['total_transaksi'] }}</h4>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <h6>Total Pendapatan</h6>
                <h4 class="fw-bold text-success">
                    Rp {{ number_format($summary['total_pendapatan']) }}
                </h4>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <h6>Pending</h6>
                <h4 class="fw-bold text-warning">{{ $summary['pending'] }}</h4>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <h6>Gagal</h6>
                <h4 class="fw-bold text-danger">{{ $summary['failed'] }}</h4>
            </div>
        </div>
    </div>

</div>


{{-- Table --}}
<div class="card shadow-sm">
    <div class="card-body">

        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Kode Pesanan</th>
                        <th>Metode</th>
                        <th>Status</th>
                        <th>Jumlah</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($payments as $pay)
                    <tr>
                        <td>{{ $loop->iteration + ($payments->currentPage()-1)*$payments->perPage() }}</td>
                        <td>{{ $pay->order->code ?? '-' }}</td>
                        <td>{{ strtoupper($pay->payment_type) }}</td>

                        <td>
                            <span class="badge
                                @if($pay->transaction_status=='settlement') bg-success
                                @elseif($pay->transaction_status=='pending') bg-warning
                                @else bg-danger @endif">
                                {{ ucfirst($pay->transaction_status) }}
                            </span>
                        </td>

                        <td>Rp {{ number_format($pay->gross_amount) }}</td>

                        <td>{{ $pay->created_at->format('d M Y H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>

            </table>
        </div>

        <div class="mt-3">
            {{ $payments->links('pagination::bootstrap-5') }}
        </div>

    </div>
</div>

@endsection

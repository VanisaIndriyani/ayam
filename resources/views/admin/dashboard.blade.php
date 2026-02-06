@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

<div class="container-fluid">

    {{-- ROW 1: STATISTICS --}}
    <div class="row g-4 mb-4">

        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body">
                    <div class="text-muted">Total Revenue</div>
                    <h3 class="fw-bold mt-2">Rp {{ number_format($totalRevenue) }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body">
                    <div class="text-muted">Total Orders</div>
                    <h3 class="fw-bold mt-2">{{ $totalOrders }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body">
                    <div class="text-muted">Total Customers</div>
                    <h3 class="fw-bold mt-2">{{ $totalCustomers }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body">
                    <div class="text-muted">Avg. Order Value</div>
                    <h3 class="fw-bold mt-2">Rp {{ number_format($averageOrderValue) }}</h3>
                </div>
            </div>
        </div>

    </div>

    {{-- ROW 2 --}}
    <div class="row g-4 mb-4">

        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body">
                    <div class="text-muted">Order Hari Ini</div>
                    <h3 class="fw-bold mt-2">{{ $todayOrders }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body">
                    <div class="text-muted">Revenue Hari Ini</div>
                    <h3 class="fw-bold mt-2">Rp {{ number_format($todayRevenue) }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body">
                    <div class="text-muted">Pending Payment</div>
                    <h3 class="fw-bold mt-2">{{ $pendingOrders }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body">
                    <div class="text-muted">Sedang Diproses</div>
                    <h3 class="fw-bold mt-2">{{ $processingOrders }}</h3>
                </div>
            </div>
        </div>

    </div>

    {{-- STATUS SUMMARY --}}
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Ringkasan Status Pesanan</h5>

            <div class="row g-3">

                @foreach ($statusSummary as $status => $total)
                    <div class="col-md-2">
                        <div class="p-3 rounded-4 text-center bg-light shadow-sm">
                            <div class="text-muted">{{ ucfirst($status) }}</div>
                            <div class="fs-3 fw-bold">{{ $total }}</div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>

    {{-- RECENT ORDERS --}}
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Pesanan Terbaru</h5>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Kode</th>
                            <th>Customer</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Pembayaran</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recentOrders as $order)
                        <tr>
                            <td>{{ $order->code }}</td>
                            <td>{{ $order->user->name ?? '-' }}</td>

                            {{-- FIX KOMPLIT UNTUK FORMAT TANGGAL --}}
                            <td>{{ $order->ordered_at ? \Carbon\Carbon::parse($order->ordered_at)->format('d M Y H:i') : '-' }}</td>

                            <td>Rp {{ number_format($order->grand_total) }}</td>
                            <td><span class="badge bg-primary">{{ ucfirst($order->status) }}</span></td>
                            <td><span class="badge bg-success">{{ ucfirst($order->payment_status) }}</span></td>

                            <td>
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-primary">
                                    Detail
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    {{-- REVENUE CHART --}}
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Revenue 30 Hari Terakhir</h5>
            <div id="revenueChart" style="height: 350px;"></div>
        </div>
    </div>

    {{-- TOP PRODUCTS --}}
    <div class="card shadow-sm border-0 rounded-4 mb-5">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Produk Terlaris</h5>

            @if($topProducts->count() == 0)
                <p class="text-muted">Belum ada data penjualan produk.</p>
            @else
                @foreach($topProducts as $item)
                    <div class="d-flex justify-content-between border-bottom py-2">
                        <span>{{ $item->product_name }}</span>
                        <span><b>{{ $item->total_sold }}</b> terjual</span>
                    </div>
                @endforeach
            @endif

        </div>
    </div>

</div>

@endsection


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
    var options = {
        chart: { type: 'area', height: 350 },
        series: [{
            name: 'Revenue',
            data: @json($chartRevenue)
        }],
        xaxis: {
            categories: @json($chartDates)
        },
        fill: {
            type: "gradient",
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.45,
                opacityTo: 0.1,
            }
        },
        colors: ['#0d6efd'],
        stroke: { curve: 'smooth', width: 3 },
        dataLabels: { enabled: false }
    };

    new ApexCharts(document.querySelector("#revenueChart"), options).render();
</script>
@endpush

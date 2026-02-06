<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $order->code }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .header { margin-bottom: 20px; }
        .header h2 { margin: 0; }
        .header p { margin: 0; }
        .table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .table th, .table td {
            border: 1px solid #ccc;
            padding: 6px 8px;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .mt-2 { margin-top: 10px; }
        .mt-4 { margin-top: 20px; }
        .mb-1 { margin-bottom: 4px; }
        .small { font-size: 11px; }
    </style>
</head>
<body>

    {{-- HEADER --}}
    <div class="header">
        <h2>Bohri Farm</h2>
        <p>Invoice Pembayaran</p>
        <p class="small">Tanggal: {{ $order->created_at->format('d M Y H:i') }}</p>
    </div>

    {{-- INFO ORDER & CUSTOMER --}}
    <table width="100%">
        <tr>
            <td width="50%">
                <strong>Kepada:</strong><br>
                {{ $user->name ?? '-' }}<br>
                {{ $order->shipping_address ?? '-' }}<br>
                Telp: {{ $order->shipping_phone ?? '-' }}
            </td>
            <td width="50%">
                <strong>Detail Invoice:</strong><br>
                Kode Pesanan: {{ $order->code }}<br>
                Status Pembayaran: {{ ucfirst($order->payment_status ?? 'pending') }}<br>
                Status Pesanan: {{ ucfirst($order->status ?? 'pending') }}
            </td>
        </tr>
    </table>

    {{-- TABEL PRODUK --}}
    <table class="table mt-4">
        <thead>
            <tr>
                <th>Produk</th>
                <th class="text-center">Qty</th>
                <th class="text-right">Harga</th>
                <th class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>{{ $item->product_name }}</td>
                <td class="text-center">{{ $item->quantity }}</td>
                <td class="text-right">Rp {{ number_format($item->price) }}</td>
                <td class="text-right">Rp {{ number_format($item->subtotal) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- RINGKASAN --}}
    <table width="100%" class="mt-4">
        <tr>
            <td width="70%"></td>
            <td width="30%">
                <table width="100%">
                    <tr>
                        <td>Subtotal</td>
                        <td class="text-right">Rp {{ number_format($order->subtotal ?? $order->grand_total) }}</td>
                    </tr>
                    @if(isset($order->shipping_cost))
                    <tr>
                        <td>Ongkir</td>
                        <td class="text-right">Rp {{ number_format($order->shipping_cost) }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td><strong>Total</strong></td>
                        <td class="text-right"><strong>Rp {{ number_format($order->grand_total) }}</strong></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- INFO PEMBAYARAN --}}
    @if($payment)
    <div class="mt-4">
        <strong>Info Pembayaran:</strong><br>
        Metode: {{ strtoupper($payment->payment_type) }}<br>
        Status: {{ ucfirst($payment->transaction_status) }}<br>
        @if($payment->va_number)
            Bank: {{ strtoupper($payment->bank) }}<br>
            VA Number: {{ $payment->va_number }}<br>
        @endif
        Jumlah: Rp {{ number_format($payment->gross_amount) }}
    </div>
    @endif

    <p class="mt-4 small">
        Invoice ini dihasilkan secara otomatis oleh sistem Bohri Farm.
    </p>

</body>
</html>

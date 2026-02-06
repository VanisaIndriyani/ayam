<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with(['payments'])
            ->latest()
            ->paginate(10);

        return view('front.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load(['items', 'payments', 'statusHistories']);

        return view('front.orders.show', compact('order'));
    }


    /**
     * QUICK ORDER — Pemesanan dari Landing Page
     */
    public function quickOrder(Request $request)
{
    $request->validate([
        'product_name' => 'required|string',
        'price'        => 'required|numeric',
        'quantity'     => 'required|integer|min:1',
    ]);

    $user = Auth::user();

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'Silakan login untuk memesan.',
        ], 401);
    }

    // =============================
    // 1. Generate order code untuk Midtrans
    // =============================
    $orderCode = 'ORD-' . strtoupper(Str::random(10));

    $grandTotal = $request->price * $request->quantity;

    // =============================
    // 2. Buat ORDER
    // pastikan kolom: code, user_id, grand_total, total_amount, status, payment_status
    // =============================
    $order = Order::create([
        'user_id'        => $user->id,
        'code'           => $orderCode,
        'total_amount'   => $grandTotal,
        'grand_total'    => $grandTotal,
        'shipping_cost'  => 0,
        'status'         => 'pending',
        'payment_status' => 'pending',
    ]);

    // =============================
    // 3. Simpan DETAIL ORDER
    // =============================
    OrderItem::create([
        'order_id'     => $order->id,
        'product_name' => $request->product_name,
        'product_id'   => null, // karena produk statis
        'price'        => $request->price,
        'quantity'     => $request->quantity,
        'subtotal'     => $grandTotal,
    ]);

    // =============================
    // 4. Simpan HISTORY STATUS (opsional)
    // =============================
    OrderStatusHistory::create([
        'order_id'    => $order->id,
        'status'      => 'pending',
        'description' => 'Pesanan dibuat melalui Quick Order Landing Page',
    ]);

    // =============================
    // 5. Berikan redirect ke halaman pembayaran Duitku
    // =============================
    return response()->json([
        'success'  => true,
        'order_id' => $order->id,
        'redirect' => route('payment.start', $order->id),
    ]);
}
public function tracking(Order $order)
{
    return view('front.orders.tracking', compact('order'));
}

public function getTrackingData(Order $order)
{
    return response()->json([
        'latitude' => $order->latitude,
        'longitude' => $order->longitude,
        'tracking_number' => $order->tracking_number,
        'status' => $order->status,
        'updated_at' => $order->updated_at->diffForHumans(),
    ]);
}

public function pay($id)
{
    $order = Order::where('user_id', auth()->id())->findOrFail($id);

    // Konfigurasi Midtrans
    \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
    \Midtrans\Config::$clientKey = config('services.midtrans.client_key');
    \Midtrans\Config::$isProduction = false; // Sandbox
    \Midtrans\Config::$isSanitized = true;
    \Midtrans\Config::$is3ds = true;

    // Data transaksi Midtrans
    $params = [
        'transaction_details' => [
            'order_id' => 'ORDER-' . $order->id . '-' . time(),
            'gross_amount' => $order->grand_total,
        ],
        'customer_details' => [
            'first_name' => auth()->user()->name,
            'email' => auth()->user()->email,
        ],
    ];

    // FIX: createTransaction() dipakai, bukan getToken()
    $response = \Midtrans\Snap::createTransaction($params);

    $snapToken = $response->token;
    $clientKey = config('services.midtrans.client_key');

    return view('front.orders.pay', compact('order', 'snapToken', 'clientKey'));
}



public function notification(Request $request)
{
    $notif = new \Midtrans\Notification();

    $orderId = explode('-', $notif->order_id)[1];
    $transaction = $notif->transaction_status;

    $order = Order::find($orderId);

    if ($transaction == 'capture' || $transaction == 'settlement') {
        $order->payment_status = 'paid';
    } elseif ($transaction == 'pending') {
        $order->payment_status = 'pending';
    } else {
        $order->payment_status = 'failed';
    }

    $order->save();

    return response()->json(['status' => 'success']);
}

public function invoice($id)
    {
        $order = Order::with(['items', 'payments', 'user'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        $pdf = Pdf::loadView('pdf.invoice', [
            'order' => $order,
            'user'  => $order->user,
            'payment' => $order->payments->first(),
        ])->setPaper('A4', 'portrait');

        $fileName = 'Invoice-' . $order->code . '.pdf';

        return $pdf->download($fileName);
        // atau kalau mau tampil di browser:
        // return $pdf->stream($fileName);
    }

}

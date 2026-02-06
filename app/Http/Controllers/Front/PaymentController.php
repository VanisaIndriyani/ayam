<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Services\DuitkuService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * START PAYMENT
     * Redirect user ke halaman pembayaran Duitku
     */
    public function start(Order $order, DuitkuService $duitku)
    {
        logger()->info('PaymentController::start', ['order_id' => $order->id, 'user_id' => auth()->id()]);

        if ($order->user_id !== auth()->id()) {
            logger()->warning('Payment Forbidden: user mismatch', ['order_user' => $order->user_id, 'auth_user' => auth()->id()]);
            abort(403);
        }

        if ($order->payment_status !== 'pending') {
            logger()->info('Payment already processed', ['status' => $order->payment_status]);
            return redirect()->route('orders.show', $order->id);
        }

        try {
            $invoice = $duitku->createInvoice($order);

            // REDIRECT KE DUITKU
            return redirect()->away($invoice['paymentUrl']);
        } catch (\Exception $e) {
            logger()->error('Duitku Payment Error: ' . $e->getMessage());
            return redirect()->route('orders.index')->with('error', 'Gagal memproses pembayaran ke Duitku: ' . $e->getMessage());
        }
    }

    /**
     * PAGE SETELAH USER SELESAI BAYAR (RETURN URL)
     */
    public function finish(Request $request, DuitkuService $duitku)
    {
        // Duitku redirects with ?merchantOrderId=...&reference=...&resultCode=...
        $orderCode  = $request->query('merchantOrderId');
        $resultCode = $request->query('resultCode');

        logger()->info('PaymentController::finish reached', $request->all());

        if ($orderCode) {
            $order = Order::where('code', $orderCode)->first();
            
            if ($order) {
                // SINKRONISASI STATUS (PENTING UNTUK LOCALHOST)
                // Jika resultCode sukses (00) tapi di DB masih pending, coba tanya Duitku
                if ($resultCode === '00' && $order->payment_status === 'pending') {
                    $statusResponse = $duitku->inquiryStatus($orderCode);
                    
                    if (isset($statusResponse['statusCode']) && $statusResponse['statusCode'] === '00') {
                        $order->payment_status = 'paid';
                        $order->status         = 'processing';
                        $order->paid_at        = now();
                        $order->save();

                        // Update juga table payment-nya
                        Payment::updateOrCreate(
                            ['order_id' => $order->id],
                            [
                                'provider'           => 'duitku',
                                'transaction_id'     => $statusResponse['reference'] ?? '-',
                                'transaction_status' => '00',
                                'gross_amount'       => $statusResponse['amount'] ?? $order->grand_total,
                                'raw_response'       => $statusResponse,
                            ]
                        );

                        logger()->info('Order status synced in finish page', ['code' => $orderCode]);
                    }
                }

                return view('front.payment.finish', compact('order'));
            }
        }

        // Fallback if no param or order not found
        return redirect()->route('orders.index')->with('info', 'Pembayaran telah diproses. Silakan cek riwayat pesanan Anda.');
    }

    /**
     * CALLBACK / WEBHOOK DARI DUITKU
     * DIPANGGIL OLEH SERVER DUITKU
     */
    public function callback(Request $request, DuitkuService $duitku)
    {
        // Verifikasi & update status order
        $duitku->handleCallback($request->all());

        return response()->json(['message' => 'OK'], 200);
    }
}

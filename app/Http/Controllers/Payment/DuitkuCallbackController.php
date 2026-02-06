<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

class DuitkuCallbackController extends Controller
{
    public function handle(Request $request)
    {
        logger()->info('DUITKU CALLBACK RAW', $request->all());

        $merchantCode = config('duitku.merchant_code');
        $apiKey       = config('duitku.api_key');

        $resultCode       = $request->resultCode;        // Duitku uses resultCode in callback
        $merchantOrderId = $request->merchantOrderId;
        $amount           = $request->amount;
        $reference        = $request->reference;
        $signature        = $request->signature;

        // === VALIDASI SIGNATURE (Duitku Callback uses MD5) ===
        // Formula: MD5(merchantcode + amount + merchantOrderId + merchantKey)
        $expectedSignature = md5($merchantCode . $amount . $merchantOrderId . $apiKey);

        if ($signature !== $expectedSignature) {
            logger()->error('DUITKU INVALID SIGNATURE', [
                'received' => $signature,
                'expected' => $expectedSignature,
                'params'   => $merchantCode . $amount . $merchantOrderId . $apiKey
            ]);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // === AMBIL ORDER ===
        $order = Order::where('code', $merchantOrderId)->first();
        if (!$order) {
            logger()->error('ORDER NOT FOUND', ['code' => $merchantOrderId]);
            return response()->json(['message' => 'Order not found'], 404);
        }

        // === UPDATE ORDER ===
        if ($resultCode === '00') {
            $order->payment_status = 'paid';
            $order->status         = 'processing';
            $order->paid_at        = now();
        } elseif ($resultCode === '01') {
            $order->payment_status = 'pending';
        } else {
            $order->payment_status = 'failed';
            $order->status         = 'cancelled';
        }

        $order->save();

        // === UPDATE PAYMENT ===
        Payment::updateOrCreate(
            ['order_id' => $order->id],
            [
                'provider'           => 'duitku',
                'transaction_id'     => $reference,
                'transaction_status' => $resultCode,
                'gross_amount'       => $amount,
                'raw_response'       => $request->all(),
            ]
        );

        logger()->info('DUITKU CALLBACK SUCCESS UPDATE', [
            'order' => $order->code,
            'resultCode' => $resultCode
        ]);

        return response()->json(['message' => 'OK'], 200);
    }

}

<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;

class DuitkuService
{
    public function createInvoice(Order $order): array
    {
        $merchantCode = config('duitku.merchant_code');
        $apiKey       = config('duitku.api_key');
        $endpoint     = config('duitku.endpoint');

        // TIMESTAMP MILLISECONDS (WAJIB INT)
        $timestamp = (int) round(microtime(true) * 1000);

        // SIGNATURE HEADER: merchantCode + timestamp + apiKey
        $signature = hash('sha256', $merchantCode . $timestamp . $apiKey);

        // ITEM DETAILS
        $itemDetails = [];
        $itemsTotal  = 0;

        foreach ($order->items as $item) {
            $price    = (int) $item->price;
            $qty      = (int) $item->quantity;
            $subtotal = $price * $qty; // Duitku expects (price * quantity) in 'price' field
            $itemsTotal += $subtotal;

            $itemDetails[] = [
                'name'     => substr($item->product_name, 0, 50),
                'price'    => $price,
                'quantity' => $qty,
            ];
        }

        // TAMBAHKAN ONGKIR SEBAGAI ITEM
        if ((int)$order->shipping_cost > 0) {
            $shippingCost = (int) $order->shipping_cost;
            $itemDetails[] = [
                'name'     => 'Ongkos Kirim',
                'price'    => $shippingCost,
                'quantity' => 1,
            ];
            $itemsTotal += $shippingCost;
        }

        // CUSTOMER DETAIL & ADDRESS
        $fullName = trim($order->shipping_name ?: auth()->user()->name);
        $nameArr  = explode(' ', $fullName);
        $firstName = $nameArr[0];
        $lastName  = count($nameArr) > 1 ? implode(' ', array_slice($nameArr, 1)) : $firstName;

        $addressArr = [
            'firstName'   => $firstName,
            'lastName'    => $lastName,
            'address'     => substr($order->shipping_address ?: '-', 0, 50),
            'city'        => substr($order->shipping_city ?: '-', 0, 50),
            'postalCode'  => substr($order->shipping_postal_code ?: '-', 0, 10),
            'phone'       => $order->shipping_phone ?: '-',
            'countryCode' => 'ID'
        ];

        $customerDetailArr = [
            'firstName'       => $firstName,
            'lastName'        => $lastName,
            'email'           => auth()->user()->email,
            'phoneNumber'     => $order->shipping_phone,
            'billingAddress'  => $addressArr,
            'shippingAddress' => $addressArr
        ];

        $payload = [
            'paymentAmount'    => (int) $order->grand_total,
            'merchantOrderId'  => (string) $order->code,
            'productDetails'   => 'Pesanan Bohri Farm - #' . $order->code,
            'customerVaName'   => substr($fullName, 0, 20),
            'email'            => auth()->user()->email,
            'phoneNumber'      => $order->shipping_phone,
            'itemDetails'      => $itemDetails,
            'customerDetail'   => $customerDetailArr,
            'callbackUrl'      => config('duitku.callback_url') ?: route('payment.duitku.callback'),
            'returnUrl'        => config('duitku.return_url') ?: route('payment.finish'),
            'expiryPeriod'     => 60,
        ];

        // VERIFIKASI AKHIR TOTAL PAYLOAD
        if ($payload['paymentAmount'] !== $itemsTotal) {
            logger()->warning('Duitku Total Mismatch local fix', [
                'grand_total' => $payload['paymentAmount'],
                'items_total' => $itemsTotal
            ]);
            $payload['paymentAmount'] = $itemsTotal;
        }

        // DEBUG PAYLOAD AS JSON STRING
        logger()->info('Duitku JSON Payload:', ['json' => json_encode($payload)]);

        $response = Http::withHeaders([
            'Accept'                => 'application/json',
            'Content-Type'          => 'application/json',
            'x-duitku-signature'    => $signature,
            'x-duitku-timestamp'    => (string) $timestamp,
            'x-duitku-merchantcode' => (string) $merchantCode,
        ])->post($endpoint, $payload)->json();

        // DEBUG RESPONSE
        logger()->info('Duitku Response:', ['response' => $response]);

        // === DEBUG JIKA GAGAL ===
        if (!isset($response['paymentUrl'])) {
            logger()->error('Duitku CreateInvoice Failed', [
                'payload'  => $payload,
                'response' => $response,
            ]);

            $errorMsg = 'Gagal membuat invoice Duitku';
            if (isset($response['response']) && is_array($response['response'])) {
                $errorMsg .= ': ' . implode(', ', $response['response']);
            } elseif (isset($response['statusMessage'])) {
                $errorMsg .= ': ' . $response['statusMessage'];
            }

            throw new \Exception($errorMsg);
        }

        // SIMPAN PAYMENT
        Payment::updateOrCreate(
            ['order_id' => $order->id],
            [
                'provider'           => 'duitku',
                'transaction_id'     => $response['reference'],
                'payment_url'        => $response['paymentUrl'],
                'transaction_status' => 'pending',
                'gross_amount'       => $order->grand_total,
                'raw_response'       => $response,
            ]
        );

        return $response;
    }

    /**
     * CEK STATUS TRANSAKSI KE DUITKU
     * Digunakan untuk sinkronisasi status jika callback tidak sampai (misal di localhost)
     */
    public function inquiryStatus($orderCode)
    {
        $merchantCode = config('duitku.merchant_code');
        $apiKey       = config('duitku.api_key');
        // Sandbox: https://api-sandbox.duitku.com/api/merchant/transactionStatus
        // Prod: https://api-prod.duitku.com/api/merchant/transactionStatus
        $endpoint = str_ireplace('createInvoice', 'transactionStatus', config('duitku.endpoint'));

        $signature = md5($merchantCode . $orderCode . $apiKey);

        $payload = [
            'merchantCode'    => $merchantCode,
            'merchantOrderId' => $orderCode,
            'signature'       => $signature
        ];

        try {
            $response = Http::post($endpoint, $payload)->json();
            logger()->info('Duitku Inquiry Status:', ['code' => $orderCode, 'response' => $response]);
            return $response;
        } catch (\Exception $e) {
            logger()->error('Duitku Inquiry Error:', ['message' => $e->getMessage()]);
            return null;
        }
    }
}

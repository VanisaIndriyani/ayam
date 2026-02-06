<?php

namespace App\Services;

use App\Models\Order;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey    = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production', false);
        Config::$isSanitized  = true;
        Config::$is3ds        = true;
    }

    public function createTransaction(Order $order)
    {
        // pastikan data numeric valid
        $grandTotal  = (int) $order->grand_total;
        $shippingCost = (int) ($order->shipping_cost ?? 0);

        // item list
        $items = $order->items->map(function ($item) {
            return [
                'id'       => $item->id, // gunakan id order_item agar unik
                'price'    => (int) $item->price,
                'quantity' => (int) $item->quantity,
                'name'     => substr($item->product_name, 0, 50),
            ];
        })->toArray();

        // tambahkan item ongkir jika ada
        if ($shippingCost > 0) {
            $items[] = [
                'id'       => 'ONGKIR',
                'price'    => $shippingCost,
                'quantity' => 1,
                'name'     => 'Ongkos Kirim',
            ];
        }

        $params = [
            'transaction_details' => [
                'order_id'      => $order->code,  // wajib CODE, bukan ID
                'gross_amount'  => $grandTotal,
            ],

            'customer_details' => [
                'first_name'    => $order->shipping_name ?? 'Customer',
                'phone'         => $order->shipping_phone ?: '0800000000',
            ],

            'item_details' => $items,

            // Fokus VA transfer
            'enabled_payments' => [
                'bca_va',
                'bni_va',
                'bri_va',
                'permata_va',
                'echannel',   // mandiri
                'bank_transfer',
            ],
        ];

        return Snap::createTransaction($params);
    }
}

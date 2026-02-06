<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuickOrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string|max:150',
            'price'        => 'required|numeric|min:1000',
            'quantity'     => 'required|integer|min:1',
        ]);

        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Harus login dahulu'
            ], 401);
        }

        // generate unique order code
        $orderCode = 'ORD-' . strtoupper(str()->random(10));

        $subtotal = $request->price * $request->quantity;

        // ========= CREATE ORDER =========
        $order = Order::create([
            'user_id'          => $user->id,
            'code'             => $orderCode,

            // default shipping (bisa diubah saat checkout nanti)
            'shipping_name'    => $user->name,
            'shipping_phone'   => $user->phone ?? '08123456789',
            'shipping_address' => $user->address ?? 'Alamat belum diisi',
            'shipping_city'    => 'Kota',

            'shipping_cost'    => 0,
            'total_amount'     => $subtotal,
            'grand_total'      => $subtotal,
            'status'           => 'pending',
            'payment_status'   => 'pending',
            'ordered_at'       => now(),
        ]);

        // ========= ADD ORDER ITEM =========
        $order->items()->create([
            'product_id'   => null,
            'product_name' => $request->product_name,
            'price'        => $request->price,
            'quantity'     => $request->quantity,
            'subtotal'     => $subtotal,
        ]);

        return response()->json([
            'success'    => true,
            'order_id'  => $order->id,
            'order_code' => $order->code
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        Order::create([
            'user_id' => 2,
            'cart_id' => null,

            'code' => 'ORD-001',

            'status' => 'completed',
            'payment_status' => 'paid',

            'total_amount' => 50000,
            'shipping_cost' => 25000,
            'grand_total' => 75000,

            'shipping_name' => 'John Doe',
            'shipping_phone' => '089876543210',
            'shipping_address' => 'Jl Merpati No 12',
            'shipping_city' => 'Bandung',
            'shipping_postal_code' => '40211',

            'courier' => 'jne',
            'service' => 'REG',
            'weight' => 1000,

            'tracking_number' => 'JNE123456',
            'tracking_url' => 'https://jne.co.id/tracking/JNE123456',

            'ordered_at' => now()->subDays(5),
            'paid_at' => now()->subDays(4),
            'shipped_at' => now()->subDays(3),
            'completed_at' => now()->subDays(1),
        ]);
    }
}

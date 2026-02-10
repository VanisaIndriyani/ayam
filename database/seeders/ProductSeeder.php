<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Ayam Remaja (14 minggu)',
                'image' => 'images/ayamremaja.jpeg',
                'price' => 70000,
                'weight' => 1000, // Estimasi 1kg
            ],
            [
                'name' => 'Ayam 23 minggu (bertelur)',
                'image' => 'images/ayamproduk.jpeg',
                'price' => 85000,
                'weight' => 1500,
            ],
            [
                'name' => 'Ayam Tua (hidup / dipotong)',
                'image' => 'images/ayamtua.jpeg',
                'price' => 55000,
                'weight' => 2000,
            ],
            [
                'name' => 'Ayam Kampung (dewasa)',
                'image' => 'images/ayamkampung.jpeg',
                'price' => 100000,
                'weight' => 1500,
            ],
            [
                'name' => 'Bebek (bertelur)',
                'image' => 'images/bebek.jpeg',
                'price' => 100000,
                'weight' => 1500,
            ],
            [
                'name' => 'Bebek Muda',
                'image' => 'images/bebekmuda.jpeg',
                'price' => 75000,
                'weight' => 1200,
            ],
            [
                'name' => 'Entok Jago (besar)',
                'image' => 'images/entokgede.jpeg',
                'price' => 200000,
                'weight' => 3000,
            ],
            [
                'name' => 'Entok Jago (sedang)',
                'image' => 'images/entokkecil.jpeg',
                'price' => 150000,
                'weight' => 2500,
            ],
            [
                'name' => 'Entok Betina',
                'image' => 'images/entokbetina.jpeg',
                'price' => 60000,
                'weight' => 1500,
            ],
            [
                'name' => 'Telur Ayam / kg',
                'image' => 'images/telur.jpeg',
                'price' => 26000,
                'weight' => 1000,
            ],
            [
                'name' => 'Telur Ayam (Paket Hemat 500gr)',
                'image' => 'images/telur.jpeg',
                'price' => 13500,
                'weight' => 500,
            ],
            [
                'name' => 'Telur Ayam 15 kg (1 peti)',
                'image' => 'images/satupeti.jpeg',
                'price' => 390000,
                'weight' => 15000,
            ],
            [
                'name' => 'Telur Ayam Kampung / pcs',
                'image' => 'images/telurayamkampung.jpeg',
                'price' => 1500,
                'weight' => 50,
            ],
            [
                'name' => 'Empan Ayam Petelur (1 kg)',
                'image' => 'images/empanayam.jpeg',
                'price' => 6000,
                'weight' => 1000,
            ],
            [
                'name' => 'Empan Ayam Petelur (10 kg)',
                'image' => 'images/empanayam.jpeg',
                'price' => 50000,
                'weight' => 10000,
            ],
        ];

        foreach ($products as $data) {
            Product::updateOrCreate(
                ['slug' => Str::slug($data['name'])], // Check by slug to avoid duplicates
                [
                    'name' => $data['name'],
                    'image' => $data['image'],
                    'price' => $data['price'],
                    'weight' => $data['weight'],
                    'stock' => 100, // Default stock
                    'description' => 'Produk berkualitas dari Bohrifarm: ' . $data['name'],
                    'is_active' => true,
                ]
            );
        }
    }
}

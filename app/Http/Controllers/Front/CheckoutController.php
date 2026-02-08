<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\RajaOngkirService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index(RajaOngkirService $rajaOngkir)
    {
        $cart = Cart::where('user_id', Auth::id())
                    ->where('status', 'active')
                    ->with('items.product')
                    ->first();

        // If no cart, maybe we can create one or redirect to products
        if (!$cart || $cart->items->isEmpty()) {
            return redirect('/#pageProduk')->with('error', 'Keranjang belanja kosong.');
        }

        $total = $cart->items->sum('subtotal');

        // $provinces removed as we use searchLocation now
        return view('front.checkout.index', compact('cart', 'total'));
    }

    public function searchLocation(Request $request, RajaOngkirService $rajaOngkir)
    {
        $query = $request->query('q');
        if (!$query) return response()->json([]);
        
        $data = $rajaOngkir->searchDestination($query);
        return response()->json($data);
    }

    public function shippingCost(Request $request, RajaOngkirService $rajaOngkir)
    {
        $request->validate([
            'destination' => 'required',
            'courier' => 'required'
        ]);

        // Handle Antar Toko
        if ($request->courier === 'antar_toko') {
            return $this->calculateAntarTokoCost($request->destination_name);
        }

        // Handle Lalamove
        if ($request->courier === 'lalamove') {
            return $this->calculateLalamoveCost($request->destination_name);
        }

        // total berat
        $weight = Cart::where('user_id', Auth::id())
            ->where('status', 'active')
            ->first()
            ->items
            ->sum(fn($item) => $item->product->weight * $item->quantity);

        // Pastikan berat minimal 1 gram (API requirement)
        if ($weight <= 0) {
            $weight = 1000; // Default 1kg jika berat 0
        }

        $origin = config('services.rajaongkir.origin_id'); 

        // Bersihkan nilai origin
        if (is_string($origin)) {
            $origin = trim($origin);
        }

        // Fallback origin jika kosong (Default: Jakarta Pusat = 152)
        if (empty($origin)) {
            $origin = 152; 
        }

        // Handle Frozen (Combined JNE YES)
        if ($request->courier === 'frozen') {
            // Kita gunakan JNE sebagai basis, lalu filter service YES
            $response = $rajaOngkir->cost(
                $origin,
                $request->destination,
                $weight,
                'jne'
            );

            // Manipulasi response untuk hanya ambil YES dan ganti nama jadi Frozen
            // Handle Structure 1: Standard RajaOngkir
            if (isset($response['rajaongkir']['results'][0]['costs'])) {
                $costs = $response['rajaongkir']['results'][0]['costs'];
                $frozenCosts = $this->filterFrozenCosts($costs);

                if (empty($frozenCosts)) {
                    $response['rajaongkir']['results'][0]['costs'] = [];
                    $response['meta']['message'] = 'Layanan Frozen tidak tersedia untuk rute ini.';
                } else {
                    $response['rajaongkir']['results'][0]['costs'] = $frozenCosts;
                    $response['rajaongkir']['results'][0]['code'] = 'frozen';
                    $response['rajaongkir']['results'][0]['name'] = 'Frozen Service';
                }
            } 
            // Handle Structure 2: Komerce (data.data.results) or similar
            elseif (isset($response['data']['results'][0]['costs'])) {
                 $costs = $response['data']['results'][0]['costs'];
                 $frozenCosts = $this->filterFrozenCosts($costs);

                 if (empty($frozenCosts)) {
                    $response['data']['results'][0]['costs'] = [];
                    $response['meta']['message'] = 'Layanan Frozen tidak tersedia untuk rute ini.';
                 } else {
                    $response['data']['results'][0]['costs'] = $frozenCosts;
                    $response['data']['results'][0]['code'] = 'frozen';
                    $response['data']['results'][0]['name'] = 'Frozen Service';
                 }
            }
            // Handle Structure 3: Komerce Flat Data (data is direct array of services)
            elseif (isset($response['data']) && is_array($response['data'])) {
                 $costs = $response['data'];
                 $frozenCosts = [];

                 foreach ($costs as $cost) {
                     // Check service name (YES) or ETD (1 day)
                     $service = strtoupper($cost['service'] ?? '');
                     $etd = $cost['etd'] ?? '';
                     
                     if (str_contains($service, 'YES') || 
                         str_contains($etd, '1-1') || 
                         (str_contains($etd, '1') && !str_contains($etd, '-')) // Exact "1" day
                     ) {
                         $cost['service'] = 'Frozen (JNE YES)';
                         $cost['description'] = 'Layanan Beku (1 Hari Sampai)';
                         $frozenCosts[] = $cost;
                     }
                 }

                 if (empty($frozenCosts)) {
                    $response['data'] = [];
                    $response['meta']['message'] = 'Layanan Frozen tidak tersedia untuk rute ini.';
                 } else {
                    $response['data'] = $frozenCosts;
                 }
            }
            
            return $response;
        }

        $response = $rajaOngkir->cost(
            $origin,
            $request->destination,
            $weight,
            $request->courier
        );

        return $response;
    }

    private function filterFrozenCosts($costs)
    {
        $frozenCosts = [];
        foreach ($costs as $cost) {
            // Cari service YES atau yang estimasinya 1 hari
            if (str_contains(strtoupper($cost['service']), 'YES') || 
                str_contains($cost['cost'][0]['etd'] ?? '', '1-1')) {
                
                $cost['service'] = 'Frozen (JNE YES)';
                $cost['description'] = 'Layanan Beku (1 Hari Sampai)';
                $frozenCosts[] = $cost;
            }
        }
        return $frozenCosts;
    }

    private function getCoordinates($destinationName)
    {
        if (!$destinationName) return null;

        // Caching Key based on destination name
        $cacheKey = 'geo_' . md5(strtolower(trim($destinationName)));
        
        return \Illuminate\Support\Facades\Cache::remember($cacheKey, 60 * 24 * 7, function () use ($destinationName) {
            
            // Clean up destination name
            // Format example: "-, BANDUNG, BANDUNG, JAWA BARAT, 40614"
            $parts = array_map('trim', explode(',', $destinationName));
            $searchQueries = [];
            
            // 1. Full string (only if it doesn't start with -)
            if (!str_starts_with($destinationName, '-')) {
                $searchQueries[] = $destinationName;
            }

            $subdistrict = $parts[0] ?? '';
            $city = $parts[1] ?? '';
            $type = $parts[2] ?? ''; // KABUPATEN / KOTA
            $province = $parts[3] ?? '';
            
            // 2. Subdistrict + City
            if ($subdistrict && $subdistrict !== '-') {
                if ($city) {
                    $searchQueries[] = "$subdistrict, $city";
                }
                $searchQueries[] = $subdistrict;
            }
            
            // 3. City + Province (Fallback - Index 1)
            if ($city) {
                $cleanCity = str_ireplace(['KOTA ', 'KABUPATEN '], '', $city);
                if ($province) {
                    $searchQueries[] = "$cleanCity, $province";
                }
                $searchQueries[] = $cleanCity;
            }

            // 4. City + Province (Fallback - Index 2)
            if ($type && $type !== '-') {
                 $cleanType = str_ireplace(['KOTA ', 'KABUPATEN '], '', $type);
                 if ($province) {
                     $searchQueries[] = "$cleanType, $province";
                 }
                 $searchQueries[] = $cleanType;
            }

            // Limit queries to top 3 most relevant to avoid spamming too much if list is long
            $searchQueries = array_slice(array_unique($searchQueries), 0, 3);
            
            // Use Http Pool for Parallel Execution (Much Faster)
            try {
                $responses = Http::pool(function ($pool) use ($searchQueries) {
                    $requests = [];
                    foreach ($searchQueries as $query) {
                        $requests[] = $pool->timeout(4) // Fast timeout 4s
                            ->withHeaders(['User-Agent' => 'Bohrifarm/1.0 (bohrifarm@example.com)'])
                            ->get('https://nominatim.openstreetmap.org/search', [
                                'q' => $query,
                                'format' => 'json',
                                'limit' => 1
                            ]);
                    }
                    return $requests;
                });

                foreach ($responses as $response) {
                    if ($response->ok()) {
                        $result = $response->json();
                        if (!empty($result)) {
                            return [
                                'lat' => $result[0]['lat'],
                                'lon' => $result[0]['lon']
                            ];
                        }
                    }
                }
            } catch (\Exception $e) {
                // Fallback to sequential if pool fails (unlikely)
            }

            return null;
        });
    }

    private function calculateLalamoveCost($destinationName)
    {
        try {
            // 1. Koordinat Toko (Bogor - Alun-alun Kota Bogor sebagai titik tengah)
            $storeLat = -6.595038; 
            $storeLng = 106.793311;

            // 2. Geocoding Destination
            $coords = $this->getCoordinates($destinationName);
            
            if (!$coords) {
                 throw new \Exception("Gagal menemukan lokasi: $destinationName");
            }

            $destLat = $coords['lat'];
            $destLng = $coords['lon'];

            // 3. Hitung Jarak (Haversine Formula)
            $earthRadius = 6371; 
            $dLat = deg2rad($destLat - $storeLat);
            $dLng = deg2rad($destLng - $storeLng);

            $a = sin($dLat / 2) * sin($dLat / 2) +
                 cos(deg2rad($storeLat)) * cos(deg2rad($destLat)) *
                 sin($dLng / 2) * sin($dLng / 2);

            $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
            $distance = $earthRadius * $c; // km

            // 4. Hitung Biaya Lalamove (Simulasi)
            // Base fare: 15.000 (0-2km)
            // Next km: 4.000/km
            
            $baseFare = 15000;
            $ratePerKm = 4000;
            
            if ($distance <= 2) {
                $cost = $baseFare;
            } else {
                $extraDistance = ceil($distance - 2);
                $cost = $baseFare + ($extraDistance * $ratePerKm);
            }

            // Round up to nearest 500
            $cost = ceil($cost / 500) * 500;

            return response()->json([
                'rajaongkir' => [
                    'results' => [
                        [
                            'code' => 'lalamove',
                            'name' => 'Lalamove',
                            'costs' => [
                                [
                                    'service' => 'Lalamove (Motor)',
                                    'description' => 'Pengiriman Instan',
                                    'cost' => [
                                        [
                                            'value' => $cost,
                                            'etd' => '1-3 Jam',
                                            'note' => 'Jarak: ' . number_format($distance, 1) . ' km'
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'meta' => [
                    'code' => 500,
                    'message' => 'Gagal menghitung tarif Lalamove: ' . $e->getMessage()
                ],
                'rajaongkir' => [
                    'results' => []
                ]
            ]);
        }
    }

    private function calculateAntarTokoCost($destinationName)
    {
        try {
            // 1. Koordinat Toko (Bogor - Alun-alun Kota Bogor sebagai titik tengah)
            // Ganti dengan koordinat akurat farm jika ada
            $storeLat = -6.595038; 
            $storeLng = 106.793311;

            // 2. Geocoding Destination
            $coords = $this->getCoordinates($destinationName);
            
            if (!$coords) {
                 throw new \Exception("Gagal menemukan lokasi: $destinationName");
            }

            $destLat = $coords['lat'];
            $destLng = $coords['lon'];

            // 3. Hitung Jarak (Haversine Formula)
            $earthRadius = 6371; // km

            $dLat = deg2rad($destLat - $storeLat);
            $dLng = deg2rad($destLng - $storeLng);

            $a = sin($dLat / 2) * sin($dLat / 2) +
                 cos(deg2rad($storeLat)) * cos(deg2rad($destLat)) *
                 sin($dLng / 2) * sin($dLng / 2);

            $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
            $distance = $earthRadius * $c; // dalam km

            // 4. Hitung Biaya (Rp 3.000 per km)
            $ratePerKm = 3000;
            $cost = ceil($distance) * $ratePerKm;

            // Minimum cost (10k)
            if ($cost < 10000) $cost = 10000;

            // 5. Return structure mirip RajaOngkir
            return response()->json([
                'rajaongkir' => [
                    'results' => [
                        [
                            'code' => 'antar_toko',
                            'name' => 'Antar Toko',
                            'costs' => [
                                [
                                    'service' => 'Reguler',
                                    'description' => 'Kurir Toko',
                                    'cost' => [
                                        [
                                            'value' => $cost,
                                            'etd' => '1', // estimasi hari
                                            'note' => 'Jarak: ' . number_format($distance, 1) . ' km'
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Antar Toko Error: ' . $e->getMessage());
            
            // Fallback jika gagal geocoding
            // Kita naikkan default fallback distance karena user komplain jarak jauh
            // Asumsi default 30km (Bogor-Cikarang approx 40-50km, tapi 30km aman)
            $fallbackDistance = 30; 
            $fallbackCost = $fallbackDistance * 3000; // 90.000
            
            return response()->json([
                'rajaongkir' => [
                    'results' => [
                        [
                            'code' => 'antar_toko',
                            'name' => 'Antar Toko',
                            'costs' => [
                                [
                                    'service' => 'Reguler',
                                    'description' => 'Kurir Toko',
                                    'cost' => [
                                        [
                                            'value' => $fallbackCost,
                                            'etd' => '1',
                                            'note' => 'Estimasi Manual (Gagal hitung otomatis)'
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]);
        }
    }

    public function process(Request $request)
    {
        // Handle GET request (fallback for redirect issues)
        if ($request->isMethod('get')) {
            return redirect()->route('checkout.index')
                ->with('error', 'Sesi kedaluwarsa atau permintaan tidak valid. Silakan coba lagi.');
        }

        logger()->info('CheckoutController::process', $request->all());
        
        $request->validate([
            'shipping_name' => 'required',
            'shipping_phone' => 'required',
            'shipping_address' => 'required',
            'shipping_city' => 'required',
            'courier' => 'required',
            'service' => 'required_unless:courier,ambil_sendiri',
            'shipping_cost' => 'required|numeric'
        ], [
            'shipping_name.required' => 'Nama penerima wajib diisi.',
            'shipping_phone.required' => 'Nomor telepon wajib diisi.',
            'shipping_address.required' => 'Alamat lengkap wajib diisi.',
            'shipping_city.required' => 'Silakan cari dan pilih kecamatan/kota Anda.',
            'courier.required' => 'Silakan pilih kurir pengiriman.',
            'service.required_unless' => 'Silakan pilih layanan pengiriman (Reguler, Hemat, dll) yang muncul setelah memilih kota dan kurir.',
            'shipping_cost.required' => 'Biaya ongkir belum terhitung. Pastikan layanan pengiriman sudah dipilih.',
        ]);

        $cart = Cart::where('user_id', Auth::id())
                    ->where('status', 'active')
                    ->with('items.product')
                    ->firstOrFail();

        $totalAmount = $cart->items->sum('subtotal');
        $grandTotal = $totalAmount + (float)$request->shipping_cost;

        // Buat order
        $order = Order::create([
            'user_id' => Auth::id(),
            'cart_id' => $cart->id,
            'code' => 'ORD-' . strtoupper(uniqid()),
            'status' => 'pending',
            'payment_status' => 'pending',

            'total_amount' => $totalAmount,
            'shipping_cost' => (float)$request->shipping_cost,
            'grand_total' => $grandTotal,

            'shipping_name' => $request->shipping_name,
            'shipping_phone' => $request->shipping_phone,
            'shipping_address' => $request->shipping_address,
            'shipping_city' => $request->shipping_city,
            'shipping_postal_code' => $request->shipping_postal_code ?? '-',

            'courier' => $request->courier,
            'service' => $request->service ?? 'Ambil Sendiri',
            'weight' => $cart->items->sum(fn($i) => $i->product->weight * $i->quantity),

            'ordered_at' => now(),
        ]);

        // Insert order items
        foreach ($cart->items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'product_name' => $item->product->name,
                'price' => $item->price,
                'quantity' => $item->quantity,
                'subtotal' => $item->subtotal,
            ]);
        }

        // tandai cart sudah dipakai
        $cart->update(['status' => 'converted']);

        return redirect()->route('payment.start', $order->id);
    }

    public function quickOrder(Request $request)
{
    try {
        // Ambil JSON dari fetch()
        $data = $request->validate([
            'product_name' => 'required|string',
            'price'        => 'required|numeric',
            'quantity'     => 'required|integer|min:1'
        ]);

        $user = auth()->user();

        // Generate order code
        $orderCode = 'QO-' . strtoupper(Str::random(8));

        // Hitung total
        $total = $data['price'] * $data['quantity'];

        // Simpan order
        $order = Order::create([
            'user_id'        => $user->id,
            'code'           => $orderCode,
            'status'         => 'pending',
            'payment_status' => 'pending',
            'shipping_name'  => $user->name,
            'shipping_phone' => $user->phone ?? '-',
            'shipping_address' => $user->address ?? '-',
            'shipping_city'  => 'Unknown',
            'shipping_postal_code' => '-',
            'total_amount'   => $total,
            'grand_total'    => $total,
        ]);

        // Simpan 1 barang sebagai order item
        OrderItem::create([
            'order_id' => $order->id,
            'product_name' => $data['product_name'],
            'quantity' => $data['quantity'],
            'price' => $data['price'],
            'subtotal' => $total,
        ]);

        return response()->json([
            'success' => true,
            'order_id' => $order->id,
            'redirect_url' => route('payment.start', $order->id)
        ]);

    } catch (\Exception $e) {

        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

}

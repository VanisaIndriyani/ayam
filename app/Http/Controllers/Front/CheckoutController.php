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
        if (!$cart) {
            return redirect()->route('products.index')->with('error', 'Keranjang belanja kosong.');
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

        $response = $rajaOngkir->cost(
            $origin,
            $request->destination,
            $weight,
            $request->courier
        );

        return $response;
    }

    private function calculateAntarTokoCost($destinationName)
    {
        try {
            // 1. Koordinat Toko (Default: Monas Jakarta, ganti dengan lokasi toko sebenarnya)
            $storeLat = -6.175392; 
            $storeLng = 106.827153;

            // 2. Geocoding Destination
            if (!$destinationName) {
                return response()->json(['rajaongkir' => ['results' => []]]);
            }

            // Gunakan Nominatim (Free, but limited)
            $response = Http::timeout(5)->withHeaders([
                'User-Agent' => 'Bohrifarm/1.0 (bohrifarm@example.com)'
            ])->get('https://nominatim.openstreetmap.org/search', [
                'q' => $destinationName,
                'format' => 'json',
                'limit' => 1
            ]);

            $data = $response->json();

            if (empty($data)) {
                 throw new \Exception("Nominatim returned empty data");
            }

            $destLat = $data[0]['lat'];
            $destLng = $data[0]['lon'];

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

            // Minimum cost (optional, e.g., 10k)
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
            
            // Fallback jika gagal geocoding (misal timeout atau limit)
            // Asumsi jarak default 10km agar user tetap bisa checkout
            $fallbackDistance = 10;
            $fallbackCost = $fallbackDistance * 3000;
             if ($fallbackCost < 10000) $fallbackCost = 10000;

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
                                            'note' => 'Estimasi Jarak: ' . $fallbackDistance . ' km (Manual)'
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
        logger()->info('CheckoutController::process', $request->all());
        
        $request->validate([
            'shipping_name' => 'required',
            'shipping_phone' => 'required',
            'shipping_address' => 'required',
            'shipping_city' => 'required',
            'courier' => 'required',
            'service' => 'required_unless:courier,manual',
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
            'service' => $request->service ?? 'Manual',
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

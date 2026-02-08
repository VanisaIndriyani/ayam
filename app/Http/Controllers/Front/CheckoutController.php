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

        // CHECK FOR ERROR / EMPTY / LIMIT
        $isError = false;
        if (isset($data['meta']['code']) && $data['meta']['code'] >= 400) $isError = true;
        if (isset($data['status']['code']) && $data['status']['code'] >= 400) $isError = true;
        
        // If error or empty data (indicating potential issue/limit), fallback
        if ($isError || (empty($data['data']) && empty($data['rajaongkir']['results']))) {
             // FALLBACK MOCK DATA for Testing when API is down
             // We include major cities to allow testing
             $fallbackCities = [
                // JABODETABEK
                ['id' => '151', 'label' => 'Jakarta Barat, DKI Jakarta'],
                ['id' => '152', 'label' => 'Jakarta Pusat, DKI Jakarta'],
                ['id' => '153', 'label' => 'Jakarta Selatan, DKI Jakarta'],
                ['id' => '154', 'label' => 'Jakarta Timur, DKI Jakarta'],
                ['id' => '155', 'label' => 'Jakarta Utara, DKI Jakarta'],
                ['id' => '78',  'label' => 'Bogor, Jawa Barat'],
                ['id' => '79',  'label' => 'Bogor (Kabupaten), Jawa Barat'],
                ['id' => '115', 'label' => 'Depok, Jawa Barat'],
                ['id' => '457', 'label' => 'Tangerang, Banten'],
                ['id' => '455', 'label' => 'Tangerang (Kabupaten), Banten'],
                ['id' => '456', 'label' => 'Tangerang Selatan, Banten'],
                ['id' => '23',  'label' => 'Bekasi, Jawa Barat'],
                ['id' => '22',  'label' => 'Bekasi (Kabupaten), Jawa Barat'],
                
                // JAWA BARAT
                ['id' => '22',  'label' => 'Bandung, Jawa Barat'],
                ['id' => '23',  'label' => 'Bandung (Kabupaten), Jawa Barat'],
                ['id' => '24',  'label' => 'Bandung Barat, Jawa Barat'],
                ['id' => '104', 'label' => 'Cimahi, Jawa Barat'],
                ['id' => '468', 'label' => 'Tasikmalaya, Jawa Barat'],
                ['id' => '469', 'label' => 'Tasikmalaya (Kabupaten), Jawa Barat'],
                ['id' => '111', 'label' => 'Cirebon, Jawa Barat'],
                ['id' => '112', 'label' => 'Cirebon (Kabupaten), Jawa Barat'],
                ['id' => '430', 'label' => 'Sukabumi, Jawa Barat'],
                ['id' => '431', 'label' => 'Sukabumi (Kabupaten), Jawa Barat'],
                ['id' => '174', 'label' => 'Karawang, Jawa Barat'],
                ['id' => '369', 'label' => 'Purwakarta, Jawa Barat'],
                ['id' => '439', 'label' => 'Subang, Jawa Barat'],
                ['id' => '173', 'label' => 'Indramayu, Jawa Barat'],
                ['id' => '149', 'label' => 'Garut, Jawa Barat'],
                ['id' => '108', 'label' => 'Cianjur, Jawa Barat'],
                ['id' => '106', 'label' => 'Ciamis, Jawa Barat'],
                ['id' => '252', 'label' => 'Majalengka, Jawa Barat'],
                ['id' => '440', 'label' => 'Sumedang, Jawa Barat'],
                ['id' => '210', 'label' => 'Kuningan, Jawa Barat'],

                // JAWA TENGAH & DIY
                ['id' => '419', 'label' => 'Semarang, Jawa Tengah'],
                ['id' => '420', 'label' => 'Semarang (Kabupaten), Jawa Tengah'],
                ['id' => '398', 'label' => 'Solo (Surakarta), Jawa Tengah'],
                ['id' => '501', 'label' => 'Yogyakarta, DI Yogyakarta'],
                ['id' => '39',  'label' => 'Bantul, DI Yogyakarta'],
                ['id' => '418', 'label' => 'Sleman, DI Yogyakarta'],
                ['id' => '137', 'label' => 'Gunung Kidul, DI Yogyakarta'],
                ['id' => '207', 'label' => 'Kulon Progo, DI Yogyakarta'],
                ['id' => '249', 'label' => 'Magelang, Jawa Tengah'],
                ['id' => '250', 'label' => 'Magelang (Kabupaten), Jawa Tengah'],
                ['id' => '363', 'label' => 'Pekalongan, Jawa Tengah'],
                ['id' => '364', 'label' => 'Pekalongan (Kabupaten), Jawa Tengah'],
                ['id' => '473', 'label' => 'Tegal, Jawa Tengah'],
                ['id' => '474', 'label' => 'Tegal (Kabupaten), Jawa Tengah'],
                ['id' => '375', 'label' => 'Purwokerto (Banyumas), Jawa Tengah'],
                ['id' => '105', 'label' => 'Cilacap, Jawa Tengah'],
                ['id' => '189', 'label' => 'Kebumen, Jawa Tengah'],
                ['id' => '376', 'label' => 'Purbalingga, Jawa Tengah'],
                ['id' => '73',  'label' => 'Banjarnegara, Jawa Tengah'],
                ['id' => '94',  'label' => 'Brebes, Jawa Tengah'],
                ['id' => '355', 'label' => 'Pemalang, Jawa Tengah'],
                ['id' => '51',  'label' => 'Batang, Jawa Tengah'],
                ['id' => '195', 'label' => 'Kendal, Jawa Tengah'],
                ['id' => '113', 'label' => 'Demak, Jawa Tengah'],
                ['id' => '206', 'label' => 'Kudus, Jawa Tengah'],
                ['id' => '170', 'label' => 'Jepara, Jawa Tengah'],
                ['id' => '350', 'label' => 'Pati, Jawa Tengah'],
                ['id' => '385', 'label' => 'Rembang, Jawa Tengah'],
                ['id' => '76',  'label' => 'Blora, Jawa Tengah'],
                ['id' => '136', 'label' => 'Grobogan, Jawa Tengah'],
                ['id' => '498', 'label' => 'Wonogiri, Jawa Tengah'],
                ['id' => '182', 'label' => 'Karanganyar, Jawa Tengah'],
                ['id' => '427', 'label' => 'Sragen, Jawa Tengah'],
                ['id' => '433', 'label' => 'Sukoharjo, Jawa Tengah'],
                ['id' => '199', 'label' => 'Klaten, Jawa Tengah'],
                ['id' => '92',  'label' => 'Boyolali, Jawa Tengah'],
                ['id' => '400', 'label' => 'Salatiga, Jawa Tengah'],

                // POPULAR KECAMATAN / ALIASES (Mapped to Parent City/Regency)
                // JABODETABEK & JABAR
                ['id' => '23',  'label' => 'Cikarang, Bekasi (Kabupaten), Jawa Barat'], // Cikarang is capital of Bekasi Regency
                ['id' => '23',  'label' => 'Cikarang Barat, Bekasi, Jawa Barat'],
                ['id' => '23',  'label' => 'Cikarang Utara, Bekasi, Jawa Barat'],
                ['id' => '23',  'label' => 'Cikarang Selatan, Bekasi, Jawa Barat'],
                ['id' => '23',  'label' => 'Cikarang Timur, Bekasi, Jawa Barat'],
                ['id' => '23',  'label' => 'Cikarang Pusat, Bekasi, Jawa Barat'],
                ['id' => '23',  'label' => 'Tambun, Bekasi, Jawa Barat'],
                ['id' => '23',  'label' => 'Cibitung, Bekasi, Jawa Barat'],
                ['id' => '174', 'label' => 'Cikampek, Karawang, Jawa Barat'],
                ['id' => '115', 'label' => 'Cibubur, Jakarta Timur / Depok'],
                ['id' => '456', 'label' => 'Ciputat, Tangerang Selatan, Banten'],
                ['id' => '456', 'label' => 'Bintaro, Tangerang Selatan, Banten'],
                ['id' => '456', 'label' => 'Serpong, Tangerang Selatan, Banten'],
                ['id' => '456', 'label' => 'Pamulang, Tangerang Selatan, Banten'],
                ['id' => '457', 'label' => 'Ciledug, Tangerang, Banten'],
                ['id' => '155', 'label' => 'Kelapa Gading, Jakarta Utara, DKI Jakarta'],
                ['id' => '155', 'label' => 'Pantai Indah Kapuk, Jakarta Utara, DKI Jakarta'],
                ['id' => '155', 'label' => 'Pluit, Jakarta Utara, DKI Jakarta'],
                ['id' => '153', 'label' => 'Kemang, Jakarta Selatan, DKI Jakarta'],
                ['id' => '153', 'label' => 'Tebet, Jakarta Selatan, DKI Jakarta'],
                ['id' => '152', 'label' => 'Menteng, Jakarta Pusat, DKI Jakarta'],
                ['id' => '152', 'label' => 'Tanah Abang, Jakarta Pusat, DKI Jakarta'],
                ['id' => '24',  'label' => 'Lembang, Bandung Barat, Jawa Barat'],
                ['id' => '22',  'label' => 'Dago (Coblong), Bandung, Jawa Barat'],
                ['id' => '22',  'label' => 'Buahbatu, Bandung, Jawa Barat'],
                
                // JATENG & JATIM & BALI
                ['id' => '501', 'label' => 'Malioboro (Gedong Tengen), Yogyakarta'],
                ['id' => '18',  'label' => 'Kuta, Badung, Bali'],
                ['id' => '128', 'label' => 'Ubud, Gianyar, Bali'],
                ['id' => '190', 'label' => 'Pare, Kediri, Jawa Timur'],

                // JAWA TIMUR
                ['id' => '444', 'label' => 'Surabaya, Jawa Timur'],
                ['id' => '256', 'label' => 'Malang, Jawa Timur'],
                ['id' => '255', 'label' => 'Malang (Kabupaten), Jawa Timur'],
                ['id' => '55',  'label' => 'Batu, Jawa Timur'],
                ['id' => '416', 'label' => 'Sidoarjo, Jawa Timur'],
                ['id' => '144', 'label' => 'Gresik, Jawa Timur'],
                ['id' => '303', 'label' => 'Mojokerto, Jawa Timur'],
                ['id' => '302', 'label' => 'Mojokerto (Kabupaten), Jawa Timur'],
                ['id' => '349', 'label' => 'Pasuruan, Jawa Timur'],
                ['id' => '348', 'label' => 'Pasuruan (Kabupaten), Jawa Timur'],
                ['id' => '367', 'label' => 'Probolinggo, Jawa Timur'],
                ['id' => '366', 'label' => 'Probolinggo (Kabupaten), Jawa Timur'],
                ['id' => '160', 'label' => 'Jember, Jawa Timur'],
                ['id' => '42',  'label' => 'Banyuwangi, Jawa Timur'],
                ['id' => '191', 'label' => 'Kediri, Jawa Timur'],
                ['id' => '190', 'label' => 'Kediri (Kabupaten), Jawa Timur'],
                ['id' => '246', 'label' => 'Madiun, Jawa Timur'],
                ['id' => '245', 'label' => 'Madiun (Kabupaten), Jawa Timur'],
                ['id' => '72',  'label' => 'Blitar, Jawa Timur'],
                ['id' => '71',  'label' => 'Blitar (Kabupaten), Jawa Timur'],
                ['id' => '484', 'label' => 'Tulungagung, Jawa Timur'],
                ['id' => '480', 'label' => 'Trenggalek, Jawa Timur'],
                ['id' => '365', 'label' => 'Ponorogo, Jawa Timur'],
                ['id' => '325', 'label' => 'Pacitan, Jawa Timur'],
                ['id' => '312', 'label' => 'Ngawi, Jawa Timur'],
                ['id' => '247', 'label' => 'Magetan, Jawa Timur'],
                ['id' => '226', 'label' => 'Lamongan, Jawa Timur'],
                ['id' => '479', 'label' => 'Tuban, Jawa Timur'],
                ['id' => '80',  'label' => 'Bojonegoro, Jawa Timur'],

                // LUAR JAWA (SUMATERA, KALIMANTAN, SULAWESI, BALI)
                ['id' => '114', 'label' => 'Denpasar, Bali'],
                ['id' => '18',  'label' => 'Badung (Kuta/Nusa Dua), Bali'],
                ['id' => '128', 'label' => 'Gianyar (Ubud), Bali'],
                ['id' => '278', 'label' => 'Medan, Sumatera Utara'],
                ['id' => '74',  'label' => 'Banjarmasin, Kalimantan Selatan'],
                ['id' => '361', 'label' => 'Pontianak, Kalimantan Barat'],
                ['id' => '399', 'label' => 'Samarinda, Kalimantan Timur'],
                ['id' => '17',  'label' => 'Balikpapan, Kalimantan Timur'],
                ['id' => '254', 'label' => 'Makassar, Sulawesi Selatan'],
                ['id' => '270', 'label' => 'Manado, Sulawesi Utara'],
                ['id' => '327', 'label' => 'Palembang, Sumatera Selatan'],
                ['id' => '21',  'label' => 'Bandar Lampung, Lampung'],
                ['id' => '318', 'label' => 'Padang, Sumatera Barat'],
                ['id' => '354', 'label' => 'Pekanbaru, Riau'],
                ['id' => '156', 'label' => 'Jambi, Jambi'],
                ['id' => '62',  'label' => 'Bengkulu, Bengkulu'],
                ['id' => '14',  'label' => 'Banda Aceh, Aceh'],
                ['id' => '54',  'label' => 'Batam, Kepulauan Riau'],
                ['id' => '336', 'label' => 'Pangkal Pinang, Bangka Belitung'],
            ];

             // Filter based on user query to simulate search
             $results = array_filter($fallbackCities, function($item) use ($query) {
                 return stripos($item['label'], $query) !== false;
             });

             // If no match found, show all (so user can at least pick something)
             // Or better, show nothing if no match to simulate real search?
             // But for fallback, better to be generous.
             $finalResults = !empty($results) ? array_values($results) : array_values($fallbackCities);

             return response()->json([
                 'meta' => ['code' => 200, 'message' => 'Fallback Data (API Limit Reached)'],
                 'data' => $finalResults
             ]);
        }

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

            // CHECK FOR API LIMIT ERROR AND PROVIDE FALLBACK (DEV MODE)
            $isError = false;
            $errorMsg = '';
            
            if (isset($response['rajaongkir']['status']['code']) && $response['rajaongkir']['status']['code'] >= 400) {
                $isError = true;
                $errorMsg = $response['rajaongkir']['status']['description'] ?? 'Unknown Error';
            } elseif (isset($response['meta']['code']) && $response['meta']['code'] >= 400) {
                $isError = true;
                $errorMsg = $response['meta']['message'] ?? 'Unknown Error';
            }

            if ($isError) {
                // Fallback Mock for Development/Testing when API Limit is hit
                // Only if error contains "limit" or we decide to always fallback on error
                if (str_contains(strtolower($errorMsg), 'limit')) {
                    return response()->json([
                        'rajaongkir' => [
                            'results' => [
                                [
                                    'code' => 'frozen',
                                    'name' => 'Frozen Service',
                                    'costs' => [
                                        [
                                            'service' => 'Frozen (JNE YES)',
                                            'description' => 'Layanan Beku (Fallback Mode)',
                                            'cost' => [
                                                [
                                                    'value' => 20000, // Fixed fallback price
                                                    'etd' => '1-1',
                                                    'note' => 'API Limit Habis (Estimasi)'
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

                 // Sort by price (cheapest first) to handle duplicates
                 usort($frozenCosts, function($a, $b) {
                     $priceA = $a['price'] ?? $a['tariff'] ?? $a['cost'] ?? $a['value'] ?? 999999999;
                     $priceB = $b['price'] ?? $b['tariff'] ?? $b['cost'] ?? $b['value'] ?? 999999999;
                     return $priceA - $priceB;
                 });

                 if (empty($frozenCosts)) {
                    $response['data'] = [];
                    $response['meta']['message'] = 'Layanan Frozen tidak tersedia untuk rute ini.';
                 } else {
                    // Take only the cheapest one
                    $response['data'] = [$frozenCosts[0]];
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

        // CHECK FOR API LIMIT ERROR AND PROVIDE FALLBACK (Standard Courier)
        $isError = false;
        $errorMsg = '';
        
        if (isset($response['rajaongkir']['status']['code']) && $response['rajaongkir']['status']['code'] >= 400) {
            $isError = true;
            $errorMsg = $response['rajaongkir']['status']['description'] ?? 'Unknown Error';
        } elseif (isset($response['meta']['code']) && $response['meta']['code'] >= 400) {
            $isError = true;
            $errorMsg = $response['meta']['message'] ?? 'Unknown Error';
        }

        if ($isError) {
             if (str_contains(strtolower($errorMsg), 'limit')) {
                // Fallback for JNE/J&T/etc
                return response()->json([
                    'rajaongkir' => [
                        'results' => [
                            [
                                'code' => $request->courier,
                                'name' => strtoupper($request->courier),
                                'costs' => [
                                    [
                                        'service' => 'HEMAT',
                                        'description' => 'Layanan Hemat (Fallback Mode)',
                                        'cost' => [
                                            [
                                                'value' => 12000, 
                                                'etd' => '4-6',
                                                'note' => 'API Limit Habis (Estimasi)'
                                            ]
                                        ]
                                    ],
                                    [
                                        'service' => 'REG',
                                        'description' => 'Layanan Reguler (Fallback Mode)',
                                        'cost' => [
                                            [
                                                'value' => 18000, 
                                                'etd' => '2-3',
                                                'note' => 'API Limit Habis (Estimasi)'
                                            ]
                                        ]
                                    ],
                                    [
                                        'service' => 'FAST/YES',
                                        'description' => 'Layanan Cepat (Fallback Mode)',
                                        'cost' => [
                                            [
                                                'value' => 30000, 
                                                'etd' => '1-1',
                                                'note' => 'API Limit Habis (Estimasi)'
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

        // Sort by price (cheapest first)
        usort($frozenCosts, function($a, $b) {
            $priceA = $a['cost'][0]['value'] ?? 999999999;
            $priceB = $b['cost'][0]['value'] ?? 999999999;
            return $priceA - $priceB;
        });

        // Return only the cheapest one (if any exist)
        return !empty($frozenCosts) ? [$frozenCosts[0]] : [];
    }

    private function getCoordinates($destinationName)
    {
        if (!$destinationName) return null;

        // Caching Key based on destination name (v3 for stability)
        $cacheKey = 'geo_v3_' . md5(strtolower(trim($destinationName)));
        
        if (\Illuminate\Support\Facades\Cache::has($cacheKey)) {
            return \Illuminate\Support\Facades\Cache::get($cacheKey);
        }
            
        // Clean up destination name
        $parts = array_map('trim', explode(',', $destinationName));
        $searchQueries = [];
        
        // Mapping based on typical Select2 format: "KELURAHAN, KECAMATAN, KOTA, PROVINSI, KODEPOS"
        $subdistrict = $parts[0] ?? ''; // Kelurahan
        $district = $parts[1] ?? '';    // Kecamatan
        $city = $parts[2] ?? '';        // Kota/Kab
        $province = $parts[3] ?? '';    // Provinsi

        // Prioritize queries that are most likely to work and be accurate
        
        // 1. Kelurahan, Kecamatan, Kota (Most Specific)
        if ($subdistrict && $district && $city && !str_starts_with($subdistrict, '-')) {
             $searchQueries[] = "$subdistrict, $district, $city";
        }

        // 1b. Postal Code (Very Accurate)
        $postalCode = null;
        if (isset($parts[4]) && is_numeric(trim($parts[4]))) {
            $postalCode = trim($parts[4]);
            $searchQueries[] = "$postalCode, Indonesia";
        }

        // 2. Kecamatan, Kota (Very Reliable)
        if ($district && $city && !str_starts_with($district, '-')) {
            $searchQueries[] = "$district, $city";
        }

        // 3. Kecamatan Only
        if ($district && !str_starts_with($district, '-')) {
             if ($province) $searchQueries[] = "$district, $province";
             $searchQueries[] = $district;
        }

        // 4. Kota Only (Broad Fallback)
        if ($city && !str_starts_with($city, '-')) {
            $cleanCity = str_ireplace(['KOTA ', 'KABUPATEN '], '', $city);
            if ($province) $searchQueries[] = "$cleanCity, $province";
            $searchQueries[] = $cleanCity;
        }

        // Remove duplicates and limit
        $searchQueries = array_slice(array_unique($searchQueries), 0, 6);
        
        $resultCoords = null;

        // SEQUENTIAL EXECUTION ONLY (To respect Nominatim Policy of 1 req/sec)
        // Parallel execution (Http::pool) causes 429 Too Many Requests and IP blocks.
        foreach ($searchQueries as $index => $query) {
             try {
                 // Add delay for 2nd request onwards to respect policy
                 if ($index > 0) {
                     usleep(1200000); // 1.2 second delay (slightly more than 1s)
                 }

                 $response = Http::timeout(10) // Increased timeout
                     ->withHeaders([
                         'User-Agent' => 'Mozilla/5.0 (compatible; Bohrifarm/1.0; +https://bohrifarm.com)',
                         'Referer' => 'https://bohrifarm.com'
                     ])
                     ->get('https://nominatim.openstreetmap.org/search', [
                         'q' => $query,
                         'format' => 'json',
                         'limit' => 1
                     ]);
                 
                 if ($response->ok()) {
                     $result = $response->json();
                     if (!empty($result)) {
                         $resultCoords = ['lat' => $result[0]['lat'], 'lon' => $result[0]['lon']];
                         break; // Found it! Stop searching.
                     }
                 }
             } catch (\Exception $e) { 
                 \Log::warning("Geocoding failed for query: $query. Error: " . $e->getMessage());
                 continue; 
             }
        }

        // Only cache if we found a result
        if ($resultCoords) {
            \Illuminate\Support\Facades\Cache::put($cacheKey, $resultCoords, 60 * 24 * 7);
        }

        return $resultCoords;
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

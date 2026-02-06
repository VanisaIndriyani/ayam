<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RajaOngkirService
{
    protected $key;

    public function __construct()
    {
        $this->key = config('services.rajaongkir.key');
    }

    public function searchDestination($query)
    {
        return Http::withHeaders([
            'key' => $this->key
        ])->get('https://rajaongkir.komerce.id/api/v1/destination/domestic-destination', [
            'search' => $query,
            'limit' => 20
        ])->json();
    }

    public function cost($origin, $destination, $weight, $courier)
    {
        // Komerce domestic cost endpoint
        // Kita tambahkan originType dan destinationType agar lebih akurat (terutama untuk API Pro/Komerce)
        // Gunakan asForm() karena API RajaOngkir/Komerce biasanya mengharapkan application/x-www-form-urlencoded
        return Http::asForm()->withHeaders([
            'key' => $this->key
        ])->post('https://rajaongkir.komerce.id/api/v1/calculate/domestic-cost', [
            "origin" => $origin,
            "originType" => "city", // Asumsi origin adalah Kota (ID 152 Jakarta Pusat adalah Kota)
            "destination" => $destination,
            "destinationType" => "subdistrict", // Hasil search biasanya return ID Kecamatan (Subdistrict)
            "weight" => $weight,
            "courier" => $courier
        ])->json();
    }
}

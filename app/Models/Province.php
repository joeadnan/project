<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class CitySeeder extends Seeder
{
    public function run()
    {
        // Set waktu eksekusi lebih lama untuk 500+ data
        ini_set('max_execution_time', 600);
        City::truncate();

        $response = Http::timeout(120)
            ->withoutVerifying()
            ->withHeaders([
                'key' => config('services.rajaongkir.key'),
            ])->get('https://komerce.id');

        if ($response->successful()) {
            $cities = $response->json()['data'] ?? [];

            foreach ($cities as $city) {
                City::create([
                    'city_id'     => $city['id'],
                    'province_id' => $city['province_id'],
                    'name'        => $city['name'] . ' - (' . $city['type'] . ')',
                ]);
            }
        }
    }
}

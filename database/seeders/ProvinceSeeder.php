<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ProvinceSeeder extends Seeder
{
    public function run()
    {
        // 1. Ambil data dari API Komerce
        $response = Http::withHeaders([
            'key' => config('services.rajaongkir.key'),
        ])->get('https://rajaongkir.komerce.id/api/v1/destination/province');

        // 2. Pastikan request berhasil sebelum looping
        if ($response->successful()) {
            
            // Perbaikan: Komerce menggunakan key 'data', bukan 'rajaongkir'
            $provinces = $response->json()['data'] ?? [];

            foreach($provinces as $province) {
                DB::table('provinces')->insert([
                    // Perbaikan: Sesuaikan field ID dan Nama jika berbeda
                    'province_id' => $province['id'] ?? $province['province_id'],
                    'name'        => $province['name'] ?? $province['province']
                ]);
            }
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class CitySeeder extends Seeder
{
    public function run()
    {
        // 1. Set waktu eksekusi sangat lama karena data kota ada 500+
        ini_set('max_execution_time', 600);

        // 2. Gunakan timeout tinggi dan abaikan verifikasi SSL (untuk XAMPP)
        $response = Http::timeout(120) 
            ->withoutVerifying() 
            ->withHeaders([
                'key' => config('services.rajaongkir.key'),
            ])->get('https://rajaongkir.com');

        // 3. Cek apakah koneksi berhasil
        if ($response->failed()) {
            dd("Koneksi Gagal! Server RajaOngkir tidak merespon. Pesan: " . $response->body());
        }

        $data = $response->json();

        // 4. Pastikan struktur 'rajaongkir' ada dalam respon
        if (!isset($data['rajaongkir']['results'])) {
            dd("Struktur data salah atau API Key tidak valid!", $data);
        }

        $cities = $data['rajaongkir']['results'];

        // 5. Simpan data ke database
        foreach ($cities as $city) {
            City::create([
                'city_id'     => $city['city_id'],
                'province_id' => $city['province_id'],
                'name'        => $city['city_name'] . ' - (' . $city['type'] . ')',
            ]);
        }
    }
}

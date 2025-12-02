<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WilayahIndonesiaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data Provinsi (contoh singkat, bisa ditambahkan lebih lengkap)
        $provinces = [
            ['id_provinsi' => '31', 'name' => 'DKI Jakarta'],
            ['id_provinsi' => '32', 'name' => 'Jawa Barat'],
            ['id_provinsi' => '33', 'name' => 'Jawa Tengah'],
            ['id_provinsi' => '34', 'name' => 'DI Yogyakarta'],
            ['id_provinsi' => '35', 'name' => 'Jawa Timur'],
            ['id_provinsi' => '36', 'name' => 'Banten'],
            // Tambahkan provinsi lain sesuai kebutuhan
        ];

        DB::table('provinces')->insert($provinces);

        // Data Kabupaten/Kota (contoh singkat)
        $regencies = [
            ['id_kab_kota' => '3175', 'province_id' => '31', 'name' => 'Kota Tangerang'],
            ['id_kab_kota' => '3174', 'province_id' => '31', 'name' => 'Kota Tangerang Selatan'],
            ['id_kab_kota' => '3273', 'province_id' => '32', 'name' => 'Kota Bandung'],
            ['id_kab_kota' => '3578', 'province_id' => '35', 'name' => 'Kota Surabaya'],
            // Tambahkan kab/kota lain sesuai kebutuhan
        ];

        DB::table('regencies')->insert($regencies);

        // Data Kecamatan (contoh singkat)
        $districts = [
            ['id_kecamatan' => '3175010', 'regency_id' => '3175', 'name' => 'Kecamatan Pinang'],
            ['id_kecamatan' => '3175020', 'regency_id' => '3175', 'name' => 'Kecamatan Cipondoh'],
            ['id_kecamatan' => '3273010', 'regency_id' => '3273', 'name' => 'Kecamatan Lengkong'],
            ['id_kecamatan' => '3578010', 'regency_id' => '3578', 'name' => 'Kecamatan Genteng'],
            // Tambahkan kecamatan lain sesuai kebutuhan
        ];

        DB::table('districts')->insert($districts);

        // Data Desa/Kelurahan (contoh singkat)
        $villages = [
            ['id_desa' => '3175010001', 'district_id' => '3175010', 'name' => 'Kelurahan Pinang'],
            ['id_desa' => '3175010002', 'district_id' => '3175010', 'name' => 'Kelurahan Sukajadi'],
            ['id_desa' => '3273010001', 'district_id' => '3273010', 'name' => 'Kelurahan Cikutra'],
            ['id_desa' => '3578010001', 'district_id' => '3578010', 'name' => 'Kelurahan Genteng Kulon'],
            // Tambahkan desa/kelurahan lain sesuai kebutuhan
        ];

        DB::table('villages')->insert($villages);
    }
}

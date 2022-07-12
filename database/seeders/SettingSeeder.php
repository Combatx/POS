<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::create([
            'nama_perusahaan' => 'Toko Usaha Baru',
            'alamat' => 'Jl Pasar Sebangkau no 13',
            'telepon' => '089534564221',
            'tipe_nota' => 1,
            'path_logo' => '/img/logo_cart.png'
        ]);
    }
}

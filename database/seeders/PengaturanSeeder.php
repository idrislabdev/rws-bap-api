<?php

namespace Database\Seeders;

use App\Models\MaPengaturan;
use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;

class PengaturanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MaPengaturan::create(['id' =>  Uuid::uuid4()->toString(), 'nama' => 'PM_JATIM', 'tipe' => 'TEXT', 'nilai' => '', 'detail_nilai' => '']);
        MaPengaturan::create(['id' =>  Uuid::uuid4()->toString(), 'nama' => 'PM_BALNUS', 'tipe' => 'TEXT', 'nilai' => '', 'detail_nilai' => '']);
        MaPengaturan::create(['id' =>  Uuid::uuid4()->toString(), 'nama' => 'OSM_REGIONAL_WHOLESALE_SERVICE', 'tipe' => 'TEXT', 'nilai' => '', 'detail_nilai' => '']);
        MaPengaturan::create(['id' =>  Uuid::uuid4()->toString(), 'nama' => 'GM_CORE_TRANSPORT_NETWORK', 'tipe' => 'TEXT', 'nilai' => '', 'detail_nilai' => '']);
        MaPengaturan::create(['id' =>  Uuid::uuid4()->toString(), 'nama' => 'MANAGER_WHOLESALE_SUPPORT', 'tipe' => 'TEXT', 'nilai' => '', 'detail_nilai' => '']);
        MaPengaturan::create(['id' =>  Uuid::uuid4()->toString(), 'nama' => 'GM_NETWORK_ENGINEERING_PROJECT', 'tipe' => 'TEXT', 'nilai' => '', 'detail_nilai' => '']);
    }
}

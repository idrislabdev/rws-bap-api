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
        MaPengaturan::create(['id' =>  Uuid::uuid4()->toString(), 'nama' => 'PM_JATIM', 'tipe' => 'TEXT', 'nilai' => 'BERNARD SINAGA', 'detail_nilai' => 'MANAGER PM JATIM']);
        MaPengaturan::create(['id' =>  Uuid::uuid4()->toString(), 'nama' => 'PM_BALNUS', 'tipe' => 'TEXT', 'nilai' => 'ERWIN CAHYADI', 'detail_nilai' => 'MANAGER PM BALNUS']);
        MaPengaturan::create(['id' =>  Uuid::uuid4()->toString(), 'nama' => 'OSM_REGIONAL_WHOLESALE_SERVICE', 'tipe' => 'TEXT', 'nilai' => 'WASITO ADI', 'detail_nilai' => 'OSM Regional Wholesale Service']);
        MaPengaturan::create(['id' =>  Uuid::uuid4()->toString(), 'nama' => 'GM_CORE_TRANSPORT_NETWORK', 'tipe' => 'TEXT', 'nilai' => 'GALUMBANG PASARIBU', 'detail_nilai' => 'GM. CORE AND TRANSPORT NETWORK
        DEPLOYMENT']);
        MaPengaturan::create(['id' =>  Uuid::uuid4()->toString(), 'nama' => 'MANAGER_WHOLESALE_SUPPORT', 'tipe' => 'TEXT', 'nilai' => 'TEGUH CIPTO EDHI', 'detail_nilai' => 'MANAGER WHOLESALE SUPPORT']);
        MaPengaturan::create(['id' =>  Uuid::uuid4()->toString(), 'nama' => 'GM_NETWORK_ENGINEERING_PROJECT', 'tipe' => 'TEXT', 'nilai' => 'ADE MULYONO', 'detail_nilai' => 'GM. Network Engineering and Project
        Jawa Bali']);
    }
}

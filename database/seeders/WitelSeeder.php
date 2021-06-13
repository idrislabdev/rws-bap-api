<?php

namespace Database\Seeders;

use App\Models\MaWitel;
use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;

class WitelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MaWitel::create(['id' => Uuid::uuid1()->toString(), 'site_witel' => 'SURABAYA UTARA']);
        MaWitel::create(['id' => Uuid::uuid1()->toString(), 'site_witel' => 'SURABAYA SELATAN']);
        MaWitel::create(['id' => Uuid::uuid1()->toString(), 'site_witel' => 'SURABAYA TIMUR']);
        MaWitel::create(['id' => Uuid::uuid1()->toString(), 'site_witel' => 'SURABAYA BARAT']);
        MaWitel::create(['id' => Uuid::uuid1()->toString(), 'site_witel' => 'SINGARAJA']);
        MaWitel::create(['id' => Uuid::uuid1()->toString(), 'site_witel' => 'DENPASAR']);
        MaWitel::create(['id' => Uuid::uuid1()->toString(), 'site_witel' => 'MATARAM']);
        MaWitel::create(['id' => Uuid::uuid1()->toString(), 'site_witel' => 'MALANG']);
        MaWitel::create(['id' => Uuid::uuid1()->toString(), 'site_witel' => 'JEMBER']);
        MaWitel::create(['id' => Uuid::uuid1()->toString(), 'site_witel' => 'SBU']);
        MaWitel::create(['id' => Uuid::uuid1()->toString(), 'site_witel' => 'KEDIRI']);
        MaWitel::create(['id' => Uuid::uuid1()->toString(), 'site_witel' => 'PASURUAN']);
        MaWitel::create(['id' => Uuid::uuid1()->toString(), 'site_witel' => 'SIDOARJO']);
        MaWitel::create(['id' => Uuid::uuid1()->toString(), 'site_witel' => 'MADIUN']);
        MaWitel::create(['id' => Uuid::uuid1()->toString(), 'site_witel' => 'SBS']);
        MaWitel::create(['id' => Uuid::uuid1()->toString(), 'site_witel' => 'MADURA']);
        MaWitel::create(['id' => Uuid::uuid1()->toString(), 'site_witel' => 'KUPANG']);
    }
}

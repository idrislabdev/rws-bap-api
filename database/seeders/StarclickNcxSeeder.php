<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class StarclickNcxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            ProfileNcxSeeder::class,
            ProfileStarclickSeeder::class,
            JabatanSeeder::class,
            JabatanStarclickSeeder::class,
            JabatanNcxSeeder::class,
        ]);
    }
}

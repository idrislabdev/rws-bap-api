<?php

namespace Database\Seeders;

use App\Models\MaProfileStarclick;
use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;

class ProfileStarclickSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csvFile = fopen(base_path("database/seeders/csv/starclick_profile.csv"), "r");


        $firstline = true;

        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {

            if (!$firstline) {
               $check = MaProfileStarclick::where('nama', $data[0])->first();
               if (!$check) {
                   MaProfileStarclick::create([
                       'id' => Uuid::uuid1()->toString(),
                       'nama' => $data[0], 
                   ]);
               }

            }

            $firstline = false;

        }

   

        fclose($csvFile);
    }
}

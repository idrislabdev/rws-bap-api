<?php

namespace Database\Seeders;

use App\Models\MaSto;
use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;

class StoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
          
        // MaSto::truncate();
        $csvFile = fopen(base_path("database/seeders/csv/sto.csv"), "r");


        $firstline = true;

        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {

            if (!$firstline) {

                MaSto::create([
                    'id' => Uuid::uuid1()->toString(),
                    'nama' => $data[0], 
                    // 'alamat' => $data[1], 
                    // 'latitude' => $data[2], 
                    // 'longitude' => $data[3], 
                ]);
            }

            $firstline = false;

        }

   

        fclose($csvFile);
    }
}

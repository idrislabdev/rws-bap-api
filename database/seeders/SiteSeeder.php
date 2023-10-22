<?php

namespace Database\Seeders;

use App\Models\MaSite;
use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid as UuidUuid;

class SiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       // MaSto::truncate();
       $csvFile = fopen(base_path("database/seeders/csv/site.csv"), "r");


       $firstline = true;

       while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {

           if (!$firstline) {
            $found = MaSite::where('site_id',$data[0])->first();
                if (!$found) {
                    MaSite::create([
                        'id' => UuidUuid::uuid1()->toString(),
                        'site_id' => $data[0],
                        'nama' => $data[1], 
                        // 'alamat' => $data[1], 
                        // 'latitude' => $data[2], 
                        // 'longitude' => $data[3], 
                    ]);
                }
           }

           $firstline = false;

       }

  

       fclose($csvFile);
    }
}

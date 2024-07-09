<?php

namespace Database\Seeders;

use App\Models\MaProfileNcxCons;
use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;

class ProfileNcxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         // MaSto::truncate();
         $csvFile = fopen(base_path("database/seeders/csv/ncx_profile.csv"), "r");


         $firstline = true;
 
         while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
 
             if (!$firstline) {
                $check = MaProfileNcxCons::where('nama', $data[0])->first();
                if (!$check) {
                    MaProfileNcxCons::create([
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

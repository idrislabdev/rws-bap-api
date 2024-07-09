<?php

namespace Database\Seeders;

use App\Models\MaJabatan;
use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;

class JabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csvFile = fopen(base_path("database/seeders/csv/master_jabatan.csv"), "r");


        $firstline = true;

        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {

            if (!$firstline) {
               $check = MaJabatan::where('nama', $data[0])->first();
               if (!$check) {
                   MaJabatan::create([
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

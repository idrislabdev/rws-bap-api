<?php

namespace Database\Seeders;

use App\Models\MaJabatan;
use Illuminate\Database\Seeder;

class JabatanStarclickSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csvFile = fopen(base_path("database/seeders/csv/jabatan_starclick.csv"), "r");


        $firstline = true;

        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {

            if (!$firstline) {
               $check = MaJabatan::where('nama', $data[0])->first();
               if ($check) {
                   MaJabatan::where('nama', $data[0])
                            ->update(array(
                                'starclick_ncx' => json_encode(explode("|",$data[1]), JSON_PRETTY_PRINT),
                            ));
               }

            }

            $firstline = false;

        }

   

        fclose($csvFile);
    }
}

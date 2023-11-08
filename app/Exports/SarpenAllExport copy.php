<?php

namespace App\Exports;

use App\Models\TrProyek;
use App\Models\TrProyekDokumen;
use App\Models\TrProyekTagihan;
use App\Models\TrProyekTagihanRc;
use App\Models\TrProyekTagihanRcDetail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class SarpenAllExport implements WithMultipleSheets
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $year;
    function __construct($year) {
        $this->year = $year;
    }

    public function sheets(): array 
    {  
        $arr_data = array();
        $arr_witel = array(
            "Singaraja",
            "Denpasar",
            "Mataram",
            "Malang",
            "Jember",
            "Kediri",
            "Pasuruan",
            "Sidoarjo",
            "Madiun",
            "Madura",
            "Kupang",
            "Surabaya Utara",
            "Surabaya Selatan"
        );

        rsort($arr_witel);

        $sheets = [];
        $sheets[] = new SarpenWitelExport($this->year);
        for ($i = 0; $i < count($arr_witel); $i++) {
            $sheets[] = new SarpenPerWitelExport($this->year, $arr_witel[$i]);
        }

        return $sheets;

    }
}

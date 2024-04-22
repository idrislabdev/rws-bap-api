<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class SarpenDashboardExport implements WithMultipleSheets
{
    protected $year;
    protected $group;
    protected $status;
    function __construct($year, $group, $status) {
        $this->year = $year;
        $this->group = $group;
        $this->status = $status;
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
            "Surabaya Selatan",
            "Wholesale"
        );

        rsort($arr_witel);

        $sheets = [];
        $sheets[] = new SarpenPerWitelExport($this->year, $this->group, $this->status, $arr_witel);

        return $sheets;

    }
}

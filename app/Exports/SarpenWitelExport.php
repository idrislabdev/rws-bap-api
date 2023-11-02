<?php

namespace App\Exports;

use App\Models\TrProyek;
use App\Models\TrProyekRab;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\TrBaSarpen;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class SarpenWitelExport implements FromView, WithTitle, WithColumnWidths, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use RegistersEventListeners;

    protected $year;
    function __construct($year) {
        $this->year = $year;

    }

  
    public function view(): View
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

        $status = array ('proposed', 'ttd_witel', 'paraf_wholesale', 'ttd_wholesale', 'finished');

        for ($i=0; $i<count($arr_witel); $i++)
        {
            $data = new \stdClass();
            $data->witel = $arr_witel[$i];

            for ($j=0; $j<count($status); $j++)
            {
                $count = TrBaSarpen::where('status', $status[$j])->whereYear('tanggal_buat', $this->year)->where('site_witel', $arr_witel[$i])->count();
                $data->{$status[$j]} = $count;
            }

            array_push($arr_data,  $data);
        }

        return view('reports.sarpen_witel', [
            'data' => $arr_data,
            'year' => $this->year
        ]);  
    }

    public function title(): string
    {
        return 'B.A Sarpen Per Witel';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 25,  
            'C' => 25,
            'D' => 25,
            'E' => 25,      
            'F' => 25,             
        ];
    }

    public static function afterSheet(AfterSheet $event)
    {
        $active_sheet = $event->sheet->getDelegate();
        $active_sheet->getStyle("A1:F1")->applyFromArray(
            [
                'font' => ['name' => 'Verdana', 'size' => '14', 'bold' => true],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'middle'],
            ]
        );
        $active_sheet->getStyle("A3:F3")->applyFromArray(
            [
                'font' => ['name' => 'Verdana', 'size' => '12', 'bold' => true],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'middle'],
                'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
                'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => 'FFE4ECFF']]
            ]
        );
        $active_sheet->getStyle("A4:F16")->applyFromArray(
            [
                'font' => ['name' => 'Verdana', 'size' => '11'],
                'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]
            ]
        );
        $active_sheet->getStyle("B4:F16")->applyFromArray(
            ['alignment' => ['horizontal' => 'center', 'vertical' => 'middle']]
        );

        $active_sheet->getStyle("A17:F17")->applyFromArray(
            [
                'font' => ['name' => 'Verdana', 'size' => '12', 'bold' => true],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'middle'],
                'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => 'FFFDFFE0']],
                'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
            ]
        );
    }
}

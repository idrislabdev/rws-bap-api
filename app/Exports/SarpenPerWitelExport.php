<?php

namespace App\Exports;

use App\Models\TrProyek;
use App\Models\TrProyekRab;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\TrBaSarpen;
use App\Models\TrBaSarpenTower;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class SarpenPerWitelExport implements FromView, WithTitle, WithColumnWidths, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use RegistersEventListeners;

    protected $year;
    protected $arr_witel;

    private static $count_rows = 0;

    function __construct($year, $arr_witel) {
        $this->year = $year;
        $this->arr_witel = $arr_witel;
    }


    public function view(): View
    {
        $data = TrBaSarpen::with(['klienObj', 'neIptvs', 'towers','ruangans','lahans','services','akseses','catuDayaMcbs','catuDayaGensets','racks'])
                          ->where('status', '<>', 'draft')
                          ->whereYear('tanggal_buat', $this->year)
                          ->whereIn('site_witel', $this->arr_witel)
                          ->orderBy('site_witel');


        self::$count_rows = $data->count();

        return view('reports.sarpen_per_witel', [
            'data'      => $data->get(),
            'year'      => $this->year,
        ]);
    }

    public function title(): string
    {
        return 'Detail';
    }

    public function columnWidths(): array
    {
        // $cols = array("A" => 5,"B" => 25,"C"=> 25);
        $cols = array();
        foreach(range('A','Z') as $x) { 
            if ($x != 'A') {
                $cols[$x] = 25;
            } else {
                $cols[$x] = 5;
            }
        } 

        foreach(range('A','M') as $x) { 
            foreach(range('A','Z') as $y) { 
                $cols[$x.$y] = 25;
            } 
        } 

        foreach(range('N','N') as $x) { 
            foreach(range('A','K') as $y) { 
                $cols[$x.$y] = 25;
            } 
        } 


        return $cols;
    }

    public static function afterSheet(AfterSheet $event)
    {
        $rows = self::$count_rows+5;


        $active_sheet = $event->sheet->getDelegate();
        $active_sheet->freezePane('D6');
        $active_sheet->getStyle("A1:J1")->applyFromArray(
            [
                'font' => ['name' => 'Verdana', 'size' => '14', 'bold' => true],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'middle'],
            ]
        );
        $active_sheet->getStyle("A3:NK5")->applyFromArray(
            [
                'font' => ['name' => 'Verdana', 'size' => '12', 'bold' => true],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'middle'],
                'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
            ]
        );
        $active_sheet->getStyle("A3:K{$rows}")->applyFromArray(
            [
                'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => 'FFE4ECFF']]
            ]
        );

        $active_sheet->getStyle("A4:NK{$rows}")->applyFromArray(
            [
                'font' => ['name' => 'Verdana', 'size' => '11'],
                'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]
            ]
        );

        $active_sheet->getStyle("L3:BA{$rows}")->applyFromArray(
            [
                'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => 'FFA0B8FF']]
            ]
        );
        $active_sheet->getStyle("BB3:CC{$rows}")->applyFromArray(
            [
                'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => 'ff91d2ff']]
            ]
        );
        $active_sheet->getStyle("CD3:DZ{$rows}")->applyFromArray(
            [
                'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => 'ffffcc00']]
            ]
        );
        $active_sheet->getStyle("EA3:FB{$rows}")->applyFromArray(
            [
                'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => 'ffa4ffa4']]
            ]
        );
        $active_sheet->getStyle("FC3:GK{$rows}")->applyFromArray(
            [
                'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => 'FFE3FFDD']]
            ]
        );

        $active_sheet->getStyle("GL3:HT{$rows}")->applyFromArray(
            [
                'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => 'FF38FFDD']]
            ]
        );
        $active_sheet->getStyle("HU3:IO{$rows}")->applyFromArray(
            [
                'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => 'FFDBFFE6']]
            ]
        );
        $active_sheet->getStyle("IP3:JJ{$rows}")->applyFromArray(
            [
                'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => 'FFDBFFFF']]
            ]
        );
        $active_sheet->getStyle("JK3:NK{$rows}")->applyFromArray(
            [
                'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => 'fff0facf']]
            ]
        );
    }
}

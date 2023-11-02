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

class SarpenPerWitelExport implements FromView, WithTitle, WithColumnWidths, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use RegistersEventListeners;

    protected $year;
    protected $site_witel;

    private static $count_rows = 0;

    function __construct($year, $site_witel) {
        $this->year = $year;
        $this->site_witel = $site_witel;
    }

  
    public function view(): View
    {
        $data = TrBaSarpen::where('status', '<>', 'draft')
                          ->whereYear('tanggal_buat', $this->year)
                          ->where('site_witel', $this->site_witel);
        
        self::$count_rows = $data->count();; 

        return view('reports.sarpen_per_witel', [
            'data'      => $data->get(),
            'year'      => $this->year,
            'site_witel'=> $this->site_witel
        ]);  
    }

    public function title(): string
    {
        return $this->site_witel;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 25,  
            'C' => 25,
            'D' => 25,
            'E' => 25,      
            'F' => 25,     
            'G' => 25,             
            'H' => 25,             
            'I' => 25,   
            'J' => 25,             
        ];
    }

    public static function afterSheet(AfterSheet $event)
    {
        $rows = self::$count_rows+3;


        $active_sheet = $event->sheet->getDelegate();
        $active_sheet->getStyle("A1:J1")->applyFromArray(
            [
                'font' => ['name' => 'Verdana', 'size' => '14', 'bold' => true],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'middle'],
            ]
        );
        $active_sheet->getStyle("A3:J3")->applyFromArray(
            [
                'font' => ['name' => 'Verdana', 'size' => '12', 'bold' => true],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'middle'],
                'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
                'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => 'FFE4ECFF']]
            ]
        );
        $active_sheet->getStyle("A4:J{$rows}")->applyFromArray(
            [
                'font' => ['name' => 'Verdana', 'size' => '11'],
                'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]
            ]
        );
    }
}

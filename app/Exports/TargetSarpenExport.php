<?php

namespace App\Exports;

use App\Models\TrProyek;
use App\Models\TrProyekRab;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\TrBaSarpen;
use App\Models\TrBaSarpenTargetWitel;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class TargetSarpenExport implements FromView, WithTitle, WithColumnWidths, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use RegistersEventListeners;

    protected $id;
    protected $arr_witel;
    function __construct($id) {
        $this->id = $id;
        $this->arr_witel = [            
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
        ];

    }

  
    public function view(): View
    {
        $arr_data = array();
        $arr_witel = $this->arr_witel;

        rsort($arr_witel);

        $status = array ('proposed', 'ttd_witel', 'paraf_wholesale', 'ttd_wholesale', 'finished');

        for ($i=0; $i<count($arr_witel); $i++)
        {
            $data = new \stdClass();
            $data->witel = $arr_witel[$i];

            $target = TrBaSarpenTargetWitel::with('details')->where('witel', $arr_witel[$i])->where('sarpen_target_id', $this->id)->first();
            $realisasi = TrBaSarpenTargetWitel::with(['details' => function ($q) {
                $q->whereNotNull('no_dokumen');
            }])->where('witel', $arr_witel[$i])->where('sarpen_target_id', $this->id)->first();

            $data = new \stdClass();
            $data->witel = $arr_witel[$i];
            $data->target = count($target->details);
            $data->realisasi = count($realisasi->details);

            array_push($arr_data,  $data);
        }

        return view('reports.target_sarpen_witel', [
            'data' => $arr_data,
        ]);  
    }

    public function title(): string
    {
        return 'Target Sarpen Per Witel';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 25,  
            'C' => 25,
            'D' => 25,        
        ];
    }

    public static function afterSheet(AfterSheet $event)
    {
        $active_sheet = $event->sheet->getDelegate();
        $active_sheet->getStyle("A1:D1")->applyFromArray(
            [
                'font' => ['name' => 'Verdana', 'size' => '14', 'bold' => true],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'middle'],
            ]
        );
        $active_sheet->getStyle("A3:D3")->applyFromArray(
            [
                'font' => ['name' => 'Verdana', 'size' => '12', 'bold' => true],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'middle'],
                'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
                'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => 'FFE4ECFF']]
            ]
        );
        $active_sheet->getStyle("A4:D16")->applyFromArray(
            [
                'font' => ['name' => 'Verdana', 'size' => '11'],
                'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]
            ]
        );
        $active_sheet->getStyle("B4:D16")->applyFromArray(
            ['alignment' => ['horizontal' => 'center', 'vertical' => 'middle']]
        );

        $active_sheet->getStyle("A17:D17")->applyFromArray(
            [
                'font' => ['name' => 'Verdana', 'size' => '12', 'bold' => true],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'middle'],
                'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => 'FFFDFFE0']],
                'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
            ]
        );
    }
}

<?php

namespace App\Exports;

use App\Models\TrWoSite;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;

class FronthaulExport implements FromView, WithTitle, WithColumnWidths, WithEvents
{
    use RegistersEventListeners;

    protected $site_witel;
    protected $status;
    protected $ba;
    protected $tahun_order;
    protected $ba_sirkulir;
    private static $count_rows = 0;

    function __construct($site_witel, $status, $ba, $tahun_order, $ba_sirkulir) {
        $this->site_witel = $site_witel;
        $this->status = $status;
        $this->ba = $ba;
        $this->tahun_order = $tahun_order;
        $this->ba_sirkulir = $ba_sirkulir;
    }

    public function view(): View
    {
        $progress = "";
        $data = DB::table(DB::raw('tr_wo_sites tr')) 
                            ->select(DB::raw("tr.*, 
                                            trw.dasar_order, 
                                            trw.lampiran_url,  
                                            p.id pengguna_id, 
                                            p.nama_lengkap,
                                            b.no_dokumen,
                                        (SELECT count(*) 
                                            FROM 
                                                tr_wo_site_images ti
                                            WHERE 
                                                tr.wo_id = ti.wo_id 
                                            AND 
                                                tr.wo_site_id = ti.wo_site_id
                                            AND
                                                ti.tipe = 'KONFIGURASI') as konfigurasi,
                                        
                                        (SELECT count(*) 
                                            FROM 
                                                tr_wo_site_images ti
                                            WHERE 
                                                tr.wo_id = ti.wo_id 
                                            AND 
                                                tr.wo_site_id = ti.wo_site_id
                                            AND
                                                ti.tipe = 'TOPOLOGI') as topologi,
        
                                        (SELECT count(*) 
                                            FROM 
                                                tr_wo_site_images ti
                                            WHERE 
                                                tr.wo_id = ti.wo_id 
                                            AND 
                                                tr.wo_site_id = ti.wo_site_id
                                            AND
                                                ti.tipe = 'OTDR') as otdr,
                                                
                                        (SELECT count(*) 
                                                FROM 
                                                    tr_wo_site_images ti
                                                WHERE 
                                                    tr.wo_id = ti.wo_id 
                                                AND 
                                                    tr.wo_site_id = ti.wo_site_id
                                                AND
                                                    ti.tipe = 'CAPTURE_TRAFIK') as capture_trafik"))
                            ->leftJoin('tr_wos as trw','tr.wo_id', '=', 'trw.id')
                            ->leftJoin('ma_penggunas as p','tr.dibuat_oleh', '=', 'p.id')
                            ->leftJoin('tr_bas as b','tr.ba_id', '=', 'b.id')
                            ->whereRaw("tr.tipe_ba = 'FRONTHAUL'")
                            ->where('tahun_order', $this->tahun_order);

        if ($this->site_witel != 'ALL') {
            $data = $data->where('tr.site_witel', $this->site_witel);
        }                                   

        if ($this->status != ""){
            $data = $data->where('tr.status', $this->status);

            if ( $this->status == 'OA') {
                if (isset($_GET['progress'])) {
                    if ($_GET['progress'] == 0) {
                        $data = $data->where('progress', true);
                        $progress = "Completed";
                    } else {
                        $data = $data->where('progress', false);
                        $progress = "Not Completed";
                    }
                }
            } 
        }

        if ($this->ba != ""){
            if ($this->ba == 0) {
                $data = $data->where('progress', 1)->whereNull('ba_id');
            } else {
                $data = $data->where('progress', 1)->whereNotNull('ba_id');
            }
        }

        if ($this->ba_sirkulir == 1) {
            $data = $data->whereNotNull('manager_wholesale');
        }
                            
        $data = $data->orderBy('trw.dasar_order')->orderBy('tr.site_id')->get();   
        self::$count_rows = $data->count();
        return view('reports.fronthaul', [
            'data' => $data,
            'tahun_order' => $this->tahun_order, 
            'site_witel' => $this->site_witel,
            'status' => $this->status,
            'progress' => $progress,
            'sirkulir' => $this->ba_sirkulir,
            'ba' => $this->ba
        ]);  
    }

    public function columnWidths(): array
    {
        return [
            'A' => 25,
            'B' => 10,  
            'C' => 25,
            'D' => 15,
            'E' => 20,      
            'F' => 10,             
            'G' => 10,             
            'H' => 10,             
            'I' => 15,             
            'J' => 10,             
            'K' => 10,             
            'L' => 10,             
            'M' => 35,             
        ];
    }

    public function title(): string
    {
        return 'Report Fronthaul';
    }


    public static function afterSheet(AfterSheet $event)
    {
        $row_length = self::$count_rows+2;
        $active_sheet = $event->sheet->getDelegate();
        $active_sheet->getStyle("A2:M2")->applyFromArray(
            [
                'font' => ['name' => 'Verdana', 'size' => '13', 'bold' => true],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'middle'],
                'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
            ]
        );
        $active_sheet->getStyle("A2:M{$row_length}")->applyFromArray(
            [
                // 'font' => ['name' => 'Calibri', 'size' => '11', 'bold' => true],
                'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
            ]
        );
       
    }
}

<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\TrBaSarpen;
use App\Models\TrBaSarpenTower;
use App\Models\TrPengajuanAplikasi;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class DashboardPengajuanAplikasiExport implements FromView, WithTitle, WithColumnWidths, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use RegistersEventListeners;

    protected $aplikasi;
    protected $tanggal_awal;
    protected $tanggal_akhir;
    protected $witel;
    protected $status;
    protected $arr_witel;

    private static $count_rows = 0;
    private static $aplikasi_type = 0;

    function __construct($aplikasi, $witel, $tanggal_awal, $tanggal_akhir) {
        $this->aplikasi = $aplikasi;
        $this->witel = $witel;
        $this->tanggal_awal = $tanggal_awal;
        $this->tanggal_akhir = $tanggal_akhir;
    }

    public function view(): View
    {
        $witel = $this->witel;
        $aplikasi = $this->aplikasi;
        $data = TrPengajuanAplikasi::with(['proposedBy', 'rejectedBy', 'approvedBy', 'processBy', 'accountProfile', 'userAccount'])
        ->whereHas('userAccount', function ($q) use ($witel){
            if ($witel != 'ALL')
                $q->where('site_witel', $witel);
        })
        ->where('aplikasi', $aplikasi)
        ->where('jenis_pengajuan', 'baru');

        if ($this->tanggal_awal != null && $this->tanggal_akhir != null) {
            $tanggal_awal = $this->tanggal_awal;
            $tanggal_akhir = $this->tanggal_akhir;
            $data = $data->whereRaw("(created_at >= '$tanggal_awal' and  created_at <= '$tanggal_akhir')");
        }

        self::$count_rows = $data->count();
        self::$aplikasi_type = $this->aplikasi;

        $view = '';
        $title = '';
        if ($this->aplikasi == 'starclick_ncx') {
            $view = 'exports.dashboard_pengajuan_aplikasi_starclick';
            if ($this->witel !== 'ALL') {
                $title = 'Daftar Pengajuan User NCX Starclick Witel '. $this->witel;
            } else {
                $title = 'Daftar Pengajuan User NCX Starclick TREG 5';
            }
        } else if ($this->aplikasi == 'ncx_cons') {
            $view = 'exports.dashboard_pengajuan_aplikasi_ncx';
            if ($this->witel !== 'ALL') {
                $title = 'Daftar Pengajuan User NCX CONS Witel '. $this->witel;
            } else {
                $title = 'Daftar Pengajuan User NCX CONS TREG 5';
            }
        }

        self::$count_rows = $data->count();

        return view($view, [
            'title' => $title,
            'data'  => $data->get(),
        ]);
    }

    public function title(): string
    {
        $title = '';
        if ($this->aplikasi == 'starclick_ncx') {
            $title = 'Daftar Pengajuan User NCX Starclick';
        } else if ($this->aplikasi == 'ncx_cons') {
            $title = 'Daftar Pengajuan User NCX CONS';
        }
        return $title;
    }

    public function columnWidths(): array
    {
        // $cols = array("A" => 5,"B" => 25,"C"=> 25);
        $cols = array();
        if ($this->aplikasi === 'starclick_ncx') {
            foreach(range('A','R') as $x) { 
                if ($x == 'C' || $x == 'D' || $x == 'F') {
                    $cols[$x] = 40;                    
                } else if ($x == 'J') {
                    $cols[$x] = 100;                    
                } else {
                    $cols[$x] = 25;
                }
            } 
        } else {
            foreach(range('A','M') as $x) { 
                if ($x == 'A') {
                    $cols[$x] = 5;                    
                } else {
                    $cols[$x] = 25;
                }
            } 
        }
        

        return $cols;
    }

    public static function starclickStyle(AfterSheet $event)
    {
        $rows = self::$count_rows+4;
        $row_plus = $rows+1;
        $start_note = $row_plus;
        $start_column_note = $start_note+1;
        $end_column_note = $start_column_note+5;


        $active_sheet = $event->sheet->getDelegate();
        $active_sheet->getStyle("A1")->applyFromArray(
            [
                'font' => ['name' => 'Calibri', 'size' => '14', 'bold' => true],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'middle'],
            ]
        );
        $active_sheet->getStyle("A3:R3")->applyFromArray(
            [
                'font' => ['name' => 'Calibri', 'size' => '11', 'bold' => true],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'middle'],
                'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
                'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => 'ffffff00']]
            ]
        );
        $active_sheet->getStyle("A4:R4")->applyFromArray(
            [
                'font' => ['name' => 'Calibri', 'size' => '11', 'bold' => true],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'middle'],
                'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
                'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => 'ffd9d9d9']]
            ]
        );

        $active_sheet->getStyle("A5:R{$row_plus}")->applyFromArray(
            [
                'font' => ['name' => 'Calibri', 'size' => '11'],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'middle'],
                'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
            ]
        );
    }

    public static function ncxStyle(AfterSheet $event)
    {
        $rows = self::$count_rows+4;
        $row_plus = $rows;
        $start_note = $row_plus+2;
        $start_column_note = $start_note+1;


        $active_sheet = $event->sheet->getDelegate();
        $active_sheet->getStyle("A1")->applyFromArray(
            [
                'font' => ['name' => 'Calibri', 'size' => '14', 'bold' => true],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'middle'],
                'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => 'ffffff00']]

            ]
        );
        $active_sheet->getStyle("A3:M3")->applyFromArray(
            [
                'font' => ['name' => 'Calibri', 'size' => '11', 'bold' => true],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'middle'],
                'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
                'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => 'fff2f2f2']]
            ]
        );

        $active_sheet->getStyle("A4:M{$row_plus}")->applyFromArray(
            [
                'font' => ['name' => 'Calibri', 'size' => '11'],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'middle', 'wrapText' => true],
                'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
            ]
        );
    }

    public static function afterSheet(AfterSheet $event)
    {
       if (self::$aplikasi_type == 'ncx_cons') {
            self::ncxStyle($event);
       } else if (self::$aplikasi_type == 'starclick_ncx') {
            self::starclickStyle($event);
       }
    }

}

<?php

namespace App\Exports;

use App\Models\MaUserAccount;
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

class DashboardUserAplikasiExport implements FromView, WithTitle, WithColumnWidths, WithEvents
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

        $data = MaUserAccount::with(['profiles' => function ($q) use ($aplikasi) {
            $q->where('aplikasi', $aplikasi)->where('status', 'AKTIF');
        }])
        ->whereHas('profiles', function ($q) use ($aplikasi) {
            $q->where('aplikasi', $aplikasi)->where('status', 'AKTIF');
        });

        if ($witel != 'ALL') {
            $data = $data->where('site_witel', $witel);
        }

        if ($this->tanggal_awal != null && $this->tanggal_akhir != null) {
            $tanggal_awal = $_GET['tanggal_awal'];
            $tanggal_akhir = $_GET['tanggal_akhir'];
            $data = $data->whereRaw("(created_at >= '$tanggal_awal' and  created_at <= '$tanggal_akhir')");
        }

        self::$count_rows = $data->count();
        self::$aplikasi_type = $this->aplikasi;

        $view = '';
        $title = '';
        if ($this->aplikasi == 'starclick_ncx') {
            if ($this->witel !== 'ALL') {
                $title = 'Daftar User Aktif NCX Starclick Witel '. $this->witel;
            } else {
                $title = 'Daftar User Aktif NCX Starclick TREG 5';
            }
        } else if ($this->aplikasi == 'ncx_cons') {
            if ($this->witel !== 'ALL') {
                $title = 'Daftar User Aktif NCX CONS Witel '. $this->witel;
            } else {
                $title = 'Daftar User Aktif NCX CONS TREG 5';
            }
        }
        $view = 'exports.dashboard_user_aktif';


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
            $title = 'Daftar User NCX Starclick';
        } else if ($this->aplikasi == 'ncx_cons') {
            $title = 'Daftar User NCX CONS';
        }
        return $title;
    }

    public function columnWidths(): array
    {
        // $cols = array("A" => 5,"B" => 25,"C"=> 25);
        $cols = array();
        foreach(range('A','N') as $x) { 
            if ($x == 'A') {
                $cols[$x] = 5;                    
            } else {
                $cols[$x] = 25;
            }
        } 
        

        return $cols;
    }

    public static function style(AfterSheet $event)
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
        $active_sheet->getStyle("A3:N3")->applyFromArray(
            [
                'font' => ['name' => 'Calibri', 'size' => '11', 'bold' => true],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'middle'],
                'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
                'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => 'fff2f2f2']]
            ]
        );

        $active_sheet->getStyle("A4:N{$row_plus}")->applyFromArray(
            [
                'font' => ['name' => 'Calibri', 'size' => '11'],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'middle', 'wrapText' => true],
                'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
            ]
        );
    }

    public static function afterSheet(AfterSheet $event)
    {
        self::style($event);
    }

}

<?php

namespace App\Exports;

use App\Models\TrWoSite;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UpgradeExport implements FromView
{
    protected $site_witel;
    protected $status;
    protected $ba;
    protected $tahun_order;

    function __construct($site_witel, $status, $ba, $tahun_order) {
        $this->site_witel = $site_witel;
        $this->status = $status;
        $this->ba = $ba;
        $this->tahun_order = $tahun_order;
    }

    public function view(): View
    {

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
                                                        ti.tipe = 'CAPTURE_TRAFIK') as capture_trafik"),
                                                )
                            ->leftJoin('tr_wos as trw','tr.wo_id', '=', 'trw.id')
                            ->leftJoin('ma_penggunas as p','tr.dibuat_oleh', '=', 'p.id')
                            ->leftJoin('tr_bas as b','tr.ba_id', '=', 'b.id')
                            ->whereRaw("tr.tipe_ba = 'UPGRADE'")
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
                    } else {
                        $data = $data->where('progress', false);
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
                            
        $data = $data->orderBy('trw.dasar_order')->orderBy('tr.site_id')->get();   
        return view('reports.upgrade', [
            'data' => $data
        ]);  
    }
}

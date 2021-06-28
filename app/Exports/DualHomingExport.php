<?php

namespace App\Exports;

use App\Models\TrWoSite;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DualHomingExport implements FromView
{
    protected $site_witel;
    protected $status;
    protected $ba;

    function __construct($site_witel, $status, $ba) {
        $this->site_witel = $site_witel;
        $this->status = $status;
        $this->ba = $ba;
    }

    public function view(): View
    {

        $data = DB::table(DB::raw('tr_wo_sites tr')) 
        ->select(DB::raw("tr.*, 
                          p.id pengguna_id, 
                          p.nama_lengkap,
                          b.no_dokumen,
                          dh.node_1, dh.node_2, dh.sto_a, dh.sto_b,
                            (SELECT count(*) 
                                FROM 
                                    tr_wo_site_images ti
                                WHERE 
                                    tr.wo_id = ti.wo_id 
                                AND 
                                    tr.wo_site_id = ti.wo_site_id
                                AND
                                    ti.tipe = 'NODE_1') as konfigurasi_node_1,
                            
                            (SELECT count(*) 
                                    FROM 
                                        tr_wo_site_images ti
                                    WHERE 
                                        tr.wo_id = ti.wo_id 
                                    AND 
                                        tr.wo_site_id = ti.wo_site_id
                                    AND
                                        ti.tipe = 'NODE_2') as konfigurasi_node_2,
                                    
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
                                            tr_wo_site_dual_homings dh
                                        WHERE 
                                            tr.wo_id = dh.wo_id 
                                        AND 
                                            tr.wo_site_id = dh.wo_site_id) as pr_dual_homing"),
                            )
                    ->leftJoin('tr_wo_site_dual_homings as dh', function ($join) {
                        $join->on('dh.wo_id', '=', 'tr.wo_id')
                            ->on('dh.wo_site_id', '=', 'tr.wo_site_id');
                    })                                                     
                    ->leftJoin('ma_penggunas as p','tr.dibuat_oleh', '=', 'p.id')
                    ->leftJoin('tr_bas as b','tr.ba_id', '=', 'b.id')
                    ->whereRaw("tr.tipe_ba = 'DUAL_HOMING'");

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
                            
        $data = $data->orderBy('tr.created_at')->orderBy('tr.site_id')->get();   
        return view('reports.dualhoming', [
            'data' => $data
        ]);  
    }
}

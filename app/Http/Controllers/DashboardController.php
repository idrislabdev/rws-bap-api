<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\NewlinkResource;
use App\Http\Resources\TrWoSiteResource;
use App\Http\Resources\UpgradeResource;
use App\Models\MaPengguna;
use App\Models\TrWoSite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class DashboardController extends Controller
{
    public function donut()
    {
        $site_witel = "";
        $tipe_ba = ""; 

        if (isset($_GET['site_witel'])){
            $site_witel = $_GET['site_witel'];
        }

        if (isset($_GET['tipe_ba'])){
            $tipe_ba = $_GET['tipe_ba'];
        }

        $all = TrWoSite::where('status', 'OA')
                        ->where('tipe_ba', $tipe_ba);

        if ($site_witel != 'ALL') {
            $all = $all->where('site_witel', $site_witel);
        }
        $all = $all->count();

        $finish = TrWoSite::where('progress', true)
                            ->where('status', 'OA')
                            ->where('tipe_ba', $tipe_ba);

        if ($site_witel != 'ALL') {
            $finish = $finish->where('site_witel', $site_witel);
        }
        $finish = $finish->count();

        $not_yet = TrWoSite::where('progress', false)
                            ->where('status', 'OA')
                            ->where('tipe_ba', $tipe_ba);

        if ($site_witel != 'ALL') {
            $not_yet = $not_yet->where('site_witel', $site_witel);
        }

        $not_yet = $not_yet->count();

        $data = new \stdClass();
        $series = array($finish, $not_yet);
        $labels = array ('FINISH', 'NOT YET');

        $data->series = $series;
        $data->labels = $labels;
             
        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => $data
        ], 200);
    }

    public function newlink()
    {
        $site_witel = "";
        $progress = null;

        if (isset($_GET['site_witel'])){
            $site_witel = $_GET['site_witel'];
        }

        if (isset($_GET['progress'])){
            $progress = $_GET['progress'];
        }

        $data = DB::table(DB::raw('tr_wo_sites tr')) 
                ->select(DB::raw("tr.*, 
                                    trw.dasar_order, 
                                    trw.lampiran_url,  
                                    p.id pengguna_id, 
                                    p.nama_lengkap,
                                    b.no_dokumen,
                                    (SELECT count(*) 
                                        FROM 
                                            tr_wo_site_lvs t
                                        WHERE 
                                            tr.wo_id = t.wo_id 
                                        AND 
                                            tr.wo_site_id = t.wo_site_id) as lv,

                                    (SELECT count(*) 
                                            FROM 
                                                tr_wo_site_qcs t
                                            WHERE 
                                                tr.wo_id = t.wo_id 
                                            AND 
                                                tr.wo_site_id = t.wo_site_id) as qc,

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
                                                ti.tipe = 'LV') as lv_image,

                                    (SELECT count(*) 
                                                FROM 
                                                    tr_wo_site_images ti
                                                WHERE 
                                                    tr.wo_id = ti.wo_id 
                                                AND 
                                                    tr.wo_site_id = ti.wo_site_id
                                                AND
                                                    ti.tipe = 'QC') as qc_image,
                                    
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
                ->whereRaw("tr.tipe_ba = 'NEW_LINK'")
                ->whereRaw("tr.status = 'OA'");
        
        if ($progress != null) {
            $data = $data->where('tr.progress', $progress);
        }                

        if ($site_witel != 'ALL') {
            $data = $data->where('tr.site_witel', $site_witel);
        }
             
        $data = $data->orderBy('trw.dasar_order')->orderBy('tr.site_id')->paginate(5)->onEachSide(5);       

        return NewlinkResource::collection(($data))->additional([
            'success' => true,
            'message' => null,
        ]);
    }

    public function upgrade()
    {
        $site_witel = "";
        $progress = null;

        if (isset($_GET['site_witel'])){
            $site_witel = $_GET['site_witel'];
        }

        if (isset($_GET['progress'])){
            $progress = $_GET['progress'];
        }

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
                ->whereRaw("tr.status = 'OA'");
        
        if ($progress != null) {
            $data = $data->where('tr.progress', $progress);
        }                

        if ($site_witel != 'ALL') {
            $data = $data->where('tr.site_witel', $site_witel);
        }
             
        $data = $data->orderBy('trw.dasar_order')->orderBy('tr.site_id')->paginate(5)->onEachSide(5);       

        return UpgradeResource::collection(($data))->additional([
            'success' => true,
            'message' => null,
        ]);
    }
}

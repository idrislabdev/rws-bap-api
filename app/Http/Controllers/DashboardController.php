<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\DualHomingResource;
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


        $ba = TrWoSite::whereNotNull('ba_id')
                            ->where('tipe_ba', $tipe_ba);

        if ($site_witel != 'ALL') {
            $ba = $ba->where('site_witel', $site_witel);
        }
        $ba = $ba->count();

        $not_ba = TrWoSite::whereNull('ba_id')
                            ->where('tipe_ba', $tipe_ba);

        if ($site_witel != 'ALL') {
            $not_ba = $not_ba->where('site_witel', $site_witel);
        }

        $not_ba = $not_ba->count();

        $data = new \stdClass();
        $series = array($ba, $not_ba);
        $labels = array ('B.A COMPLETE', 'B.A NOT COMPLETE');

        $data->series = $series;
        $data->labels = $labels;
             
        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => $data
        ], 200);
    }

    public function list()
    {
        if (isset($_GET['tipe_ba'])){
            $tipe_ba = $_GET['tipe_ba'];
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Pilih Tipe BA Terlebih Dahulu',
                'data' => null
            ], 422);
        }
        
        $witel = ['Singaraja','Denpasar','Mataram','Malang','Jember','Kediri','Pasuruan','Sidoarjo','Madiun','Madura','Kupang','Surabaya Utara','Surabaya Selatan'];

        $obj = new \stdClass();
        $data = array();
        if (isset($_GET['site_witel'])){
            $obj = new \stdClass(); 
            $obj->witel = $_GET['site_witel'];
            $obj->total_order =TrWoSite::where('tipe_ba', $tipe_ba)->where('site_witel', $_GET['site_witel'])->count();
            $obj->total_ogp = TrWoSite::where('tipe_ba', $tipe_ba)->where('site_witel', $_GET['site_witel'])->where('status', 'OGP')->count();
            $obj->total_oa = TrWoSite::where('tipe_ba', $tipe_ba)->where('site_witel', $_GET['site_witel'])->where('status', 'OA')->count();
            $obj->total_oa_complete = TrWoSite::where('tipe_ba', $tipe_ba)->where('site_witel', $_GET['site_witel'])->where('status', 'OA')->where('progress', 1)->count();
            $obj->total_oa_not_yet = TrWoSite::where('tipe_ba', $tipe_ba)->where('site_witel', $_GET['site_witel'])->where('status', 'OA')->where('progress', 0)->count();

            array_push($data, $obj);
        } else {
            for ($i=0; $i<count($witel); $i++) {
                $obj = new \stdClass(); 
                $obj->witel = $witel[$i];
                $obj->total_order =TrWoSite::where('tipe_ba', $tipe_ba)->where('site_witel', $witel[$i])->count();
                $obj->total_ogp = TrWoSite::where('tipe_ba', $tipe_ba)->where('site_witel', $witel[$i])->where('status', 'OGP')->count();
                $obj->total_oa = TrWoSite::where('tipe_ba', $tipe_ba)->where('site_witel', $witel[$i])->where('status', 'OA')->count();
                $obj->total_oa_complete = TrWoSite::where('tipe_ba', $tipe_ba)->where('site_witel', $witel[$i])->where('status', 'OA')->where('progress', 1)->count();
                $obj->total_oa_not_yet = TrWoSite::where('tipe_ba', $tipe_ba)->where('site_witel', $witel[$i])->where('status', 'OA')->where('progress', 0)->count();
    
                array_push($data, $obj);
            }
        }
        

        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => $data
        ], 200);

    }

    public function newlink()
    {
        if (isset($_GET['site_witel'])){
            $site_witel = $_GET['site_witel'];
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Pilih Witel Terlebih Dahulu',
                'data' => null
            ], 422);
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
                                    ->where('tr.site_witel', $site_witel);

                
        if (isset($_GET['status'])){
            $data = $data->where('tr.status', $_GET['status']);

            if ( $_GET['status'] == 'OA' &&  $_GET['progress'] == 1) {
                $data = $data->where('progress', true);
            } else  if ( $_GET['status'] == 'OA' &&  $_GET['progress'] == 0) {
                $data = $data->where('progress', false);
            }
        }
                                    
        $data = $data->orderBy('trw.dasar_order')->orderBy('tr.site_id')->paginate(10)->onEachSide(5);       

        return NewlinkResource::collection(($data))->additional([
            'success' => true,
            'message' => null,
        ]);
    }

    public function upgrade()
    {
        if (isset($_GET['site_witel'])){
            $site_witel = $_GET['site_witel'];
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Pilih Witel Terlebih Dahulu',
                'data' => null
            ], 422);
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
                                    ->where('tr.site_witel', $site_witel);

                
        if (isset($_GET['status'])){
            $data = $data->where('tr.status', $_GET['status']);

            if ( $_GET['status'] == 'OA' &&  $_GET['progress'] == 1) {
                $data = $data->where('progress', true);
            } else  if ( $_GET['status'] == 'OA' &&  $_GET['progress'] == 0) {
                $data = $data->where('progress', false);
            }
        }
                                    
        $data = $data->orderBy('trw.dasar_order')->orderBy('tr.site_id')->paginate(10)->onEachSide(5);       

        return UpgradeResource::collection(($data))->additional([
            'success' => true,
            'message' => null,
        ]);
    }

    public function dualHoming()
    {
        if (isset($_GET['site_witel'])){
            $site_witel = $_GET['site_witel'];
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Pilih Witel Terlebih Dahulu',
                'data' => null
            ], 422);
        }

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
                                    ->whereRaw("tr.tipe_ba = 'DUAL_HOMING'")
                                    ->where('tr.site_witel', $site_witel);

                
        if (isset($_GET['status'])){
            $data = $data->where('tr.status', $_GET['status']);

            if ( $_GET['status'] == 'OA' &&  $_GET['progress'] == 1) {
                $data = $data->where('progress', true);
            } else  if ( $_GET['status'] == 'OA' &&  $_GET['progress'] == 0) {
                $data = $data->where('progress', false);
            }
        }
                                    
        $data = $data->orderBy('tr.created_at')->orderBy('tr.site_id')->paginate(25)->onEachSide(5);       

        return DualHomingResource::collection(($data))->additional([
            'success' => true,
            'message' => null,
        ]);
    }

}

<?php

namespace App\Http\Controllers\CNOP\Transaksi;

use App\Helper\UtilityHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\DUAL_HOMINGResource;
use App\Http\Resources\DualHomingResource;
use App\Models\MaPengaturan;
use App\Models\MaPengguna;
use App\Models\TrBa;
use App\Models\TrWo;
use App\Models\TrWoSite;
use App\Models\TrWoSiteDualHomings;
use App\Models\TrWoSiteImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

use PDF;

class DualHomingController extends Controller
{
    private $_hari = ['SENIN', 'SELASA', 'RABU', 'KAMIS', 'JUMAT', 'SABTU','MINGGU'];
    private $_month = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

    public function index()
    {
        $pengguna = MaPengguna::find(Auth::user()->id);

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

        if ($pengguna->site_witel) {
            $data = $data->whereRaw("tr.site_witel = '$pengguna->site_witel'");
        }                            
        
        $q = $_GET['q'];
        
        if ($q) {
            $data = $data->whereRaw("(site_name like '%$q%' or 
                                    tr.site_witel like '%$q%' or 
                                    tr.tsel_reg like '%$q%' or 
                                    site_id like '%$q%')");
        }
                                    
        $data = $data->orderBy('tr.created_at')->orderBy('tr.site_id')->paginate(25)->onEachSide(5);       

        return DualHomingResource::collection(($data))->additional([
            'success' => true,
            'message' => null,
        ]);
    }

    public function store(Request $request)
    {
        $v = Validator::make($request->all(), [
            'site_id'       => 'required',
            'site_name'     => 'required',
            'site_witel'    => 'required',
            'tsel_reg'      => 'required',
            'keterangan'    => 'required',
            'bandwidth'     => 'required'
        ]);

        if ($v->fails())
        {
            return response()->json([
                'status' => false,
                'message' => 'error',
                'data' => $v->errors()
            ], 422);
        }   
        
        DB::beginTransaction();
        try {
            $wo_id =  Uuid::uuid4()->toString();

            $wo_id =  Uuid::uuid4()->toString();
            $wo = new TrWo();
            $wo->id = $wo_id;
            $wo->dasar_order = $wo_id;
            $wo->tipe_ba = 'DUAL_HOMING';
            $wo->save();

            $counter =  1;
            $check_site = TrWoSite::where('site_id', $request->site_id)->where('tipe_ba', 'DUAL_HOMING')->first();
            if (!$check_site) {
                $wo_site = new TrWoSite();
                $wo_site->wo_id = $wo_id;
                $wo_site->wo_site_id = $counter;
                $wo_site->site_id = $request->site_id;
                $wo_site->site_name = $request->site_name;
                $wo_site->site_witel = strtoupper($request->site_witel);
                $wo_site->tsel_reg = strtoupper($request->tsel_reg);
                $wo_site->tgl_on_air = date('Y-m-d');
                $wo_site->data_2g = 0; 
                $wo_site->data_3g = 0; 
                $wo_site->data_4g = 0; 
                $wo_site->jumlah = $request->bandwidth;
                $wo_site->dibuat_oleh = Auth::user()->id;
                $wo_site->status = 'OGP';
                $wo_site->progress = false;
                $wo_site->tipe_ba = 'DUAL_HOMING';
                $wo_site->keterangan = $request->keterangan;
                $wo_site->save();
                
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'error',
                    'data' => 'Maaf, Site ID Sudah Pernah Diinputkan Sebelumnya'
                ], 422);
            }
        

            DB::commit();
    
            return response()->json([
                'status' => true,
                'message' => 'success',
                'data' => null
            ], 200);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'data' => $e->getMessage(),
                'success' => true,
                'message' => 'error',
            ], 400);
        }
    }

    public function show($wo_id, $wo_site_id)
    {
        try {
            $data = DB::table(DB::raw('tr_wo_sites tr, tr_wos trw, ma_penggunas p')) 
                                    ->select(DB::raw("tr.*, trw.dasar_order, trw.lampiran_url,  p.id pengguna_id, p.nama_lengkap"))
                                    ->whereRaw("p.id = tr.dibuat_oleh")
                                    ->whereRaw("tr.wo_id = trw.id")
                                    ->whereRaw("tr.tipe_ba = 'DUAL_HOMING'")
                                    ->where('tr.wo_id', $wo_id)
                                    ->where('tr.wo_site_id', $wo_site_id)
                                    ->first();

            // return (new TrWoSiteResource($data))->additional([
            //     'success' => true,
            //     'message' => 'suksess'
            // ]);

            return response()->json([
                'data' => $data,
                'success' => true,
                'message' => null,
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
               'data' => null,
                'success' => false,
                'message' => 'Data Tidak Ditemukan',
            ], 404);
        }
    }

    public function update(Request $request, $wo_id, $wo_site_id)
    {
        $v = Validator::make($request->all(), [
            'site_id' => 'required',
            'site_name' => 'required',
            'site_witel' => 'required',
            'tsel_reg' => 'required',
            'bandwidth'     => 'required'
        ]);

        if($v->fails())
        {
            return response()->json([
                'data' => null,
                'succes' => false,
                'message' => $v->errors()
            ], 422);
        }

        $data = TrWoSite::where('wo_id', $wo_id)->where('wo_site_id', $wo_site_id)->first();

        if($data == null)
        {
            return response()->json([
                'data' => null,
                'succes' => false,
                'message' => 'Data Tidak Ditemukan'
            ], 422);
        }


        try {

            $update = TrWoSite::where('wo_id', $wo_id)->where('wo_site_id', $wo_site_id)
                            ->update(array(
                                'site_id' => $request->site_id,
                                'site_name' => $request->site_name,
                                'tsel_reg' => $request->tsel_reg,
                                'site_witel' => $request->site_witel,
                                'jumlah' => $request->bandwidth,
                                'keterangan' => $request->keterangan,
                            ));

            return response()->json([
                'data' => $data,
                'success' => true,
                'message' => null,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'data' => $e->getMessage(),
                'success' => true,
                'message' => $e,
            ], 400);
        }
    }

    public function storeParameter(Request $request, $wo_id, $wo_site_id)
    {
        $v = Validator::make($request->all(), [
            'tipe_topologi' => 'required',
            'sto_a' => 'required',
            'sto_b' => 'required',
            'metro_1' => 'required',
            'metro_2' => 'required',
            'node_1' => 'required',
            'node_2' => 'required',
            'port_otb_1' => 'required',
            'port_otb_2' => 'required',
            'odc_odp_1' => 'required',
            'odc_odp_2' => 'required',
            'tipe_modem' => 'required',
        ]);

        if($v->fails())
        {
            return response()->json([
                'data' => null,
                'succes' => false,
                'message' => $v->errors()
            ], 422);
        }

        $data = TrWoSite::where('wo_id', $wo_id)->where('wo_site_id', $wo_site_id)->first();

        if($data == null)
        {
            return response()->json([
                'data' => null,
                'succes' => false,
                'message' => 'Data Tidak Ditemukan'
            ], 422);
        }

        DB::beginTransaction();
        try {

            $dual_homing = new TrWoSiteDualHomings();
            $dual_homing->wo_id = $wo_id;
            $dual_homing->wo_site_id = $wo_site_id;
            $dual_homing->tipe_topologi =  $request->tipe_topologi;
            $dual_homing->jenis_node = $request->jenis_node;
            $dual_homing->sto_a =  $request->sto_a;
            $dual_homing->sto_b =  $request->sto_b; 
            $dual_homing->metro_1 =  $request->metro_1; 
            $dual_homing->metro_2 =  $request->metro_2; 
            $dual_homing->node_1 =  $request->node_1;
            $dual_homing->node_2 =  $request->node_2;
            $dual_homing->port_otb_1 =  $request->port_otb_1;
            $dual_homing->port_otb_2 =  $request->port_otb_2;
            $dual_homing->odc_odp_1 =  $request->odc_odp_1;
            $dual_homing->odc_odp_2 =  $request->odc_odp_2;
            $dual_homing->tipe_modem =  $request->tipe_modem;
            $dual_homing->tipe_service =  $request->tipe_service;

            $dual_homing->save();

            $check_evident = UtilityHelper::checkEvident($wo_id, $wo_site_id);

            if ($check_evident->topologi == 1 
            && $check_evident->node_1 == 1 
            && $check_evident->node_2 == 1 
            && $check_evident->pr_dual_homing == 1) 
            {
                TrWoSite::where('wo_id', $wo_id)->where('wo_site_id', $wo_site_id)
                        ->update(array(
                            'progress' => true,
                        ));

            }

            DB::commit();

            return response()->json([
                'data' => $data,
                'success' => true,
                'message' => null,
            ], 200);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'data' => $e->getMessage(),
                'success' => true,
                'message' => $e,
            ], 400);
        }
    }

    public function updateParameter(Request $request, $wo_id, $wo_site_id)
    {
        $data = TrWoSiteDualHomings::where('wo_id', $wo_id)->where('wo_site_id', $wo_site_id)->first();

        if($data == null)
        {
            return response()->json([
                'data' => null,
                'succes' => false,
                'message' => 'Data Tidak Ditemukan'
            ], 422);
        }

        DB::beginTransaction();
        try {

            $update = TrWoSiteDualHomings::where('wo_id', $wo_id)->where('wo_site_id', $wo_site_id)
                            ->update(array(
                                'tipe_topologi' => ($request->tipe_topologi) ? $request->tipe_topologi : null,
                                'jenis_node' => ($request->jenis_node) ? $request->jenis_node : null,
                                'sto_a' => ($request->sto_a) ? $request->sto_a : null,
                                'sto_b' => ($request->sto_b) ? $request->sto_b : null,
                                'metro_1' => ($request->metro_1) ? $request->metro_1 : null,
                                'metro_2' => ($request->metro_2) ? $request->metro_2 : null,
                                'node_1' => ($request->node_1) ? $request->node_1 : null,
                                'node_2' => ($request->node_2) ? $request->node_2 : null,
                                'port_otb_1' => ($request->port_otb_1) ? $request->port_otb_1 : null,
                                'port_otb_2' => ($request->port_otb_2) ? $request->port_otb_2 : null,
                                'odc_odp_1' => ($request->odc_odp_1) ? $request->odc_odp_1 : null,
                                'odc_odp_2' => ($request->odc_odp_2) ? $request->odc_odp_2 : null,
                                'tipe_modem' => ($request->tipe_modem) ? $request->tipe_modem : null,
                                'tipe_service' => ($request->tipe_service) ? $request->tipe_service : null,
                            ));

            $check_evident = UtilityHelper::checkEvident($wo_id, $wo_site_id);

            if ($check_evident->topologi == 1 
            && $check_evident->node_1 == 1 
            && $check_evident->node_2 == 1 
            && $check_evident->pr_dual_homing == 1) 
            {
                TrWoSite::where('wo_id', $wo_id)->where('wo_site_id', $wo_site_id)
                        ->update(array(
                            'progress' => true,
                        ));

            }

            DB::commit();


            return response()->json([
                'data' => $data,
                'success' => true,
                'message' => null,
            ], 200);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'data' => $e->getMessage(),
                'success' => true,
                'message' => $e,
            ], 400);
        }
    }

    public function showParameter($wo_id, $wo_site_id)
    {
        try {
            $data = TrWoSiteDualHomings::where('wo_id', $wo_id)->where('wo_site_id', $wo_site_id)->first();

            if($data == null)
            {
                return response()->json([
                    'data' => null,
                    'succes' => false,
                    'message' => 'Data Tidak Ditemukan'
                ], 422);
            }

            return response()->json([
                'data' => $data,
                'success' => true,
                'message' => null,
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
               'data' => null,
                'success' => false,
                'message' => 'Data Tidak Ditemukan',
            ], 404);
        }
    }

    
    public function updateOA(Request $request, $wo_id, $wo_site_id)
    {
        $data = TrWoSite::where('wo_id', $wo_id)->where('wo_site_id', $wo_site_id)->where('status', 'OGP')->first();

        if($data == null)
        {
            return response()->json([
                'data' => null,
                'succes' => false,
                'message' => 'Data Tidak Ditemukan'
            ], 422);
        }


        try {

            $update = TrWoSite::where('wo_id', $wo_id)->where('wo_site_id', $wo_site_id)
                            ->update(array(
                                'status' => 'OA',
                                'tgl_on_air' => $request->tgl_on_air
                            ));

            return response()->json([
                'data' => $data,
                'success' => true,
                'message' => null,
            ], 200);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'data' => $e->getMessage(),
                'success' => true,
                'message' => $e,
            ], 400);
        }
    }

    public function backOGP($wo_id, $wo_site_id)
    {
        $data = TrWoSite::where('wo_id', $wo_id)->where('wo_site_id', $wo_site_id)->where('status', 'OA')->first();

        if($data == null)
        {
            return response()->json([
                'data' => null,
                'succes' => false,
                'message' => 'Data Tidak Ditemukan'
            ], 422);
        }


        try {

            $update = TrWoSite::where('wo_id', $wo_id)->where('wo_site_id', $wo_site_id)
                            ->update(array(
                                'status' => 'OGP',
                            ));

            return response()->json([
                'data' => $data,
                'success' => true,
                'message' => null,
            ], 200);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'data' => $e->getMessage(),
                'success' => true,
                'message' => $e,
            ], 400);
        }
    }

    public function checkSiteBA(Request $request)
    {
        $v = Validator::make($request->all(), [
            'tsel_reg' => 'required',
            'no_dokumen' => 'required',
            'jenis_node' => 'required'
        ]);

        if($v->fails())
        {
            return response()->json([
                'data' => null,
                'succes' => false,
                'message' => $v->errors()
            ], 422);
        }

        $sites = DB::table(DB::raw('tr_wo_sites tr,  ma_penggunas p, tr_wo_site_dual_homings dh')) 
                                ->select(DB::raw("tr.*, 
                                                p.id pengguna_id, 
                                                p.nama_lengkap"))
                                                ->whereRaw("p.id = tr.dibuat_oleh")
                                                ->whereRaw("dh.wo_id = tr.wo_id")
                                                ->whereRaw("dh.wo_site_id = tr.wo_site_id")
                                                ->whereRaw("tr.tipe_ba = 'DUAL_HOMING'")
                                                ->whereRaw("tr.progress = true")
                                                ->whereRaw("tr.status = 'OA'")
                                                ->where('tsel_reg', $request->tsel_reg)
                                                ->where('jenis_node', $request->jenis_node)
                                                ->whereNull('ba_id')
                                                ->get();

        $check_dokumen = TrBa::where('no_dokumen', $request->no_dokumen)->first();

        if ($check_dokumen)
        {
            return response()->json([
                'data' => [],
                'success' => true,
                'message' => 'Maaf, No dokumen sudah pernah digunakan sebelumnya.',
             ], 200);
        }

        return response()->json([
            'data' => $sites,
            'success' => true,
            'message' => 'Maaf, Tidak Ada Data Yang Bisa Diproses.',
        ], 200);
    }

    public function createBA(Request $request)
    {
        $v = Validator::make($request->all(), [
            'no_dokumen' => 'required',
            'tgl_dokumen' => 'required',
            'tsel_reg' => 'required',
            'sites' => 'required',
        ]);

        if($v->fails())
        {
            return response()->json([
                'data' => null,
                'succes' => false,
                'message' => $v->errors()
            ], 422);
        }

        $check_dokumen = TrBa::where('no_dokumen', $request->no_dokumen)->first();

        if ($check_dokumen)
        {
            return response()->json([
                'data' => null,
                'success' => true,
                'message' => 'Maaf, No dokumen sudah pernah digunakan sebelumnya.',
             ], 200);
        }


        DB::beginTransaction();
        try {
            
            // $pengguna = MaPengguna::findOrFail(Auth::user()->id);

            $ba = new TrBa();
            $id = Uuid::uuid4()->toString();
            $ba->id = $id;
            $ba->no_dokumen = $request->no_dokumen;
            $ba->tgl_dokumen = $request->tgl_dokumen;
            $ba->tsel_reg = $request->tsel_reg;
            $ba->tipe = 'DUAL_HOMING';
            $ba->dibuat_oleh = Auth::user()->id;
            $ba->status_sirkulir = 0;
            $ba->save();

            $sites = $request->sites;

            foreach ($sites as $site) {
                TrWoSite::where('tipe_ba', 'DUAL_HOMING')
                ->where('progress', true)
                ->where('status', 'OA')
                ->where('tsel_reg', $request->tsel_reg)
                ->where('wo_id',$site['wo_id'])
                ->where('wo_site_id', $site['wo_site_id'])
                ->whereNull('ba_id')
                ->update(array(
                    'ba_id' => $id,
                ));
            }

            
        

            DB::commit();
    
            $url = $this->fileBA($id);

            return response()->json([
                'data' => $ba,
                'success' => true,
                'message' => 'error',
            ], 200);

            // return (new TrBaResource($ba))->additional([
            //     'success' => true,
            //     'message' => 'Berita Acara Berhasil Dibuat'
            // ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'data' => $e->getMessage(),
                'success' => true,
                'message' => 'error',
            ], 400);
        }
    }

    public function deleteBA($id)
    {
        DB::beginTransaction();
        try {
            
            TrWoSite::where('ba_id', $id)->where('tipe_ba', 'DUAL_HOMING')
            ->update(array(
                'ba_id' => null,
            )); 

            TrBa::where('id', $id)->where('tipe', 'DUAL_HOMING')->delete();
        
            $path = storage_path().'/app/public/pdf/'.$id .'.pdf';

            if(file_exists($path))
                unlink($path);


            DB::commit();
    
            return response()->json([
                'status' => true,
                'message' => 'success',
                'data' => null
            ], 200);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'data' => $e->getMessage(),
                'success' => true,
                'message' => 'error',
            ], 400);
        }
    }

    public function fileBA($id)
    {
        $jenis_dokumen = array(
                            "Dokumen uji terima", 
                            "Data Parameter & Topology E2E",
                            "Data Konfigurasi Tiap Node" 
                        );

        $data_ba = TrBa::where('id', $id)->first();
        $dasar_permintaan = '';
        $data_site = array();
        $count = 0;
        $total_bw = 0;
        $total_site = 0;
        $count++;

        $wo = TrWoSite::where('ba_id', $id)->distinct()->get('wo_id');
        
        $arr_wo = array();
        foreach ($wo as $w) {
            $arr_wo[] = $w->id;
        }

        $data_wo = TrWo::whereIn('id', $wo)->get();
        $count = 0;
        foreach ($data_wo as $w) {
            $count++;
            $dasar_permintaan = $dasar_permintaan.$w->dasar_order;
            if ($count < count($data_wo)) {
                $dasar_permintaan = $dasar_permintaan.', ';
            }

            $data_sites = TrWoSite::where('wo_id', $w->id)->where('ba_id', $id)->get();
            $count_site=0;
            $text_site='';
            foreach ($data_sites as $site) {

                $site->dasar_order = $w->dasar_order;

    
                $node_1 = TrWoSiteImage::where('wo_id', $site->wo_id)
                                                        ->where('wo_site_id', $site->wo_site_id)
                                                        ->where('tipe', 'node_1')
                                                        ->first();
    
                $site->node_1 = $node_1->image_url;
                
                $node_2 = TrWoSiteImage::where('wo_id', $site->wo_id)
                                                ->where('wo_site_id', $site->wo_site_id)
                                                ->where('tipe', 'node_2')
                                                ->first();
    
                $site->node_2 = $node_2->image_url;
                
                $topologi = TrWoSiteImage::where('wo_id', $site->wo_id)
                                                ->where('wo_site_id', $site->wo_site_id)
                                                ->where('tipe', 'TOPOLOGI')
                                                ->first();

                $site->topologi = $topologi->image_url;

                $parameter = TrWoSiteDualHomings::where('wo_id', $site->wo_id)
                                                ->where('wo_site_id', $site->wo_site_id)
                                                ->first();
                
                $site->parameter = $parameter;
    
                $total_site++;
                $total_bw = $total_bw + $site->jumlah;
    
                array_push($data_site, $site);

                $count_site++;
                $text_site = $text_site.$site->site_id;
                if ($count_site < count($data_sites)) {
                    $text_site = $text_site.', ';
                }                
            }
            $w->daftar_site = $text_site;
        }

        
        $hari = date('N', strtotime($data_ba->tgl_dokumen));
        $tgl = date('j', strtotime($data_ba->tgl_dokumen));
        $bulan = date('n', strtotime($data_ba->tgl_dokumen));
        $tahun = date('Y', strtotime($data_ba->tgl_dokumen));

        $format_tanggal = new \stdClass();
        $format_tanggal->hari = $this->_hari[$hari-1];
        $format_tanggal->tgl = strtoupper(UtilityHelper::terbilang($tgl));
        $format_tanggal->tgl_nomor = $tgl;
        $format_tanggal->bulan = $this->_month[$bulan-1];
        $format_tanggal->tahun_nomor = $tahun;
        $format_tanggal->tahun = strtoupper(UtilityHelper::terbilang($tahun));

        $people_ttd = new \stdClass();
        $people_ttd->osm_regional = MaPengaturan::where('nama', 'OSM_REGIONAL_WHOLESALE_SERVICE')->first();
        $people_ttd->gm_core_transport = MaPengaturan::where('nama', 'GM_CORE_TRANSPORT_NETWORK')->first();
        $people_ttd->manager_wholesale = MaPengaturan::where('nama', 'MANAGER_WHOLESALE_SUPPORT')->first();
        $people_ttd->manager_pm_jatim = MaPengaturan::where('nama', 'PM_JATIM')->first();
        $people_ttd->manager_pm_balnus = MaPengaturan::where('nama', 'PM_BALNUS')->first();
        $people_ttd->gm_network = MaPengaturan::where('nama', 'GM_NETWORK_ENGINEERING_PROJECT')->first();
        $people_ttd->telkomsel_rto_region_jatim = MaPengaturan::where('nama', 'TELKOMSEL_RTO_REGION_JATIM')->first();
        $people_ttd->telkomsel_rto_region_balnus = MaPengaturan::where('nama', 'TELKOMSEL_RTO_REGION_BALNUS')->first();
        $people_ttd->telkomsel_qc_network_region_jatim = MaPengaturan::where('nama', 'TELKOMSEL_QC_NETWORK_REGION_JATIM')->first();
        $people_ttd->telkomsel_qc_network_region_balnus = MaPengaturan::where('nama', 'TELKOMSEL_QC_NETWORK_REGION_BALNUS')->first();


        $pdf = PDF::loadView('dualhoming', [
            'jenis_dokumen'     => $jenis_dokumen,
            'data_wo'           => $data_wo,
            'data_site'         => $data_site,
            'data_ba'           => $data_ba,
            'dasar_permintaan'  => $dasar_permintaan,
            'total_bw'          => $total_bw,
            'total_site'        => $total_site,
            'format_tanggal'    => $format_tanggal,
            'people_ttd'        => $people_ttd
        ])->setPaper('a4');

        // return $pdf->download('berita_acara.pdf');


        $file_name = $id.'.pdf';

        Storage::put('public/pdf/'.$file_name, $pdf->output());

        return $file_name;



        // return view('dualhoming',  [
        //     'jenis_dokumen'     => $jenis_dokumen,
        //     'data_wo'           => $data_wo,
        //     'data_site'         => $data_site,
        //     'data_ba'           => $data_ba,
        //     'dasar_permintaan'  => $dasar_permintaan,
        //     'total_bw'          => $total_bw,
        //     'total_site'        => $total_site,
        //     'format_tanggal'    => $format_tanggal,
        //     'people_ttd'        => $people_ttd
        // ]);

    }

    public function downloadBA($id)
    {
        // return response()->file(storage_path().'/app/public/pdf/'.$id.'.pdf');
        return response()->download(storage_path().'/app/public/pdf/'.$id.'.pdf');

    }

    private function arrayToString($data) 
    {
        $string='';
        $i=0;
        foreach ($data as $value){
            if ($i==0) {
                $string .= "'".$value."'";
            } else {
                $string .= ",'".$value."'";
            }
            $i++;      
        }

        return $string;
    }
}

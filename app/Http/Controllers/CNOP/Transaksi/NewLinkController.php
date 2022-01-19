<?php

namespace App\Http\Controllers\CNOP\Transaksi;

use App\Helper\UtilityHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\NewlinkResource;
use App\Http\Resources\TrBaResource;
use App\Http\Resources\TrWoSiteResource;
use App\Models\MaNomorDokumen;
use App\Models\MaPengaturan;
use App\Models\MaPengguna;
use App\Models\MaWitel;
use App\Models\TrBa;
use App\Models\TrWo;
use App\Models\TrWoSite;
use App\Models\TrWoSiteImage;
use App\Models\TrWoSiteLv;
use App\Models\TrWoSiteQc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

use PDF;

class NewLinkController extends Controller
{
    private $_hari = ['SENIN', 'SELASA', 'RABU', 'KAMIS', 'JUMAT', 'SABTU','MINGGU'];
    private $_month = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

    public function index()
    {
        $pengguna = MaPengguna::find(Auth::user()->id);
        // $witel = "";
        // if ($pengguna->witel_id != null) {
        //     $witel = MaWitel::find($pengguna->witel_id); 
        // }
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
                                    ->whereRaw("tr.tipe_ba = 'NEW_LINK'");

        if ($pengguna->site_witel) {
            $data = $data->whereRaw("tr.site_witel = '$pengguna->site_witel'");
        }

        
        $q = $_GET['q'];
        
        if ($q) {
            $data = $data->whereRaw("(program like '%$q%' or 
                                    site_name like '%$q%' or 
                                    tr.site_witel like '%$q%' or 
                                    tr.tsel_reg like '%$q%' or 
                                    site_id like '%$q%' or 
                                    dasar_order like '%$q%')");
        }
                                    
        $data = $data->orderBy('trw.dasar_order')->orderBy('tr.site_id')->paginate(25)->onEachSide(5);       

        return NewlinkResource::collection(($data))->additional([
            'success' => true,
            'message' => null,
        ]);
    }

    public function store(Request $request)
    {
        $v = Validator::make($request->all(), [
            'sites' => 'required',
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
            $sites = $request->sites;
        
            $counter = 0;
            $dasar_order = "";
            $wo_id = "";
            foreach ($sites as $site) {
                if ($dasar_order != $site['dasar_order']) {
                    $counter = 1;
                    $check = TrWo::where('dasar_order', $site['dasar_order'])->first();
                    if (!$check) {
                        $wo_id =  Uuid::uuid4()->toString();
                        $dasar_order = $site['dasar_order'];    
                        $wo = new TrWo();
                        $wo->id = $wo_id;
                        $wo->dasar_order = $site['dasar_order'];
                        $wo->tipe_ba = 'NEW_LINK';
                        $wo->save();
                    } else {
                        $wo_id = $check->id;
                        $counter = TrWoSite::where('wo_id', $wo_id)->max('wo_site_id') + 1;
                    }
                    
                }
                else {
                    $counter++;
                }

                $check_site = TrWoSite::where('wo_id', $wo_id)->where('site_id', $site['site_id'])->where('tipe_ba', 'NEW_LINK')->first();
                if (!$check_site) {
                    $wo_site = new TrWoSite();
                    $wo_site->wo_id = $wo_id;
                    $wo_site->wo_site_id = $counter;
                    $wo_site->site_id = $site['site_id'];
                    $wo_site->site_name = (isset($site['site_name'])) ? $site['site_name'] : '-';
                    $wo_site->site_witel = $site['site_witel'];
                    $wo_site->tsel_reg = $site['tsel_reg'];
                    $wo_site->tgl_on_air = $site['tgl_on_air'];
                    $wo_site->data_2g = 0; 
                    $wo_site->data_3g = 0; 
                    $wo_site->data_4g = 0; 
                    $wo_site->jumlah = $site['data_bandwidth'];
                    $wo_site->program = $site['program'];
                    $wo_site->dibuat_oleh = Auth::user()->id;
                    $wo_site->status = 'OGP';
                    $wo_site->progress = false;
                    $wo_site->tipe_ba = 'NEW_LINK';
                    $wo_site->save();
                } else {
                    TrWoSite::where('wo_id', $wo_id)->where('site_id', $site['site_id'])->where('tipe_ba', 'NEW_LINK')
                    ->update(array(
                        'program' => $site['program']
                    ));
                }
                
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
                                    ->whereRaw("tr.tipe_ba = 'NEW_LINK'")
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
            'program' => 'required',
            'jumlah' => 'required',
            'tgl_on_air' => 'required',
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
                                'program' => $request->program,
                                'jumlah' => $request->jumlah,
                                'tgl_on_air' => $request->tgl_on_air,
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

    public function updateBW(Request $request, $wo_id, $wo_site_id)
    {
        $v = Validator::make($request->all(), [
            'data_2g' => 'required',
            'data_3g' => 'required',
            'data_4g' => 'required',
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
                                'data_2g' => $request->data_2g,
                                'data_3g' => $request->data_3g,
                                'data_4g' => $request->data_4g,
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

    public function updateNeType(Request $request, $wo_id, $wo_site_id)
    {
        $v = Validator::make($request->all(), [
            'ne_type' => 'required',
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
                                'ne_type' => $request->ne_type,
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
        ]);

        if($v->fails())
        {
            return response()->json([
                'data' => null,
                'succes' => false,
                'message' => $v->errors()
            ], 422);
        }

        $sites = DB::table(DB::raw('tr_wo_sites tr, tr_wos trw, ma_penggunas p')) 
                                ->select(DB::raw("tr.*, 
                                                trw.dasar_order, 
                                                trw.lampiran_url,  
                                                p.id pengguna_id, 
                                                p.nama_lengkap"))
                                                ->whereRaw("p.id = tr.dibuat_oleh")
                                                ->whereRaw("tr.wo_id = trw.id")
                                                ->whereRaw("tr.tipe_ba = 'NEW_LINK'")
                                                ->whereRaw("tr.progress = true")
                                                ->whereRaw("tr.status = 'OA'")
                                                ->where('tsel_reg', $request->tsel_reg)
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
            $ba->tipe = 'NEW_LINK';
            $ba->dibuat_oleh = Auth::user()->id;
            $ba->status_sirkulir = 0;
            $ba->save();

            $sites = $request->sites;

            foreach ($sites as $site) {
                TrWoSite::where('tipe_ba', 'NEW_LINK')
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

            $data = new MaNomorDokumen();
            $data->id = Uuid::uuid4()->toString();
            $data->no_dokumen = $request->no_dokumen;
            $data->tipe_dokumen = 'NEW_LINK';
            $data->tgl_dokumen = $request->tgl_dokumen;
            $data->save();
        
            DB::commit();
    
            $url = $this->fileBA($id);

            return response()->json([
                'data' => $ba,
                'success' => true,
                'message' => 'success',
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

    public function createBAByPass(Request $request)
    {
        $v = Validator::make($request->all(), [
            'no_dokumen' => 'required',
            'tgl_dokumen' => 'required',
            'tsel_reg' => 'required',
            'sites' => 'required',
            // 'file_ba' => 'required'
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
            $ba->tipe = 'NEW_LINK';
            $ba->dibuat_oleh = Auth::user()->id;
            $ba->status_sirkulir = 0;
            $ba->save();

            $sites = $request->sites;
        
            $count = 0;
            foreach ($sites as $site) {
                $update = TrWoSite::where('tipe_ba', 'NEW_LINK')
                        ->where('progress', false)
                        ->where('tsel_reg', $request->tsel_reg)
                        ->where('site_id', $site)
                        ->whereNull('ba_id')
                        ->update(array(
                            'ba_id' => $id,
                            'progress' => true,
                            'status' => 'OA'
                        ));
                if ($update > 0)
                    $count++;
            }

            if ($count > 0) {

                Storage::putFileAs('public/pdf',$request->file('file_ba'), $id.'.'.$request->file('file_ba')->getClientOriginalExtension());
                
                DB::commit();
        
    
                return response()->json([
                    'data' => $ba,
                    'success' => true,
                    'message' => 'succes',
                ], 200);
            } else {
                DB::rollback();
                return response()->json([
                    'data' => 'Tidak Ada Data Yang Diproses',
                    'success' => false,
                    'message' => null,
                ], 400);
            }




        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'data' => $e->getMessage(),
                'success' => false,
                'message' => 'error',
            ], 400);
        }
    }

    public function deleteBA($id)
    {
        DB::beginTransaction();
        try {

            $check = TrBa::where('id', $id)->first();
            
            if ($check) {
                $no_dokumen = $check->no_dokumen;

                TrWoSite::where('ba_id', $id)->where('tipe_ba', 'NEW_LINK')
                ->update(array(
                    'ba_id' => null,
                )); 
    
                TrBa::where('id', $id)->where('tipe', 'NEW_LINK')->delete();
    
                $path = storage_path().'/app/public/pdf/'.$id .'.pdf';
    
                if(file_exists($path))
                    unlink($path);
    
                MaNomorDokumen::where('no_dokumen', $no_dokumen)->delete();

                DB::commit();
        
                return response()->json([
                    'status' => true,
                    'message' => 'success',
                    'data' => null
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data Tidak Ditemukan',
                    'data' => null
                ], 400);
            }
           

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
                            "LV Certificate*", 
                            "QC Certificate*", 
                            "Data Konfigurasi & Topology E2E", 
                            "Capture trafik oleh Telkom",
                            "Referensi Work Order"
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

                $site->qc_latency = TrWoSiteQc::where('wo_id', $site->wo_id)
                                                    ->where('wo_site_id', $site->wo_site_id)
                                                    ->where('tipe', 'latency')
                                                    ->first();
        
                $site->qc_packet_loss = TrWoSiteQc::where('wo_id', $site->wo_id)
                                                    ->where('wo_site_id', $site->wo_site_id)
                                                    ->where('tipe','packet_loss')
                                                    ->first();
    
                $site->lv_latency = TrWoSiteLv::where('wo_id', $site->wo_id)
                                                    ->where('wo_site_id', $site->wo_site_id)
                                                    ->where('tipe', 'latency')
                                                    ->first();
    
                $site->lv_packet_loss = TrWoSiteLv::where('wo_id', $site->wo_id)
                                                ->where('wo_site_id', $site->wo_site_id)
                                                ->where('tipe','packet_loss')
                                                ->first();
    
                $konfigurasi = TrWoSiteImage::where('wo_id', $site->wo_id)
                                                        ->where('wo_site_id', $site->wo_site_id)
                                                        ->where('tipe', 'KONFIGURASI')
                                                        ->first();
    
                $site->konfigurasi = $konfigurasi->image_url;
                
                $trafik = TrWoSiteImage::where('wo_id', $site->wo_id)
                                                ->where('wo_site_id', $site->wo_site_id)
                                                ->where('tipe', 'CAPTURE_TRAFIK')
                                                ->first();
    
                $site->trafik = $trafik->image_url;
                
                $topologi = TrWoSiteImage::where('wo_id', $site->wo_id)
                                                ->where('wo_site_id', $site->wo_site_id)
                                                ->where('tipe', 'TOPOLOGI')
                                                ->first();
                
                $site->topologi = $topologi->image_url;

                $lv_image = TrWoSiteImage::where('wo_id', $site->wo_id)
                                                ->where('wo_site_id', $site->wo_site_id)
                                                ->where('tipe', 'LV')
                                                ->first();
                
                $site->lv_image = $lv_image->image_url;

                $qc_image = TrWoSiteImage::where('wo_id', $site->wo_id)
                                                ->where('wo_site_id', $site->wo_site_id)
                                                ->where('tipe', 'QC')
                                                ->first();
                
                $site->qc_image = $qc_image->image_url;
    
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
        $people_ttd->telkomsel_pm_region_jatim = MaPengaturan::where('nama', 'TELKOMSEL_PM_REGION_JATIM')->first();
        $people_ttd->telkomsel_pm_region_balnus = MaPengaturan::where('nama', 'TELKOMSEL_PM_REGION_BALNUS')->first();
	/*
         return view('newlink',  [
             'jenis_dokumen'     => $jenis_dokumen,
             'data_wo'           => $data_wo,
             'data_site'         => $data_site,
             'data_ba'           => $data_ba,
             'dasar_permintaan'  => $dasar_permintaan,
             'total_bw'          => $total_bw,
             'total_site'        => $total_site,
             'format_tanggal'    => $format_tanggal,
             'people_ttd'        => $people_ttd
         ]);
        */
	
        $pdf = PDF::loadView('newlink', [
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

        $file_name = $id.'.pdf';

        Storage::put('public/pdf/'.$file_name, $pdf->output());

        return $file_name;
        
	// return $pdf->download('berita_acara.pdf');
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

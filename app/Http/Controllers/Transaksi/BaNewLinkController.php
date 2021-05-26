<?php

namespace App\Http\Controllers\Transaksi;

use App\Helper\UtilityHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\TrBaResource;
use App\Http\Resources\TrBaSiteLampiranResource;
use App\Http\Resources\TrBaSiteLvResource;
use App\Http\Resources\TrBaSiteQcResource;
use App\Http\Resources\TrBaSiteResource;
use App\Models\MaPengaturan;
use App\Models\MaPengguna;
use App\Models\TrBa;
use App\Models\TrBaSite;
use App\Models\TrBaSiteLampiran;
use App\Models\TrBaSiteLv;
use App\Models\TrBaSiteQc;
use App\Models\TrBaWo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

use Ramsey\Uuid\Uuid;

use PDF;
use PDF2;


class BaNewLinkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $_hari = ['MINGGU', 'SENIN', 'SELASA', 'RABU', 'KAMIS', 'JUMAT', 'SABTU'];
    private $_month = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];


    public function index()
    {
        // $data = TrBa::where('tipe', 'new_link')->paginate();
        $data = DB::table(DB::raw('tr_bas tr, ma_penggunas p')) 
                                    ->select(DB::raw("tr.*, p.id pengguna_id, p.nama_lengkap"))
                                    ->whereRaw("p.id = tr.dibuat_oleh");

        $data = $data->orderBy('tr.created_at')->paginate(15)->onEachSide(5);       
        
        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => $data
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $v = Validator::make($request->all(), [
            'no_dokumen' => 'required|unique:tr_bas,no_dokumen',
            'tgl_dokumen' => 'required',
            'wilayah' => 'in:JATIM,BALNUS'
        ]);

        if($v->fails())
        {
            return response()->json([
                'data' => null,
                'succes' => false,
                'message' => $v->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            
            // $pengguna = MaPengguna::findOrFail(Auth::user()->id);

            $ba = new TrBa();
            $id = Uuid::uuid4()->toString();
            $ba->id = $id;
            $ba->no_dokumen = $request->no_dokumen;
            $ba->tgl_dokumen = $request->tgl_dokumen;
            $ba->wilayah = $request->wilayah;
            $ba->tipe = 'new_link';
            $ba->dibuat_oleh = Auth::user()->id;
            $ba->save();

            $sites = $request->sites;
        
            $counter = 0;
            $dasar_order = "";
            $wo_id = "";
            foreach ($sites as $site) {
                $counter++;

                if ($dasar_order != $site['dasar_order']) {
                    $check = TrBaWo::where('ba_id', $id)->where('dasar_order', $site['dasar_order'])->first();
                    if (!$check) {
                        $wo_id =  Uuid::uuid4()->toString();
                        $dasar_order = $site['dasar_order'];    
                        $ba_wo = new TrBaWo();
                        $ba_wo->ba_id = $id;
                        $ba_wo->wo_id = $wo_id;
                        $ba_wo->dasar_order = $site['dasar_order'];
                        $ba_wo->save();
                    } else {
                        $ba_wo = $check->wo_id;
                    }
                    
                }

                $ba_site = new TrBaSite();
                $ba_site->ba_id = $id;
                $ba_site->wo_id = $wo_id;
                $ba_site->ba_site_id = $counter;
                $ba_site->site_id = $site['site_id'];
                $ba_site->site_name = $site['site_name'];
                $ba_site->site_witel = $site['site_witel'];
                $ba_site->tgl_on_air = $site['tgl_on_air'];
                $ba_site->data_2g = 0; //$site['data_2g'];
                $ba_site->data_3g = 0; //$site['data_3g'];
                $ba_site->data_4g = 0; //$site['data_4g'];
                $ba_site->jumlah = $site['data_bandwidth'];
                $ba_site->program = $site['program'];
                $ba_site->dibuat_oleh = Auth::user()->id;
                $ba_site->save();
            }
        

            DB::commit();
    
            return (new TrBaResource($ba))->additional([
                'success' => true,
                'message' => 'Data New Link Berhasil Ditambahkan'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'data' => $e->getMessage(),
                'success' => true,
                'message' => 'error',
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $data = TrBa::with('sites')->findOrFail($id);
            return (new TrBaResource($data))->additional([
                'success' => true,
                'message' => 'suksess'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
               'data' => null,
                'success' => false,
                'message' => 'Data Tidak Ditemukan',
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        abort(404);
    }

    //sites

    public function showSites($id)
    {
        try {
            $data_order = TrBaWo::where('ba_id', $id)->get();
            // $sites = TrBaSite::where('ba_id', $id)->get();
            
            foreach ($data_order as $data) {
                $sites = TrBaSite::where('ba_id', $id)->where('wo_id', $data->wo_id)->get();
                foreach ($sites as $site) {
                    $site->qc = TrBaSiteQc::where('ba_id', $id)->where('wo_id', $data->wo_id)->where('ba_site_id', $site->ba_site_id)->get();
                    $site->lv = TrBaSiteLv::where('ba_id', $id)->where('wo_id', $data->wo_id)->where('ba_site_id', $site->ba_site_id)->get();;
                    $site->konfigurasi = TrBaSiteLampiran::where('ba_id', $id)
                                                         ->where('wo_id', $data->wo_id)
                                                         ->where('ba_site_id', $site->ba_site_id)
                                                         ->where('tipe', 'KONFIGURASI')
                                                         ->first();
                    $site->trafik = TrBaSiteLampiran::where('ba_id', $id)
                                                    ->where('wo_id', $data->wo_id)
                                                    ->where('ba_site_id', $site->ba_site_id)
                                                    ->where('tipe', 'CAPTURE_TRAFIK')
                                                    ->first();
                    $site->topologi = TrBaSiteLampiran::where('ba_id', $id)
                                                    ->where('wo_id', $data->wo_id)
                                                    ->where('ba_site_id', $site->ba_site_id)
                                                    ->where('tipe', 'TOPOLOGI')
                                                    ->first();
                }

                $data->sites = $sites;
                // $data->lampiran_url = 'oke';
            }

            return (new TrBaResource($data_order))->additional([
                'success' => true,
                'message' => 'suksess'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
               'data' => null,
                'success' => false,
                'message' => $th,
            ], 404);
        }
    }

    // ba work order
    public function showWo($ba_id, $wo_id)
    {
        try {

            $data_order = DB::table(DB::raw('tr_bas b, tr_ba_wos w')) 
                                        ->select(DB::raw("w.*, b.wilayah, b.no_dokumen"))
                                        ->whereRaw("b.id = w.ba_id")
                                        ->where('ba_id', $ba_id)
                                        ->where('wo_id', $wo_id)
                                        ->first();
            
            $sites = TrBaSite::where('ba_id', $ba_id)->where('wo_id', $data_order->wo_id)->get();
            foreach ($sites as $site) {
                $site->qc_latency = TrBaSiteQc::where('ba_id', $ba_id)
                                              ->where('wo_id', $data_order->wo_id)
                                              ->where('ba_site_id', $site->ba_site_id)
                                              ->where('tipe', 'latency')
                                              ->first();
                $site->qc_packet_loss = TrBaSiteQc::where('ba_id', $ba_id)
                                                  ->where('wo_id', $data_order->wo_id)
                                                  ->where('ba_site_id', $site->ba_site_id)
                                                  ->where('tipe','packet_loss')
                                                  ->first();

                $site->lv_latency = TrBaSiteLv::where('ba_id', $ba_id)
                                                  ->where('wo_id', $data_order->wo_id)
                                                  ->where('ba_site_id', $site->ba_site_id)
                                                  ->where('tipe', 'latency')
                                                  ->first();

                $site->lv_packet_loss = TrBaSiteLv::where('ba_id', $ba_id)
                                                ->where('wo_id', $data_order->wo_id)
                                                ->where('ba_site_id', $site->ba_site_id)
                                                ->where('tipe','packet_loss')
                                                ->first();

                $site->konfigurasi = TrBaSiteLampiran::where('ba_id', $ba_id)
                                                        ->where('wo_id', $data_order->wo_id)
                                                        ->where('ba_site_id', $site->ba_site_id)
                                                        ->where('tipe', 'KONFIGURASI')
                                                        ->first();

                $site->trafik = TrBaSiteLampiran::where('ba_id', $ba_id)
                                                ->where('wo_id', $data_order->wo_id)
                                                ->where('ba_site_id', $site->ba_site_id)
                                                ->where('tipe', 'CAPTURE_TRAFIK')
                                                ->first();

                $site->topologi = TrBaSiteLampiran::where('ba_id', $ba_id)
                                                ->where('wo_id', $data_order->wo_id)
                                                ->where('ba_site_id', $site->ba_site_id)
                                                ->where('tipe', 'TOPOLOGI')
                                                ->first();
            }

            $data_order->sites = $sites;

            // return (new TrBaResource($data_order))->additional([
            //     'success' => true,
            //     'message' => 'suksess'
            // ]);

            return response()->json([
                'data' => $data_order,
                 'success' => true,
                 'message' => null,
             ], 200);

        } catch (\Throwable $th) {
            return response()->json([
               'data' => null,
                'success' => false,
                'message' => $th,
            ], 404);
        }
    }

    public function updateLampiranWo(Request $request, $ba_id, $wo_id)
    {
        $v = Validator::make($request->all(), [
            'lampiran' => 'required'
        ]);

        if ($v->fails())
        {
            return response()->json([
                'status' => false,
                'message' => 'error',
                'data' => $v->errors()
            ], 422);
        }   

        $data = TrBaWo::where('ba_id', $ba_id)->where('wo_id', $wo_id)->first();

        if($data == null)
        {
            return response()->json([
                'data' => null,
                'succes' => false,
                'message' => 'Data Tidak Ditemukan'
            ], 422);
        }

        $url = $this->prsoesUpload($request->file('lampiran'));

        DB::beginTransaction();
        try {

            $update = TrBaWo::where('ba_id', $ba_id)->where('wo_id', $wo_id)
                            ->update(array(
                                'lampiran_url' => $url,
                            ));

            DB::commit();
    
            return response()->json([
                'status' => true,
                'message' => 'Data Berhasil Diupload',
                'data' => $data
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

    public function destroyLampiranWo($ba_id, $wo_id)
    {
        $data = TrBaWo::where('ba_id', $ba_id)->where('wo_id', $wo_id)->first();

        if(!$data)
        {
            return response()->json([
                'status' => false,
                'message' => 'Data Tidak Ditemukan',
                'data' => null
            ], 404);
        }

        $path = public_path().'/lampirans/'.$data->lampiran_url;
        unlink($path);

        $update = TrBaWo::where('ba_id', $ba_id)->where('wo_id', $wo_id)
                            ->update(array(
                                'lampiran_url' => null,
                        ));
        

        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => $data
        ], 200);
    }

    //site

    public function updateSite(Request $request, $ba_id, $wo_id, $ba_site_id)
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

        $data = TrBaSite::where('ba_id', $ba_id)->where('wo_id', $wo_id)->where('ba_site_id', $ba_site_id)->first();

        if($data == null)
        {
            return response()->json([
                'data' => null,
                'succes' => false,
                'message' => 'Data Tidak Ditemukan'
            ], 422);
        }


        try {

            $data = TrBaSite::where('ba_id', $ba_id)->where('wo_id', $wo_id)->where('ba_site_id', $ba_site_id)
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


    // lv
    public function indexLv($ba_id, $ba_site_id)
    {
        $data = TrBaSiteLv::where('ba_id', $ba_id)->where('ba_site_id', $ba_site_id)->paginate();
        return TrBaSiteLvResource::collection($data)->additional([
            'success' => true,
            'message' => null
        ]);
    }

    public function storeLv(Request $request, $ba_id, $wo_id, $ba_site_id)
    {
        $v = Validator::make($request->all(), [
            'packet_loss_h1' => 'required',
            'packet_loss_h2' => 'required',
            'packet_loss_h3' => 'required',
            'packet_loss_h4' => 'required',
            'packet_loss_h5' => 'required',
            'packet_loss_result' => 'required',
            'latency_h1' => 'required',
            'latency_h2' => 'required',
            'latency_h3' => 'required',
            'latency_h4' => 'required',
            'latency_h5' => 'required',
            'latency_result' => 'required',

        ]);

        if($v->fails())
        {
            return response()->json([
                'data' => null,
                'succes' => false,
                'message' => $v->errors()
            ], 422);
        }

        $check_packet = TrBaSiteLv::where('ba_id', $ba_id)->where('wo_id', $wo_id)->where('ba_site_id', $ba_site_id)->where('tipe', 'packet_loss')->first();
        $check_latency = TrBaSiteLv::where('ba_id', $ba_id)->where('wo_id', $wo_id)->where('ba_site_id', $ba_site_id)->where('tipe', 'latency')->first();

        if($check_packet != null)
        {
            return response()->json([
                'data' => null,
                'succes' => false,
                'message' => 'Packet Loss Sudah Pernah Diinput'
            ], 422);
        }

        if($check_latency != null)
        {
            return response()->json([
                'data' => null,
                'succes' => false,
                'message' => 'Latency Sudah Pernah Diinput'
            ], 422);
        }

        DB::beginTransaction();
        try {

            
            $packet = new TrBaSiteLv();
            $packet->ba_id = $ba_id;
            $packet->ba_site_id = $ba_site_id;
            $packet->wo_id = $wo_id;
            $packet->tipe = 'packet_loss';
            $packet->hour1 = $request->packet_loss_h1;
            $packet->hour2 = $request->packet_loss_h2;
            $packet->hour3 = $request->packet_loss_h3;
            $packet->hour4 = $request->packet_loss_h4;
            $packet->hour5 = $request->packet_loss_h5;
            $packet->result = $request->packet_loss_result;
            $packet->pass = $request->packet_loss_pass;
            $packet->dibuat_oleh = Auth::user()->id;
            $packet->save();

            $latency = new TrBaSiteLv();
            $latency->ba_id = $ba_id;
            $latency->ba_site_id = $ba_site_id;
            $latency->wo_id = $wo_id;
            $latency->tipe = 'latency';
            $latency->hour1 = $request->latency_h1;
            $latency->hour2 = $request->latency_h2;
            $latency->hour3 = $request->latency_h3;
            $latency->hour4 = $request->latency_h4;
            $latency->hour5 = $request->latency_h5;
            $latency->result = $request->latency_result;
            $latency->pass = $request->latency_pass;
            $latency->dibuat_oleh = Auth::user()->id;
            $latency->save();
            

            DB::commit();

            $ba_site = TrBaSite::with('ba')->where('ba_id', $ba_id)->where('ba_site_id', $ba_site_id)->first();
            $ba_site->lv = TrBaSiteLv::where('ba_id', $ba_id)->where('ba_site_id', $ba_site_id)->get();
    
            return (new TrBaSiteResource($ba_site))->additional([
                'success' => true,
                'message' => 'Data LV Berhasil Diupdate'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'data' => $e->getMessage(),
                'success' => true,
                'message' => 'error',
            ], 400);
        }
    }

    public function updateLv(Request $request, $ba_id, $wo_id, $ba_site_id)
    {
        $v = Validator::make($request->all(), [
            'packet_loss_h1' => 'required',
            'packet_loss_h2' => 'required',
            'packet_loss_h3' => 'required',
            'packet_loss_h4' => 'required',
            'packet_loss_h5' => 'required',
            'packet_loss_result' => 'required',
            'packet_loss_pass' => 'required',
            'latency_h1' => 'required',
            'latency_h2' => 'required',
            'latency_h3' => 'required',
            'latency_h4' => 'required',
            'latency_h5' => 'required',
            'latency_result' => 'required',
            'latency_pass' => 'required'

        ]);

        if($v->fails())
        {
            return response()->json([
                'data' => null,
                'succes' => false,
                'message' => $v->errors()
            ], 422);
        }

        $packet = TrBaSiteLv::where('ba_id', $ba_id)->where('wo_id', $wo_id)->where('ba_site_id', $ba_site_id)->where('tipe', 'packet_loss')->first();
        $latency = TrBaSiteLv::where('ba_id', $ba_id)->where('wo_id', $wo_id)->where('ba_site_id', $ba_site_id)->where('tipe', 'latency')->first();

        if($packet == null)
        {
            return response()->json([
                'data' => null,
                'succes' => false,
                'message' => 'Packet Loss Tidak Ditemukan'
            ], 422);
        }

        if($latency == null)
        {
            return response()->json([
                'data' => null,
                'succes' => false,
                'message' => 'Latency Tidak Ditemukan'
            ], 422);
        }

        DB::beginTransaction();
        try {
            
            $packet->hour1 = $request->packet_loss_h1;
            $packet->hour2 = $request->packet_loss_h2;
            $packet->hour3 = $request->packet_loss_h3;
            $packet->hour4 = $request->packet_loss_h4;
            $packet->hour5 = $request->packet_loss_h5;
            $packet->result = $request->packet_loss_result;
            $packet->pass = $request->packet_loss_pass;
            $packet->update();

            $latency->hour1 = $request->latency_h1;
            $latency->hour2 = $request->latency_h2;
            $latency->hour3 = $request->latency_h3;
            $latency->hour4 = $request->latency_h4;
            $latency->hour5 = $request->latency_h5;
            $latency->result = $request->latency_result;
            $latency->pass = $request->latency_pass;
            $latency->update();
            

            DB::commit();

            $ba_site = TrBaSite::with('ba')->where('ba_id', $ba_id)->where('ba_site_id', $ba_site_id)->first();
            $ba_site->lv = TrBaSiteLv::where('ba_id', $ba_id)->where('ba_site_id', $ba_site_id)->get();
    
            return (new TrBaSiteResource($ba_site))->additional([
                'success' => true,
                'message' => 'Data LV Berhasil Diupdate'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'data' => $e->getMessage(),
                'success' => true,
                'message' => 'error',
            ], 400);
        }
    }

    public function showLv($ba_id, $ba_site_id, $tipe)
    {
        try {
            $data = TrBaSiteLv::where('ba_id', $ba_id)->where('ba_site_id', $ba_site_id)->where('tipe', 'latency')->first();
            return (new TrBaSiteLvResource($data))->additional([
                'success' => true,
                'message' => 'suksess'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'data' => null,
                'success' => false,
                'message' => $th,
            ], 404);
        }
    }

    //Qc

    public function indexQc($ba_id, $wo_id,  $ba_site_id)
    {
        $data = TrBaSiteQc::where('ba_id', $ba_id)->where('wo_id', $wo_id)->where('ba_site_id', $ba_site_id)->get();
        return TrBaSiteQcResource::collection($data)->additional([
            'success' => true,
            'message' => null
        ]);
    }

    public function storeQc(Request $request, $ba_id, $wo_id, $ba_site_id)
    {
        $v = Validator::make($request->all(), [
            'packet_loss_d1' => 'required',
            'packet_loss_d2' => 'required',
            'packet_loss_d3' => 'required',
            'packet_loss_d4' => 'required',
            'packet_loss_d5' => 'required',
            'packet_loss_d6' => 'required',
            'packet_loss_result' => 'required',
            'packet_loss_pass' => 'required',
            'latency_d1' => 'required',
            'latency_d2' => 'required',
            'latency_d3' => 'required',
            'latency_d4' => 'required',
            'latency_d5' => 'required',
            'latency_d6' => 'required',
            'latency_result' => 'required',
            'latency_pass' => 'required'

        ]);

        if($v->fails())
        {
            return response()->json([
                'data' => null,
                'succes' => false,
                'message' => $v->errors()
            ], 422);
        }

        $check_packet = TrBaSiteQc::where('ba_id', $ba_id)->where('wo_id', $wo_id)->where('ba_site_id', $ba_site_id)->where('tipe', 'packet_loss')->first();
        $check_latency = TrBaSiteQc::where('ba_id', $ba_id)->where('wo_id', $wo_id)->where('ba_site_id', $ba_site_id)->where('tipe', 'latency')->first();


        if($check_packet != null)
        {
            return response()->json([
                'data' => null,
                'succes' => false,
                'message' => 'Packet Loss Sudah Pernah Diinput'
            ], 422);
        }

        if($check_latency != null)
        {
            return response()->json([
                'data' => null,
                'succes' => false,
                'message' => 'Latency Sudah Pernah Diinput'
            ], 422);
        }

        DB::beginTransaction();
        try {
            
            $packet = new TrBaSiteQc();
            $packet->ba_id = $ba_id;
            $packet->ba_site_id = $ba_site_id;
            $packet->wo_id = $wo_id;
            $packet->tipe = 'packet_loss';
            $packet->day1 = $request->packet_loss_d1;
            $packet->day2 = $request->packet_loss_d2;
            $packet->day3 = $request->packet_loss_d3;
            $packet->day4 = $request->packet_loss_d4;
            $packet->day5 = $request->packet_loss_d5;
            $packet->day6 = $request->packet_loss_d6;
            $packet->result = $request->packet_loss_result;
            $packet->pass = $request->packet_loss_pass;
            $packet->dibuat_oleh = Auth::user()->id;
            $packet->save();

            $latency = new TrBaSiteQc();
            $latency->ba_id = $ba_id;
            $latency->ba_site_id = $ba_site_id;
            $latency->wo_id = $wo_id;
            $latency->tipe = 'latency';
            $latency->day1 = $request->latency_d1;
            $latency->day2 = $request->latency_d2;
            $latency->day3 = $request->latency_d3;
            $latency->day4 = $request->latency_d4;
            $latency->day5 = $request->latency_d5;
            $latency->day6 = $request->latency_d6;
            $latency->result = $request->latency_result;
            $latency->pass = $request->latency_pass;
            $latency->dibuat_oleh = Auth::user()->id;
            $latency->save();
            

            DB::commit();

            $ba_site = TrBaSite::with('ba')->where('ba_id', $ba_id)->where('ba_site_id', $ba_site_id)->first();
            $ba_site->qc = TrBaSiteQc::where('ba_id', $ba_id)->where('ba_site_id', $ba_site_id)->get();
    
            return (new TrBaSiteResource($ba_site))->additional([
                'success' => true,
                'message' => 'Data QC Berhasil Diupdate'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'data' => $e->getMessage(),
                'success' => true,
                'message' => 'error',
            ], 400);
        }
    }

    public function updateQC(Request $request, $ba_id, $wo_id, $ba_site_id)
    {
        $v = Validator::make($request->all(), [
            'packet_loss_d1' => 'required',
            'packet_loss_d2' => 'required',
            'packet_loss_d3' => 'required',
            'packet_loss_d4' => 'required',
            'packet_loss_d5' => 'required',
            'packet_loss_d6' => 'required',
            'packet_loss_result' => 'required',
            'packet_loss_pass' => 'required',
            'latency_d1' => 'required',
            'latency_d2' => 'required',
            'latency_d3' => 'required',
            'latency_d4' => 'required',
            'latency_d5' => 'required',
            'latency_d6' => 'required',
            'latency_result' => 'required',
            'latency_pass' => 'required'

        ]);

        if($v->fails())
        {
            return response()->json([
                'data' => null,
                'succes' => false,
                'message' => $v->errors()
            ], 422);
        }

        $packet = TrBaSiteQc::where('ba_id', $ba_id)->where('wo_id', $wo_id)->where('ba_site_id', $ba_site_id)->where('tipe', 'packet_loss')->first();
        $latency = TrBaSiteQc::where('ba_id', $ba_id)->where('wo_id', $wo_id)->where('ba_site_id', $ba_site_id)->where('tipe', 'latency')->first();

        if($packet == null)
        {
            return response()->json([
                'data' => null,
                'succes' => false,
                'message' => 'Packet Loss Tidak Ditemukan'
            ], 422);
        }

        if($latency == null)
        {
            return response()->json([
                'data' => null,
                'succes' => false,
                'message' => 'Latency Tidak Ditemukan'
            ], 422);
        }

        DB::beginTransaction();
        try {            

            TrBaSiteQc::where('ba_id', $ba_id)
                               ->where('wo_id', $wo_id)
                               ->where('ba_site_id', $ba_site_id)
                               ->where('tipe', 'packet_loss')
                                ->update(array(
                                    'day1' => $request->packet_loss_d1,
                                    'day2' => $request->packet_loss_d2,
                                    'day3' => $request->packet_loss_d3,
                                    'day4' => $request->packet_loss_d4,
                                    'day5' => $request->packet_loss_d5,
                                    'day6' => $request->packet_loss_d6,
                                    'result' => $request->packet_loss_result,
                                    'pass' => $request->packet_loss_pass
                                ));

            
            TrBaSiteQc::where('ba_id', $ba_id)
                            ->where('wo_id', $wo_id)
                            ->where('ba_site_id', $ba_site_id)
                            ->where('tipe', 'latency')
                                ->update(array(
                                    'day1' => $request->latency_d1,
                                    'day2' => $request->latency_d2,
                                    'day3' => $request->latency_d3,
                                    'day4' => $request->latency_d4,
                                    'day5' => $request->latency_d5,
                                    'day6' => $request->latency_d6,
                                    'result' => $request->latency_result,
                                    'pass' => $request->latency_pass
                                ));                                
            

            DB::commit();

            $ba_site = TrBaSite::with('ba')->where('ba_id', $ba_id)->where('wo_id', $wo_id)->where('ba_site_id', $ba_site_id)->first();
            $ba_site->qc = TrBaSiteQc::where('ba_id', $ba_id)->where('wo_id', $wo_id)->where('ba_site_id', $ba_site_id)->get();
    
            return (new TrBaSiteResource($ba_site))->additional([
                'success' => true,
                'message' => 'Data QC Berhasil Diupdate'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'data' => $e->getMessage(),
                'success' => true,
                'message' => 'error',
            ], 400);
        }
    }

    public function showQc($ba_id, $ba_site_id, $tipe)
    {
        try {
            $data = TrBaSiteQc::where('ba_id', $ba_id)->where('ba_site_id', $ba_site_id)->where('tipe', $tipe)->first();
            return (new TrBaSiteQcResource($data))->additional([
                'success' => true,
                'message' => 'suksess'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
               'data' => null,
                'success' => false,
                'message' => 'Data Tidak Ditemukan',
            ], 404);
        }
    }

    //lampiran

    public function storeLampiran(Request $request, $ba_id, $wo_id, $ba_site_id)
    {
        $v = Validator::make($request->all(), [
            'tipe' => 'required',
            'lampirans' => 'required'
        ]);

        if ($v->fails())
        {
            return response()->json([
                'status' => false,
                'message' => 'error',
                'data' => $v->errors()
            ], 422);
        }   

        $url_arr = array();;
        foreach ($request->file('lampirans') as $lampiran)
        {
            $url = $this->prsoesUpload($lampiran);
            array_push($url_arr, $url);
        }

        DB::beginTransaction();
        try {

            $counter = TrBaSiteLampiran::where('ba_id', $ba_id)
                                        ->where('wo_id',$wo_id)
                                        ->where('ba_site_id',$ba_site_id)
                                        ->max("id");

            foreach ($url_arr as $image_url) {
                $counter++;
                $data = new TrBaSiteLampiran();
                $data->ba_id = $ba_id;
                $data->wo_id = $wo_id;
                $data->ba_site_id = $ba_site_id;
                $data->id = $counter;
                $data->tipe = $request->tipe;
                $data->image_url = $image_url;
                $data->dibuat_oleh = Auth::user()->id;
                $data->save();
            }

            DB::commit();

            $lampiran = TrBaSiteLampiran::where('ba_id', $ba_id)->where('ba_site_id', $ba_site_id)->where('tipe', $request->tipe)->get();
    
            return (new TrBaSiteLampiranResource($lampiran))->additional([
                'success' => true,
                'message' => 'Data Lampiran Berhasil Diupdate'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'data' => $e->getMessage(),
                'success' => true,
                'message' => 'error',
            ], 400);
        }
    }

    public function showLampiran($ba_id, $ba_site_id, $id)
    {
        

        try {
            $data = TrBaSiteLampiran::where('ba_id', $ba_id)->where('ba_site_id', $ba_site_id)->where('id', $id)->first();
            return (new TrBaSiteLampiranResource($data))->additional([
                'success' => true,
                'message' => 'suksess'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
               'data' => null,
                'success' => false,
                'message' => 'Data Tidak Ditemukan',
            ], 404);
        }
    }

    public function destroyLampiran($ba_id, $wo_id, $ba_site_id, $id)
    {
        $data = TrBaSiteLampiran::where('ba_id', $ba_id)->where('wo_id', $wo_id)->where('ba_site_id', $ba_site_id)->where('id', $id)->first();

        if(!$data)
        {
            return response()->json([
                'status' => false,
                'message' => 'Data Tidak Ditemukan',
                'data' => null
            ], 404);
        }

        

        $path = public_path().'/lampirans/'.$data->image_url;
        unlink($path);

        // $whereArray = array('ba_id' => $ba_id,'wo_id' => $wo_id, 'ba_site_id' => $ba_site_id, 'id' => $id);
        // return TrBaSiteLampiran::whereArray($whereArray)->delete();

        $data = TrBaSiteLampiran::where('ba_id', $ba_id)->where('wo_id', $wo_id)->where('ba_site_id', $ba_site_id)->where('id', $id)->delete();

        

        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => $data
        ], 200);
    }

    // file berita acara (ba)

    public function fileBA($id)
    {
        $jenis_dokumen = array(
                            "Dokumen uji terima", 
                            "LV Certificate*", 
                            "QC Certificate*", 
                            "Data konfigurasi& Topology E2E", 
                            "Capture trafik oleh Telkom",
                            "Referensi Work Order"
                        );

        $data_ba = TrBa::where('id', $id)->first();
        $data_wo = TrBaWo::where('ba_id', $id)->get();  
        $dasar_permintaan = '';
        // $data_site = TrBaWo::where('ba_id', $id)->get();  
        $data_site = array();
        $count = 0;
        $total_bw = 0;
        $total_site = 0;
        foreach ($data_wo as $data) {
            $count++;
            $sites = TrBaSite::where('ba_id', $id)->where('wo_id', $data->wo_id)->get();
            $daftar_site = "";
            foreach ($sites as $site) {
                $site->qc_latency = TrBaSiteQc::where('ba_id', $id)
                                              ->where('wo_id', $data->wo_id)
                                              ->where('ba_site_id', $site->ba_site_id)
                                              ->where('tipe', 'latency')
                                              ->first();
                $site->qc_packet_loss = TrBaSiteQc::where('ba_id', $id)
                                                  ->where('wo_id', $data->wo_id)
                                                  ->where('ba_site_id', $site->ba_site_id)
                                                  ->where('tipe','packet_loss')
                                                  ->first();

                $site->lv_latency = TrBaSiteLv::where('ba_id', $id)
                                                  ->where('wo_id', $data->wo_id)
                                                  ->where('ba_site_id', $site->ba_site_id)
                                                  ->where('tipe', 'latency')
                                                  ->first();

                $site->lv_packet_loss = TrBaSiteLv::where('ba_id', $id)
                                                ->where('wo_id', $data->wo_id)
                                                ->where('ba_site_id', $site->ba_site_id)
                                                ->where('tipe','packet_loss')
                                                ->first();

                $konfigurasi = TrBaSiteLampiran::where('ba_id', $id)
                                                     ->where('wo_id', $data->wo_id)
                                                     ->where('ba_site_id', $site->ba_site_id)
                                                     ->where('tipe', 'KONFIGURASI')
                                                     ->first();
                $site->konfigurasi = $konfigurasi->image_url; //url('').'/api/file/'.$konfigurasi->image_url;
                
                $trafik = TrBaSiteLampiran::where('ba_id', $id)
                                                ->where('wo_id', $data->wo_id)
                                                ->where('ba_site_id', $site->ba_site_id)
                                                ->where('tipe', 'CAPTURE_TRAFIK')
                                                ->first();

                $site->trafik = $trafik->image_url;
                
                $topologi = TrBaSiteLampiran::where('ba_id', $id)
                                                ->where('wo_id', $data->wo_id)
                                                ->where('ba_site_id', $site->ba_site_id)
                                                ->where('tipe', 'TOPOLOGI')
                                                ->first();
                
                $site->topologi = $topologi->image_url;
                $site->dasar_order = $data->dasar_order;
                array_push($data_site, $site);
                $total_site++;
                $total_bw = $total_bw + $site->jumlah;

                $daftar_site = $daftar_site.$site->site_id;
                if ($count < count($sites)) {
                    $daftar_site = $daftar_site.', ';
                }
            }

            $dasar_permintaan = $dasar_permintaan.$data->dasar_order;
            if ($count < count($data_wo)) {
                $dasar_permintaan = $dasar_permintaan.', ';
            }

            $data_wo->daftar_site = $daftar_site;
            
        }         

        $hari = date('N', strtotime($data_ba->tgl_dokumen));
        $tgl = date('j', strtotime($data_ba->tgl_dokumen));
        $bulan = date('n', strtotime($data_ba->tgl_dokumen));
        $tahun = date('Y', strtotime($data_ba->tgl_dokumen));
        $hari = date('N', strtotime($data_ba->tgl_dokumen));

        $format_tanggal = new \stdClass();
        $format_tanggal->hari = $this->_hari[$hari];
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

        // return view('newlink',  [
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
        

        $pdf = PDF2::loadView('newlink', [
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
        return $pdf->download('berita_acara.pdf');
    }

    private function prsoesUpload($file)
    {
        $nama_file = Uuid::uuid4()->toString();
        
        // $url =  Storage::putFileAs('public/image', $file, $nama_file.'.'.$file->getClientOriginalExtension());
        $file->move('lampirans/',$nama_file.'.'.$file->getClientOriginalExtension());

        return $nama_file.'.'.$file->getClientOriginalExtension();
    }

    public function fileLampiran($name)
    {
        $storagePath = public_path().'/lampirans/'.$name;

        return response()->file($storagePath);
    }


}

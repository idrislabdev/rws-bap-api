<?php

namespace App\Http\Controllers\Transaksi;

use App\Helper\UtilityHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\TrBaResource;
use App\Http\Resources\TrWoResource;
use App\Http\Resources\TrWoSiteLvResource;
use App\Http\Resources\TrWoSiteResource;
use App\Models\TrWo;
use App\Models\TrWoSite;
use App\Models\TrWoSiteLv;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class LvController extends Controller
{

    public function index($wo_id, $wo_site_id)
    {
        $data = TrWoSiteLv::where('wo_id', $wo_id)->where('wo_site_id', $wo_site_id)->paginate();
        return TrWoSiteLvResource::collection($data)->additional([
            'success' => true,
            'message' => null
        ]);
    }

    public function store(Request $request, $wo_id, $wo_site_id)
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

        $check_packet = TrWoSiteLv::where('wo_id', $wo_id)->where('wo_site_id', $wo_site_id)->where('tipe', 'packet_loss')->first();
        $check_latency = TrWoSiteLv::where('wo_id', $wo_id)->where('wo_site_id', $wo_site_id)->where('tipe', 'latency')->first();

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

            
            $packet = new TrWoSiteLv();
            $packet->wo_id = $wo_id;
            $packet->wo_site_id = $wo_site_id;
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

            $latency = new TrWoSiteLv();
            $latency->wo_id = $wo_id;
            $latency->wo_site_id = $wo_site_id;
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

            $check_evident = UtilityHelper::checkEvident($wo_id, $wo_site_id);

            if ( $check_evident->lampiran_url != null
                && $check_evident->lv == 2 
                && $check_evident->qc == 2 
                && $check_evident->topologi > 0 
                && $check_evident->konfigurasi > 0 
                && $check_evident->capture_trafik > 0)
            {
                TrWoSite::where('wo_id', $wo_id)->where('wo_site_id', $wo_site_id)
                            ->update(array(
                                'status' => true,
                            ));

            }
            

            DB::commit();

            $lv = TrWoSiteLv::where('wo_id', $wo_id)->where('wo_site_id', $wo_site_id)->get();
    
            return (new TrWoSiteLvResource($lv))->additional([
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

    public function update(Request $request, $wo_id, $wo_site_id)
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

        $packet = TrWoSiteLv::where('wo_id', $wo_id)->where('wo_site_id', $wo_site_id)->where('tipe', 'packet_loss')->first();
        $latency = TrWoSiteLv::where('wo_id', $wo_id)->where('wo_site_id', $wo_site_id)->where('tipe', 'latency')->first();


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

            TrWoSiteLv::where('wo_id', $wo_id)
                        ->where('wo_site_id', $wo_site_id)
                        ->where('tipe', 'packet_loss')
                        ->update(array(
                            'hour1' => $request->packet_loss_h1,
                            'hour2' => $request->packet_loss_h2,
                            'hour3' => $request->packet_loss_h3,
                            'hour4' => $request->packet_loss_h4,
                            'hour5' => $request->packet_loss_h5,
                            'result' => $request->packet_loss_result,
                            'pass' => $request->packet_loss_pass
                        ));

            
            TrWoSiteLv::where('wo_id', $wo_id)
                        ->where('wo_site_id', $wo_site_id)
                        ->where('tipe', 'latency')
                            ->update(array(
                                'hour1' => $request->latency_h1,
                                'hour2' => $request->latency_h2,
                                'hour3' => $request->latency_h3,
                                'hour4' => $request->latency_h4,
                                'hour5' => $request->latency_h5,
                                'result' => $request->latency_result,
                                'pass' => $request->latency_pass
                            ));                                
            

            DB::commit();

            $lv = TrWoSiteLv::where('wo_id', $wo_id)->where('wo_site_id', $wo_site_id)->get();
    
            return (new TrWoSiteLvResource($lv))->additional([
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

    // public function showLv($ba_id, $ba_site_id, $tipe)
    // {
    //     try {
    //         $data = TrBaSiteLv::where('ba_id', $ba_id)->where('ba_site_id', $ba_site_id)->where('tipe', 'latency')->first();
    //         return (new TrBaSiteLvResource($data))->additional([
    //             'success' => true,
    //             'message' => 'suksess'
    //         ]);
    //     } catch (\Throwable $th) {
    //         return response()->json([
    //             'data' => null,
    //             'success' => false,
    //             'message' => $th,
    //         ], 404);
    //     }
    // }
}

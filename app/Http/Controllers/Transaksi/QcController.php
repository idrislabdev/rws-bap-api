<?php

namespace App\Http\Controllers\Transaksi;

use App\Helper\UtilityHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\TrBaResource;
use App\Http\Resources\TrWoResource;
use App\Http\Resources\TrWoSiteQcResource;
use App\Http\Resources\TrWoSiteResource;
use App\Models\TrWo;
use App\Models\TrWoSite;
use App\Models\TrWoSiteQc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class QcController extends Controller
{
    
    public function index($wo_id,  $wo_site_id)
    {
        $data = TrWoSiteQc::where('wo_id', $wo_id)->where('wo_site_id', $wo_site_id)->get();
        return TrWoSiteQcResource::collection($data)->additional([
            'success' => true,
            'message' => null
        ]);
    }

    public function store(Request $request, $wo_id, $wo_site_id)
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
            'latency_pass' => 'required',
            'day1_date' => 'required',
            'day2_date' => 'required',
            'day3_date' => 'required',
            'day4_date' => 'required',
            'day5_date' => 'required',
            'day6_date' => 'required',

        ]);

        if($v->fails())
        {
            return response()->json([
                'data' => null,
                'succes' => false,
                'message' => $v->errors()
            ], 422);
        }

        $check_packet = TrWoSiteQc::where('wo_id', $wo_id)->where('wo_site_id', $wo_site_id)->where('tipe', 'packet_loss')->first();
        $check_latency = TrWoSiteQc::where('wo_id', $wo_id)->where('wo_site_id', $wo_site_id)->where('tipe', 'latency')->first();


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
            
            $packet = new TrWoSiteQc();
            $packet->wo_site_id = $wo_site_id;
            $packet->wo_id = $wo_id;
            $packet->tipe = 'packet_loss';
            $packet->day1 = $request->packet_loss_d1;
            $packet->day2 = $request->packet_loss_d2;
            $packet->day3 = $request->packet_loss_d3;
            $packet->day4 = $request->packet_loss_d4;
            $packet->day5 = $request->packet_loss_d5;
            $packet->day6 = $request->packet_loss_d6;

            $packet->day1_date = $request->day1_date;
            $packet->day2_date = $request->day2_date;
            $packet->day3_date = $request->day3_date;
            $packet->day4_date = $request->day4_date;
            $packet->day5_date = $request->day5_date;
            $packet->day6_date = $request->day6_date;

            $packet->result = $request->packet_loss_result;
            $packet->pass = $request->packet_loss_pass;
            $packet->dibuat_oleh = Auth::user()->id;
            $packet->save();

            $latency = new TrWoSiteQc();
            $latency->wo_site_id = $wo_site_id;
            $latency->wo_id = $wo_id;
            $latency->tipe = 'latency';
            $latency->day1 = $request->latency_d1;
            $latency->day2 = $request->latency_d2;
            $latency->day3 = $request->latency_d3;
            $latency->day4 = $request->latency_d4;
            $latency->day5 = $request->latency_d5;
            $latency->day6 = $request->latency_d6;

            $latency->day1_date = $request->day1_date;
            $latency->day2_date = $request->day2_date;
            $latency->day3_date = $request->day3_date;
            $latency->day4_date = $request->day4_date;
            $latency->day5_date = $request->day5_date;
            $latency->day6_date = $request->day6_date;

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
                                    'progress' => true,
                                ));

            }

            DB::commit();

            $qc = TrWoSiteQc::where('wo_id', $wo_id)->where('wo_site_id', $wo_site_id)->get();
    
            return (new TrWoSiteQcResource($qc))->additional([
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

    public function update(Request $request, $wo_id, $wo_site_id)
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
            'latency_pass' => 'required',
            'day1_date' => 'required',
            'day2_date' => 'required',
            'day3_date' => 'required',
            'day4_date' => 'required',
            'day5_date' => 'required',
            'day6_date' => 'required',

        ]);

        if($v->fails())
        {
            return response()->json([
                'data' => null,
                'succes' => false,
                'message' => $v->errors()
            ], 422);
        }

        $packet = TrWoSiteQc::where('wo_id', $wo_id)->where('wo_site_id', $wo_site_id)->where('tipe', 'packet_loss')->first();
        $latency = TrWoSiteQc::where('wo_id', $wo_id)->where('wo_site_id', $wo_site_id)->where('tipe', 'latency')->first();

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

            TrWoSiteQc::where('wo_id', $wo_id)
                        ->where('wo_site_id', $wo_site_id)
                        ->where('tipe', 'packet_loss')
                        ->update(array(
                            'day1' => $request->packet_loss_d1,
                            'day2' => $request->packet_loss_d2,
                            'day3' => $request->packet_loss_d3,
                            'day4' => $request->packet_loss_d4,
                            'day5' => $request->packet_loss_d5,
                            'day6' => $request->packet_loss_d6,
                            'result' => $request->packet_loss_result,
                            'pass' => $request->packet_loss_pass,
                            'day1_date' => $request->day1_date,
                            'day2_date' => $request->day2_date,
                            'day3_date' => $request->day3_date,
                            'day4_date' => $request->day4_date,
                            'day5_date' => $request->day5_date,
                            'day6_date' => $request->day6_date,
                        ));

            
            TrWoSiteQc::where('wo_id', $wo_id)
                        ->where('wo_site_id', $wo_site_id)
                        ->where('tipe', 'latency')
                            ->update(array(
                                'day1' => $request->latency_d1,
                                'day2' => $request->latency_d2,
                                'day3' => $request->latency_d3,
                                'day4' => $request->latency_d4,
                                'day5' => $request->latency_d5,
                                'day6' => $request->latency_d6,
                                'result' => $request->latency_result,
                                'pass' => $request->latency_pass,
                                'day1_date' => $request->day1_date,
                                'day2_date' => $request->day2_date,
                                'day3_date' => $request->day3_date,
                                'day4_date' => $request->day4_date,
                                'day5_date' => $request->day5_date,
                                'day6_date' => $request->day6_date,
                            ));                                
            

            DB::commit();

            $qc = TrWoSiteQc::where('wo_id', $wo_id)->where('wo_site_id', $wo_site_id)->get();
    
            return (new TrWoSiteQcResource($qc))->additional([
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
}

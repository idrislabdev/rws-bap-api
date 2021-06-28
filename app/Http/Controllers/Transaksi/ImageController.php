<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Http\Resources\TrBaResource;
use App\Http\Resources\TrWoResource;
use App\Http\Resources\TrWoSiteImageResource;
use App\Http\Resources\TrWoSiteLvResource;
use App\Http\Resources\TrWoSiteResource;
use App\Models\TrWo;
use App\Models\TrWoSite;
use App\Models\TrWoSiteImage;
use App\Models\TrWoSiteLv;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Helper\UtilityHelper;
use Ramsey\Uuid\Uuid;

class ImageController extends Controller
{
    public function index($wo_id, $wo_site_id)
    {
        $data = TrWoSiteImage::where('wo_id', $wo_id)->where('wo_site_id', $wo_site_id)->paginate();
        return TrWoSiteImageResource::collection($data)->additional([
            'success' => true,
            'message' => null
        ]);
    }

    public function store(Request $request, $wo_id, $wo_site_id)
    {
        $v = Validator::make($request->all(), [
            'tipe' => 'in:KONFIGURASI,TOPOLOGI,CAPTURE_TRAFIK,LV,QC,NODE_1,NODE_2',
            'images' => 'required'
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
        foreach ($request->file('images') as $image)
        {
            $url = $this->prosesUpload($image);
            array_push($url_arr, $url);
        }

        DB::beginTransaction();
        try {

            $counter = TrWoSiteImage::where('wo_id',$wo_id)
                                    ->where('wo_site_id',$wo_site_id)
                                    ->max("id");

            foreach ($url_arr as $image_url) {
                $counter++;
                $data = new TrWoSiteImage();
                $data->wo_id = $wo_id;
                $data->wo_site_id = $wo_site_id;
                $data->id = $counter;
                $data->tipe = $request->tipe;
                $data->image_url = $image_url;
                $data->dibuat_oleh = Auth::user()->id;
                $data->save();
            }

            $check_evident = UtilityHelper::checkEvident($wo_id, $wo_site_id);

            if ($check_evident->tipe_ba == 'NEW_LINK') {
                if ( $check_evident->lampiran_url != null
                && $check_evident->lv == 2 
                && $check_evident->qc == 2 
                && $check_evident->lv_image > 0 
                && $check_evident->qc_image > 0 
                && $check_evident->topologi > 0 
                && $check_evident->konfigurasi > 0 
                && $check_evident->capture_trafik > 0)
                {
                    TrWoSite::where('wo_id', $wo_id)->where('wo_site_id', $wo_site_id)
                                ->update(array(
                                    'progress' => true,
                                ));

                }
            } else if ($check_evident->tipe_ba == 'UPGRADE') {
                if ( $check_evident->lampiran_url != null
                && $check_evident->topologi > 0 
                && $check_evident->konfigurasi > 0 
                && $check_evident->capture_trafik > 0)
                {
                    TrWoSite::where('wo_id', $wo_id)->where('wo_site_id', $wo_site_id)
                                ->update(array(
                                    'progress' => true,
                                ));

                }
            } else if ($check_evident->tipe_ba == 'DUAL_HOMING') {
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
            }

            DB::commit();

            $image = TrWoSiteImage::where('wo_site_id', $wo_site_id)->where('tipe', $request->tipe)->get();
    
            return (new TrWoSiteImageResource($image))->additional([
                'success' => true,
                'message' => 'Data image Berhasil Diupdate',
                'data' => $check_evident
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

    public function show($wo_id, $wo_site_id, $id)
    {
        

        try {
            $data = TrWoSiteImage::where('wo_id', $wo_id)->where('wo_site_id', $wo_site_id)->where('id', $id)->first();
            return (new TrWoSiteImageResource($data))->additional([
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

    public function destroy($wo_id, $wo_site_id, $id)
    {
        $data = TrWoSiteImage::where('wo_id', $wo_id)->where('wo_site_id', $wo_site_id)->where('id', $id)->first();

        if(!$data)
        {
            return response()->json([
                'status' => false,
                'message' => 'Data Tidak Ditemukan',
                'data' => null
            ], 404);
        }

        DB::beginTransaction();
        try {

            $path = public_path().'/lampirans/'.$data->image_url;
            unlink($path);

            // $whereArray = array('ba_id' => $ba_id,'wo_id' => $wo_id, 'wo_site_id' => $wo_site_id, 'id' => $id);
            // return TrWoSiteImage::whereArray($whereArray)->delete();

            $data = TrWoSiteImage::where('wo_id', $wo_id)->where('wo_site_id', $wo_site_id)->where('id', $id)->delete();

            TrWoSite::where('wo_id', $wo_id)->where('wo_site_id', $wo_site_id)->whereNull('ba_id')
            ->update(array(
                'progress' => false,
            )); 

            DB::commit();

            return response()->json([
                'progress' => true,
                'message' => 'success',
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

    private function prosesUpload($file)
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

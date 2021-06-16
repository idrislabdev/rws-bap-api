<?php

namespace App\Http\Controllers\Transaksi;

use App\Helper\UtilityHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\TrBaResource;
use App\Http\Resources\TrWoResource;
use App\Http\Resources\TrWoSiteResource;
use App\Models\TrWo;
use App\Models\TrWoSite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class WoController extends Controller
{
    public function update(Request $request, $id)
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

        $data = TrWo::findOrFail($id);

        if($data == null)
        {
            return response()->json([
                'data' => null,
                'succes' => false,
                'message' => 'Data Tidak Ditemukan'
            ], 422);
        }

        $url = $this->prosesUpload($request->file('lampiran'));

        DB::beginTransaction();
        try {

           
            $data->lampiran_url = $url;
            $data->save();
            
            $sites = TrWoSite::where('wo_id', $id)->get();
            foreach ($sites as $site) {
                $check_evident = UtilityHelper::checkEvident($site['wo_id'], $site['wo_site_id']);

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
                        TrWoSite::where('wo_id',  $site['wo_id'])->where('wo_site_id', $site['wo_site_id'])
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
                        TrWoSite::where('wo_id',  $site['wo_id'])->where('wo_site_id', $site['wo_site_id'])
                            ->update(array(
                                'progress' => true,
                            ));

                    }
                } else if ($check_evident->tipe_ba == 'DUAL_HOMING') {
                    if ( $check_evident->lampiran_url != null
                    && $check_evident->topologi == 1 
                    && $check_evident->konfigurasi  == 2
                    && $check_evident->pr_dual_homing == 1 )
                    {
                        TrWoSite::where('wo_id',  $site['wo_id'])->where('wo_site_id', $site['wo_site_id'])
                            ->update(array(
                                'progress' => true,
                            ));

                    }
                }
            }

            
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

    public function show($id)
    {
        try {
            $data = TrWo::findOrFail($id);
            return (new TrWoResource($data))->additional([
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

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            try {
    
                $data = TrWo::findOrFail($id);
            
                $path = public_path().'/lampirans/'.$data->lampiran_url;
                unlink($path);
    
                $data->lampiran_url = null;
                $data->save();
    
                TrWoSite::where('wo_id')->where('wo_site_id')
                ->update(array(
                    'progress' => false,
                )); 

                DB::commit();

                
                return (new TrWoResource($data))->additional([
                    'success' => true,
                    'message' => 'suksess'
                ]);
    
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json([
                    'data' => $e->getMessage(),
                    'success' => true,
                    'message' => 'error',
                ], 400);
            }
        } catch (\Throwable $th) {
            return response()->json([
               'data' => null,
                'success' => false,
                'message' => 'Data Tidak Ditemukan',
            ], 404);
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

<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Resources\MaPeranResource;
use App\Models\MaHakAkses;
use App\Models\MaPeran;
use App\Models\MaPeranDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Validator;

class PeranController extends Controller
{
    public function index()
    {
        $data = new MaPeran();
        if (isset($_GET['page'])) {

            if (isset($_GET['q'])) {
                $q = $_GET['q'];
                $data = $data->whereRaw("(nama like '%$q%')");
            }

            $data = $data->orderBy('nama')->paginate(25)->onEachSide(5);
        } else {
            $data = $data->orderBy('nama')->get();
        }

        return MaPeranResource::collection($data)->additional([
            'success' => true,
            'message' => null
        ]);
    }

    public function store(Request $request)
    {
        $v = Validator::make($request->all(), [
            'nama'          => 'required|unique:ma_perans',
            'hak_akseses'   => 'required'
        ]);

        if ($v->fails()) {
            return response()->json([
                'data' => null,
                'succes' => false,
                'message' => $v->errors()
            ], 422);
        }

        $pk_id = Uuid::uuid4()->toString();
        DB::beginTransaction();
   
        try {
            $data               = new MaPeran();
            $data->id           = $pk_id;
            $data->nama         = $request->nama;
            $data->save();

            foreach ($request->hak_akseses as $key => $item) {
                $hak_akses = MaHakAkses::where('nama', $item)->first();

                if ($hak_akses) {
                    $peran_detail               = new MaPeranDetail();
                    $peran_detail->peran_id     = $pk_id;    
                    $peran_detail->hak_akses_id = $hak_akses->id;

                    $peran_detail->save();
                }
            }

            DB::commit();

            return (new MaperanResource($data))->additional([
                'success' => true,
                'message' => 'Data Peran Telah Tersimpan'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'data' => null,
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $data = MaPeran::with('detail.hakAkses')->find($id);
            if ($data) {
                return (new MaperanResource($data))->additional([
                    'success' => true,
                    'message' => 'suksess'
                ]);
            } else {
                return response()->json([
                    'data' => null,
                    'success' => false,
                    'message' => 'Data Peran Tidak Ditemukan'
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'data' => null,
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $data = MaPeran::findOrFail($id);
            if ($data) {
                if ($request->nama != null || $request->nama != "")
                    $data->nama = $request->nama;

                $data->save();

                MaPeranDetail::where('peran_id', $data->id)->delete();

                foreach ($request->hak_akseses as $key => $item) {
                    $hak_akses = MaHakAkses::where('nama', $item)->first();
    
                    if ($hak_akses) {
                        $check = MaPeranDetail::where('peran_id', $data->id)->where('hak_akses_id', $hak_akses->id)->first();
                        $peran_detail               = new MaPeranDetail();
                        $peran_detail->peran_id     = $data->id;    
                        $peran_detail->hak_akses_id = $hak_akses->id;
    
                        $peran_detail->save();
                    }
                }

                DB::commit();

                return (new MaperanResource($data))->additional([
                    'success' => true,
                    'message' => 'Data Peran Berhasil Dirubah'
                ]);
                
            } else {
                return response()->json([
                    'data' => null,
                    'success' => false,
                    'message' => 'Data Peran Tidak Ditemukan'
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'data' => null,
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }


    public function destroy($id)
    {
        $data = MaPeran::find($id);
        if (!$data) {
            return response()->json([
                'data' => null,
                'success' => false,
                'message' => 'Data Peran Tidak Ditemukan'
            ], 404);
        }

        try {
            $data->delete();

            return response()->json([
                'status' => true,
                'message' => 'Data Peran Berhasil Dihapus',
                'data' => $data
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'data' => null,
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function getAll()
    {
        $data = new MaPeran();
        $data = $data->orderBy('nama')->get();
        
        return MaPeranResource::collection($data)->additional([
            'success' => true,
            'message' => null
        ]);
    }

}

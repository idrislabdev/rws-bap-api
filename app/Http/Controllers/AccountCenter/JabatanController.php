<?php

namespace App\Http\Controllers\AccountCenter;

use App\Http\Controllers\Controller;
use App\Http\Resources\MaJabatanResource;
use App\Models\MaJabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class JabatanController extends Controller
{
    public function index()
    {
        $data = new MaJabatan();
        if (isset($_GET['page'])) {

            if (isset($_GET['q'])) {
                $q = $_GET['q'];
                $data = $data->whereRaw("(nama like '%$q%')");
            }

            $data = $data->orderBy('nama')->paginate(25)->onEachSide(5);
        } else {
            $data = $data->get();
        }

        return MaJabatanResource::collection($data)->additional([
            'success' => true,
            'message' => null
        ]);
    }

    public function store(Request $request)
    {
        $v = Validator::make($request->all(), [
            'nama'          => 'required',
            'starclick_ncx' => 'required',
            'ncx_cons'      => 'required'
        ]);

        if ($v->fails()) {
            return response()->json([
                'data' => null,
                'succes' => false,
                'message' => $v->errors()
            ], 422);
        }

   
        try {
            $data = new MaJabatan();
            $data->id = Uuid::uuid4()->toString();
            $data->nama = $request->nama;
            // $data->ncx_cons = $request->ncx_cons;
            // $data->starclick_ncx = $request->starclick_ncx;
            $data->save();

            return (new MaJabatanResource($data))->additional([
                'success' => true,
                'message' => 'Data Baru Telah Dibuat'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'data' => null,
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function update($id, Request $request)
    {
        $v = Validator::make($request->all(), [
            'nama'          => 'required',
            'starclick_ncx' => 'required',
            'ncx_cons'      => 'required'
        ]);

        if ($v->fails()) {
            return response()->json([
                'data' => null,
                'succes' => false,
                'message' => $v->errors()
            ], 422);
        }

   
        try {

            $data = MaJabatan::findOrFail($id);

            MaJabatan::where('id', $id)
            ->update(array(
                'nama' => $request->nama,
                'ncx_cons' => $request->ncx_cons,
                'starclick_ncx' => $request->starclick_ncx,
            ));

            return (new MaJabatanResource($data))->additional([
                'success' => true,
                'message' => 'Data Baru Telah Dibuat'
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
            $data = MaJabatan::find($id);
            if ($data) {
                return (new MaJabatanResource($data))->additional([
                    'success' => true,
                    'message' => 'suksess'
                ]);
            } else {
                return response()->json([
                    'data' => null,
                    'success' => false,
                    'message' => 'Data Jabatan Tidak Ditemukan'
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

    public function destroy($id)
    {
        // abort(404);
        $data = MaJabatan::find($id);

        if (!$data) {
            return response()->json([
                'status' => false,
                'message' => 'Data Tidak Ditemukan',
                'data' => null
            ], 404);
        }

        $data->delete();

        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => $data
        ], 200);
    }
}

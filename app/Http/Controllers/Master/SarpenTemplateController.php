<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Resources\MaSiteResource;
use App\Models\MaSite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class SarpenTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = new MaSite;
        if (isset($_GET['page'])) {

            if (isset($_GET['q'])) {
                $q = $_GET['q'];
                $data = $data->whereRaw("(nama like '%$q%' or group like '%$q%')");
            }

            $data = $data->orderBy('nama')->paginate(25)->onEachSide(5);
        } else {
            $data = $data->orderBy('nama')->get();
        }

        return MaSiteResource::collection($data)->additional([
            'success' => true,
            'message' => null
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort(404);
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
            'nama' => 'required',
            'group' => 'in:TELKOM,OTHER',
            'sto_site' => 'STO,SITE,NO_ORDER',
            'is_no_dokumen_klien' => 'required',
        ]);

        if ($v->fails()) {
            return response()->json([
                'data' => null,
                'succes' => false,
                'message' => $v->errors()
            ], 422);
        }

        try {
            $data = new MaSite();
            $data->id = Uuid::uuid4()->toString();
            $data->nama = $request->nama;
            $data->group = $request->group;
            $data->sto_site = $request->sto_site;
            $data->is_no_dokumen_klien = $request->is_no_dokumen_klien;
            if ($request->has('tower')) {
                $data->tower = json_encode($request->tower, JSON_PRETTY_PRINT);
            }
            if ($request->has('rack')) {
                $data->rack = json_encode($request->rack, JSON_PRETTY_PRINT);
            }
            if ($request->has('tower')) {
                $data->tower = json_encode($request->tower, JSON_PRETTY_PRINT);
            }
            if ($request->has('ruangan')) {
                $data->ruangan = json_encode($request->ruangan, JSON_PRETTY_PRINT);
            }
            if ($request->has('catu_daya_mcb')) {
                $data->catu_daya_mcb = json_encode($request->catu_daya_mcb, JSON_PRETTY_PRINT);
            }
            if ($request->has('catu_daya_genset')) {
                $data->catu_daya_genset = json_encode($request->catu_daya_genset, JSON_PRETTY_PRINT);
            }
            if ($request->has('service')) {
                $data->service = json_encode($request->service, JSON_PRETTY_PRINT);
            }
            if ($request->has('akses')) {
                $data->akses = json_encode($request->akses, JSON_PRETTY_PRINT);
            }
           
            $data->save();

            return (new MaSiteResource($data))->additional([
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $data = MaSite::findOrFail($id);
            return (new MaSiteResource($data))->additional([
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

        try {
            $data = MaSite::findOrFail($id);

            if ($request->has('tower')) {
                $data->tower = json_encode($request->tower, JSON_PRETTY_PRINT);
            }
            if ($request->has('rack')) {
                $data->rack = json_encode($request->rack, JSON_PRETTY_PRINT);
            }
            if ($request->has('tower')) {
                $data->tower = json_encode($request->tower, JSON_PRETTY_PRINT);
            }
            if ($request->has('ruangan')) {
                $data->ruangan = json_encode($request->ruangan, JSON_PRETTY_PRINT);
            }
            if ($request->has('catu_daya_mcb')) {
                $data->catu_daya_mcb = json_encode($request->catu_daya_mcb, JSON_PRETTY_PRINT);
            }
            if ($request->has('catu_daya_genset')) {
                $data->catu_daya_genset = json_encode($request->catu_daya_genset, JSON_PRETTY_PRINT);
            }
            if ($request->has('service')) {
                $data->service = json_encode($request->service, JSON_PRETTY_PRINT);
            }
            if ($request->has('akses')) {
                $data->akses = json_encode($request->akses, JSON_PRETTY_PRINT);
            }


            $data->save();
            return (new MaSiteResource($data))->additional([
                'success' => true,
                'message' => 'Data Berhasil Dirubah'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'data' => null,
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // abort(404);
        $data = MaSite::find($id);

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

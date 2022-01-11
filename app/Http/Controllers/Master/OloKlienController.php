<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Resources\MaOloKlienResource;
use App\Models\MaOloKlien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class OloKlienController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = new MaOloKlien;
        if (isset($_GET['page'])) {

            if (isset($_GET['q'])) {
                $q = $_GET['q'];
                $data = $data->whereRaw("(nama_perusahaan like '%$q%' or nama_penanggung_jawab like '%$q%')");
            }

            $data = $data->orderBy('nama_perusahaan')->paginate(25)->onEachSide(5);
        } else {
            $data = $data->get();
        }

        return MaOloKlienResource::collection($data)->additional([
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
            'nama_perusahaan' => 'required',
            'alamat_perusahaan' => 'required',
            'nama_penanggung_jawab' => 'required',
            'jabatan_penanggung_jawab' => 'required',

        ]);

        if ($v->fails()) {
            return response()->json([
                'data' => null,
                'succes' => false,
                'message' => $v->errors()
            ], 422);
        }

        try {
            $data = new MaOloKlien();
            $data->id = Uuid::uuid4()->toString();
            $data->nama_perusahaan = $request->nama_perusahaan;
            $data->alamat_perusahaan = $request->alamat_perusahaan;
            $data->nama_penanggung_jawab = $request->nama_penanggung_jawab;
            $data->jabatan_penanggung_jawab = $request->jabatan_penanggung_jawab;

            $data->save();

            return (new MaOloKlienResource($data))->additional([
                'success' => true,
                'message' => 'Data Baru Telah Dibuat'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'data' => null,
                'success' => false,
                'message' => $th->getMessage(),
            ], 404);
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
            $data = MaOloKlien::findOrFail($id);
            return (new MaOloKlienResource($data))->additional([
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
            $data = MaOloKlien::findOrFail($id);

            if ($request->nama_perusahaan != null || $request->nama_perusahaan != "")
                $data->nama_perusahaan = $request->nama_perusahaan;

            if ($request->alamat_perusahaan != null || $request->alamat_perusahaan != "")
                $data->alamat_perusahaan = $request->alamat_perusahaan;

            if ($request->nama_penanggung_jawab != null || $request->nama_penanggung_jawab != "")
                $data->nama_penanggung_jawab = $request->nama_penanggung_jawab;

            if ($request->jabatan_penaggung_jawab != null || $request->jabatan_penaggung_jawab != "")
                $data->jabatan_penaggung_jawab = $request->jabatan_penaggung_jawab;


            $data->save();
            return (new MaOloKlienResource($data))->additional([
                'success' => true,
                'message' => 'Data Berhasil Dirubah'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'data' => null,
                'success' => false,
                'message' => $th->getMessage(),
            ], 404);
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
        $data = MaOloKlien::find($id);

        if(!$data)
        {
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

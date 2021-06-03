<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Resources\MaPengaturanResource;
use App\Models\MaPengaturan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class PengaturanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = MaPengaturan::paginate();
        return MaPengaturanResource::collection(($data))->additional([
            'success' => true,
            'message' => null,
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
            'nama' => 'required|unique:ma_pengaturans,nama',
        ]);

        if($v->fails())
        {
            return response()->json([
                'data' => null,
                'succes' => false,
                'message' => $v->errors()
            ], 422);
        }

        try {
            $data = new MaPengaturan();
            $data->id = Uuid::uuid4()->toString();
            $data->nama = strtoupper($request->nama);
            $data->nilai = ($request->nilai != null || $request->nilai != "") ? $request->nilai : "" ;
            $data->detail_nilai = ($request->detail_nilai != null || $request->detail_nilai != "") ? $request->detail_nilai : "" ;
            $data->save();

            return (new MaPengaturanResource($data))->additional([
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
            $data = MaPengaturan::findOrFail($id);
            return (new MaPengaturanResource($data))->additional([
                'success' => true,
                'message' => null
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
            $data = MaPengaturan::findOrFail($id);
            
            if ($request->nilai != null || $request->nilai != "")
                $data->nilai = $request->nilai;

            if ($request->detail_nilai != null || $request->detail_nilai != "")
                $data->detail_nilai = $request->detail_nilai;


            $data->save();

            return  (new MaPengaturanResource($data))->additional([
                'success' => true,
                'message' => 'Data Berhasil Dirubah'
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        abort(404);
    }
}

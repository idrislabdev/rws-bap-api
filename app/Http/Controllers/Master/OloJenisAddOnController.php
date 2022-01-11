<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Resources\MaOloJenisAddOnResource;
use App\Models\MaOloJenisAddOn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class OloJenisAddOnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = new MaOloJenisAddOn;
        if (isset($_GET['page'])) {

            if (isset($_GET['q'])) {
                $q = $_GET['q'];
                $data = $data->whereRaw("(nama_add_on like '%$q%' or satuan like '%$q%')");
            }

            $data = $data->orderBy('nama_add_on')->paginate(25)->onEachSide(5);
        } else {
            $data = $data->get();
        }

        return MaOloJenisAddOnResource::collection($data)->additional([
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
            'nama_add_on' => 'required|unique:ma_olo_jenis_add_ons,nama_add_on',
            'satuan' => 'required'
        ]);

        if ($v->fails()) {
            return response()->json([
                'data' => null,
                'succes' => false,
                'message' => $v->errors()
            ], 422);
        }

        try {
            $data = new MaOloJenisAddOn();
            $data->id = Uuid::uuid4()->toString();
            $data->nama_add_on = $request->nama_add_on;
            $data->satuan = $request->satuan;
            $data->save();

            return (new MaOloJenisAddOnResource($data))->additional([
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
            $data = MaOloJenisAddOn::findOrFail($id);
            return (new MaOloJenisAddOnResource($data))->additional([
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
            $data = MaOloJenisAddOn::findOrFail($id);

            if ($request->nama_add_on != null || $request->nama_add_on != "")
                $data->nama_add_on = $request->nama_add_on;

            if ($request->satuan != null || $request->satuan != "")
                $data->satuan = $request->satuan;


            $data->save();
            return (new MaOloJenisAddOnResource($data))->additional([
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
        $data = MaOloJenisAddOn::find($id);

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

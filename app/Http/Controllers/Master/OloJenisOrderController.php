<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Resources\MaOloJenisOrderResource;
use App\Models\MaOloJenisOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class OloJenisOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = new MaOloJenisOrder;
        if (isset($_GET['page'])) {

            if (isset($_GET['q'])) {
                $q = $_GET['q'];
                $data = $data->whereRaw("(nama_jenis_order like '%$q%')");
            }

            $data = $data->orderBy('nama_jenis_order')->paginate(25)->onEachSide(5);
        } else {
            $data = $data->get();
        }

        return MaOloJenisOrderResource::collection($data)->additional([
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
            'nama_jenis_order' => 'required|unique:ma_olo_jenis_orders,nama_jenis_order',
        ]);

        if ($v->fails()) {
            return response()->json([
                'data' => null,
                'succes' => false,
                'message' => $v->errors()
            ], 422);
        }

        try {
            $data = new MaOloJenisOrder();
            $data->id = Uuid::uuid4()->toString();
            $data->nama_jenis_order = $request->nama_jenis_order;
            $data->save();

            return (new MaOloJenisOrderResource($data))->additional([
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
            $data = MaOloJenisOrder::findOrFail($id);
            return (new MaOloJenisOrderResource($data))->additional([
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
            $data = MaOloJenisOrder::findOrFail($id);

            if ($request->nama_jenis_order != null || $request->nama_jenis_order != "")
                $data->nama_jenis_order = $request->nama_jenis_order;

            $data->save();
            return (new MaOloJenisOrderResource($data))->additional([
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
        $data = MaOloJenisOrder::find($id);

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

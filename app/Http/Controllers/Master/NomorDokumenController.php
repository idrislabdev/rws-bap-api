<?php

namespace App\Http\Controllers\Master;

use App\Helper\UtilityHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\MaNomorDokumenResource;
use App\Models\MaNomorDokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class NomorDokumenController extends Controller
{

    public function index()
    {
        $data = MaNomorDokumen::paginate();
        return MaNomorDokumenResource::collection($data)->additional([
            'success' => true,
            'message' => null
        ]);
    }

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
            'tipe' => 'in:NEW_LINK,DUAL_HOMING,COMBAT_TEMPORARY,DISMANTLE,UPGRADE,OLO_BAUT, OLO_BAST',
        ]);

        try {

            $data = new MaNomorDokumen();
            $data->id = Uuid::uuid4()->toString();
            $data->no_dokumen = UtilityHelper::checkNomorDokumen();
            $data->tipe_dokumen = $request->tipe_dokumen;
            $data->tgl_dokumen = date('Y-m-d');
            $data->save();

            return (new MaNomorDokumenResource($data))->additional([
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

    public function show($id)
    {
        try {
            $data = MaNomorDokumen::findOrFail($id);
            return (new MaNomorDokumenResource($data))->additional([
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


    public function check()
    {
        $no_dokumen = UtilityHelper::checkNomorDokumen();
        return response()->json([
            'data' => $no_dokumen,
            'success' => true,
            'message' => null,
        ], 200);
    }
}

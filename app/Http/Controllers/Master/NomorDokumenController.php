<?php

namespace App\Http\Controllers\Master;

use App\Helper\UtilityHelper;
use App\Http\Controllers\CNOP\Transaksi\DualHomingController;
use App\Http\Controllers\CNOP\Transaksi\NewLinkController;
use App\Http\Controllers\CNOP\Transaksi\UpgradeController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\OLO\Transaksi\BeritaAcaraController;
use App\Http\Resources\MaNomorDokumenResource;
use App\Models\MaNomorDokumen;
use App\Models\TrBa;
use App\Models\TrOloBa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class NomorDokumenController extends Controller
{

    public function index()
    {
        $data = new MaNomorDokumen;
        if (isset($_GET['page'])) {

            if (isset($_GET['q'])) {
                $q = $_GET['q'];
                $data = $data->whereRaw("(no_dokumen like '%$q%' or tipe_dokumen like '%$q%')");
            }

            $data = $data->orderByDesc('created_at')->paginate(25)->onEachSide(5);
        } else {
            $data = $data->get();
        }

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
            if ($request->tipe_dokumen != 'PKS') {
                $data->no_dokumen = UtilityHelper::checkNomorDokumen();
            } else {
                $data->no_dokumen = UtilityHelper::checkNomorDokumenPKS();
            }
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

    public function downloadDokumen()
    {
        $tipe = $_GET['tipe'];
        $nomor_dokumen = $_GET['nomor_dokumen'];
        if ($tipe == 'OLO_BAUT') {

            $data = TrOloBa::where('no_dokumen_baut', $nomor_dokumen)->first();
            $baut = new BeritaAcaraController();
            return $baut->fileBA($data->id, 'baut');

        } else if ($tipe == 'OLO_BAST') {

            $data = TrOloBa::where('no_dokumen_bast', $nomor_dokumen)->first();
            $baut = new BeritaAcaraController();
            return $baut->fileBA($data->id, 'bast');
            
        } else if ($tipe == 'NEW_LINK') {

            $data = TrBa::where('no_dokumen', $nomor_dokumen)->first();
            $ba = new NewLinkController();
            return $ba->downloadBA($data->id);

        } else if ($tipe == 'DUAL_HOMING') {

            $data = TrBa::where('no_dokumen', $nomor_dokumen)->first();
            $ba = new DualHomingController();
            return $ba->downloadBA($data->id);

        } else if ($tipe == 'UPGRADE') {

            $data = TrBa::where('no_dokumen', $nomor_dokumen)->first();
            $ba = new UpgradeController();
            return $ba->downloadBA($data->id);
        }
    }

    
}

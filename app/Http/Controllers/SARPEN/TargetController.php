<?php

namespace App\Http\Controllers\SARPEN;

use App\Http\Controllers\Controller;
use App\Http\Resources\TrBaSarpenResource;
use App\Http\Resources\TrBaSarpenTargetResource;
use App\Http\Resources\TrbaSarpenTargetWitelDetailResource;
use App\Models\TrBaSarpen;
use App\Models\TrBaSarpenTarget;
use App\Models\TrBaSarpenTargetWitel;
use App\Models\TrBaSarpenTargetWitelDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Mockery\Undefined;
use Ramsey\Uuid\Uuid;

class TargetController extends Controller
{
    private $_witels = ['Denpasar','Jember','Kediri','Kupang','Madiun','Madura','Malang','Mataram','Pasuruan','Sidoarjo','Singaraja','Surabaya Selatan','Surabaya Utara'];
    
    public function index()
    {
        $data = TrBaSarpenTarget::with(['witels' => function ($query) {
            $query->withCount(['details','realisasi']);

            // $query->withCount([
            //     'details' => function ($q_detail) { $q_detail->whereNull('no_dokumen'); },
            //     'realisasi' => function ($q_detail) { $q_detail->whereNotNull('no_dokumen'); }
            // ]);
        }]);
        if (isset($_GET['page'])) {

            if (isset($_GET['q'])) {
                $q = $_GET['q'];
                $data = $data->whereRaw("(nama_project like '%$q%')");
            }

            $data = $data->orderBy('nama_project')->paginate(25)->onEachSide(5);
        } else {
            $data = $data->orderBy('nama_project')->get();
        }

        return TrBaSarpenResource::collection($data)->additional([
            'success' => true,
            'message' => null
        ]);
    }
    
    public function store(Request $request)
    {
        $v = Validator::make($request->all(), [
            'nama_project' => 'required',
            'tanggal_mulai' => 'required',
            'tanggal_berakhir' => 'required',
            'witels' => 'required'
        ]);

        if ($v->fails()) {
            return response()->json([
                'data' => null,
                'succes' => false,
                'message' => $v->errors()
            ], 422);
        }

        if ($request->tanggal_mulai > $request->tanggal_berakhir)
        {
            return response()->json([
                'status' => false,
                'message' => 'Tanggal berakhir harus lebih besar dari tanggal mulai',
                'data' => null
            ], 422);
        }

        DB::beginTransaction();

        try {
            $id = Uuid::uuid4()->toString();
            $target = new TrBaSarpenTarget();
            $target->id = $id;
            $target->nama_project = $request->nama_project;
            $target->tanggal_mulai = $request->tanggal_mulai;
            $target->tanggal_berakhir = $request->tanggal_berakhir;
            $target->status = 'active';
            $target->save();

            $witels = $request->witels;
            $count = 1;
            foreach ($witels as $witel) {
                $target_witel = new TrBaSarpenTargetWitel();
                $target_witel->sarpen_target_id = $id;
                $target_witel->no = $count;
                $target_witel->witel = $witel['witel'];
                $target_witel->kota = $witel['kota'];
                $target_witel->tsel_regional = $witel['tsel_regional'];
                $target_witel->telkom_regional = $witel['telkom_regional'];
                $target_witel->save();

                $details = $request->details;

                $counter = 1;
                foreach($details as $detail) {
                    if ($detail['witel'] == $witel['witel']) {
                        $witel_detail = new TrBaSarpenTargetWitelDetail();
                        $witel_detail->sarpen_target_detail_id = $id;
                        $witel_detail->detail_no = $count;
                        $witel_detail->no = $counter;
                        $witel_detail->tipe = $detail['tipe'];
                        $witel_detail->kode = $detail['kode'];
                        $witel_detail->alamat = $detail['alamat'] ?? null;
                        $witel_detail->save();
                        $counter++;
                    }
                }
                $count++;
            }

            DB::commit();

            return (new TrBaSarpenTargetResource($target))->additional([
                'success' => true,
                'message' => 'Data Baru Telah Dibuat'
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'data' => $th->getMessage(),
                'success' => true,
                'message' => 'error',
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $data = TrBaSarpenTarget::with('witels.details')->findOrFail($id);
            return (new TrBaSarpenTargetResource($data))->additional([
                'success' => true,
                'message' => 'suksess'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'data' => null,
                'success' => false,
                'message' => $th->getMessage(),
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {

        try {
            $data = TrBaSarpenTarget::findOrFail($id);

            if ($request->status != null || $request->status != "")
                $data->status = $request->status;

            if ($request->tanggal_mulai != null || $request->tanggal_mulai != "")
                $data->tanggal_mulai = $request->tanggal_mulai;
            
            if ($request->tanggal_berakhir != null || $request->tanggal_berakhir != "")
                $data->tanggal_berakhir = $request->tanggal_berakhir;

            $data->save();
            return (new TrBaSarpenTargetWitel($data))->additional([
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

    public function destroy($id)
    {
        // abort(404);
        $data = TrBaSarpenTarget::find($id);

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

    public function bulkUpload(Request $request, $id)
    {
        $v = Validator::make($request->all(), [
            'details' => 'required'
        ]);

        if ($v->fails()) {
            return response()->json([
                'data' => null,
                'succes' => false,
                'message' => $v->errors()
            ], 422);
        }
        
        DB::beginTransaction();
        try {

            $count = 1;
            $details = $request->details;
            foreach($details as $detail) {
                $witel = TrBaSarpenTargetWitel::where('sarpen_target_id', $id)->where('witel', $detail['witel'])->first();
                $counter = TrBaSarpenTargetWitelDetail::where('sarpen_target_detail_id', $id)->where('detail_no', $witel->no)->max("no");

                $witel_detail = new TrBaSarpenTargetWitelDetail();
                $witel_detail->sarpen_target_detail_id = $id;
                $witel_detail->detail_no = $witel->no;
                $witel_detail->no = $counter+1;
                $witel_detail->tipe = $detail['tipe'];
                $witel_detail->kode = $detail['kode'];
                $witel_detail->alamat = $detail['alamat'] ?? null;
                $witel_detail->save();
            }

            DB::commit();

            return response()->json([
                'data' => [],
                'success' => true,
                'message' => null,
            ], 200);

        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'data' => $th->getMessage(),
                'success' => true,
                'message' => 'error',
            ], 500);
        }
    }

    public function addWitelDetail(Request $request, $target_id, $no)
    {
        $v = Validator::make($request->all(), [
            'tipe' => 'required',
            'kode' => 'required',
        ]);

        if ($v->fails()) {
            return response()->json([
                'data' => null,
                'succes' => false,
                'message' => $v->errors()
            ], 422);
        }

        $target_witel = TrBaSarpenTargetWitel::where('sarpen_target_id', $target_id)->where('no', $no)->first();
        if (!$target_witel) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak dapat ditemukan',
                'data' => null
            ], 404);
        }

        DB::beginTransaction();
        try {
            $counter = TrBaSarpenTargetWitelDetail::where('sarpen_target_detail_id', $target_id)->where('detail_no', $no)->max('no') + 1;
            $witel_detail = new TrBaSarpenTargetWitelDetail();
            $witel_detail->sarpen_target_detail_id = $target_witel->sarpen_target_id;
            $witel_detail->detail_no = $target_witel->no;
            $witel_detail->no = $counter;
            $witel_detail->tipe = $request->tipe;
            $witel_detail->kode = $request->kode;
            $witel_detail->alamat = $request->alamat;
            $witel_detail->save();
            DB::commit();

            return (new TrbaSarpenTargetWitelDetailResource($witel_detail))->additional([
                'success' => true,
                'message' => 'Data Baru Telah Dibuat'
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'data' => $th->getMessage(),
                'success' => true,
                'message' => 'error',
            ], 500);
        }
    }

    public function deleteWitelDetail($target_id, $detail_no, $no)
    {
        try {
            $data = TrBaSarpenTargetWitelDetail::where('sarpen_target_detail_id', $target_id)
                ->where('detail_no', $detail_no)
                ->where('no', $no)
                ->first();

            if(!$data)
            {
                return response()->json([
                    'status' => false,
                    'message' => 'Data Tidak Ditemukan',
                    'data' => null
                ], 404);
            }

            if ($data && $data->no_dokumen != null) 
            {
                return response()->json([
                    'status' => false,
                    'message' => 'Data Tidak Dapat Dihapus',
                    'data' => null
                ], 403);
            }

            TrBaSarpenTargetWitelDetail::where('sarpen_target_detail_id', $target_id)
                ->where('detail_no', $detail_no)
                ->where('no', $no)->delete();

            return response()->json([
                'status' => true,
                'message' => 'success',
                'data' => $data
            ], 200);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'data' => $th->getMessage(),
                'success' => true,
                'message' => 'error',
            ], 500);
        }
    }

    public function closeTarget(Request $request, $id)
    {
        $data = TrBaSarpenTarget::where('id', $id)->first();

        if(!$data)
        {
            return response()->json([
                'status' => false,
                'message' => 'Data Tidak Ditemukan',
                'data' => null
            ], 404);
        }

        try {
            $data = TrBaSarpenTarget::findOrFail($id);
            $data->tanggal_berakhir = date('Y-m-d');
            $data->status = 'not_active';

            $data->save();
            return (new TrBaSarpenTargetResource($data))->additional([
                'success' => true,
                'message' => 'Data Berhasil Ditutup'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'data' => null,
                'success' => false,
                'message' => $th->getMessage(),
            ], 404);
        }
    }

    public function dataTarget()
    {
        $kode = $_GET['kode'];
        $witel = $_GET['witel'];
        $data = TrBaSarpenTarget::with(['witels' => function ($query) use ($kode, $witel) {
            if (Auth::user()->role === 'WITEL') {
                $query->where('witel', Auth::user()->site_witel);
            } else if (Auth::user()->role === 'RWS' || Auth::user()->role === 'ROOT') {
                $query->where('witel', $witel);
            }
            $query->with([
                'details' => function ($q_detail) use ($kode)  { 
                    $q_detail->where('kode', $kode)->whereNull('no_dokumen'); 
                },
            ]);
        }])->where('status', 'active')->where('tanggal_berakhir', '>=', date('Y-m-d'))->get();

        return TrBaSarpenResource::collection($data)->additional([
            'success' => true,
            'message' => null
        ]);
    }

    public function downloadTemplate()
    {
        $storagePath = public_path() . '/downloads/template_bulk_target.xlsx';
        return response()->file($storagePath);
    }
}

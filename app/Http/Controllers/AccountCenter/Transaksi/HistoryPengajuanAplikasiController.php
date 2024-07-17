<?php

namespace App\Http\Controllers\AccountCenter\Transaksi;

use App\Exports\PengajuanAplikasiExport;
use App\Http\Controllers\Controller;
use App\Http\Resources\HistoryPengajuanAplikasiResource;
use App\Http\Resources\TrHistoryPengajuanResource;
use App\Models\MaPengguna;
use App\Models\TrHistoryPengajuan;
use App\Models\TrPengajuanAplikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;
use Excel;

class HistoryPengajuanAplikasiController extends Controller
{
    private $_month = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

    public function index()
    {
        $data = TrHistoryPengajuan::with(['createdBy'])->withCount(['detailPengajuan']);
        if (isset($_GET['page'])) {

            // if (isset($_GET['q']) && $_GET['q'] !== '') {
            //     $q = $_GET['q'];
            //     $data = $data->whereRaw("(no_dokumen like '%$q%' or 
            //         nama_sto like '%$q%' or nama_site like '%$q%' or
            //         regional like '%$q%' or nama_klien like '%$q%')");
            // }

            if (isset($_GET['aplikasi'])) {
                $data = $data->where('aplikasi', $_GET['aplikasi']);
            }

            if (isset($_GET['status'])) {
                $data = $data->where('status', $_GET['status']);
            }

            $per_page = 10;
            if (isset($_GET['per_page']))
                $per_page = $_GET['per_page'];

            $data = $data->orderByDesc('created_at')->paginate($per_page)->onEachSide(5);
        } else {
            if (isset($_GET['group'])) {
                $data = $data->where('group', $_GET['group']);
            }
            $data = $data->get();
        }

        return TrHistoryPengajuanResource::collection(($data))->additional([
            'success' => true,
            'message' => null,
        ]);
    }

    public function store(Request $request)
    {
        $v = Validator::make($request->all(), [
            'aplikasi' => 'required',
            'jenis_pengajuan' => 'required|in:BARU,REAKTIVASI,TAMBAH-FITUR,HAPUS',
        ]);

        $bulan = date('n', strtotime(date('Y-m-d')));
        $tahun = date('Y', strtotime(date('Y-m-d')));

        $batch = TrHistoryPengajuan::where('nama', $request->type)->max('batch') + 1;

        $nama = '';
        if ($request->type === 'starclick_ncx') {
            $nama = 'Starclick';
        } else if ($request->type === 'ncx_cons') {
            $nama = 'NCX';
        }

        DB::beginTransaction();
        try {
            $history = new TrHistoryPengajuan();
            $history->id = Uuid::uuid4()->toString();
            $history->nama = "Pengajuan_{$nama}_Batch_{$batch}_{$this->_month[$bulan-1]}_{$tahun}.xlsx";
            $history->aplikasi = $request->type;
            $history->batch = $batch;
            $history->tanggal = date('Y-m-d');
            $history->status = 'process';
            $history->created_by = Auth::user()->id;
            $history->created_by_data = json_encode(MaPengguna::find(Auth::user()->id), JSON_PRETTY_PRINT);
            $history->save();
            
            TrPengajuanAplikasi::whereIn('id', $request->ids)
            ->update(array(
                'status_pengajuan' => 'process',
                'history_id' => $history->id,
                'process_by' => Auth::user()->id,
                'process_by_data' => json_encode(MaPengguna::find(Auth::user()->id), JSON_PRETTY_PRINT)
            ));
            DB::commit();

            return (new TrHistoryPengajuanResource($history))->additional([
                'success' => true,
                'message' => 'Data Baru Telah Dibuat'
            ]);

        } catch (\Throwable $th) {
            DB::rollback();
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
            $data = TrHistoryPengajuan::with(['createdBy'])->withCount(['detailPengajuan'])->findOrFail($id);

            return (new TrHistoryPengajuanResource($data))->additional([
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

    public function downloadPengajuan($aplikasi, $history_id)
    {
        
        return Excel::download(new PengajuanAplikasiExport($aplikasi, $history_id), 'pengajuan_aplikasi_starclick.xlsx');
    }
}

<?php

namespace App\Http\Controllers\AccountCenter\Transaksi;

use App\Exports\PengajuanAplikasiExport;
use App\Http\Controllers\Controller;
use App\Http\Resources\HistoryPengajuanAplikasiResource;
use App\Http\Resources\TrHistoryPengajuanResource;
use App\Models\MaPengguna;
use App\Models\MaUserAccountProfile;
use App\Models\TrHistoryPengajuan;
use App\Models\TrPengajuanAplikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;
use Excel;
use ZipArchive;

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

        $batch = TrHistoryPengajuan::where('aplikasi', $request->type)
                                    ->whereYear('tanggal', $tahun)
                                    ->whereMonth('tanggal',$bulan)
                                    ->max('batch') + 1;

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

    public function update($id, Request $request)
    {
        $v = Validator::make($request->all(), [
            'ids' => 'required',
            'file' => 'required',
        ]);

        $check = TrHistoryPengajuan::findOrFail($id);
        if (!$check) {
            return response()->json([
                'status' => false,
                'message' => 'Data Tidak Ditemukan',
                'data' => null
            ], 404);
        }

        DB::beginTransaction();
        try {
            $nama = explode(".", $check->nama)[0];
            $ids = json_decode($request->ids);
            $url_file = $this->uploadNotaDinas($request->file('file'), 'nota-dinas_'.$nama);

            TrPengajuanAplikasi::where('history_id', $id)->whereIn('id', $ids)
            ->update(array(
                'status_pengajuan' => 'finished',
            ));

            $pengajuans = TrPengajuanAplikasi::whereIn('id', $ids)->get();
            foreach ($pengajuans as $key => $pengajuan) {
                $status = 'AKTIF';
                if ($pengajuan->jenis_pengajuan == 'hapus') {
                   $status = 'TIDAK-AKTIF';
                }
                
                MaUserAccountProfile::where('pengajuan_aplikasi_id', $pengajuan->id)
                ->update(array(
                    'status' => $status,
                ));
               
            }

           

            TrPengajuanAplikasi::where('history_id', $id)->whereNotIn('id', $ids)
            ->update(array(
                'status_pengajuan' => 'rejected_hub',
                'history_id' => null,
                // 'rejected_hub_by' => Auth::user()->id,
                // 'rejected_hub_by_data' => json_encode(MaPengguna::find(Auth::user()->id), JSON_PRETTY_PRINT)
            ));

            TrHistoryPengajuan::where('id', $id)
            ->update(array(
                'status' => 'finished',
                'nota_dinas_url' => $url_file
            ));
            
            DB::commit();

            return (new TrHistoryPengajuanResource($check))->additional([
                'success' => true,
                'message' => 'suksess'
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'data' => null,
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }

        if ($v->fails()) {
            return response()->json([
                'data' => null,
                'succes' => false,
                'message' => $v->errors()
            ], 422);
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
        
        return Excel::download(new PengajuanAplikasiExport($aplikasi, $history_id), 'pengajuan_aplikasi.xlsx');
    }

    public function downloadNotaDinas($name)
    {
        
        $storagePath = public_path() . '/nota-dinas/' . $name;
        return response()->file($storagePath);
    }

    public function downloadZip($aplikasi, $history_id)
    {

        $pengajuans = TrPengajuanAplikasi::with(['userAccount'])->where('history_id',$history_id)->get();
        $history = TrHistoryPengajuan::find($history_id);

        $excelPath = public_path().'/temp-folder/'.$history->nama;
        $notaDinasPath = public_path().'/nota-dinas/'.$history->nota_dinas_url;
        if(file_exists($excelPath))
            unlink($excelPath);

        Excel::store(new PengajuanAplikasiExport($aplikasi, $history_id), $history->nama, 'public_temp');
        $zip        = new ZipArchive;
        $zipFile    = public_path().'/temp-folder/temp-account.zip';
        if(file_exists($zipFile))
            unlink($zipFile);

        if ($zip->open($zipFile, ZipArchive::CREATE) === TRUE)
        {
            foreach ($pengajuans as $key => $pengajuan) {
                $user_account = $pengajuan->userAccount;
                $zip->addFile('data-ktp/' . $user_account->image_ktp_url);
                $zip->addFile('file-pakta/' . $user_account->file_pakta_url);
            }
            $zip->addFile($excelPath, $history->nama);
            $zip->addFile($notaDinasPath, $history->nota_dinas_url);
            $zip->close();
        }

        return response()->download($zipFile);

    }

    private function uploadNotaDinas($file, $nama_file)
    {
        $file->move('nota-dinas/', $nama_file . '.' . $file->getClientOriginalExtension());

        return $nama_file . '.' . $file->getClientOriginalExtension();
    }

}

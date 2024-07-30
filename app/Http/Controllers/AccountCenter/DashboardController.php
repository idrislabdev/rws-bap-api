<?php

namespace App\Http\Controllers\AccountCenter;

use App\Exports\DashboardPengajuanAplikasiExport;
use App\Exports\DashboardUserAplikasiExport;
use App\Http\Controllers\Controller;
use App\Http\Resources\MaUserAccountResource;
use App\Http\Resources\TrPengajuanAplikasiResource;
use App\Models\MaUserAccount;
use App\Models\TrPengajuanAplikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Excel;

class DashboardController extends Controller
{
    public function summaryWitel()
    {
        $arr_data = array();
        $arr_witel = array(
            "Singaraja",
            "Denpasar",
            "Mataram",
            "Malang",
            "Jember",
            "Kediri",
            "Pasuruan",
            "Sidoarjo",
            "Madiun",
            "Madura",
            "Kupang",
            "Surabaya Utara",
            "Surabaya Selatan",
            "Wholesale"
        );

        if (Auth::user()->role === 'WITEL') {
            $arr_witel = array(Auth::user()->site_witel);
        }

        $status = array ('proposed', 'ttd_witel', 'paraf_wholesale', 'ttd_wholesale', 'finished');

        for ($i=0; $i<count($arr_witel); $i++)
        {
            $witel = $arr_witel[$i];
            $user_starclick_count = MaUserAccount::with('profiles')->where('site_witel',$witel)
                                ->whereHas('profiles', function ($q) {
                                    $q->where('aplikasi', 'starclick_ncx')->where('status', 'AKTIF');
                                });

            if (isset($_GET['tanggal_awal']) && isset($_GET['tanggal_akhir'])) {
                $tanggal_awal = $_GET['tanggal_awal'];
                $tanggal_akhir = $_GET['tanggal_akhir'];
                $user_starclick_count = $user_starclick_count->whereRaw("(created_at >= '$tanggal_awal' and  created_at <= '$tanggal_akhir')");
            }

            $user_ncx_count = MaUserAccount::with('profiles')->where('site_witel',$witel)
                                ->whereHas('profiles', function ($q) {
                                    $q->where('aplikasi', 'ncx_cons')->where('status', 'AKTIF');
                                });

            if (isset($_GET['tanggal_awal']) && isset($_GET['tanggal_akhir'])) {
                $tanggal_awal = $_GET['tanggal_awal'];
                $tanggal_akhir = $_GET['tanggal_akhir'];
                $user_ncx_count = $user_ncx_count->whereRaw("(created_at >= '$tanggal_awal' and  created_at <= '$tanggal_akhir')");
            }

            $pengajuan_starclick_count = TrPengajuanAplikasi::with('userAccount')
                                            ->whereHas('userAccount', function ($q) use ($witel){
                                                $q->where('site_witel', $witel);
                                            })
                                            ->where('aplikasi', 'starclick_ncx')
                                            ->where('jenis_pengajuan', 'baru');

            if (isset($_GET['tanggal_awal']) && isset($_GET['tanggal_akhir'])) {
                $tanggal_awal = $_GET['tanggal_awal'];
                $tanggal_akhir = $_GET['tanggal_akhir'];
                $pengajuan_starclick_count = $pengajuan_starclick_count->whereRaw("(created_at >= '$tanggal_awal' and  created_at <= '$tanggal_akhir')");
            }

            $pengajuan_ncx_count = TrPengajuanAplikasi::with('userAccount')
                                            ->whereHas('userAccount', function ($q) use ($witel) {
                                                $q->where('site_witel', $witel);
                                            })
                                            ->where('aplikasi', 'ncx_cons')
                                            ->where('jenis_pengajuan', 'baru');

            if (isset($_GET['tanggal_awal']) && isset($_GET['tanggal_akhir'])) {
                $tanggal_awal = $_GET['tanggal_awal'];
                $tanggal_akhir = $_GET['tanggal_akhir'];
                $pengajuan_ncx_count = $pengajuan_ncx_count->whereRaw("(created_at >= '$tanggal_awal' and  created_at <= '$tanggal_akhir')");
            }

            $data = new \stdClass();
            $data->witel = $arr_witel[$i];
            $data->user_starclick_ncx = $user_starclick_count->count();
            $data->user_ncx_cons = $user_ncx_count->count();
            $data->pengajuan_starclick_ncx = $pengajuan_starclick_count->count();
            $data->pengajuan_ncx_cons = $pengajuan_ncx_count->count();
            
            array_push($arr_data,  $data);
        }

        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => $arr_data
        ], 200);
    }

    public function userAccount($aplikasi)
    {
        $witel = $_GET['witel'];

        $user_account = MaUserAccount::with(['profiles' => function ($q) use ($aplikasi) {
            $q->where('aplikasi', $aplikasi)->where('status', 'AKTIF');
        }])
        ->whereHas('profiles', function ($q) use ($aplikasi) {
            $q->where('aplikasi', $aplikasi)->where('status', 'AKTIF');
        });

        if ($witel != 'ALL') {
            $user_account = $user_account->where('site_witel', $witel);
        }

        if (isset($_GET['tanggal_awal']) && isset($_GET['tanggal_akhir'])) {
            $tanggal_awal = $_GET['tanggal_awal'];
            $tanggal_akhir = $_GET['tanggal_akhir'];
            $user_account = $user_account->whereRaw("(created_at >= '$tanggal_awal' and  created_at <= '$tanggal_akhir')");
        }

        return MaUserAccountResource::collection($user_account->get())->additional([
            'success' => true,
            'message' => null
        ]);
    }

    public function pengajuanUser($aplikasi)
    {
        $witel = $_GET['witel'];

        $pengajuan_account = TrPengajuanAplikasi::with('userAccount')
                ->whereHas('userAccount', function ($q) use ($witel){
                    if ($witel != 'ALL')
                        $q->where('site_witel', $witel);
                })
                ->where('aplikasi', $aplikasi)
                ->where('jenis_pengajuan', 'baru');

        if (isset($_GET['tanggal_awal']) && isset($_GET['tanggal_akhir'])) {
            $tanggal_awal = $_GET['tanggal_awal'];
            $tanggal_akhir = $_GET['tanggal_akhir'];
            $pengajuan_account = $pengajuan_account->whereRaw("(created_at >= '$tanggal_awal' and  created_at <= '$tanggal_akhir')");
        }

        return TrPengajuanAplikasiResource::collection(($pengajuan_account->get()))->additional([
            'success' => true,
            'message' => null,
        ]);
    }

    public function downloadUser($aplikasi)
    {
        $witel = $_GET['witel'];
        $tanggal_awal = null;
        $tanggal_akhir = null;

        if (isset($_GET['tanggal_awal']) && isset($_GET['tanggal_akhir'])) {
            $tanggal_awal = $_GET['tanggal_awal'];
            $tanggal_akhir = $_GET['tanggal_akhir'];
        }
        
        return Excel::download(new DashboardUserAplikasiExport($aplikasi, $witel, $tanggal_awal, $tanggal_akhir), 'user_aplikasi_aktif.xlsx');
    }

    public function downloadPengajuan($aplikasi)
    {
        $witel = $_GET['witel'];
        $tanggal_awal = null;
        $tanggal_akhir = null;

        if (isset($_GET['tanggal_awal']) && isset($_GET['tanggal_akhir'])) {
            $tanggal_awal = $_GET['tanggal_awal'];
            $tanggal_akhir = $_GET['tanggal_akhir'];
        }
        
        return Excel::download(new DashboardPengajuanAplikasiExport($aplikasi, $witel, $tanggal_awal, $tanggal_akhir), 'pengajuan_aplikasi.xlsx');
    }

}

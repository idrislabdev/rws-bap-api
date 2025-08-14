<?php

namespace App\Http\Controllers\CNOP\Transaksi;

use App\Helper\UtilityHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\DismantleResource;
use App\Models\MaNomorDokumen;
use App\Models\MaPengaturan;
use App\Models\MaPengguna;
use App\Models\TrBa;
use App\Models\TrWo;
use App\Models\TrWoSite;
use App\Models\TrWoSiteImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

use PDF;

class DismantleController extends Controller
{
    private $_hari = ['SENIN', 'SELASA', 'RABU', 'KAMIS', 'JUMAT', 'SABTU', 'MINGGU'];
    private $_month = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

    public function index()
    {
        $pengguna = MaPengguna::find(Auth::user()->id);

        $data = DB::table(DB::raw('tr_wo_sites tr'))
            ->select(
                DB::raw("tr.*, 
                                                      trw.dasar_order, 
                                                      trw.lampiran_url,  
                                                      p.id pengguna_id, 
                                                      p.nama_lengkap,
                                                      b.no_dokumen"),
            )
            ->leftJoin('tr_wos as trw', 'tr.wo_id', '=', 'trw.id')
            ->leftJoin('ma_penggunas as p', 'tr.dibuat_oleh', '=', 'p.id')
            ->leftJoin('tr_bas as b', 'tr.ba_id', '=', 'b.id')
            ->whereRaw("tr.tipe_ba = 'DISMANTLE'");

        if ($pengguna->site_witel) {
            $data = $data->whereRaw("tr.site_witel = '$pengguna->site_witel'");
        }

        $q = $_GET['q'];

        if ($q) {
            $data = $data->whereRaw("(program like '%$q%' or 
                                    site_name like '%$q%' or 
                                    tr.site_witel like '%$q%' or 
                                    tr.tsel_reg like '%$q%' or 
                                    site_id like '%$q%' or 
                                    tahun_order like '%$q%' or 
                                    dasar_order like '%$q%')");
        }

        if (($_GET['tahun_order'])) {
            $data = $data->where("tahun_order", $_GET['tahun_order']);
        }

        $data = $data->orderBy('trw.dasar_order')->orderBy('tr.site_id')->paginate(25)->onEachSide(5);

        return DismantleResource::collection(($data))->additional([
            'success' => true,
            'message' => null,
        ]);
    }

    public function store(Request $request)
    {
        $v = Validator::make($request->all(), [
            'sites' => 'required',
        ]);

        if ($v->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'error',
                'data' => $v->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $sites = $request->sites;

            $counter = 0;
            $dasar_order = "";
            $wo_id = "";
            foreach ($sites as $site) {
                if ($dasar_order != $site['dasar_order']) {
                    $counter = 1;
                    $check = TrWo::where('dasar_order', $site['dasar_order'])->first();
                    if (!$check) {
                        $wo_id =  Uuid::uuid4()->toString();
                        $dasar_order = $site['dasar_order'];
                        $wo = new TrWo();
                        $wo->id = $wo_id;
                        $wo->dasar_order = $site['dasar_order'];
                        $wo->tipe_ba = 'DISMANTLE';
                        $wo->save();
                    } else {
                        $wo_id = $check->id;
                        $counter = TrWoSite::where('wo_id', $wo_id)->max('wo_site_id') + 1;
                    }
                } else {
                    $counter++;
                }

                $check_site = TrWoSite::where('wo_id', $wo_id)
                    ->where('site_id', $site['site_id'])
                    ->where('tipe_ba', 'DISMANTLE')
                    ->where('tahun_order', $site['tahun_order'])
                    ->where('siborder_id', $site['siborder_id'])
                    ->first();
                    
                if (!$check_site) {
                    $wo_site = new TrWoSite();
                    $wo_site->wo_id = $wo_id;
                    $wo_site->wo_site_id = $counter;
                    $wo_site->site_id = $site['site_id'];
                    $wo_site->site_name = (isset($site['site_name'])) ? $site['site_name'] : '-';
                    $wo_site->site_witel = $site['site_witel'];
                    $wo_site->tsel_reg = $site['tsel_reg'];
                    $wo_site->tgl_deactivate = $site['tgl_deactivate'];
                    $wo_site->data_2g = 0;
                    $wo_site->data_3g = 0;
                    $wo_site->data_4g = 0;
                    $wo_site->jumlah = $site['data_bandwidth'];
                    $wo_site->bts_position = $site['bts_position'];
                    $wo_site->dibuat_oleh = Auth::user()->id;
                    $wo_site->status = 'OGP';
                    $wo_site->progress = false;
                    $wo_site->tipe_ba = 'DISMANTLE';
                    $wo_site->tahun_order = $site['tahun_order'];
                    $wo_site->siborder_id = $site['siborder_id'];
                    $wo_site->save();
                } else {
                    TrWoSite::where('wo_id', $wo_id)
                        ->where('site_id', $site['site_id'])
                        ->where('tipe_ba', 'DISMANTLE')
                        ->where('tahun_order', $site['tahun_order'])
                        ->where('siborder_id', $site['siborder_id'])
                        ->update(array(
                            'program' => $site['program']
                        ));
                }
            }


            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'success',
                'data' => null
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'data' => $e->getMessage(),
                'success' => true,
                'message' => 'error',
            ], 400);
        }
    }

    public function show($wo_id, $wo_site_id)
    {
        try {
            $data = DB::table(DB::raw('tr_wo_sites tr, tr_wos trw, ma_penggunas p'))
                ->select(DB::raw("tr.*, trw.dasar_order, trw.lampiran_url,  p.id pengguna_id, p.nama_lengkap"))
                ->whereRaw("p.id = tr.dibuat_oleh")
                ->whereRaw("tr.wo_id = trw.id")
                ->whereRaw("tr.tipe_ba = 'DISMANTLE'")
                ->where('tr.wo_id', $wo_id)
                ->where('tr.wo_site_id', $wo_site_id)
                ->first();

            // return (new TrWoSiteResource($data))->additional([
            //     'success' => true,
            //     'message' => 'suksess'
            // ]);

            return response()->json([
                'data' => $data,
                'success' => true,
                'message' => null,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'data' => null,
                'success' => false,
                'message' => 'Data Tidak Ditemukan',
            ], 404);
        }
    }

    public function update(Request $request, $wo_id, $wo_site_id)
    {
        $v = Validator::make($request->all(), [
            'site_id' => 'required',
            'site_name' => 'required',
            'site_witel' => 'required',
            'tsel_reg' => 'required',
            'program' => 'required',
            'jumlah' => 'required',
            'keterangan' => 'required',
        ]);

        if ($v->fails()) {
            return response()->json([
                'data' => null,
                'succes' => false,
                'message' => $v->errors()
            ], 422);
        }

        $data = TrWoSite::where('wo_id', $wo_id)->where('wo_site_id', $wo_site_id)->first();

        if ($data == null) {
            return response()->json([
                'data' => null,
                'succes' => false,
                'message' => 'Data Tidak Ditemukan'
            ], 422);
        }


        try {

            $update = TrWoSite::where('wo_id', $wo_id)->where('wo_site_id', $wo_site_id)
                ->update(array(
                    'site_id' => $request->site_id,
                    'site_name' => $request->site_name,
                    'tsel_reg' => $request->tsel_reg,
                    'site_witel' => $request->site_witel,
                    'jumlah' => $request->jumlah,
                    'keterangan' => $request->keterangan,
                    'program' => $request->program
                ));

            return response()->json([
                'data' => $data,
                'success' => true,
                'message' => null,
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'data' => $e->getMessage(),
                'success' => true,
                'message' => $e,
            ], 400);
        }
    }

    public function deactivate(Request $request, $wo_id, $wo_site_id)
    {
        $data = TrWoSite::where('wo_id', $wo_id)->where('wo_site_id', $wo_site_id)->where('progress', 0)->first();

        if ($data == null) {
            return response()->json([
                'data' => null,
                'succes' => false,
                'message' => 'Data Tidak Ditemukan'
            ], 422);
        }


        try {

            $update = TrWoSite::where('wo_id', $wo_id)->where('wo_site_id', $wo_site_id)
                ->update(array(
                    'progress' => 1,
                    'tgl_deactivate' => $request->tgl_deactivate
                ));

            return response()->json([
                'data' => $data,
                'success' => true,
                'message' => null,
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'data' => $e->getMessage(),
                'success' => true,
                'message' => $e,
            ], 400);
        }
    }

    public function checkSiteBA(Request $request)
    {
        $v = Validator::make($request->all(), [
            'tsel_reg' => 'required',
            'no_dokumen' => 'required',
            'tahun_order' => 'required'
        ]);

        if($v->fails())
        {
            return response()->json([
                'data' => null,
                'succes' => false,
                'message' => $v->errors()
            ], 422);
        }

        $sites = DB::table(DB::raw('tr_wo_sites tr, tr_wos trw, ma_penggunas p')) 
                                ->select(DB::raw("tr.*, 
                                                trw.dasar_order, 
                                                trw.lampiran_url,  
                                                p.id pengguna_id, 
                                                p.nama_lengkap"))
                                                ->whereRaw("p.id = tr.dibuat_oleh")
                                                ->whereRaw("tr.wo_id = trw.id")
                                                ->whereRaw("tr.tipe_ba = 'DISMANTLE'")
                                                ->whereRaw("tr.progress = true")
                                                ->where('tsel_reg', $request->tsel_reg)
                                                ->where('tahun_order', $request->tahun_order)
                                                ->whereNull('ba_id')
                                                ->get();

        $check_dokumen = TrBa::where('no_dokumen', $request->no_dokumen)->first();
        $check_no_dokumen = MaNomorDokumen::where('no_dokumen', $request->no_dokumen)->first();

        if ($check_dokumen || $check_no_dokumen)
        {
            return response()->json([
                'data' => [],
                'success' => true,
                'message' => 'Maaf, No dokumen sudah pernah digunakan sebelumnya.',
             ], 200);
        }

        return response()->json([
            'data' => $sites,
            'success' => true,
            'message' => 'Maaf, Tidak Ada Data Yang Bisa Diproses.',
        ], 200);
    }

    public function createBA(Request $request)
    {
        $v = Validator::make($request->all(), [
            'no_dokumen' => 'required',
            'tgl_dokumen' => 'required',
            'tsel_reg' => 'required',
            'sites' => 'required',
        ]);

        if ($v->fails()) {
            return response()->json([
                'data' => null,
                'succes' => false,
                'message' => $v->errors()
            ], 422);
        }

        $check_dokumen = TrBa::where('no_dokumen', $request->no_dokumen)->first();

        if ($check_dokumen) {
            return response()->json([
                'data' => null,
                'success' => true,
                'message' => 'Maaf, No dokumen sudah pernah digunakan sebelumnya.',
            ], 200);
        }


        DB::beginTransaction();
        try {


            $ba = new TrBa();
            $id = Uuid::uuid4()->toString();
            $ba->id = $id;
            $ba->no_dokumen = $request->no_dokumen;
            $ba->tgl_dokumen = $request->tgl_dokumen;
            $ba->tsel_reg = $request->tsel_reg;
            $ba->tipe = 'DISMANTLE';
            $ba->dibuat_oleh = Auth::user()->id;
            $ba->status_sirkulir = 0;
            $ba->save();

            $sites = $request->sites;

            foreach ($sites as $site) {
                TrWoSite::where('tipe_ba', 'DISMANTLE')
                    ->where('progress', true)
                    ->where('tsel_reg', $request->tsel_reg)
                    ->where('wo_id', $site['wo_id'])
                    ->where('wo_site_id', $site['wo_site_id'])
                    ->whereNull('ba_id')
                    ->update(array(
                        'ba_id' => $id,
                    ));
            }

            $data = new MaNomorDokumen();
            $data->id = Uuid::uuid4()->toString();
            $data->no_dokumen = $request->no_dokumen;
            $data->tipe_dokumen = 'DISMANTLE';
            $data->tgl_dokumen = $request->tgl_dokumen;
            $data->save();

            DB::commit();

            $url = $this->fileBA($id);

            return response()->json([
                'data' => $ba,
                'success' => true,
                'message' => 'error',
            ], 200);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'data' => $e->getMessage(),
                'success' => true,
                'message' => 'error',
            ], 400);
        }
    }


    public function deleteBA($id)
    {
        DB::beginTransaction();
        try {

            $check = TrBa::where('id', $id)->first();

            if ($check) {
                $no_dokumen = $check->no_dokumen;

                TrWoSite::where('ba_id', $id)->where('tipe_ba', 'DISMANTLE')
                    ->update(array(
                        'ba_id' => null,
                    ));

                TrBa::where('id', $id)->where('tipe', 'DISMANTLE')->delete();

                $path = storage_path() . '/app/public/pdf/' . $id . '.pdf';

                if (file_exists($path))
                    unlink($path);

                MaNomorDokumen::where('no_dokumen', $no_dokumen)->delete();

                DB::commit();

                return response()->json([
                    'status' => true,
                    'message' => 'success',
                    'data' => null
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data Tidak Ditemukan',
                    'data' => null
                ], 400);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'data' => $e->getMessage(),
                'success' => true,
                'message' => 'error',
            ], 400);
        }
    }

    public function parafWholesale($id)
    {
        $data = TrBa::find($id);
        if(!$data)
        {
            return response()->json([
                'status' => false,
                'message' => 'Data Tidak Ditemukan',
                'data' => null
            ], 404);
        }

        $pengguna = MaPengguna::find(Auth::user()->id);
        if ($pengguna->ttd_image === null) 
        {
            return response()->json([
                'status' => false,
                'message' => 'Anda Belum Set Tanda Tangan, Silahkan Set Tanda Tangan Anda Terlebih Dahulu',
                'data' => null
            ], 422);
        }

        DB::beginTransaction();
        try {
            $data->paraf_wholesale = Auth::user()->id;

            $paraf_wholesale = new \stdClass();
            $paraf_wholesale->status_dokumen = 'APPROVED';
            $paraf_wholesale->ttd_image = $pengguna->ttd_image;

            $data->paraf_wholesale_data  = json_encode($paraf_wholesale, JSON_PRETTY_PRINT);
            $data->save();
            $this->fileBA($id);
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'success',
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'data' => $e->getMessage(),
                'success' => true,
                'message' => 'error',
            ], 400);
        }
    }

    public function ttdWholesale($id)
    {
        $data = TrBa::find($id);
        if(!$data)
        {
            return response()->json([
                'status' => false,
                'message' => 'Data Tidak Ditemukan',
                'data' => null
            ], 404);
        }

        $user = MaPengguna::find(Auth::user()->id);
        $pengaturan = MaPengaturan::where('nama', 'MANAGER_WHOLESALE_SUPPORT')->first();
        $pengguna = MaPengguna::where('nama_lengkap', $pengaturan->nilai)->first();


        if ($pengguna->ttd_image === null) 
        {
            return response()->json([
                'status' => false,
                'message' => 'Anda / Manager Wholesale Support Belum Set Tanda Tangan, Silahkan Set Tanda Tangan Anda Terlebih Dahulu',
                'data' => null
            ], 422);
        }

        DB::beginTransaction();
        try {
            $data->manager_wholesale = Auth::user()->id;

            $manager_wholesale = new \stdClass();
            $manager_wholesale->status_dokumen = 'APPROVED';
            $manager_wholesale->ttd_image = $pengguna->ttd_image;
            $manager_wholesale->paraf_image = $pengguna->paraf_image;

            $data->manager_wholesale_data  = json_encode($manager_wholesale, JSON_PRETTY_PRINT);
            $data->save();
            $this->fileBA($id);
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'success',
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'data' => $e->getMessage(),
                'success' => true,
                'message' => 'error',
            ], 400);
        }
    }

    public function fileBA($id)
    {
        set_time_limit(1800);

        $jenis_dokumen = array(
            "Dokumen uji terima",
            "Data Konfigurasi & Topology E2E",
            "Capture Trafik oleh Telkom",
            "Referensi Work Order"
        );

        $data_ba = TrBa::where('id', $id)->first();
        $dasar_permintaan = '';
        $data_site = array();
        $count = 0;
        $total_bw = 0;
        $total_site = 0;
        $count++;

        $wo = TrWoSite::where('ba_id', $id)->distinct()->get('wo_id');

        $arr_wo = array();
        foreach ($wo as $w) {
            $arr_wo[] = $w->id;
        }

        $data_wo = TrWo::whereIn('id', $wo)->get();
        $count = 0;
        
        foreach ($data_wo as $w) {
            $count++;
            $dasar_permintaan = $dasar_permintaan.$w->dasar_order;
            if ($count < count($data_wo)) {
                $dasar_permintaan = $dasar_permintaan.', ';
            }

            $data_sites = TrWoSite::where('wo_id', $w->id)->where('ba_id', $id)->get();
            $count_site=0;
            $text_site='';
            foreach ($data_sites as $site) {

                $site->dasar_order = $w->dasar_order;

                $total_site++;
                $total_bw = $total_bw + $site->jumlah;
    
                array_push($data_site, $site);

                $count_site++;
                $text_site = $text_site.$site->site_id;
                if ($count_site < count($data_sites)) {
                    $text_site = $text_site.', ';
                }                
            }
            $w->daftar_site = $text_site;
        }

        $hari = date('N', strtotime($data_ba->tgl_dokumen));
        $tgl = date('j', strtotime($data_ba->tgl_dokumen));
        $bulan = date('n', strtotime($data_ba->tgl_dokumen));
        $tahun = date('Y', strtotime($data_ba->tgl_dokumen));

        $format_tanggal = new \stdClass();
        $format_tanggal->hari = $this->_hari[$hari - 1];
        $format_tanggal->tgl = strtoupper(UtilityHelper::terbilang($tgl));
        $format_tanggal->tgl_nomor = $tgl;
        $format_tanggal->bulan = $this->_month[$bulan - 1];
        $format_tanggal->tahun_nomor = $tahun;
        $format_tanggal->tahun = strtoupper(UtilityHelper::terbilang($tahun));

        $people_ttd = new \stdClass();
        $people_ttd->osm_regional = MaPengaturan::where('nama', 'OSM_REGIONAL_WHOLESALE_SERVICE')->first();
        $people_ttd->gm_core_transport = MaPengaturan::where('nama', 'GM_CORE_TRANSPORT_NETWORK')->first();
        $people_ttd->manager_wholesale = MaPengaturan::where('nama', 'MANAGER_WHOLESALE_SUPPORT')->first();
        $people_ttd->manager_pm_jatim = MaPengaturan::where('nama', 'PM_JATIM')->first();
        $people_ttd->manager_pm_balnus = MaPengaturan::where('nama', 'PM_BALNUS')->first();
        $people_ttd->gm_network = MaPengaturan::where('nama', 'GM_NETWORK_ENGINEERING_PROJECT')->first();

        // $paraf_wholesale = json_decode($data_ba->paraf_wholesale_data);
        $manager_wholesale = json_decode($data_ba->manager_wholesale_data);


        $pdf = PDF::loadView('dismantle', [
            'jenis_dokumen'     => $jenis_dokumen,
            'data_wo'           => $data_wo,
            'data_site'         => $data_site,
            'data_ba'           => $data_ba,
            'dasar_permintaan'  => $dasar_permintaan,
            'total_bw'          => $total_bw,
            'total_site'        => $total_site,
            'format_tanggal'    => $format_tanggal,
            'people_ttd'        => $people_ttd,
            // 'paraf_wholesale'   => $paraf_wholesale,
            'manager_wholesale' => $manager_wholesale,
        ])->setPaper('a4');

        $file_name = $id . '.pdf';


        Storage::put('public/pdf/' . $file_name, $pdf->output());

        return $file_name;
    }

    public function refresh($id)
    {
        $file_name = $id . '.pdf';

        $path = storage_path() . '/app/public/pdf/' . $file_name;
        if (file_exists($path))
            unlink($path);

        $this->fileBA($id);
    }

    public function downloadBA($id)
    {
        // return response()->file(storage_path().'/app/public/pdf/'.$id.'.pdf');
        return response()->download(storage_path() . '/app/public/pdf/' . $id . '.pdf');
    }

    private function arrayToString($data)
    {
        $string = '';
        $i = 0;
        foreach ($data as $value) {
            if ($i == 0) {
                $string .= "'" . $value . "'";
            } else {
                $string .= ",'" . $value . "'";
            }
            $i++;
        }

        return $string;
    }
}

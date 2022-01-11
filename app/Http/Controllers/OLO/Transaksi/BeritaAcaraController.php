<?php

namespace App\Http\Controllers\OLO\Transaksi;

use App\Exports\OloExport;
use App\Helper\UtilityHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\TrOloBaDetailResource;
use App\Http\Resources\TrOloBaResource;
use App\Models\MaNomorDokumen;
use App\Models\MaOloProduk;
use App\Models\MaPengaturan;
use App\Models\TrOloBa;
use App\Models\TrOloBaDetail;
use App\Models\TrOloBaDetailAddOn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Validator;
use PDF;
use Excel;

class BeritaAcaraController extends Controller
{
    private $_hari = ['SENIN', 'SELASA', 'RABU', 'KAMIS', 'JUMAT', 'SABTU', 'MINGGU'];
    private $_month = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

    public function index()
    {
        $data = TrOloBa::with('dibuatOleh');
        if (isset($_GET['page'])) {

            if (isset($_GET['q'])) {
                $q = $_GET['q'];
                $data = $data->whereRaw("(klien_nama_baut like '%$q%' or klien_lokasi_kerja_baut like '%$q%')");
            }
            $per_page = 50;
            if (isset($_GET['per_page']))
                $per_page = $_GET['per_page'];

            $data = $data->orderBy('tgl_dokumen')->paginate($per_page)->onEachSide(5);
        } else {
            $data = $data->get();
        }

        return TrOloBaResource::collection(($data))->additional([
            'success' => true,
            'message' => null,
        ]);
    }

    public function store(Request $request)
    {
        $v = Validator::make($request->all(), [
            'tgl_dokumen' => 'required',
            'klien_id'  => 'required',
            'klien_penanggung_jawab_baut' => 'required',
            'klien_jabatan_penanggung_jawab_baut' => 'required',
            'klien_lokasi_kerja_baut' => 'required',
            'klien_nama_baut' => 'required',
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
            $no_dokumen_bast = null;
            $no_dokumen_baut = null;

            $no_dokumen_baut = UtilityHelper::checkNomorDokumen();
            $data = new MaNomorDokumen();
            $data->id = Uuid::uuid4()->toString();
            $data->no_dokumen = $no_dokumen_baut;
            $data->tipe_dokumen = 'OLO_BAUT';
            $data->tgl_dokumen = $request->tgl_dokumen;
            $data->save();

            $ba = new TrOloBa();
            $id = Uuid::uuid4()->toString();
            $ba->id                                  = $id;
            $ba->no_dokumen_baut                     = $no_dokumen_baut;
            $ba->tgl_dokumen                         = $request->tgl_dokumen;
            $ba->klien_id                            = $request->klien_id;
            $ba->klien_penanggung_jawab_baut         = $request->klien_penanggung_jawab_baut;
            $ba->klien_jabatan_penanggung_jawab_baut = $request->klien_jabatan_penanggung_jawab_baut;
            $ba->klien_lokasi_kerja_baut             = $request->klien_lokasi_kerja_baut;
            $ba->klien_nama_baut                     = $request->klien_nama_baut;
            $ba->jenis_order                         = $request->jenis_order;
            $ba->jenis_order_id                      = $request->jenis_order_id;
            $ba->dibuat_oleh                         = Auth::user()->id;
            $ba->status_approval                     = false;
            $ba->save();

            $details = $request->detail;

            $counter = 0;
            $sigma = 0;
            foreach ($details as $detail) {
                $counter++;

                $ba_site                    = new TrOloBaDetail();
                $ba_site->olo_ba_id         = $id;
                $ba_site->id                = $counter;
                $ba_site->ao_sc_order       = $detail['ao_sc_order'];
                $ba_site->sid               = $detail['sid'];
                $ba_site->produk_id         = $detail['produk_id'];
                $ba_site->produk            = $detail['produk'];
                $ba_site->bandwidth_mbps    = $detail['bandwidth_mbps'] ?: null;
                $ba_site->jenis_order       = $detail['jenis_order'];
                $ba_site->jenis_order_id    = $detail['jenis_order_id'];
                $ba_site->alamat_instalasi  = $detail['alamat_instalasi'];
                $ba_site->tgl_order         = $detail['tgl_order'];
                $ba_site->dibuat_oleh       = Auth::user()->id;
                $ba_site->save();

                if (count($detail['add_on']) > 0) {
                    $add_ons = $detail['add_on'];
                    foreach ($add_ons as $add_on) {
                        $ba_add_on = new TrOloBaDetailAddOn();
                        $ba_add_on->olo_ba_id       = $id;
                        $ba_add_on->id              = $counter;
                        $ba_add_on->add_on_id       = $add_on['add_on_id'];
                        $ba_add_on->nama_add_on     = $add_on['nama_add_on'];
                        $ba_add_on->satuan          = $add_on['satuan'];
                        $ba_add_on->jumlah          = $add_on['jumlah'];
                        $ba_add_on->save();
                    }
                }

                $check_produk = MaOloProduk::find($detail['produk_id']);
                if ($check_produk->sigma)
                    $sigma++;
            }

            if ($sigma > 0) {
                $no_dokumen_bast = UtilityHelper::checkNomorDokumen();
                $data = new MaNomorDokumen();
                $data->id = Uuid::uuid4()->toString();
                $data->no_dokumen = $no_dokumen_bast;
                $data->tipe_dokumen = 'OLO_BAST';
                $data->tgl_dokumen = $request->tgl_dokumen;
                $data->save();

                $ba = TrOloBa::find($id);
                $ba->no_dokumen_bast = $no_dokumen_bast;
                $ba->update();
            }


            DB::commit();

            return (new TrOloBaResource($ba))->additional([
                'success' => true,
                'message' => 'Data Berhasil Dibuat',
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'data' => $e->getMessage(),
                'success' => true,
                'message' => 'error',
            ], 400);
        }
    }

    public function show($id)
    {
        try {
            $data = TrOloBa::with('detail.addOn')->findOrFail($id);
            if ($data) {
                return (new TrOloBaResource($data))->additional([
                    'success' => true,
                    'message' => 'suksess'
                ]);
            } else {
                return response()->json([
                    'data' => null,
                    'success' => false,
                    'message' => 'Data Tidak Ditemukan',
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'data' => null,
                'success' => false,
                'message' =>  $th->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {

            // TrOloBa::where('id', $id)->where('tipe', $tipe)->delete();
            TrOloBa::where('id', $id)->delete();

            $path = storage_path() . '/app/public/pdf/' . $id . '.pdf';

            if (file_exists($path))
                unlink($path);

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

    public function update(Request $request, $id)
    {

        $v = Validator::make($request->all(), [
            'tgl_dokumen' => 'required',
            'klien_id'  => 'required',
            'klien_penanggung_jawab_baut' => 'required',
            'klien_jabatan_penanggung_jawab_baut' => 'required',
            'klien_lokasi_kerja_baut' => 'required',
            'klien_nama_baut' => 'required',
        ]);

        if ($v->fails()) {
            return response()->json([
                'data' => null,
                'succes' => false,
                'message' => $v->errors()
            ], 422);
        }

        try {
            $data = TrOloBa::findOrFail($id);
            if ($data) {

                DB::beginTransaction();
                try {

                    $data->tgl_dokumen                         = $request->tgl_dokumen;
                    $data->klien_id                            = $request->klien_id;
                    $data->klien_penanggung_jawab_baut         = $request->klien_penanggung_jawab_baut;
                    $data->klien_jabatan_penanggung_jawab_baut = $request->klien_jabatan_penanggung_jawab_baut;
                    $data->klien_lokasi_kerja_baut             = $request->klien_lokasi_kerja_baut;
                    $data->klien_nama_baut                     = $request->klien_nama_baut;

                    $detail = TrOloBaDetail::where('olo_ba_id', $data->id);
                    $addon_detail = TrOloBaDetailAddOn::where('olo_ba_id', $data->id);
                    $addon_detail->delete();
                    $detail->delete();

                    $details = $request->detail;

                    $counter = 0;
                    $sigma = 0;
                    foreach ($details as $detail) {
                        $counter++;

                        $ba_site                    = new TrOloBaDetail();
                        $ba_site->olo_ba_id         = $id;
                        $ba_site->id                = $counter;
                        $ba_site->ao_sc_order       = $detail['ao_sc_order'];
                        $ba_site->sid               = $detail['sid'];
                        $ba_site->produk_id         = $detail['produk_id'];
                        $ba_site->produk            = $detail['produk'];
                        $ba_site->bandwidth_mbps    = $detail['bandwidth_mbps'] ?: null;
                        $ba_site->jenis_order       = $detail['jenis_order'];
                        $ba_site->jenis_order_id    = $detail['jenis_order_id'];
                        $ba_site->alamat_instalasi  = $detail['alamat_instalasi'];
                        $ba_site->tgl_order      = $detail['tgl_order'];
                        $ba_site->dibuat_oleh       = Auth::user()->id;
                        $ba_site->save();

                        if (count($detail['add_on']) > 0) {
                            $add_ons = $detail['add_on'];
                            foreach ($add_ons as $add_on) {
                                $ba_add_on = new TrOloBaDetailAddOn();
                                $ba_add_on->olo_ba_id       = $id;
                                $ba_add_on->id              = $counter;
                                $ba_add_on->add_on_id       = $add_on['add_on_id'];
                                $ba_add_on->nama_add_on     = $add_on['nama_add_on'];
                                $ba_add_on->satuan          = $add_on['satuan'];
                                $ba_add_on->jumlah          = $add_on['jumlah'];
                                $ba_add_on->save();
                            }
                        }

                        $check_produk = MaOloProduk::find($detail['produk_id']);
                        if ($check_produk->sigma)
                            $sigma++;
                    }

                    if ($sigma > 0 && $data->no_dokumen_bast == null) {
                        $no_dokumen_bast = UtilityHelper::checkNomorDokumen();
                        $dokumen = new MaNomorDokumen();
                        $dokumen->id = Uuid::uuid4()->toString();
                        $dokumen->no_dokumen = $no_dokumen_bast;
                        $dokumen->tipe_dokumen = 'OLO_BAST';
                        $dokumen->tgl_dokumen = date('Y-m-d');
                        $dokumen->save();

                        $data->no_dokumen_bast = $no_dokumen_bast;
                    }

                    if ($sigma == 0 && $data->no_dokumen_bast != null) {
                        $dokumen = MaNomorDokumen::where('no_dokumen', $data->no_dokumen_bast)->first();
                        $dokumen->delete();

                        $data->no_dokumen_bast = null;
                    }

                    $data->update();

                    DB::commit();

                    return (new TrOloBaResource($data))->additional([
                        'success' => true,
                        'message' => 'Data Berhasil Diiupdate',
                    ]);
                } catch (\Exception $e) {
                    DB::rollback();
                    return response()->json([
                        'data' => $e->getMessage(),
                        'success' => true,
                        'message' => 'error',
                    ], 400);
                }
            } else {
                return response()->json([
                    'data' => null,
                    'success' => false,
                    'message' => 'Data Tidak Ditemukan',
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'data' => null,
                'success' => false,
                'message' => $th->getMessage(),
            ], 404);
        }
    }


    public function fileBA($id, $tipe)
    {
        $jenis_dokumen = array(
            "Dokumen uji terima",
            "Data Parameter & Topology E2E",
            "Data Konfigurasi Tiap Node"
        );

        $data = TrOloBa::where('id', $id)->first();

        $detail = TrOloBaDetail::where('olo_ba_id', $id);

        $detail = DB::table(DB::raw('tr_olo_ba_details tr, ma_olo_produks p'))
            ->select(DB::raw("tr.*"))
            ->whereRaw("p.id = tr.produk_id")
            ->where('tr.olo_ba_id', $id);

        if ($tipe == 'bast') {
            $detail = $detail->where('sigma', true)->get();
        } else {
            $detail = $detail->get();
        }

        $hari = date('N', strtotime($data->tgl_dokumen));
        $tgl = date('j', strtotime($data->tgl_dokumen));
        $bulan = date('n', strtotime($data->tgl_dokumen));
        $tahun = date('Y', strtotime($data->tgl_dokumen));

        $format_tanggal = new \stdClass();
        $format_tanggal->hari = $this->_hari[$hari - 1];
        $format_tanggal->tgl = strtoupper(UtilityHelper::terbilang($tgl));
        $format_tanggal->tgl_nomor = $tgl;
        $format_tanggal->bulan = $this->_month[$bulan - 1];
        $format_tanggal->tahun_nomor = $tahun;
        $format_tanggal->tahun = strtoupper(UtilityHelper::terbilang($tahun));

        $people_ttd = new \stdClass();
        $people_ttd->manager_wholesale = MaPengaturan::where('nama', 'MANAGER_WHOLESALE_SUPPORT')->first();

        $header_html = view()->make('olo_header')->render();
        $footer_html = view()->make('olo_footer')->render();


        if ($tipe == 'baut') {
            $pdf = PDF::loadView('olo_baut', [
                'data'          => $data,
                'detail'        => $detail,
                'format_tanggal' => $format_tanggal,
                'people_ttd'    => $people_ttd
            ])
                ->setOption('footer-html', $footer_html)
                ->setOption('header-html', $header_html)
                ->setPaper('a4');

            // return $pdf->stream('baut.pdf');
            return $pdf->download('baut.pdf');
        } else if ($tipe == 'bast') {
            $pdf = PDF::loadView('olo_bast', [
                'data'          => $data,
                'detail'        => $detail,
                'format_tanggal' => $format_tanggal,
                'people_ttd'    => $people_ttd
            ])
                ->setOption('footer-html', $footer_html)
                ->setOption('header-html', $header_html)
                ->setPaper('a4');

            // return $pdf->stream('baut.pdf');
            return $pdf->download('baut.pdf');
        }

        // $file_name = $id.'.pdf';

        // Storage::put('public/pdf/'.$file_name, $pdf->output());

        // return $file_name;



        // // return view('dualhoming',  [
        // //     'jenis_dokumen'     => $jenis_dokumen,
        // //     'data_wo'           => $data_wo,
        // //     'data_site'         => $data_site,
        // //     'data_ba'           => $data_ba,
        // //     'dasar_permintaan'  => $dasar_permintaan,
        // //     'total_bw'          => $total_bw,
        // //     'total_site'        => $total_site,
        // //     'format_tanggal'    => $format_tanggal,
        // //     'people_ttd'        => $people_ttd
        // // ]);

    }

    public static function formatAddOn($id, $olo_ba_id)
    {
        $text = '';

        $data = TrOloBaDetailAddOn::where('id', $id)->where('olo_ba_id', $olo_ba_id)->get();

        for ($i = 0; $i < count($data); $i++) {
            $text = $text . $data[$i]['nama_add_on'] . ' ' . $data[$i]['jumlah'] . ' ' . $data[$i]['satuan'];
            if ($i + 1 !== count($data))
                $text = $text . ', ';
        }

        return $text;
    }

    public function reportView()
    {
        $data = TrOloBaDetail::with('addOn');
        if (isset($_GET['page'])) {

            if (isset($_GET['q'])) {
                $q = $_GET['q'];
                $data = $data->whereRaw("(produk like '%$q%' or jenis_order like '%$q%')");
            }
            $per_page = 50;
            if (isset($_GET['per_page']))
                $per_page = $_GET['per_page'];
            $data = $data->orderBy('created_at')->paginate($per_page)->onEachSide(5);
        } else {
            $data = $data->orderBy('created_at')->get();
        }

        return TrOloBaDetailResource::collection(($data))->additional([
            'success' => true,
            'message' => null,
        ]);
    }

    public function reportDownload()
    {
        $data = new TrOloBaDetail;

        $filter = "";
        if (isset($_GET['filter'])) {
            $filter = $_GET['filter'];
        }

        return Excel::download(new OloExport($filter), 'olo.xlsx');
    }
}

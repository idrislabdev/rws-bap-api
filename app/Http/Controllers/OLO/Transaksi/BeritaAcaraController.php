<?php

namespace App\Http\Controllers\OLO\Transaksi;

use App\Exports\OloExport;
use App\Helper\UtilityHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\OloViewResource;
use App\Http\Resources\TrOloBaDetailResource;
use App\Http\Resources\TrOloBaLampiranResource;
use App\Http\Resources\TrOloBaResource;
use App\Models\DraftOloBa;
use App\Models\MaNomorDokumen;
use App\Models\MaOloKlien;
use App\Models\MaOloProduk;
use App\Models\MaPengaturan;
use App\Models\MaPengguna;
use App\Models\TrOloBa;
use App\Models\TrOloBaDetail;
use App\Models\TrOloBaDetailAddOn;
use App\Models\TrOloBaLampiran;
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
        $data = TrOloBa::with('dibuatOleh')->where('tipe_ba', 'OLO');
        if (isset($_GET['page'])) {

            if (isset($_GET['q'])) {
                $q = $_GET['q'];
                $data = $data->whereRaw("(klien_nama_baut like '%$q%' or 
                                          klien_lokasi_kerja_baut like '%$q%' or
                                          no_dokumen_baut like '%$q%' or 
                                          no_dokumen_bast like '%$q%' or 
                                          jenis_order like '%$q%' or 
                                          klien_penanggung_jawab_baut like '%$q%'

                )");
            }
            if (isset($_GET['filter']))
            {
                $filter = $_GET['filter'];
                if($filter == 'belum_paraf')
                    $data = $data->whereNull('paraf_wholesale')->whereNull('manager_wholesale')->whereNull('dokumen_sirkulir');

                if($filter == 'belum_ttd')
                    $data = $data->whereNotNull('paraf_wholesale')->whereNull('manager_wholesale')->whereNull('dokumen_sirkulir');

                if($filter == 'belum_upload')
                    $data = $data->whereNotNull('paraf_wholesale')->whereNotNull('manager_wholesale')->whereNull('dokumen_sirkulir');

                if($filter == 'selesai')
                    $data = $data->whereNotNull('paraf_wholesale')->whereNotNull('manager_wholesale')->whereNotNull('dokumen_sirkulir');

            }

            if (isset($_GET['sort_by']))
            {
                if ($_GET['sort'] == 'asc') {
                    $data = $data->orderBy($_GET['sort_by']);
                } else {
                    $data = $data->orderByDesc($_GET['sort_by']);
                }
            } else {
                $data = $data->orderByDesc('tgl_dokumen');

            }

            $per_page = 50;
            if (isset($_GET['per_page']))
                $per_page = $_GET['per_page'];


            $data = $data->paginate($per_page)->onEachSide(5);
        } else {
            $data = $data->get();
        }

        return TrOloBaResource::collection(($data))->additional([
            'success' => true,
            'message' => null,
        ]);
    }

    public function checkNomor(Request $request)
    {
        $v = Validator::make($request->all(), [
            'jenis_ba' => 'required',
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

            if ($request->jenis_ba === 'BAST') {
                $no_dokumen_bast = UtilityHelper::checkNomorDokumen();
                $data = new MaNomorDokumen();
                $data->id = Uuid::uuid4()->toString();
                $data->no_dokumen = $no_dokumen_bast;
                $data->tipe_dokumen = 'OLO_BAST';
                $data->tgl_dokumen = date('Y-m-d');
                $data->save();

                DB::rollBack();

                $dokumen = new \stdClass();
                $dokumen->no_dokumen_baut = null;
                $dokumen->no_dokumen_bast = $no_dokumen_bast;

                return response()->json([
                    'data' => $dokumen,
                    'success' => true,
                    'message' => null,
                ], 200);
            } else if ($request->jenis_ba === 'BAUT') {
                $no_dokumen_baut = UtilityHelper::checkNomorDokumen();
                $data = new MaNomorDokumen();
                $data->id = Uuid::uuid4()->toString();
                $data->no_dokumen = $no_dokumen_baut;
                $data->tipe_dokumen = 'OLO_BAUT';
                $data->tgl_dokumen = date('Y-m-d');
                $data->save();

                DB::rollBack();

                $dokumen = new \stdClass();
                $dokumen->no_dokumen_baut = $no_dokumen_baut;
                $dokumen->no_dokumen_bast = null;

                return response()->json([
                    'data' => $dokumen,
                    'success' => true,
                    'message' => null,
                ], 200);
            } else if ($request->jenis_ba === 'BAST DAN BAUT') {
                $no_dokumen_baut = UtilityHelper::checkNomorDokumen();
                $data = new MaNomorDokumen();
                $data->id = Uuid::uuid4()->toString();
                $data->no_dokumen = $no_dokumen_baut;
                $data->tipe_dokumen = 'OLO_BAUT';
                $data->tgl_dokumen = date('Y-m-d');
                $data->save();

                $no_dokumen_bast = UtilityHelper::checkNomorDokumen();
                $data = new MaNomorDokumen();
                $data->id = Uuid::uuid4()->toString();
                $data->no_dokumen = $no_dokumen_bast;
                $data->tipe_dokumen = 'OLO_BAST';
                $data->tgl_dokumen = date('Y-m-d');
                $data->save();

                DB::rollBack();

                $dokumen = new \stdClass();
                $dokumen->no_dokumen_baut = $no_dokumen_baut;
                $dokumen->no_dokumen_bast = $no_dokumen_bast;

                return response()->json([
                    'data' => $dokumen,
                    'success' => true,
                    'message' => null,
                ], 200);
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

    public function store(Request $request)
    {
        $v = Validator::make($request->all(), [
            'tgl_dokumen' => 'required',
            'klien_id'  => 'required',
            'klien_penanggung_jawab_baut' => 'required',
            'klien_jabatan_penanggung_jawab_baut' => 'required',
            'klien_lokasi_kerja_baut' => 'required',
            'klien_nama_baut' => 'required',
            'jenis_ba' => 'required',
            'draft_id' => 'required'
        ]);

        if ($v->fails()) {
            return response()->json([
                'data' => null,
                'succes' => false,
                'message' => $v->errors()
            ], 422);
        }

        $check_bast = null;
        $check_baut = null;

        if ($request->jenis_ba === 'BAUT') {
            $check_baut = MaNomorDokumen::where('no_dokumen', $request->no_dokumen_baut)->first();
        } else if ($request->jenis_ba === 'BAST') {
            $check_bast = MaNomorDokumen::where('no_dokumen', $request->no_dokumen_bast)->first();
        } else if ($request->jenis_ba === 'BAST DAN BAUT') {
            $check_baut = MaNomorDokumen::where('no_dokumen', $request->no_dokumen_baut)->first();
            $check_bast = MaNomorDokumen::where('no_dokumen', $request->no_dokumen_bast)->first();
        }

        if ($check_baut || $check_bast) {

            $dokumen = new \stdClass();
            $dokumen->no_dokumen_baut = ($check_baut) ? 'Nomor BAUT Sudah Pernah Digunakan' : '';
            $dokumen->no_dokumen_bast = ($check_bast) ? 'Nomor BAST Sudah Pernah Digunakan' : '';

            return response()->json([
                'data' => $dokumen,
                'success' => false,
                'message' => 'Maaf, No dokumen sudah pernah digunakan sebelumnya.',
            ], 422);
        }

        $check_klien = MaOloKlien::find($request->klien_id);
        if (!$check_klien) {
            return response()->json([
                'data' => null,
                'success' => false,
                'message' => 'Data Tidak Ditemukan',
            ], 404);
        }

        DB::beginTransaction();
        try {
            $no_dokumen_bast = null;
            $no_dokumen_baut = null;

            if ($request->jenis_ba == 'BAST') {
                $no_dokumen_bast = $request->no_dokumen_bast;
                $data = new MaNomorDokumen();
                $data->id = Uuid::uuid4()->toString();
                $data->no_dokumen = $no_dokumen_bast;
                $data->tipe_dokumen = 'OLO_BAST';
                $data->tgl_dokumen = $request->tgl_dokumen;
                $data->save();

                $ba = new TrOloBa();
                $id = Uuid::uuid4()->toString();
                $ba->id                                  = $id;
                $ba->no_dokumen_bast                     = $no_dokumen_bast;
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
                $ba->jenis_ba                            = $request->jenis_ba;
                $ba->alamat_bast                         = $request->alamat_bast;
                $ba->save();

                $details = json_decode($request->detail);

                $counter = 0;
                $sigma = 0;
                foreach ($details as $detail) {
                    $counter++;

                    $ba_site                    = new TrOloBaDetail();
                    $ba_site->olo_ba_id         = $id;
                    $ba_site->id                = $counter;
                    $ba_site->ao_sc_order       = $detail->ao_sc_order;
                    $ba_site->sid               = $detail->sid;
                    $ba_site->produk_id         = $detail->produk_id;
                    $ba_site->produk            = $detail->produk;
                    $ba_site->bandwidth_mbps    = $detail->bandwidth_mbps ?: null;
                    $ba_site->jenis_order       = $detail->jenis_order;
                    $ba_site->jenis_order_id    = $detail->jenis_order_id;
                    $ba_site->alamat_instalasi  = $detail->alamat_instalasi;
                    $ba_site->tgl_order         = $detail->tgl_order;
                    $ba_site->dibuat_oleh       = Auth::user()->id;
                    $ba_site->save();

                    if (count($detail->add_on) > 0) {
                        $add_ons = $detail->add_on;
                        foreach ($add_ons as $add_on) {
                            $ba_add_on = new TrOloBaDetailAddOn();
                            $ba_add_on->olo_ba_id       = $id;
                            $ba_add_on->id              = $counter;
                            $ba_add_on->add_on_id       = $add_on->add_on_id;
                            $ba_add_on->nama_add_on     = $add_on->nama_add_on;
                            $ba_add_on->satuan          = $add_on->satuan;
                            $ba_add_on->jumlah          = $add_on->jumlah;
                            $ba_add_on->save();
                        }
                    }
                }

                DraftOloBa::where('id', $request->draft_id)->delete();

                DB::commit();

                return (new TrOloBaResource($ba))->additional([
                    'success' => true,
                    'message' => 'Data Berhasil Dibuat',
                ]);
            }
            if ($request->jenis_ba == 'BAUT') {
                $no_dokumen_baut = $request->no_dokumen_baut;
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
                $ba->jenis_ba                            = $request->jenis_ba;
                $ba->save();

                $details = json_decode($request->detail);

                $counter = 0;
                $sigma = 0;
                foreach ($details as $detail) {
                    $counter++;

                    $ba_site                    = new TrOloBaDetail();
                    $ba_site->olo_ba_id         = $id;
                    $ba_site->id                = $counter;
                    $ba_site->ao_sc_order       = $detail->ao_sc_order;
                    $ba_site->sid               = $detail->sid;
                    $ba_site->produk_id         = $detail->produk_id;
                    $ba_site->produk            = $detail->produk;
                    $ba_site->bandwidth_mbps    = $detail->bandwidth_mbps ?: null;
                    $ba_site->jenis_order       = $detail->jenis_order;
                    $ba_site->jenis_order_id    = $detail->jenis_order_id;
                    $ba_site->alamat_instalasi  = $detail->alamat_instalasi;
                    $ba_site->tgl_order         = $detail->tgl_order;
                    $ba_site->dibuat_oleh       = Auth::user()->id;
                    $ba_site->save();

                    if (count($detail->add_on) > 0) {
                        $add_ons = $detail->add_on;
                        foreach ($add_ons as $add_on) {
                            $ba_add_on = new TrOloBaDetailAddOn();
                            $ba_add_on->olo_ba_id       = $id;
                            $ba_add_on->id              = $counter;
                            $ba_add_on->add_on_id       = $add_on->add_on_id;
                            $ba_add_on->nama_add_on     = $add_on->nama_add_on;
                            $ba_add_on->satuan          = $add_on->satuan;
                            $ba_add_on->jumlah          = $add_on->jumlah;
                            $ba_add_on->save();
                        }
                    }
                }

                $url_arr = array();;

                if ($request->file('lampirans')) {
                    foreach ($request->file('lampirans') as $lampiran) {
                        $url = $this->prosesUpload($lampiran);
                        array_push($url_arr, $url);
                    }
                }

                $counter = 0;
                foreach ($url_arr as $lampiran_url) {
                    $counter++;
                    $data = new TrOloBaLampiran();
                    $data->olo_ba_id = $id;
                    $data->id = $counter;
                    $data->url = $request->tipe;
                    $data->url = $lampiran_url;
                    $data->dibuat_oleh = Auth::user()->id;
                    $data->save();
                }

                DraftOloBa::where('id', $request->draft_id)->delete();

                DB::commit();

                return (new TrOloBaResource($ba))->additional([
                    'success' => true,
                    'message' => 'Data Berhasil Dibuat',
                ]);
            } else if ($request->jenis_ba == 'BAST DAN BAUT') {
                $no_dokumen_baut = $request->no_dokumen_baut;
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
                $ba->jenis_ba                            = $request->jenis_ba;
                $ba->alamat_bast                         = $request->alamat_bast;
                $ba->save();

                $details = json_decode($request->detail);

                $counter = 0;
                $sigma = 0;
                foreach ($details as $detail) {
                    $counter++;

                    $ba_site                    = new TrOloBaDetail();
                    $ba_site->olo_ba_id         = $id;
                    $ba_site->id                = $counter;
                    $ba_site->ao_sc_order       = $detail->ao_sc_order;
                    $ba_site->sid               = $detail->sid;
                    $ba_site->produk_id         = $detail->produk_id;
                    $ba_site->produk            = $detail->produk;
                    $ba_site->bandwidth_mbps    = $detail->bandwidth_mbps ?: null;
                    $ba_site->jenis_order       = $detail->jenis_order;
                    $ba_site->jenis_order_id    = $detail->jenis_order_id;
                    $ba_site->alamat_instalasi  = $detail->alamat_instalasi;
                    $ba_site->tgl_order         = $detail->tgl_order;
                    $ba_site->dibuat_oleh       = Auth::user()->id;
                    $ba_site->save();

                    if (count($detail->add_on) > 0) {
                        $add_ons = $detail->add_on;
                        foreach ($add_ons as $add_on) {
                            $ba_add_on = new TrOloBaDetailAddOn();
                            $ba_add_on->olo_ba_id       = $id;
                            $ba_add_on->id              = $counter;
                            $ba_add_on->add_on_id       = $add_on->add_on_id;
                            $ba_add_on->nama_add_on     = $add_on->nama_add_on;
                            $ba_add_on->satuan          = $add_on->satuan;
                            $ba_add_on->jumlah          = $add_on->jumlah;
                            $ba_add_on->save();
                        }
                    }

                    $check_produk = MaOloProduk::find($detail->produk_id);
                    if ($check_produk->sigma)
                        $sigma++;
                }

                if ($sigma > 0) {
                    $no_dokumen_bast = $request->no_dokumen_bast;
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

                $url_arr = array();;

                if ($request->file('lampirans')) {
                    foreach ($request->file('lampirans') as $lampiran) {
                        $url = $this->prosesUpload($lampiran);
                        array_push($url_arr, $url);
                    }
                }

                $counter = 0;
                foreach ($url_arr as $lampiran_url) {
                    $counter++;
                    $data = new TrOloBaLampiran();
                    $data->olo_ba_id = $id;
                    $data->id = $counter;
                    $data->url = $request->tipe;
                    $data->url = $lampiran_url;
                    $data->dibuat_oleh = Auth::user()->id;
                    $data->save();
                }

                DraftOloBa::where('id', $request->draft_id)->delete();

                DB::commit();

                return (new TrOloBaResource($ba))->additional([
                    'success' => true,
                    'message' => 'Data Berhasil Dibuat',
                ]);
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

    public function show($id)
    {
        try {
            $data = TrOloBa::with('lampiran')->with('klien')->with('detail.addOn')->findOrFail($id);
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

            $check = TrOloBa::where('id', $id)->first();
            if ($check) {
                TrOloBa::where('id', $id)->delete();


                if ($check->no_dokumen_baut != null)
                    MaNomorDokumen::where('no_dokumen', $check->no_dokumen_baut)->delete();

                if ($check->no_dokumen_bast != null)
                    MaNomorDokumen::where('no_dokumen', $check->no_dokumen_bast)->delete();

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

    public function update(Request $request, $id)
    {

        $v = Validator::make($request->all(), [
            'tgl_dokumen' => 'required',
            'klien_id'  => 'required',
            'klien_penanggung_jawab_baut' => 'required',
            'klien_jabatan_penanggung_jawab_baut' => 'required',
            'klien_lokasi_kerja_baut' => 'required',
            'klien_nama_baut' => 'required',
            'jenis_ba' => 'required'
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
                    $data->alamat_bast                         = $request->alamat_bast;

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

        $lampiran = TrOloBaLampiran::where('olo_ba_id', $id)->get();

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

        $paraf_wholesale = json_decode($data->paraf_wholesale_data);
        $manager_wholesale = json_decode($data->manager_wholesale_data);


        if ($tipe == 'baut') {
            $pdf = PDF::loadView('olo_baut', [
                'data'              => $data,
                'detail'            => $detail,
                'lampiran'          => $lampiran,
                'format_tanggal'    => $format_tanggal,
                'people_ttd'        => $people_ttd,
                'paraf_wholesale'   => $paraf_wholesale,
                'manager_wholesale' => $manager_wholesale,

            ])
                ->setOption('footer-html', $footer_html)
                ->setOption('header-html', $header_html)
                ->setPaper('a4');

            $name = "BAUT_".$data->no_dokumen_baut . ".pdf";
            return $pdf->download($name);
        } else if ($tipe == 'bast') {
            $pdf = PDF::loadView('olo_bast', [
                'data'              => $data,
                'detail'            => $detail,
                'format_tanggal'    => $format_tanggal,
                'people_ttd'        => $people_ttd,
                'paraf_wholesale'   => $paraf_wholesale,
                'manager_wholesale' => $manager_wholesale,
            ])
                ->setOption('footer-html', $footer_html)
                ->setOption('header-html', $header_html)
                ->setPaper('a4');


            $name = "BAST_".$data->no_dokumen_bast . ".pdf";
            return $pdf->download($name);
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
        $data = DB::table(DB::raw('tr_olo_bas b, tr_olo_ba_details d'))
            ->select(
                DB::raw("*, (SELECT 
                                GROUP_CONCAT(CONCAT(nama_add_on, ' ', jumlah, ' ', satuan) SEPARATOR ', ')
                                FROM tr_olo_ba_detail_add_ons tr
                                    WHERE 
                                        tr.olo_ba_id = d.olo_ba_id 
                                    AND 
                                        tr.id = d.id) as add_ons")
            )
            ->where('tipe_ba', 'OLO')
            ->whereRaw('b.id = d.olo_ba_id');

        if (isset($_GET['page'])) {

            if (isset($_GET['q'])) {
                $q = $_GET['q'];
                $data = $data->whereRaw(
                    "(
                        produk like '%$q%' or 
                        ao_sc_order like '%$q%' or
                        sid like '%$q%' or
                        b.klien_nama_baut like '%$q%' or 
                        b.jenis_order like '%$q%' or 
                        b.no_dokumen_baut like '%$q%' or 
                        b.no_dokumen_bast like '%$q%'
                    )"
                );
            }
            $per_page = 50;
            if (isset($_GET['per_page']))
                $per_page = $_GET['per_page'];

            $data = $data->orderBy('d.created_at')->paginate($per_page)->onEachSide(5);
        } else {
            $data = $data->orderBy('d.created_at')->get();
        }

        return OloViewResource::collection(($data))->additional([
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

    public function addOnlist($olo_ba_id, $id)
    {
        $data = TrOloBaDetailAddOn::where('olo_ba_id', $olo_ba_id)->where('id', $id)->get();

        return response()->json([
            'status' => true,
            'message' => $data,
            'data' => null
        ], 200);
    }

    private function prosesUpload($file)
    {
        $nama_file = Uuid::uuid4()->toString();

        // $url =  Storage::putFileAs('public/image', $file, $nama_file.'.'.$file->getClientOriginalExtension());
        $file->move('lampirans/', $nama_file . '.' . $file->getClientOriginalExtension());

        return $nama_file . '.' . $file->getClientOriginalExtension();
    }

    private function prosesUploadDokumen($file)
    {
        $nama_file = Uuid::uuid4()->toString();
        $file->move('olo-sirkulir/', $nama_file . '.' . $file->getClientOriginalExtension());
        return $nama_file . '.' . $file->getClientOriginalExtension();
    }

    public function updateLampiran(Request $request, $olo_ba_id)
    {
        $v = Validator::make($request->all(), [
            'lampiran' => 'required'
        ]);

        if ($v->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'error',
                'data' => $v->errors()
            ], 422);
        }


        $data = TrOloBa::where('id', $olo_ba_id)->first();

        if (!$data) {
            return response()->json([
                'status' => false,
                'message' => 'Data Tidak Ditemukan',
                'data' => null
            ], 404);
        }

        DB::beginTransaction();
        try {

            $counter = TrOloBaLampiran::where('olo_ba_id', $olo_ba_id)
                ->max("id");

            $url = $this->prosesUpload($request->file('lampiran'));

            $counter++;
            $data = new TrOloBaLampiran();
            $data->olo_ba_id = $olo_ba_id;
            $data->id = $counter;
            $data->url = $request->tipe;
            $data->url = $url;
            $data->dibuat_oleh = Auth::user()->id;
            $data->save();

            DB::commit();

            return (new TrOloBaLampiranResource($data))->additional([
                'success' => true,
                'message' => 'Data Lampiran Berhasil Diupdate',
                'data' => $data
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

    public function removeLampiran($olo_ba_id, $id)
    {
        $data = TrOloBaLampiran::where('olo_ba_id', $olo_ba_id)->where('id', $id)->first();

        if (!$data) {
            return response()->json([
                'status' => false,
                'message' => 'Data Tidak Ditemukan',
                'data' => null
            ], 404);
        }

        DB::beginTransaction();
        try {

            $path = public_path() . '/lampirans/' . $data->url;
            if (file_exists($path))
                unlink($path);

            $data = TrOloBaLampiran::where('olo_ba_id', $olo_ba_id)->where('id', $id)->delete();

            DB::commit();

            return response()->json([
                'progress' => true,
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

    public function parafWholesale($id)
    {
        $data = TrOloBa::find($id);
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
        $data = TrOloBa::find($id);
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

            $data->manager_wholesale_data  = json_encode($manager_wholesale, JSON_PRETTY_PRINT);
            $data->save();
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

    public function uploadDokumen(Request $request, $olo_ba_id) 
    {
        $data = TrOloBa::find($olo_ba_id);

        if(!$data)
        {
            return response()->json([
                'status' => false,
                'message' => 'Data Tidak Ditemukan',
                'data' => null
            ], 404);
        }

        DB::beginTransaction();
        try {
            $url = $this->prosesUploadDokumen($request->file('file'));

            $data->dokumen_sirkulir = $url;
            $data->save();

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

    public function dokumenSirkulir($name)
    {
        $storagePath = public_path().'/olo-sirkulir/'.$name;
        return response()->file($storagePath);
    }


    public function fileLampiran($name)
    {
        $storagePath = public_path() . '/lampirans/' . $name;

        return response()->file($storagePath);
    }
}

<?php

namespace App\Http\Controllers\TIF\Transaksi;

use App\Http\Controllers\Controller;
use App\Http\Resources\DraftOloBaResource;
use App\Models\DraftOloBa;
use App\Models\DraftOloBaDetail;
use App\Models\DraftOloBaDetailAddOn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;
 
class DraftBeritaAcaraController extends Controller
{
    public function index()
    {
        $data = DraftOloBa::with('dibuat')->where('tipe_ba', 'OLO_TIF')->where('dibuat_oleh', Auth::user()->id);
        if (isset($_GET['page'])) {

            if (isset($_GET['q'])) {
                $q = $_GET['q'];
                $data = $data->whereRaw("(klien_nama_baut like '%$q%' or klien_lokasi_kerja_baut like '%$q%')");
            }
            $per_page = 50;
            if (isset($_GET['per_page']))
                $per_page = $_GET['per_page'];

            $data = $data->orderByDesc('tgl_dokumen')->paginate($per_page)->onEachSide(5);
        } else {
            $data = $data->get();
        }

        return DraftOloBaResource::collection(($data))->additional([
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
            'jenis_ba' => 'required'
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
            $ba = new DraftOloBa();
            $id = Uuid::uuid4()->toString();
            $ba->id                                  = $id;
            $ba->tgl_dokumen                         = $request->tgl_dokumen;
            $ba->klien_id                            = $request->klien_id;
            $ba->klien_penanggung_jawab_baut         = $request->klien_penanggung_jawab_baut;
            $ba->klien_jabatan_penanggung_jawab_baut = $request->klien_jabatan_penanggung_jawab_baut;
            $ba->klien_lokasi_kerja_baut             = $request->klien_lokasi_kerja_baut;
            $ba->klien_nama_baut                     = $request->klien_nama_baut;
            $ba->jenis_order                         = $request->jenis_order;
            $ba->jenis_order_id                      = $request->jenis_order_id;
            $ba->dibuat_oleh                         = Auth::user()->id;
            $ba->jenis_ba                            = $request->jenis_ba;
            $ba->alamat_bast                         = $request->alamat_bast;
            $ba->tipe_ba                             = 'OLO_TIF';
            $ba->save();

            $details = $request->detail;

            $counter = 0;
            $sigma = 0;
            foreach ($details as $detail) {
                $counter++;

                $ba_site                    = new DraftOloBaDetail();
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
                        $ba_add_on = new DraftOloBaDetailAddOn();
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

            DB::commit();

            return (new DraftOloBaResource($ba))->additional([
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
            $data = DraftOloBa::findOrFail($id);
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

                    $detail = DraftOloBaDetail::where('olo_ba_id', $data->id);
                    $addon_detail = DraftOloBaDetailAddOn::where('olo_ba_id', $data->id);
                    $addon_detail->delete();
                    $detail->delete();

                    $details = $request->detail;

                    $counter = 0;
                    $sigma = 0;
                    foreach ($details as $detail) {
                        $counter++;

                        $ba_site                    = new DraftOloBaDetail();
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
                                $ba_add_on = new DraftOloBaDetailAddOn();
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

                    return (new DraftOloBaResource($data))->additional([
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

    public function show($id)
    {
        try {
            $data = DraftOloBa::with('klien')->with('detail.addOn')->findOrFail($id);
            if ($data) {
                return (new DraftOloBaResource($data))->additional([
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

            $check = DraftOloBa::where('id', $id)->first();
            if ($check) {
                DraftOloBa::where('id', $id)->delete();

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
}

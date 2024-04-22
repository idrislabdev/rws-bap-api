<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Resources\MaSarpenTemplateResource;
use App\Models\MaSarpenTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;
use App\Helper\UtilityHelper;
use App\Models\MaPengguna;
use PDF2;


class SarpenTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
     private $_hari = ['SENIN', 'SELASA', 'RABU', 'KAMIS', 'JUMAT', 'SABTU', 'MINGGU'];
     private $_month = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

    public function index()
    {
        $data = new MaSarpenTemplate;
        if (isset($_GET['page'])) {

            if (isset($_GET['q'])) {
                $q = $_GET['q'];
                $data = $data->whereRaw("(nama like '%$q%' or group like '%$q%')");
            }

            $data = $data->orderBy('created_at')->paginate(25)->onEachSide(5);
        } else {
            $data = $data->orderBy('created_at')->get();
        }

        return MaSarpenTemplateResource::collection($data)->additional([
            'success' => true,
            'message' => null
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
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
            'nama' => 'required',
            'group' => 'in:TELKOM,OTHER,IPTV',
            'sto_site' => 'in:STO,SITE,NO_ORDER',
            'tower' => 'required',
            'rack' => 'required',
            'ruangan' => 'required',
            'catu_daya_mcb' => 'required',
            'catu_daya_genset' => 'required',
            'service' => 'required',
            'akses' => 'required',
            'catatan' => 'required',
            'paraf_wholesale' => 'required',
            'manager_wholesale' => 'required',
        ]);

        if ($v->fails()) {
            return response()->json([
                'data' => null,
                'succes' => false,
                'message' => $v->errors()
            ], 422);
        }

        try {
            $data = new MaSarpenTemplate();
            $data->id = Uuid::uuid4()->toString();
            $data->nama = $request->nama;
            $data->group = $request->group;
            $data->sto_site = $request->sto_site;
            $data->ne_iptv = $request->ne_iptv;
            $data->tower = $request->tower;
            $data->rack = $request->rack;
            $data->ruangan = $request->ruangan;
            $data->lahan = $request->lahan;
            $data->catu_daya_mcb = $request->catu_daya_mcb;
            $data->catu_daya_genset = $request->catu_daya_genset;
            $data->service = $request->service;
            $data->akses = $request->akses;
            $data->catatan = $request->catatan;
            $data->paraf_wholesale = $request->paraf_wholesale;
            $data->manager_wholesale = $request->manager_wholesale;
          
            $data->save();

            return (new MaSarpenTemplateResource($data))->additional([
                'success' => true,
                'message' => 'Data Baru Telah Dibuat'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'data' => null,
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $data = MaSarpenTemplate::findOrFail($id);
            return (new MaSarpenTemplateResource($data))->additional([
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        try {
            $data = MaSarpenTemplate::findOrFail($id);

            if ($request->has('ne_iptv')) {
                $data->ne_iptv = $request->ne_iptv;
            }
            if ($request->has('tower')) {
                $data->tower = $request->tower;
            }
            if ($request->has('rack')) {
                $data->rack = $request->rack;
            }
            if ($request->has('tower')) {
                $data->tower = $request->tower;
            }
            if ($request->has('ruangan')) {
                $data->ruangan = $request->ruangan;
            }
            if ($request->has('lahan')) {
                $data->lahan = $request->lahan;
            }
            if ($request->has('catu_daya_mcb')) {
                $data->catu_daya_mcb = $request->catu_daya_mcb;
            }
            if ($request->has('catu_daya_genset')) {
                $data->catu_daya_genset = $request->catu_daya_genset;
            }
            if ($request->has('service')) {
                $data->service = $request->service;
            }
            if ($request->has('akses')) {
                $data->akses = $request->akses;
            }
            if ($request->has('paraf_wholesale')) {
                $data->paraf_wholesale = $request->paraf_wholesale;
            }
            if ($request->has('manager_wholesale')) {
                $data->manager_wholesale = $request->manager_wholesale;
            }


            $data->save();
            return (new MaSarpenTemplateResource($data))->additional([
                'success' => true,
                'message' => 'Data Berhasil Dirubah'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'data' => null,
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // abort(404);
        $data = MaSarpenTemplate::find($id);

        if (!$data) {
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

    public function preview($id)
    {
        $setting = MaSarpenTemplate::find($id);

        $tgl_dokumen = date('Y-m-d');
        $hari = date('N', strtotime($tgl_dokumen));
        $tgl = date('j', strtotime($tgl_dokumen));
        $bulan = date('n', strtotime($tgl_dokumen));
        $tahun = date('Y', strtotime($tgl_dokumen));
        $hari = date('N', strtotime($tgl_dokumen));

        $format_tanggal = new \stdClass();
        $format_tanggal->hari = $this->_hari[$hari-1];
        $format_tanggal->tgl = strtoupper(UtilityHelper::terbilang($tgl));
        $format_tanggal->tgl_nomor = $tgl;
        $format_tanggal->bulan = $this->_month[$bulan-1];
        $format_tanggal->tahun_nomor = $tahun;
        $format_tanggal->tahun = strtoupper(UtilityHelper::terbilang($tahun));

        // return view('sarpen',  [
        //     'format_tanggal'    => $format_tanggal,
        //     'tgl_dokumen'       => $tgl_dokumen
        // ]);

        $manager_wholesale = MaPengguna::find($setting->manager_wholesale);

        $pdf = PDF2::loadView('sarpen', [
            'setting'           => $setting,
            'format_tanggal'    => $format_tanggal,
            'tgl_dokumen'       => $tgl_dokumen,
            'manager_wholesale' => null,
            'manager_witel'     => null,
            'paraf_wholesale'   => null,
            'status'            => 'TEMPLATE',
            'klien'             => null,
            'site_survey'       => null,
            'towers'            => null,
            'ruangans'          => null,
            'lahans'            => null,
            'services'          => null,
            'racks'             => null,
            'akseses'           => null,
            'catu_daya_mcbs'    => null,
            'catu_daya_gensets' => null,
            'catatan'           => null,
            'paraf_wholesale_data' => null,
            'no_dokumen'        => null,
            'no_dokumen_klien'  => null,
            'gambar'            => null,
        ])->setPaper('a4');
        return $pdf->stream('berita_acara_sar[em.pdf');
    }

    public function dataTemplate($group)
    {
        $data = MaSarpenTemplate::where('group', $group);
        $data = $data->orderBy('created_at')->get();

        return MaSarpenTemplateResource::collection($data)->additional([
            'success' => true,
            'message' => null
        ]);
    }
}

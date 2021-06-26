<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Http\Resources\TrBaResource;
use App\Http\Resources\TrWoSiteResource;
use App\Models\TrBa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BeritaAcaraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = TrBa::with('dibuatOleh');
        if (isset($_GET['page'])) {
            
            if (isset($_GET['tipe'])){
                $tipe = $_GET['tipe'];
                $data = $data->where('tipe', $tipe);
            }

            if (isset($_GET['q'])){
                $q = $_GET['q'];
                $data = $data->whereRaw("(tsel_reg like '%$q%' or no_dokumen like '%$q%')");
            }

            $data = $data->orderBy('no_dokumen')->paginate(25)->onEachSide(5);
        } else {
            $data = $data->get();
        }

        return TrBaResource::collection(($data))->additional([
            'success' => true,
            'message' => null,
        ]);
    }

    public function indexSites($id)
    {
        $data = DB::table(DB::raw('tr_wo_sites tr')) 
                                    ->select(DB::raw("tr.*, 
                                                      trw.dasar_order, 
                                                      trw.lampiran_url,  
                                                      p.id pengguna_id, 
                                                      p.nama_lengkap,
                                                      b.no_dokumen,
                                                        (SELECT count(*) 
                                                            FROM 
                                                                tr_wo_site_lvs t
                                                            WHERE 
                                                                tr.wo_id = t.wo_id 
                                                            AND 
                                                                tr.wo_site_id = t.wo_site_id) as lv,

                                                        (SELECT count(*) 
                                                                FROM 
                                                                    tr_wo_site_qcs t
                                                                WHERE 
                                                                    tr.wo_id = t.wo_id 
                                                                AND 
                                                                    tr.wo_site_id = t.wo_site_id) as qc,

                                                        (SELECT count(*) 
                                                            FROM 
                                                                tr_wo_site_images ti
                                                            WHERE 
                                                                tr.wo_id = ti.wo_id 
                                                            AND 
                                                                tr.wo_site_id = ti.wo_site_id
                                                            AND
                                                                ti.tipe = 'KONFIGURASI') as konfigurasi,
                                                        
                                                        (SELECT count(*) 
                                                            FROM 
                                                                tr_wo_site_images ti
                                                            WHERE 
                                                                tr.wo_id = ti.wo_id 
                                                            AND 
                                                                tr.wo_site_id = ti.wo_site_id
                                                            AND
                                                                ti.tipe = 'TOPOLOGI') as topologi,
                                                                
                                                        (SELECT count(*) 
                                                                FROM 
                                                                    tr_wo_site_images ti
                                                                WHERE 
                                                                    tr.wo_id = ti.wo_id 
                                                                AND 
                                                                    tr.wo_site_id = ti.wo_site_id
                                                                AND
                                                                    ti.tipe = 'CAPTURE_TRAFIK') as capture_trafik"),
                                                        )
                                    ->leftJoin('tr_wos as trw','tr.wo_id', '=', 'trw.id')
                                    ->leftJoin('ma_penggunas as p','tr.dibuat_oleh', '=', 'p.id')
                                    ->leftJoin('tr_bas as b','tr.ba_id', '=', 'b.id')
                                    ->where("ba_id", $id);

        
        $q = $_GET['q'];
        
        if ($q) {
            $data = $data->whereRaw("(program like '%$q%' or 
                                    site_name like '%$q%' or 
                                    site_witel like '%$q%' or 
                                    tr.tsel_reg like '%$q%' or 
                                    site_id like '%$q%' or 
                                    dasar_order like '%$q%')");
        }
                                    
        $data = $data->orderBy('tr.created_at')->paginate(25)->onEachSide(5);       

        return TrWoSiteResource::collection(($data))->additional([
            'success' => true,
            'message' => null,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

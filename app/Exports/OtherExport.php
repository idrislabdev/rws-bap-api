<?php

namespace App\Exports;


use App\Models\TrOloBaDetail;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OtherExport implements FromView
{
    protected $filter;
    protected $status;
    protected $ba;

    function __construct($filter)
    {
        $this->filter = $filter;
    }

    public function view(): View
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
            ->where('tipe_ba', 'CNOP')
            ->whereRaw('b.id = d.olo_ba_id');

        if (isset($_GET['filter'])) {
            $filter = $_GET['filter'];
            $data = $data->whereRaw(
                "(
                        produk like '%$filter%' or 
                        ao_sc_order like '%$filter%' or
                        sid like '%$filter%' or
                        b.klien_nama_baut like '%$filter%' or 
                        b.jenis_order like '%$filter%' or 
                        b.no_dokumen_baut like '%$filter%' or 
                        b.no_dokumen_bast like '%$filter%'
                    )"
            );
        }

        $data = $data->orderBy('d.created_at')->get();

        return view('reports.other', [
            'data' => $data
        ]);
    }
}

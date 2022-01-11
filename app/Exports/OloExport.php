<?php

namespace App\Exports;

use App\Models\TrOloBaDetail;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OloExport implements FromView
{
    protected $filter;
    protected $status;
    protected $ba;

    function __construct($filter) {
        $this->filter = $filter;
    }

    public function view(): View
    {

        $data = new TrOloBaDetail;
        if ($this->filter != '') {
            $data = $data->whereRaw("(produk like '%$this->filter%' or jenis_order like '%$this->filter%')");
        }
                            
        $data = $data->orderBy('created_at')->get();   
        return view('reports.olo', [
            'data' => $data
        ]);  
    }
}

<?php

namespace App\Http\Controllers\SARPEN;

use App\Exports\SarpenAllExport;
use App\Exports\SarpenWitelExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Excel;

class ReportController extends Controller
{
    public function baSesuaiWitel()
    {
        $year = date('Y');
        if (isset($_GET['year'])){
            $year = $_GET['year'];
        }
        
        return Excel::download(new SarpenAllExport($year), 'sarpen_sesuai_witel.xlsx');
    }
}

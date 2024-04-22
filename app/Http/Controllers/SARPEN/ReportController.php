<?php

namespace App\Http\Controllers\SARPEN;

use App\Exports\SarpenAllExport;
use App\Exports\SarpenDashboardExport;
use App\Exports\SarpenWitelExport;
use App\Exports\TargetSarpenExport;
use App\Http\Controllers\Controller;
use App\Models\TrBaSarpenTarget;
use Illuminate\Http\Request;
use Excel;

class ReportController extends Controller
{
    public function baSesuaiWitel()
    {
        $year = date('Y');
        $group = 'ALL';
        $status = 'ALL';

        if (isset($_GET['year'])){
            $year = $_GET['year'];
        }

        if (isset($_GET['group'])){
            $group = $_GET['group'];
        }

        if (isset($_GET['status'])){
            $status = $_GET['status'];
        }
        
        return Excel::download(new SarpenAllExport($year, $group, $status), 'sarpen_sesuai_witel.xlsx');
    }

    public function baDashboard()
    {
        $year = date('Y');
        $group = 'ALL';
        $status = 'ALL';

        if (isset($_GET['year'])){
            $year = $_GET['year'];
        }

        if (isset($_GET['group'])){
            $group = $_GET['group'];
        }

        if (isset($_GET['status'])){
            $status = $_GET['status'];
        }
        
        return Excel::download(new SarpenDashboardExport($year, $group, $status), 'sarpen_sesuai_witel.xlsx');
    }

    public function targetSarpen()
    {
        $id = TrBaSarpenTarget::where('status', 'active')->first()->id;
        if (isset($_GET['id'])){
            $id = $_GET['id'];
        }
        
        return Excel::download(new TargetSarpenExport($id), 'target_sarpen_witel.xlsx');
    }
}

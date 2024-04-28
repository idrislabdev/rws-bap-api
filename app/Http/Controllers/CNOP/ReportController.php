<?php

namespace App\Http\Controllers\CNOP;

use App\Http\Controllers\Controller;
use App\Exports\DualHomingExport;
use App\Exports\NewLinkExport;
use App\Exports\RelokasiExport;
use App\Exports\UpgradeExport;
use Illuminate\Http\Request;
use Excel;

class ReportController extends Controller
{
    public function newlink()
    {
        $site_witel = "";
        $status = "";
        $ba = "";
        $tahun_order = "";
        $ba_sirkulir = "";


        if (isset($_GET['site_witel'])){
            $site_witel = $_GET['site_witel'];
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Pilih Witel Terlebih Dahulu',
                'data' => null
            ], 422);
        }

        if (isset($_GET['status'])){
            $status =  $_GET['status'];
        }

        if (isset($_GET['ba'])){
            $ba=$_GET['ba'];
        }

        if (isset($_GET['tahun'])){
            $tahun_order=$_GET['tahun'];
        }

        if (isset($_GET['ba_sirkulir'])){
            $ba_sirkulir=$_GET['ba_sirkulir'];
        }
        
        return Excel::download(new NewLinkExport($site_witel, $status, $ba, $tahun_order, $ba_sirkulir), 'newlink.xlsx');
    }

    public function upgrade()
    {
        $site_witel = "";
        $status = "";
        $ba = "";
        $tahun_order = "";
        $ba_sirkulir = "";

        
        if (isset($_GET['site_witel'])){
            $site_witel = $_GET['site_witel'];
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Pilih Witel Terlebih Dahulu',
                'data' => null
            ], 422);
        }

        if (isset($_GET['status'])){
            $status =  $_GET['status'];
        }

        if (isset($_GET['ba'])){
            $ba=$_GET['ba'];
        }

        if (isset($_GET['tahun'])){
            $tahun_order=$_GET['tahun'];
        }

        if (isset($_GET['ba_sirkulir'])){
            $ba_sirkulir=$_GET['ba_sirkulir'];
        }
        
        return Excel::download(new UpgradeExport($site_witel, $status, $ba, $tahun_order, $ba_sirkulir), 'upgrade.xlsx');
    }

    public function relokasi()
    {
        $site_witel = "";
        $status = "";
        $ba = "";
        $tahun_order = "";
        $ba_sirkulir = "";

        
        if (isset($_GET['site_witel'])){
            $site_witel = $_GET['site_witel'];
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Pilih Witel Terlebih Dahulu',
                'data' => null
            ], 422);
        }

        if (isset($_GET['status'])){
            $status =  $_GET['status'];
        }

        if (isset($_GET['ba'])){
            $ba=$_GET['ba'];
        }

        if (isset($_GET['tahun'])){
            $tahun_order=$_GET['tahun'];
        }

        if (isset($_GET['ba_sirkulir'])){
            $ba_sirkulir=$_GET['ba_sirkulir'];
        }
        
        return Excel::download(new RelokasiExport($site_witel, $status, $ba, $tahun_order, $ba_sirkulir), 'relokasi.xlsx');
    }

    public function dualhoming()
    {
        $site_witel = "";
        $status = "";
        $ba = "";
        $ba_sirkulir = "";
        
        if (isset($_GET['site_witel'])){
            $site_witel = $_GET['site_witel'];
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Pilih Witel Terlebih Dahulu',
                'data' => null
            ], 422);
        }

        if (isset($_GET['status'])){
            $status =  $_GET['status'];
        }

        if (isset($_GET['ba'])){
            $ba=$_GET['ba'];
        }

        if (isset($_GET['ba_sirkulir'])){
            $ba_sirkulir=$_GET['ba_sirkulir'];
        }
        
        return Excel::download(new DualHomingExport($site_witel, $status, $ba, $ba_sirkulir), 'dualhoming.xlsx');
    }
}

<?php

namespace App\Http\Controllers\SARPEN;

use App\Http\Controllers\Controller;
use App\Models\TrBaSarpen;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function donut()
    {
        $site_witel = "";
        $year = date('Y');

        $tipe_ba = ""; 

        if (isset($_GET['site_witel'])){
            $site_witel = $_GET['site_witel'];
        }

        if (isset($_GET['year'])){
            $year = $_GET['year'];
        }


        $ba_diajukan = TrBaSarpen::where('status', 'proposed')->whereYear('tanggal_buat', $year);
        $ba_ttd_witel = TrBaSarpen::where('status', 'ttd_witel')->whereYear('tanggal_buat', $year);
        $ba_paraf_wholesale = TrBaSarpen::where('status', 'paraf_wholesale')->whereYear('tanggal_buat', $year);
        $ba_ttd_wholesale = TrBaSarpen::where('status', 'ttd_wholesale')->whereYear('tanggal_buat', $year);
        $ba_sirkulir = TrBaSarpen::where('status', 'finished')->whereYear('tanggal_buat', $year);

        if ($site_witel != 'ALL') {
            $ba_diajukan = $ba_diajukan->where('site_witel', $site_witel);
            $ba_ttd_witel = $ba_ttd_witel->where('site_witel', $site_witel);
            $ba_paraf_wholesale = $ba_paraf_wholesale->where('site_witel', $site_witel);
            $ba_ttd_wholesale = $ba_ttd_wholesale->where('site_witel', $site_witel);
            $ba_sirkulir = $ba_sirkulir->where('site_witel', $site_witel);

        }

        $data = new \stdClass();
        $series = array($ba_diajukan->count(), 
                        $ba_ttd_witel->count(), 
                        $ba_paraf_wholesale->count(), 
                        $ba_ttd_wholesale->count(), 
                        $ba_sirkulir->count()
                );
        $labels = array ('Proses Manager Witel', 'Proses Officer Wholesale', 'Proses Manager Wholesale', 'TTD. Lengkap', 'Selesai / Sirkulir');

        $data->series = $series;
        $data->labels = $labels;
             
        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => $data
        ], 200);
    }

    public function column()
    {
        $site_witel = "";
        $year = date('Y');
        $tipe_ba = ""; 

        if (isset($_GET['site_witel'])){
            $site_witel = $_GET['site_witel'];
        }


        if (isset($_GET['year'])){
            $year = $_GET['year'];
        }

        $arr_ba = array();

        for ($i = 1; $i <= 12; $i++)
        {
            $data_ba = new TrBaSarpen;
            if ($site_witel != 'ALL') {
                $data_ba = $data_ba->where('site_witel', $site_witel);
            }

            $data_ba =  $data_ba->where('status', '=', 'finished')
            ->whereMonth('tanggal_buat', $i)
            ->whereYear('tanggal_buat', $year)
            ->count();

            array_push($arr_ba,  $data_ba);
        }
       
        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => $arr_ba
        ], 200);
    }

    public function baSesuaiWitel()
    {
        $year = date('Y');
        if (isset($_GET['year'])){
            $year = $_GET['year'];
        }

        $arr_data = array();
        $arr_witel = array(
            "Singaraja",
            "Denpasar",
            "Mataram",
            "Malang",
            "Jember",
            "Kediri",
            "Pasuruan",
            "Sidoarjo",
            "Madiun",
            "Madura",
            "Kupang",
            "Surabaya Utara",
            "Surabaya Selatan"
        );

        $status = array ('proposed', 'ttd_witel', 'paraf_wholesale', 'ttd_wholesale', 'finished');

        for ($i=0; $i<count($arr_witel); $i++)
        {
            $data = new \stdClass();
            $data->witel = $arr_witel[$i];

            for ($j=0; $j<count($status); $j++)
            {
                $count = TrBaSarpen::where('status', $status[$j])->whereYear('tanggal_buat', $year)->where('site_witel', $arr_witel[$i])->count();
                $data->{$status[$j]} = $count;
            }

            array_push($arr_data,  $data);
        }

        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => $arr_data
        ], 200);
    }
}

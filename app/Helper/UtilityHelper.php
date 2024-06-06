<?php

namespace App\Helper;

use Illuminate\Support\Facades\DB;

class UtilityHelper
{
    public static function penyebut($nilai)
    {
        $nilai = abs($nilai);
        $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
        $temp = "";
        if ($nilai < 12) {
            $temp = " " . $huruf[$nilai];
        } else if ($nilai < 20) {
            $temp = self::penyebut($nilai - 10) . " belas";
        } else if ($nilai < 100) {
            $temp = self::penyebut($nilai / 10) . " puluh" . self::penyebut($nilai % 10);
        } else if ($nilai < 200) {
            $temp = " seratus" . self::penyebut($nilai - 100);
        } else if ($nilai < 1000) {
            $temp = self::penyebut($nilai / 100) . " ratus" . self::penyebut($nilai % 100);
        } else if ($nilai < 2000) {
            $temp = " seribu" . self::penyebut($nilai - 1000);
        } else if ($nilai < 1000000) {
            $temp = self::penyebut($nilai / 1000) . " ribu" . self::penyebut($nilai % 1000);
        } else if ($nilai < 1000000000) {
            $temp = self::penyebut($nilai / 1000000) . " juta" . self::penyebut($nilai % 1000000);
        } else if ($nilai < 1000000000000) {
            $temp = self::penyebut($nilai / 1000000000) . " milyar" . self::penyebut(fmod($nilai, 1000000000));
        } else if ($nilai < 1000000000000000) {
            $temp = self::penyebut($nilai / 1000000000000) . " trilyun" . self::penyebut(fmod($nilai, 1000000000000));
        }
        return $temp;
    }

    public static function terbilang($nilai)
    {
        if ($nilai < 0) {
            $hasil = "minus " . trim(self::penyebut($nilai));
        } else {
            $hasil = trim(self::penyebut($nilai));
        }
        return $hasil;
    }

    public static function checkEvident($wo_id, $wo_site_id)
    {
        $data = DB::table(DB::raw('tr_wo_sites tr, tr_wos trw, ma_penggunas p'))
            ->select(
                DB::raw("tr.*, trw.lampiran_url,  
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
                                                        ti.tipe = 'LV') as lv_image,

                                        (SELECT count(*) 
                                                        FROM 
                                                            tr_wo_site_images ti
                                                        WHERE 
                                                            tr.wo_id = ti.wo_id 
                                                        AND 
                                                            tr.wo_site_id = ti.wo_site_id
                                                        AND
                                                            ti.tipe = 'QC') as qc_image,
                                            
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
                                                    ti.tipe = 'CAPTURE_TRAFIK') as capture_trafik,
                                                    

                                        (SELECT count(*) 
                                            FROM 
                                                tr_wo_site_images ti
                                            WHERE 
                                                tr.wo_id = ti.wo_id 
                                            AND 
                                                tr.wo_site_id = ti.wo_site_id
                                            AND
                                                ti.tipe = 'NODE_1') as node_1,


                                        (SELECT count(*) 
                                            FROM 
                                                tr_wo_site_images ti
                                            WHERE 
                                                tr.wo_id = ti.wo_id 
                                            AND 
                                                tr.wo_site_id = ti.wo_site_id
                                            AND
                                                ti.tipe = 'NODE_1') as node_2,

                                        (SELECT count(*) 
                                                    FROM 
                                                        tr_wo_site_dual_homings dh
                                                    WHERE 
                                                        tr.wo_id = dh.wo_id 
                                                    AND 
                                                        tr.wo_site_id = dh.wo_site_id) as pr_dual_homing"),
            )
            ->where('tr.wo_id', $wo_id)
            ->where('tr.wo_site_id', $wo_site_id)
            ->whereRaw("p.id = tr.dibuat_oleh")
            ->whereRaw("tr.wo_id = trw.id")
            ->first();

        return $data;
    }

    public static function checkNomorDokumen()
    {
        $data_id = DB::table('ma_nomor_dokumens')
            ->select(DB::raw('max(SUBSTRING(no_dokumen, 6, 4)) as result'))
            ->whereYear('tgl_dokumen', date('Y'))
            ->where('created_at', '>=', '2023-01-02 14:49:50')
            ->first();

        $counter = ($data_id) ? (int)$data_id->result + 1 : 1;
        $id = 'TEL. ' . sprintf("%04d", $counter);

        return $id . '/YN.000/DR5-11000000/' . date('Y');
    }
}

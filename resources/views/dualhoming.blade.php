<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Berita Acara Dual Homing</title>

        <!-- Fonts -->
        <!-- <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet"> -->

        <!-- Styles -->
        <!-- <link rel="stylesheet" href="/assets/css/bootstrap.min.css"> -->
        <!-- <link rel="stylesheet" href="/assets/css/font.css"> -->

        <!-- <link href="{{ public_path('/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" /> -->
        <link href="{{ public_path('/assets/css/font.css') }}" rel="stylesheet" type="text/css" />



        <style>
            /* @font-face {
                font-family: Carlito;
                font-weight: normal;
                font-style: normal;
                src: url("{{ storage_path('fonts/carlito.regular.ttf') }}") format("truetype");
            }
            @font-face {
                font-family: Carlito;
                font-weight: bold;
                font-style: bold;
                src: url("{{ storage_path('fonts/carlito.bold.ttf') }}") format("truetype");
            } */
            @page { margin: 15px; }
            .page_break_before { 
                page-break-before: always; 
                page-break-inside: avoid;
            }
            .page_break_after { 
                page-break-after: always; 
                page-break-inside: avoid;
            }
            body {
                font-family: Arial, Helvetica, sans-serif;
                line-height: 1.5;
            }
            header {
                position: fixed;
                top:  0px;
                left: 0px;
                right: 0px;
                margin-bottom : 20px;   
            }

            p {
                font-size: 14px;
            }
            h3 {    
                font-size: 20px;
                font-weight: normal;
            }
            h4 {
                font-size: 18px;
            }
            td, th {
                font-size: 14px;
                padding-bottom: 0px;
                padding-top: 0px;
            }
            .table-header {
                width: 100%;
                border-collapse: collapse;
                border: thin solid black;
            }
            .table-header td{
                border: thin solid black;
                text-align: center;
            }
            .table-header th{
                border: thin solid black;
            }
            .table-jumlah-site {
                width: 100%;
                border-collapse: collapse;
                /* border: 1px solid black; */
            }
            .table-jumlah-site td{
                border: thin solid black;
                text-align: center;
                font-size: 12px;
                padding: 10px;
            }
            .table-jumlah-site th{
                border: thin solid black;
                text-align: center;
                font-size: 12px;
                padding: 7px;
            }

            .table-site {
                width: 100%;
                border-collapse: collapse;
                /* border: 1px solid black; */
            }
            .table-site td{
                border: thin solid black;
                text-align: center;
                font-size: 12px;
                padding: 1px;
            }
            .table-site th{
                border: thin solid black;
                font-size: 12px;
            }
            .table-kelengkapan {
                width: 100%;
                border-collapse: collapse;
                /* border: 1px solid black; */
            }
            .table-kelengkapan td{
                border: thin solid black;
                font-size: 14px;
                padding: 4px;
                vertical-align: middle;
            }
            .table-kelengkapan th{
                border: thin solid black;
                font-size: 14px;
                padding : 5px;
            }
            .table-lv-qc {
                width: 100%;
                border-collapse: collapse;
                /* border: 1px solid black; */
            }
            .table-lv-qc td{
                border: thin solid black;
                text-align: center;
                font-size: 14px;
                padding: 4px;
            }
            .table-lv-qc th{
                border: thin solid black;
                font-size: 12px;
                background-color : #2f5395;
                color : #fff;
                padding : 5px;
            }
            .td-center {
                vertical-align: middle; 
                text-align: center;
            }
            .header {
                margin-top: 0px;
                margin-bottom: 10px;
            }
            .header-margin {
                margin-bottom: 3px !important;
                margin-top: 3px !important;
            }
            .content-ttd {
                padding-left: 35px !important;
                margin-bottom: 10px;
            }
            .margin-content {
                margin-bottom: 15px;
            }
            .margin-ttd {
                margin-bottom: 40px;
            }
            .margin-header-logo {
                margin-left: 40px;
                margin-right: 30px;
                margin-bottom: 0px;
                top: 0px;
            }
            .margin-body {
                margin-left: 70px;
                margin-right: 70px;
            }
            .mt-small {
                margin-top : 5px;
            }
            .mt-large {
                margin-top :10px;
            }
            .mt-xl {
                margin-top :20px;
            }
            .mb-small {
                margin-bottom : 10px;
            }
            .mb-large {
                margin-bottom :20px;
            }
            .mb-xl {
                margin-bottom :30px;
            }
            .text-telkom {
                color : #ff0000;
            }
            .text-center {
                text-align: center
            }
            .wrapword {
                white-space: -moz-pre-wrap !important;  /* Mozilla, since 1999 */
                white-space: -webkit-pre-wrap;          /* Chrome & Safari */ 
                white-space: -pre-wrap;                 /* Opera 4-6 */
                white-space: -o-pre-wrap;               /* Opera 7 */
                white-space: pre-wrap;                  /* CSS3 */
                word-wrap: break-word;                  /* Internet Explorer 5.5+ */
                word-break: break-all;
                white-space: normal;
            }
        </style>
    </head>
    <body>
        <!-- halaman pertama -->
        <main>
            <div class="page_break_after">
                <div class="header">
                    <table class="table-header">
                        <tr>
                            <td colspan="3" class="text-center">
                                <h3 class="header-margin"><center>BERITA ACARA UJI TERIMA (BAUT) DUAL HOMING</center></h3>
                                <h3 class="header-margin"><center>Nomor : {{$data_ba->no_dokumen}}</center></h3>
                            </td>
                        </tr>
                        <tr>
                            <!-- <td style="width:50%"><img src="/assets/images/telkomsel.png" style="width:100px;"></td>
                            <td style="width:50%; text-align: right;"><img src="/assets/images/telkom.png" style="width:100px;"></td> -->
                            <td style="width:20%;"><img src="{{ public_path('/assets/images/telkomsel.png') }}" style="width:100px;"></td>
                            <td>
                                <h3 class="header-margin"><center>LINK METRO ETHERNET</center></h3>
                                <h3 class="header-margin"><center>AREA JATIM BALNUS</center></h3>
                            </td>
                            <td style="width:20%;"><img src="{{ public_path('/assets/images/telkom.png') }}" style="width:100px;"></td>
                        </tr>
                    </table>
                </div>
                <div class="margin-body">
                    <p>
                        Pada hari ini <stron>{{$format_tanggal->hari}}</stron> tanggal <strong>{{$format_tanggal->tgl}}</strong> Bulan <strong>{{strtoupper($format_tanggal->bulan)}}</strong> Tahun <strong>{{$format_tanggal->tahun}} ({{strtoupper(date('d/m/Y', strtotime($data_ba->tgl_dokumen)))}})</strong>, bertempat di Telkom Reg-5, beralamat di Telkom Landmark Tower Surabaya, Jalan Dr. Ir. H.
                        Soekarno No.175 Surabaya, kami yang bertanda tangan dibawah ini :
                    </p>
                </div>
                <div class="content-ttd margin-body">
                    <table>
                        <tr>
                            <td style="width:50px">I. </td>
                            <td style="width:200px">Nama / NIK</td>
                            <td style="width:10px">:</td>
                            <td>{{$people_ttd->osm_regional->nilai}}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Jabatan</td>
                            <td>:</td>
                            <td>{{$people_ttd->osm_regional->detail_nilai}}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Lokasi Kerja</td>
                            <td>:</td>
                            <td>TELKOM REGIONAL V</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td colspan="3">Selanjutnya disebut <strong>TELKOM</strong></td>
                        </tr>
                    </table>
                </div>
                <div class="content-ttd margin-body">
                    <table>
                        <tr>
                            <td style="width:50px">II. </td>
                            <td style="width:200px">Nama</td>
                            <td style="width:10px">:</td>
                            <td>{{(strtoupper($data_ba->tsel_reg) == 'JAWA TIMUR') ? strtoupper($people_ttd->telkomsel_qc_network_region_jatim->nilai) : strtoupper($people_ttd->telkomsel_qc_network_region_balnus->nilai)}}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Jabatan</td>
                            <td>:</td>
                            <td>{{(strtoupper($data_ba->tsel_reg) == 'JAWA TIMUR') ? strtoupper($people_ttd->telkomsel_qc_network_region_jatim->detail_nilai) : strtoupper($people_ttd->telkomsel_qc_network_region_balnus->detail_nilai)}}</td>
                        <tr>
                            <td></td>
                            <td>Lokasi Kerja</td>
                            <td>:</td>
                            <td>
                                {{
                                    (strtoupper($data_ba->tsel_reg) == 'JAWA TIMUR') 
                                    ? strtoupper(str_replace("GM", "", $people_ttd->telkomsel_qc_network_region_jatim->detail_nilai)) 
                                    : strtoupper(str_replace("GM", "",$people_ttd->telkomsel_qc_network_region_balnus->detail_nilai))
                                }}
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Perusahaan</td>
                            <td>:</td>
                            <td>PT. TELKOMSEL</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td colspan="3">Selanjutnya disebut <strong>TELKOMSEL</strong></td>
                        </tr>
                    </table>
                </div>
                <div class="margin-body">
                    <p class="mt-3">
                        Menyatakan SURAT NIM NOMOR 1445/NM.01/IX/2020, tanggal 29 September 2020
                        bahwa berdasarkan hasil evaluasi dan check data teknis Metro dan OLT , terdapat {{$total_site}} site
                        yang memiliki konfigurasi dual homing (detail site terlampir), Satu site ini sudah dilakukan
                        pengetesan dengan hasil baik sesuai rule CNOP 2.0
                    </p>
                </div>
                <div class="margin-content margin-body">
                    <table class="table-jumlah-site" cellpadding="0" cellspacing="0">
                        <thead>
                            <tr style="background-color: #c4e9e9;">
                                <th class="td-center">Regional</th>
                                <th class="td-center">Jumlah Site</th>
                                <th class="td-center">BW TPS(Mbps)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{$data_ba->tsel_reg}}</td>
                                <td>{{$total_site}}</td>
                                <td>{{$total_bw}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>  
                <div class="margin-body">
                    <p>
                        Demikian Berita Acara ini dibuat dengan sebenarnya dalam rangkap 2 (dua) yang sama bunyinya dan mempunyai
                        kekuatan hukum yang sama setelah ditanda tangani ke dua belah Pihak.
                    </p>
                </div>
                <div class="margin-body">
                    <table style="width:100%;">
                        <tr class="text-center">
                            <td style="font-weight:bold;"></td>
                            <td></td>
                            <td style="font-weight:bold;">Surabaya, {{$format_tanggal->tgl_nomor}} {{$format_tanggal->bulan}} {{$format_tanggal->tahun_nomor}}</td>
                        </tr>
                        <tr class="text-center">
                            <td style="padding-bottom: 50px; width:45%; font-weight:bold;">TELKOMSEL</td>
                            <td style="width:10%;"></td>
                            <td style="padding-bottom: 50px; width:45%; font-weight:bold;">TELKOM</td>
                        </tr>
                        <tr class="text-center">
                            <td style="font-weight:bold; text-decoration: underline">{{(strtoupper($data_ba->tsel_reg) == 'JAWA TIMUR') ? $people_ttd->telkomsel_qc_network_region_jatim->nilai : $people_ttd->telkomsel_qc_network_region_balnus->nilai}}</td>
                            <td style="width:10%"></td>
                            <td style="font-weight:bold; text-decoration: underline">{{$people_ttd->osm_regional->nilai}}</td>

                        </tr>
                        <tr class="text-center">
                            <td>{{(strtoupper($data_ba->tsel_reg) == 'JAWA TIMUR') ? strtoupper($people_ttd->telkomsel_qc_network_region_jatim->detail_nilai) : strtoupper($people_ttd->telkomsel_qc_network_region_balnus->detail_nilai)}}</td>
                            <td style="width:10%"></td>
                            <td style="vertical-align: top;">{{strtoupper($people_ttd->osm_regional->detail_nilai)}}</td>
                        </tr>
                    </table>            
                </div>
            </div>
            <!-- halaman 3 (ketiga) -->
            <div class="page_break_after">
                <div class="margin-body mb-large">
                    <h4 class="header-margin">BERITA ACARA AKTIVASI PREMIUM SITE Nomor : {{$data_ba->no_dokumen}}</h4>
                    <h4 class="header-margin">Tanggal : {{$data_ba->tgl_dokumen}}</h4>
                </div>
                <div class="margin-content margin-body mb-xl">
                    <table class="table-site" cellpadding="0" cellspacing="0">
                        <thead>
                            <tr style="background-color: #c4e9e9;">
                                <th style="width:5%" rowspan="2" class="td-center">No. </th>
                                <th style="width:5%" rowspan="2" class="td-center">SITE ID</th>
                                <th style="width:25%" rowspan="2" class="td-center">SITE NAME</th>
                                <th style="width:5%" rowspan="2" class="td-center">BW</th>
                                <th style="width:10%" rowspan="2" class="td-center">JENIS NODE</th>
                                <th style="width:15%" rowspan="2" class="td-center">{{$data_site[0]->parameter->jenis_node}} 1</th>
                                <th style="width:5%" rowspan="2" class="td-center">STO 1</th>
                                <th style="width:15%" rowspan="2" class="td-center">{{$data_site[0]->parameter->jenis_node}} 2</th>
                                <th style="width:5%" rowspan="2" class="td-center">STO 2</th>
                                <th style="width:20%" rowspan="2" class="td-center">KETERANGAN</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for ($a=0; $a<@count($data_site); $a++)
                                <tr>
                                    <td>{{$a+1}}</td>
                                    <td>{{$data_site[$a]->site_id}}</td>
                                    <td class="wrapword">{{$data_site[$a]->site_name}}</td>
                                    <td>{{$data_site[$a]->jumlah}}</td>
                                    <td>{{$data_site[$a]->parameter->jenis_node}}</td>
                                    <td>{{$data_site[$a]->parameter->node_1}}</td>
                                    <td>{{$data_site[$a]->parameter->sto_a}}</td>
                                    <td>{{$data_site[$a]->parameter->node_2}}</td>
                                    <td>{{$data_site[$a]->parameter->sto_b}}</td>
                                    <td>Dual Homing</td>
                                    <!-- <td>{{$data_site[$a]->parameter->keterangan}}</td> -->
                                </tr>
                            @endfor 
                        </tbody>
                    </table>
                </div>
                <div class="margin-body">
                    <table style="width:100%;">
                        <tr class="text-center">
                            <td style="font-weight:bold;"></td>
                            <td></td>
                            <td style="font-weight:bold;">Surabaya, {{$format_tanggal->tgl_nomor}} {{$format_tanggal->bulan}} {{$format_tanggal->tahun_nomor}}</td>
                        </tr>
                        <tr class="text-center">
                            <td style="padding-bottom: 50px; width:40%; font-weight:bold;">TELKOMSEL</td>
                            <td style="width:20%;"></td>
                            <td style="padding-bottom: 50px; width:40%; font-weight:bold;">TELKOM</td>
                        </tr>
                        <tr class="text-center">
                            <td style="font-weight:bold; text-decoration: underline">{{(strtoupper($data_ba->tsel_reg) == 'JAWA TIMUR') ? $people_ttd->telkomsel_rto_region_jatim->nilai : $people_ttd->telkomsel_rto_region_balnus->nilai}}</td>
                            <td style="width:20%"></td>
                            <td style="font-weight:bold; text-decoration: underline">{{$people_ttd->manager_wholesale->nilai}}</td>

                        </tr>
                        <tr class="text-center">
                            <td>MANAGER {{(strtoupper($data_ba->tsel_reg) == 'JAWA TIMUR') ? strtoupper($people_ttd->telkomsel_rto_region_jatim->detail_nilai) : strtoupper($people_ttd->telkomsel_rto_region_balnus->detail_nilai)}}</td>
                            <td style="width:20%"></td>
                            <td style="vertical-align: top;">{{strtoupper($people_ttd->manager_wholesale->detail_nilai)}}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- halaman topologi dan parameter  -->
            @if (@count($data_site) > 1)
                @for($a=0; $a<@count($data_site); $a++)
                    <div class="page_break_after">
                        <div class="margin-content margin-body mb-small">
                            <table class="table-kelengkapan cellpadding="0" cellspacing="0">
                                <tbody>
                                    <tr>
                                        <td colspan="2" style="padding-bottom: 15px; font-weight:bold;"><span style="font-style: italic;">Lampiran BAUT Nomor</span> : {{$data_ba->no_dokumen}}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="padding-bottom: 15px;"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="text-align: left !important; font-weight:bold;">Data Topologi Site {{$data_site[$a]->site_id}}</td>
                                    </tr>
                                    <tr>
                                        <td width="5%"></td>
                                        <td>
                                            <img src="{{ public_path().'/lampirans/'. $data_site[$a]->topologi }}" style="width:700px; height:400px; object-fit: cover;">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="5%"></td>
                                        <td>
                                            <table class="table-kelengkapan" cellpading="0" cellspacing="0">
                                                <thead>
                                                    <tr style="background-color: #c4e9e9;">
                                                        <th>PARAMETER SITE</th>
                                                        <th>KETERANGAN DETAIL</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Site ID</td>
                                                        <td>{{$data_site[$a]->site_id}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Type Topologi</td>
                                                        <td>{{$data_site[$a]->parameter->tipe_topologi}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Nama STO A</td>
                                                        <td>{{$data_site[$a]->parameter->sto_a}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Nama STO B</td>
                                                        <td>{{$data_site[$a]->parameter->sto_b}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>METRO 1</td>
                                                        <td>{{$data_site[$a]->parameter->metro_1}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>METRO 2</td>
                                                        <td>{{$data_site[$a]->parameter->metro_2}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{$data_site[$a]->parameter->jenis_node}} 1</td>
                                                        <td>{{$data_site[$a]->parameter->node_1}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{$data_site[$a]->parameter->jenis_node}} 2</td>
                                                        <td>{{$data_site[$a]->parameter->node_2}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>PORT OTB 1</td>
                                                        <td>{{$data_site[$a]->parameter->port_otb_1}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>PORT OTB 2</td>
                                                        <td>{{$data_site[$a]->parameter->port_otb_2}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>ODC/ODP 1</td>
                                                        <td>{{$data_site[$a]->parameter->odc_odp_1}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>ODC/ODP 2</td>
                                                        <td>{{$data_site[$a]->parameter->odc_odp_2}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Tipe Service</td>
                                                        <td>{{$data_site[$a]->parameter->tipe_service}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Tipe Modem  ONT/L2/L3SW</td>
                                                        <td>{{$data_site[$a]->parameter->tipe_modem}}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endfor

                <!-- halaman node 1 -->

                @for($a=1; $a<=@count($data_site); $a+=2)
                    @if ($a <= @count($data_site))
                        <div class="page_break_after">
                            <div class="margin-content margin-body mb-small">
                                <table class="table-kelengkapan cellpadding="0" cellspacing="0">
                                    <tbody>
                                        <tr>
                                            <td colspan="2" style="padding-bottom: 15px; font-weight:bold;"><span style="font-style: italic;">Lampiran BAUT Nomor</span> : {{$data_ba->no_dokumen}}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="padding-bottom: 15px;"></td>
                                        </tr>
                                        @for ($b=$a; $b<=$a+1; $b++)
                                            @if ($b <= @count($data_site))
                                                <tr>
                                                    <td colspan="2" style="text-align: left !important; font-weight:bold;">Datek {{$data_site[0]->parameter->jenis_node}} 1 Site {{$data_site[$b-1]->site_id}}</td>
                                                </tr>
                                                <tr>
                                                    <td width="5%"></td>
                                                    <td>
                                                        <img src="{{ public_path().'/lampirans/'. $data_site[$b-1]->node_1 }}" style="width:700px; height:400px; object-fit: cover;">
                                                    </td>
                                                </tr>
                                            @endif
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div> 
                    @endif
                @endfor

                <!-- halaman node 2  -->
                
                @for($a=1; $a<=@count($data_site); $a+=2)
                    @if ($a <= @count($data_site))
                        <div class="page_break_after">
                            <div class="margin-content margin-body mb-small">
                                <table class="table-kelengkapan cellpadding="0" cellspacing="0">
                                    <tbody>
                                        <tr>
                                            <td colspan="2" style="padding-bottom: 15px; font-weight:bold;"><span style="font-style: italic;">Lampiran BAUT Nomor</span> : {{$data_ba->no_dokumen}}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="padding-bottom: 15px;"></td>
                                        </tr>
                                        @for ($b=$a; $b<=$a+1; $b++)
                                            @if ($b <= @count($data_site))
                                                <tr>
                                                    <td colspan="2" style="text-align: left !important; font-weight:bold;">Datek {{$data_site[0]->parameter->jenis_node}} 2 Site {{$data_site[$b-1]->site_id}}</td>
                                                </tr>
                                                <tr>
                                                    <td width="5%"></td>
                                                    <td>
                                                        <img src="{{ public_path().'/lampirans/'. $data_site[$b-1]->node_2 }}" style="width:700px; height:400px; object-fit: cover;">
                                                    </td>
                                                </tr>
                                            @endif
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div> 
                    @endif
                @endfor
            @endif

            @if (@count($data_site) == 1)
                <div class="page_break_after">
                    <div class="margin-content margin-body mb-small">
                        <table class="table-kelengkapan cellpadding="0" cellspacing="0">
                            <tbody>
                                <tr>
                                    <td colspan="2" style="padding-bottom: 15px; font-weight:bold;"><span style="font-style: italic;">Lampiran BAUT Nomor</span> : {{$data_ba->no_dokumen}}</td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="padding-bottom: 15px;"></td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="text-align: left !important; font-weight:bold;">Data Topologi Site {{$data_site[0]->site_id}}</td>
                                </tr>
                                <tr>
                                    <td width="5%"></td>
                                    <td>
                                        <img src="{{ public_path().'/lampirans/'. $data_site[0]->topologi }}" style="width:700px; height:300px; object-fit: cover;">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="text-align: left !important; font-weight:bold;">Data Parameter Site {{$data_site[0]->site_id}}</td>
                                </tr>
                                <tr>
                                    <td width="5%"></td>
                                    <td>
                                        <table class="table-kelengkapan" cellpading="0" cellspacing="0">
                                            <thead>
                                                <tr style="background-color: #c4e9e9;">
                                                    <th>PARAMETER SITE</th>
                                                    <th>KETERANGAN DETAIL</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Site ID</td>
                                                    <td>{{ $data_site[0]->site_id}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Type Topologi</td>
                                                    <td>{{$data_site[0]->parameter->tipe_topologi}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Nama STO A</td>
                                                    <td>{{$data_site[0]->parameter->sto_a}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Nama STO B</td>
                                                    <td>{{$data_site[0]->parameter->sto_b}}</td>
                                                </tr>
                                                <tr>
                                                    <td>METRO 1</td>
                                                    <td>{{$data_site[0]->parameter->metro_1}}</td>
                                                </tr>
                                                <tr>
                                                    <td>METRO 2</td>
                                                    <td>{{$data_site[0]->parameter->metro_2}}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{$data_site[0]->parameter->jenis_node}} 1</td>
                                                    <td>{{$data_site[0]->parameter->node_1}}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{$data_site[0]->parameter->jenis_node}} 2</td>
                                                    <td>{{$data_site[0]->parameter->node_2}}</td>
                                                </tr>
                                                <tr>
                                                    <td>PORT OTB 1</td>
                                                    <td>{{$data_site[0]->parameter->port_otb_1}}</td>
                                                </tr>
                                                <tr>
                                                    <td>PORT OTB 2</td>
                                                    <td>{{$data_site[0]->parameter->port_otb_2}}</td>
                                                </tr>
                                                <tr>
                                                    <td>ODC/ODP 1</td>
                                                    <td>{{$data_site[0]->parameter->odc_odp_1}}</td>
                                                </tr>
                                                <tr>
                                                    <td>ODC/ODP 2</td>
                                                    <td>{{$data_site[0]->parameter->odc_odp_2}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Tipe Service</td>
                                                    <td>{{$data_site[0]->parameter->tipe_service}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Tipe Modem  ONT/L2/L3SW</td>
                                                    <td>{{$data_site[0]->parameter->tipe_modem}}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div> 
                <div class="page_break_after">
                    <div class="margin-content margin-body mb-small">
                        <table class="table-kelengkapan cellpadding="0" cellspacing="0">
                            <tbody>
                                <tr>
                                    <td colspan="2" style="text-align: left !important; font-weight:bold;">Datek Node 1 Site {{$data_site[0]->site_id}}</td>
                                </tr>
                                <tr>
                                    <td width="5%"></td>
                                    <td>
                                        <img src="{{ public_path().'/lampirans/'. $data_site[0]->node_1 }}" style="width:700px; height:300px; object-fit: cover;">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="text-align: left !important; font-weight:bold;">Datek Node 2 Site {{$data_site[0]->site_id}}</td>
                                </tr>
                                <tr>
                                    <td width="5%"></td>
                                    <td>
                                        <img src="{{ public_path().'/lampirans/'. $data_site[0]->node_2 }}" style="width:700px; height:300px; object-fit: cover;">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div> 
            @endif
        </main>
    </body>
</html>
</span>
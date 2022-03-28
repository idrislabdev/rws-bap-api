<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Berita Acara Dismantle</title>

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
                <div class="margin-header-logo">
                    <table style="width:100%;">
                        <tr>
                            <td style="width:50%"><img src="{{ public_path('/assets/images/telkomsel.png') }}" style="width:100px;"></td>
                            <td style="width:50%; text-align: right;"><img src="{{ public_path('/assets/images/telkom.png') }}" style="width:100px;"></td>
                        </tr>
                    </table>
                </div>
                <div class="header">
                    <h3 class="header-margin font-weight-bold"><center>BERITA ACARA (BILLING OFF)</center></h3>
                    <h4 class="header-margin font-weight-bold"><center>Nomor : {{$data_ba->no_dokumen}}</center></h4>
                </div>
                <div class="margin-body">
                    <p>Pada hari ini <stron>{{$format_tanggal->hari}}</stron> tanggal <strong>{{$format_tanggal->tgl}}</strong> Bulan <strong>{{strtoupper($format_tanggal->bulan)}}</strong> Tahun <strong>{{$format_tanggal->tahun}} ({{strtoupper(date('d/m/Y', strtotime($data_ba->tgl_dokumen)))}})</strong>, kami yang bertanda tangan dibawah ini :</p>
                </div>
                <div class="content-ttd margin-body">
                    <table>
                        <tr>
                            <td style="width:50px">I. </td>
                            <td style="width:200px">Nama</td>
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
                            <td colspan="3">Selanjutnya disebut TELKOM</td>
                        </tr>
                    </table>
                </div>
                <div class="content-ttd margin-body">
                    <table>
                        <tr>
                            <td style="width:50px">II. </td>
                            <td style="width:200px">Nama</td>
                            <td style="width:10px">:</td>
                            <td>{{$people_ttd->gm_core_transport->nilai}}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Jabatan</td>
                            <td>:</td>
                            <td>{{$people_ttd->gm_core_transport->detail_nilai}}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Lokasi Kerja</td>
                            <td>:</td>
                            <td>PT. Telkomsel - Regional</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td colspan="3">Selanjutnya disebut TELKOMSEL</td>
                        </tr>
                    </table>
                </div>
                <div class="margin-body">
                    <p class="mt-3">
                        Menyatakan bahwa TELKOM telah menyelesaikan pekerjaan sebagai berikut ( detail data terlampir) :
                    </p>
                </div>
                <div class="margin-content margin-body">
                    <table class="table-jumlah-site" cellpadding="0" cellspacing="0">
                        <thead>
                            <tr style="background-color: #c4e9e9;">
                                <th class="td-center">NO</th>
                                <th class="td-center">ORDER TYPE</th>
                                <th class="td-center">JUMLAH SITE TLK</th>
                                <th class="td-center">TOTAL KAP (Mbps)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>DISMANTLE</td>
                                <td>{{$total_site}} SITE</td>
                                <td>{{$total_bw}} Mbps</td>
                            </tr>
                        </tbody>
                    </table>
                </div>  
                <div class="margin-body">
                    <p>
                        Demikian Berita Acara ini dibuat dengan sebenarnya dalam rangkap 2 (dua) yang sama bunyinya dan
                        mempunyai kekuatan hukum yang sama setelah ditanda tangani ke dua belah Pihak.
                    </p>
                </div>
                <div class="margin-body text-center">
                    <p>Surabaya, {{$format_tanggal->tgl_nomor}} {{$format_tanggal->bulan}} {{$format_tanggal->tahun_nomor}}</p>
                </div>
                <div class="margin-body">
                    <table style="width:100%;">
                        <tr class="text-center">
                            <td style="padding-bottom: 70px; width:40%; font-weight:bold;">TELKOMSEL</td>
                            <td style="width:20%;"></td>
                            <td style="padding-bottom: 70px; width:40%; font-weight:bold;">TELKOM</td>
                        </tr>
                        <tr class="text-center">
                            <td style="font-weight:bold; text-decoration: underline">{{$people_ttd->gm_network->nilai}}</td>
                            <td style="width:20%"></td>
                            <td style="font-weight:bold; text-decoration: underline">{{$people_ttd->osm_regional->nilai}}</td>
                        </tr>
                    </table>            
                </div>
            </div>
            <!-- halaman 2 (keempat) -->
            <div class="page_break_after">
                <div class="margin-header-logo">
                    <table style="width:100%;">
                        <tr>
                            <td style="width:50%"><img src="{{ public_path('/assets/images/telkomsel.png') }}" style="width:100px;"></td>
                            <td style="width:50%; text-align: right;"><img src="{{ public_path('/assets/images/telkom.png') }}" style="width:100px;"></td>
                        </tr>
                    </table>
                </div>
                <div class="margin-content margin-body header">
                    <h4 class="header-margin">LAMPIRAN BERITA ACARA DISMANTLE LINK TELKOMSEL</h4>
                    <h4 class="header-margin">NOMOR : {{$data_ba->no_dokumen}}</h4>
                    <h4 class="header-margin">TANGGAL {{$format_tanggal->tgl_nomor}} {{$format_tanggal->bulan}} {{$format_tanggal->tahun_nomor}}</h4>
                </div>
                <div class="margin-content margin-body mb-xl">
                    <table class="table-site" cellpadding="0" cellspacing="0">
                        <thead>
                            <tr>
                                <th style="width:4%" rowspan="2" class="td-center">No. </th>
                                <th style="width:10%" rowspan="2" class="td-center">SITE ID</th>
                                <th style="width:18%" rowspan="2" class="td-center">SITE NAME</th>
                                <th style="width:18%" rowspan="2" class="td-center">BTS POSITION</th>
                                <th style="width:10%" rowspan="2" class="td-center">TANGGAL DEAKTIVASI</th>
                                <th style="width:10%" rowspan="2 style="text-align:center;">BW (Mbps)</th>
                                <th style="width:20%" rowspan="2" class="td-center">DASAR ORDER</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for ($a=0; $a<@count($data_site); $a++)
                                <tr>
                                    <td>{{$a+1}}</td>
                                    <td>{{$data_site[$a]->site_id}}</td>
                                    <td class="wrapword">{{$data_site[$a]->site_name}}</td>
                                    <td>{{$data_site[$a]->bts_position}}</td>
                                    <td>{{strtoupper(date('d-M-y', strtotime($data_site[$a]->tgl_deactivate)))}}</td>
                                    <td>{{$data_site[$a]->jumlah}}</td>
                                    <td>{{$data_site[$a]->dasar_order}}</td>
                                </tr>
                            @endfor 
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </body>
</html>
</span>
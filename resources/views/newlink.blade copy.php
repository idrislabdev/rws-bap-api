<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

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
                font-size: 11px;
            }
            h3 {    
                font-size: 17px;
                font-weight: normal;
            }
            h4 {
                font-size: 14px;
            }
            td, th {
                font-size: 11px;
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
                font-size: 11px;
                padding: 2px;
            }
            .table-site th{
                border: thin solid black;
                font-size:10px;
            }
            .table-kelengkapan {
                width: 100%;
                border-collapse: collapse;
                /* border: 1px solid black; */
            }
            .table-kelengkapan td{
                border: thin solid black;
                font-size: 12px;
                padding: 4px;
                vertical-align: middle;
            }
            .table-kelengkapan th{
                border: thin solid black;
                font-size:11px;
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
                font-size: 11px;
                padding: 4px;
            }
            .table-lv-qc th{
                border: thin solid black;
                font-size:10px;
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
        </style>
    </head>
    <body>
        <!-- halaman pertama -->
        <main>
            <div class="page_break_after">
                <div class="margin-header-logo">
                    <!-- <img src="{{ public_path('/assets/images/telkomsel.png') }}" style="width:50px;"> -->
                    <table style="width:100%;">
                        <tr>
                            <!-- <td style="width:50%"><img src="/assets/images/telkomsel.png" style="width:100px;"></td>
                            <td style="width:50%; text-align: right;"><img src="/assets/images/telkom.png" style="width:100px;"></td> -->
                            <td style="width:50%"><img src="{{ public_path('/assets/images/telkomsel.png') }}" style="width:100px;"></td>
                            <td style="width:50%; text-align: right;"><img src="{{ public_path('/assets/images/telkom.png') }}" style="width:100px;"></td>
                        </tr>
                    </table>
                </div>
                <div class="header">
                    <h3 class="header-margin font-weight-bold"><center>BERITA ACARA UJI TERIMA (BAUT) NEW AKSES LINK</center></h3>
                    <h4 class="header-margin font-weight-bold"><center>Nomor : TEL.131/YN.000/DR5-11000000/2021</center></h4>
                </div>
                <div class="margin-body">
                    <p>Pada hari ini <stron>SENIN</stron> tanggal <strong>DELAPAN</strong> Bulan FEBRUARI Tahun <strong>DUA RIBU DUA PULUH (08/02/2021)</strong>, kami yang bertanda tangan dibawah ini :</p>
                </div>
                <div class="content-ttd margin-body">
                    <table>
                        <tr>
                            <td style="width:50px">I. </td>
                            <td style="width:200px">Nama</td>
                            <td style="width:10px">:</td>
                            <td>WASITO ADI</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Jabatan</td>
                            <td>:</td>
                            <td>OSM REGIONAL WHOLESALE SERVICE</td>
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
                            <td>GALUMBANG PASARIBU</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Jabatan</td>
                            <td>:</td>
                            <td>GM. CORE AND TRANSPORT NETWORK DEPLOYMENT</td>
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
                    <p class="mt-3">TELKOM dan TELKOMSEL Menyatakan bahwa sebagai berikut :</p>
                </div>
                <div class="margin-content margin-body">
                    <table>
                        <tr>
                            <td style="width:288px">Dasar Permintaan</td>
                            <td style="width:10px">:</td>
                            <td>1048/NM.08/II/2021 , 1315/NM.01/VII/2020 , 1012/NM.01/II/2020</td>
                        </tr>
                        <tr>
                            <td style="width:288px">Produk</td>
                            <td style="width:10px">:</td>
                            <td>Akses Link</td>
                        </tr>
                        <tr>
                            <td style="width:288px">Jenis Layanan</td>
                            <td style="width:10px">:</td>
                            <td>FO</td>
                        </tr>
                        <tr>
                            <td style="width:288px">Lingkup Pekerjaan</td>
                            <td style="width:10px">:</td>
                            <td>New Akses Link</td>
                        </tr>
                        <tr>
                            <td style="width:288px">Jumlah Akses Link (Jumlah BW)</td>
                            <td style="width:10px">:</td>
                            <td>1 Akses Link dengan BW 190 Mbps</td>
                        </tr>
                    </table>
                </div>
                <div class="margin-content margin-body">
                    <table>
                        <tr>
                            <td style="width:30px">1.</td>
                            <td>TELKOM telah menyelesaikan pekerjaan dan melakukan uji terima dengan hasil pengujian terlampir</td>
                        </tr>
                        <tr>
                            <td style="width:30px">2.</td>
                            <td>TELKOMSEL menyatakan bahwa hasil pekerjaan tersebut “BAIK”</td>
                        </tr>
                        <tr>
                            <td style="width:30px">3.</td>
                            <td>Telah selesai dilakukan aktivasi/integrasi link milik TELKOM oleh TELKOMSEL dengan data sebagai berikut :</td>
                        </tr>
                    </table>
                </div>
                <div class="margin-content margin-body">
                    <table class="table-site" cellpadding="0" cellspacing="0">
                        <thead>
                            <tr>
                                <th rowspan="2" class="td-center">No. </th>
                                <th rowspan="2" class="td-center">SITE ID</th>
                                <th rowspan="2" class="td-center">SITE NAME</th>
                                <th rowspan="2" class="td-center">WITEL</th>
                                <th rowspan="2" class="td-center">TANGGAL ON AIR</th>
                                <th colspan="4" style="text-align:center;">BANDWIDTH (Mbps)</th>
                                <th rowspan="2" class="td-center">PROGRAM</th>
                                <th rowspan="2" class="td-center">NO. ORDER</th>
                            </tr>
                            <tr>
                                <th style="text-align: center;">2G</th>
                                <th style="text-align: center;">3G</th>
                                <th style="text-align: center;">4G</th>
                                <th style="text-align: center;">JML</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for($a=1; $a<=9 ; $a++)
                            <tr>
                                <td>{{$a}}</td>
                                <td>PYA015</td>
                                <td>BELEKA</td>
                                <td>MATARAM</td>
                                <td>03-Feb-2021</td>
                                <td>10</td>
                                <td>50</td>
                                <td>130</td>
                                <td>190</td>
                                <td>FIMO</td>
                                <td>1545/NM.01/XI/2020</td>
                            </tr>
                            @endfor 
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
                            <td style="padding-bottom: 50px; width:40%; font-weight:bold;">TELKOM</td>
                            <td style="width:20%;"></td>
                            <td style="padding-bottom: 50px; width:40%; font-weight:bold;">TELKOMSEL</td>
                        </tr>
                        <tr class="text-center">
                            <td style="font-weight:bold; text-decoration: underline">WASITO ADI</td>
                            <td style="width:20%"></td>
                            <td style="font-weight:bold; text-decoration: underline">GALUMBANG PASARIBU</td>
                        </tr>
                        <tr class="text-center">
                            <td style="vertical-align: top;">OSM Regional Wholesale Service</td>
                            <td style="width:20%"></td>
                            <td>GM. CORE AND TRANSPORT NETWORK DEPLOYMENT</td>
                        </tr>
                    </table>            
                </div>
            </div>

            <!-- halaman 2 (kedua) -->
            <div class="page_break_after">
                <div class="margin-header-logo">
                    <!-- <img src="{{ public_path('/assets/images/telkomsel.png') }}" style="width:50px;"> -->
                    <table style="width:100%;">
                        <tr>
                            <!-- <td style="width:50%"><img src="/assets/images/telkomsel.png" style="width:100px;"></td>
                            <td style="width:50%; text-align: right;"><img src="/assets/images/telkom.png" style="width:100px;"></td> -->
                            <td style="width:50%"><img src="{{ public_path('/assets/images/telkomsel.png') }}" style="width:100px;"></td>
                            <td style="width:50%; text-align: right;"><img src="{{ public_path('/assets/images/telkom.png') }}" style="width:100px;"></td>
                        </tr>
                    </table>
                </div>
                <div class="header">
                    <h3 class="header-margin font-underline"><center>Kelengkapan Dokument BAUT</center></h3>
                    <h4 class="header-margin"><center><span class="font-italic">Lampiran Baut</span> Nomor : TEL.131/YN.000/DR5-11000000/2021</center></h4>
                </div>
                <div class="margin-content margin-body">
                    <table class="table-kelengkapan" cellpadding="0" cellspacing="0">
                        <thead>
                            <tr>
                                <th style="vertical-align: middle; text-align: center; width:5%">NO</th>
                                <th style="vertical-align: middle; text-align: center; width:30%">JENIS DOKUMEN</th>
                                <th style="vertical-align: middle; text-align: center; width:45%"">HASIL</th>
                                <th style="vertical-align: middle; text-align: center; width:20%"">CATATAN</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for($a=0; $a<@count($jenis_dokumen) ; $a++)
                                <tr>
                                    <td class="text-center">{{$a+1}}</td>
                                    <td>{{$jenis_dokumen[$a]}}</td>
                                    <td class="text-center">
                                        <img src="{{ public_path('/assets/images/check.png') }}"  style="width:15px;"> OK
                                        <img src="{{ public_path('/assets/images/square.png') }}"  style="width:13px; margin-left: 15px;"> NOT OK
                                        <img src="{{ public_path('/assets/images/square.png') }}"  style="width:13px; margin-left: 15px;"> <span>N/A</span>
                                    </td>
                                    <td></td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
                <div class="margin-body mb-large">
                    <p>
                        Catatan : * hanya untuk new link
                    </p>
                </div>
                <div class="margin-body">
                    <table style="width:100%;">
                        <tr class="text-center">
                            <td style="padding-bottom: 50px; width:40%; font-weight:bold;">TELKOM</td>
                            <td style="width:20%;"></td>
                            <td style="padding-bottom: 50px; width:40%; font-weight:bold;">TELKOMSEL</td>
                        </tr>
                        <tr class="text-center">
                            <td style="font-weight:bold; text-decoration: underline">TEGUH CIPTO EDHI</td>
                            <td style="width:20%"></td>
                            <td style="font-weight:bold; text-decoration: underline">BERNARD SINAGA</td>
                        </tr>
                        <tr class="text-center">
                            <td style="vertical-align: top;">MANAGER WHOLESALE SUPPORT</td>
                            <td style="width:20%"></td>
                            <td>GMANAGER PM JATIM</td>
                        </tr>
                    </table>            
                </div>
            </div>

            <!-- halaman 3 (ketiga) -->
            <div class="page_break_after">
                <div class="margin-header-logo">
                    <!-- <img src="{{ public_path('/assets/images/telkomsel.png') }}" style="width:50px;"> -->
                    <table style="width:100%;">
                        <tr>
                            <!-- <td style="width:50%"><img src="/assets/images/telkomsel.png" style="width:100px;"></td>
                            <td style="width:50%; text-align: right;"><img src="/assets/images/telkom.png" style="width:100px;"></td> -->
                            <td style="width:50%"><img src="{{ public_path('/assets/images/telkomsel.png') }}" style="width:100px;"></td>
                            <td style="width:50%; text-align: right;"><img src="{{ public_path('/assets/images/telkom.png') }}" style="width:100px;"></td>
                        </tr>
                    </table>
                </div>
                <div class="header">
                    <h3 class="header-margin font-underline"><center>Berita Acara Kelengkapan Dokument BAUT</center></h3>
                    <h4 class="header-margin"><center><span class="font-italic">Lampiran Baut</span> Nomor : TEL.131/YN.000/DR5-11000000/2021</center></h4>
                </div>
                <div class="margin-body mb-large">
                    <p>
                        Aktivasi/ integrasi link milik TELKOM oleh TELKOMSEL dengan data sebagai berikut :
                    </p>
                </div>
                <div class="margin-content margin-body mb-xl">
                    <table class="table-site" cellpadding="0" cellspacing="0">
                        <thead>
                            <tr>
                                <th rowspan="2" class="td-center">No. </th>
                                <th rowspan="2" class="td-center">SITE ID</th>
                                <th rowspan="2" class="td-center">SITE NAME</th>
                                <th rowspan="2" class="td-center">WITEL</th>
                                <th rowspan="2" class="td-center">TANGGAL ON AIR</th>
                                <th colspan="4" style="text-align:center;">BANDWIDTH (Mbps)</th>
                                <th rowspan="2" class="td-center">PROGRAM</th>
                                <th rowspan="2" class="td-center">NO. ORDER</th>
                            </tr>
                            <tr>
                                <th style="text-align: center;">2G</th>
                                <th style="text-align: center;">3G</th>
                                <th style="text-align: center;">4G</th>
                                <th style="text-align: center;">JML</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for($a=1; $a<=9 ; $a++)
                            <tr>
                                <td>{{$a}}</td>
                                <td>PYA015</td>
                                <td>BELEKA</td>
                                <td>MATARAM</td>
                                <td>03-Feb-2021</td>
                                <td>10</td>
                                <td>50</td>
                                <td>130</td>
                                <td>190</td>
                                <td>FIMO</td>
                                <td>1545/NM.01/XI/2020</td>
                            </tr>
                            @endfor 
                        </tbody>
                    </table>
                </div>
                <div class="margin-body">
                    <table style="width:100%;">
                        <tr class="text-center">
                            <td style="width:35%"></td>
                            <td style="font-weight:bold;">Surabaya, 22 Maret 2021</td>
                            <td style="width:35%"></td>
                        </tr>
                        <tr class="text-center">
                            <td style="width:35%"></td>
                            <td>Mengetahui,</td>
                            <td style="width:35%"></td>
                        </tr>
                        <tr class="text-center">
                            <td style="width:35%"></td>
                            <td style="padding-bottom: 50px;font-weight:bold;">TELKOM</td>
                            <td style="width:35%"></td>
                        </tr>
                        <tr class="text-center">
                            <td style="width:35%"></td>
                            <td style="font-weight:bold; text-decoration: underline">ADE MULYONO</td>
                            <td style="width:35%"></td>
                        </tr>
                        <tr class="text-center font-weight-bold">
                            <td style="width:35%"></td>
                            <td style="vertical-align: top;">GM. Network Engineering and Project Jawa Bali</td>
                            <td style="width:35%"></td>
                        </tr>
                    </table>            
                </div>
            </div>

            <!-- halaman lv -->
            <div class="page_break_after">
                <div class="margin-header-logo">
                    <table style="width:100%;">
                        <tr>
                            <td style="width:50%"></td>
                            <td style="width:50%; text-align: right;"><img src="{{ public_path('/assets/images/telkom.png') }}" style="width:100px;"></td>
                        </tr>
                    </table>
                </div>
                <div class="header">
                    <h3 class="header-margin font-weight-bold"><center>4G SITE <span class="text-telkom">LV</span> ACCEPTANCE CERTIFICATE</center></h3>
                </div>
                <div class="margin-content margin-body mb-small">
                    <table class="table-site" cellpadding="0" cellspacing="0">
                        <tbody>
                            <tr class="font-weight-bold">
                                <td class="td-center">Site Name</td>
                                <td class="td-center">Sales Cluster</td>
                                <td class="td-center">Band</td>
                                <td class="td-center">LNBTSID</td>
                                <td class="td-center">eutranCellID</td>
                            </tr>
                            <tr class="font-italic">
                                <td class="td-center">RADIOANDIKA</td>
                                <td class="td-center">JATIM</td>
                                <td class="td-center">3G/4G</td>
                                <td class="td-center"></td>
                                <td class="td-center"></td>
                            </tr>
                            <tr class="font-weight-bold ">
                                <td class="td-center">Site ID</td>
                                <td class="td-center">NE Type</td>
                                <td class="td-center">Integration Date</td>
                                <td class="td-center">Area</td>
                                <td class="td-center">Type of Work</td>
                            </tr>   
                            <tr class="font-italic">
                                <td class="td-center">KED418</td>
                                <td class="td-center"></td>
                                <td class="td-center">12-MAR-21</td>
                                <td class="td-center">JATIM</td>
                                <td class="td-center">FIMO</td>
                            </tr>
                            <tr class="font-weight-bold ">
                                <td class="td-center">Problem :</td>
                                <td class="td-center" colspan="4"></td>
                            </tr>   
                            <tr class="font-weight-bold ">
                                <td class="td-center" >Justification :</td>
                                <td class="td-center" colspan="4"></td>
                            </tr>   
                            <tr class="font-weight-bold ">
                                <td class="td-center">Escalation :</td>
                                <td class="td-center" colspan="4">[ Y/N ]</td>
                            </tr>   
                        </tbody>
                    </table>
                </div>
                <div class="margin-content margin-body mb-small">
                    <p class="font-weight-bold font-size-12" style="margin-bottom:0px;">1. Statistical Quality</p>
                    <p class="font-weight-bold font-size-12" style="margin-top:0px;">1.1 KPI Transport Performance</p>
                    <table class="table-lv-qc" cellpadding="0" cellspacing="0">
                        <thead>
                            <tr>
                                <th rowspan="3" class="td-center">KPI Parameter</th>
                                <th colspan="9" class="td-center">SITE LEVEL</th>
                            </tr>
                            <tr>
                                <th class="td-center">Hour 1</th>
                                <th class="td-center">Hour 2</th>
                                <th class="td-center">Hour 3</th>
                                <th class="td-center">Hour 4</th>
                                <th class="td-center">Hour 5</th>
                                <th rowspan="2" class="td-center">Result</th>
                                <th rowspan="2" class="td-center">Target</th>
                                <th rowspan="2" class="td-center">Threshold</th>
                                <th rowspan="2" class="td-center">Pass</th>
                            </tr>
                            <tr>
                                <th class="td-center">17.00</th>
                                <th class="td-center">18.00</th>
                                <th class="td-center">19.00</th>
                                <th class="td-center">20.00</th>
                                <th class="td-center">21.00</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="font-weight-bold" style="text-align: left !important;">L2 Packet Loss</td>
                                <td class="td-center">0</td>
                                <td class="td-center">0</td>
                                <td class="td-center">0</td>
                                <td class="td-center">0</td>
                                <td class="td-center">0</td>
                                <td class="td-center">Clear</td>
                                <td class="td-center">Clear/Spike</td>
                                <td class="td-center">&lt;=0.1%</td>
                                <td class="td-center">Pass</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold" style="text-align: left !important;">L3 Latency</td>
                                <td class="td-center">4,395</td>
                                <td class="td-center">4,395</td>
                                <td class="td-center">4,395</td>
                                <td class="td-center">4,395</td>
                                <td class="td-center">4,395</td>
                                <td class="td-center">Clear</td>
                                <td class="td-center">&lt;=20ms</td>
                                <td class="td-center">&lt;=20ms</td>
                                <td class="td-center">Pass</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="margin-content margin-body mb-large">
                    <p class="font-weight-bold font-size-12">2. Remarks</p>
                    <table class="table-kelengkapan" cellpadding="0" cellspacing="0">
                        <tbody>
                            <tr>
                                <td style="padding-bottom:70px;"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="margin-content margin-body mb-small">
                    <table class="table-site" cellpadding="0" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Document Prepared by:</th>
                                <th>Measurement Prepared by:</th>
                                <th>Approved by:</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="padding-bottom:70px;"></td>
                                <td style="padding-bottom:70px;"></td>
                                <td style="padding-bottom:70px;"></td>
                            </tr>
                            <tr>
                                <td style="text-align:left; font-weight:bold; border-bottom:none;">TELKOM</td>
                                <td style="text-align:left; font-weight:bold; border-bottom:none;">TELKOMSEL RTSO REGION</td>
                                <td style="text-align:left; font-weight:bold; border-bottom:none;">TELKOMSEL PROJECT</td>
                            </tr>
                            <tr>
                                <td style="text-align:left; font-weight:bold; border-bottom: none; border-top:none;"></td>
                                <td style="text-align:left; font-weight:bold; border-bottom: none; border-top:none;"></td>
                                <td style="text-align:left; font-weight:bold; border-bottom: none; border-top:none;">DEPLOYMENT REGION</td>
                            </tr>
                            <tr>
                                <td style="text-align:left; font-weight:bold; border-bottom: none; border-top:none;">Name: Teguh Cipto E.</td>
                                <td style="text-align:left; font-weight:bold; border-bottom: none; border-top:none;">Name: Hanif Zakarya</td>
                                <td style="text-align:left; font-weight:bold; border-bottom: none; border-top:none;">Name: Didin Kuswoyo</td>
                            </tr>
                            <tr>
                                <td style="text-align:left; font-weight:bold; border-top:none;">Date: 22-03-2021</td>
                                <td style="text-align:left; font-weight:bold; border-top:none;">Date: 22-03-2021</td>
                                <td style="text-align:left; font-weight:bold; border-top:none;">Date: 22-03-2021</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- halaman qc -->
            <div class="page_break_after">
            <div class="margin-header-logo">
                    <table style="width:100%;">
                        <tr>
                            <td style="width:50%"></td>
                            <td style="width:50%; text-align: right;"><img src="{{ public_path('/assets/images/telkom.png') }}" style="width:100px;"></td>
                        </tr>
                    </table>
                </div>
                <div class="header">
                    <h3 class="header-margin font-weight-bold"><center>4G SITE <span class="text-telkom">QC</span> ACCEPTANCE CERTIFICATE</center></h3>
                </div>
                <div class="margin-content margin-body mb-small">
                    <table class="table-site" cellpadding="0" cellspacing="0">
                        <tbody>
                            <tr class="font-weight-bold">
                                <td class="td-center">Site Name</td>
                                <td class="td-center">Sales Cluster</td>
                                <td class="td-center">Band</td>
                                <td class="td-center">LNBTSID</td>
                                <td class="td-center">eutranCellID</td>
                            </tr>
                            <tr class="font-italic">
                                <td class="td-center">RADIOANDIKA</td>
                                <td class="td-center">JATIM</td>
                                <td class="td-center">3G/4G</td>
                                <td class="td-center"></td>
                                <td class="td-center"></td>
                            </tr>
                            <tr class="font-weight-bold ">
                                <td class="td-center">Site ID</td>
                                <td class="td-center">NE Type</td>
                                <td class="td-center">Integration Date</td>
                                <td class="td-center">Area</td>
                                <td class="td-center">Type of Work</td>
                            </tr>   
                            <tr class="font-italic">
                                <td class="td-center">KED418</td>
                                <td class="td-center"></td>
                                <td class="td-center">12-MAR-21</td>
                                <td class="td-center">JATIM</td>
                                <td class="td-center">FIMO</td>
                            </tr>
                            <tr class="font-weight-bold ">
                                <td class="td-center">Problem :</td>
                                <td class="td-center" colspan="4"></td>
                            </tr>   
                            <tr class="font-weight-bold ">
                                <td class="td-center" >Justification :</td>
                                <td class="td-center" colspan="4"></td>
                            </tr>   
                            <tr class="font-weight-bold ">
                                <td class="td-center">Escalation :</td>
                                <td class="td-center" colspan="4">[ Y/N ]</td>
                            </tr>   
                        </tbody>
                    </table>
                </div>
                <div class="margin-content margin-body mb-small">
                    <p class="font-weight-bold font-size-12" style="margin-bottom:0px;">1. Statistical Quality</p>
                    <p class="font-weight-bold font-size-12" style="margin-top:0px;">1.1 KPI Transport Performance</p>
                    <table class="table-lv-qc" cellpadding="0" cellspacing="0">
                        <thead>
                            <tr>
                                <th rowspan="3" class="td-center">KPI Parameter</th>
                                <th colspan="10" class="td-center">SITE LEVEL</th>
                            </tr>
                            <tr>
                                <th class="td-center" colspan="3">1st 3 Days</th>
                                <th class="td-center" colspan="3">2nd 3 Days</th>
                                <th rowspan="2" class="td-center">Result</th>
                                <th rowspan="2" class="td-center">Target</th>
                                <th rowspan="2" class="td-center">Threshold</th>
                                <th rowspan="2" class="td-center">Pass</th>
                            </tr>
                            <tr>
                                <th class="td-center">Day 1</th>
                                <th class="td-center">Day 2</th>
                                <th class="td-center">Day 3</th>
                                <th class="td-center">Day 4</th>
                                <th class="td-center">Day 5</th>
                                <th class="td-center">Day 6</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="font-weight-bold" style="text-align: left !important;">L2 Packet Loss</td>
                                <td class="td-center">Clear</td>
                                <td class="td-center">Clear</td>
                                <td class="td-center">Clear</td>
                                <td class="td-center">Clear</td>
                                <td class="td-center">Clear</td>
                                <td class="td-center">Clear</td>
                                <td class="td-center">Clear</td>
                                <td class="td-center">Clear/Spike</td>
                                <td class="td-center">&lt;=0.1%</td>
                                <td class="td-center">Pass</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold" style="text-align: left !important;">L3 Latency</td>
                                <td class="td-center">4,395</td>
                                <td class="td-center">4,395</td>
                                <td class="td-center">4,395</td>
                                <td class="td-center">4,395</td>
                                <td class="td-center">4,395</td>
                                <td class="td-center">4,395</td>
                                <td class="td-center">Clear</td>
                                <td class="td-center">&lt;=20ms</td>
                                <td class="td-center">&lt;=20ms</td>
                                <td class="td-center">Pass</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="margin-content margin-body mb-small">
                    <p class="font-weight-bold font-size-12">1.2 Document</p>
                    <table class="table-lv-qc" style="width:50%" cellpadding="0" cellspacing="0">
                        <thead>
                            <tr>
                                <th class="td-center">Document Attachment</th>
                                <th  class="td-center">Pass</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="text-align:left;">Topology Link Route</td>
                                <td style="text-align:left;"></td>
                            </tr>
                            <tr>
                                <td style="text-align:left;">Bandwidth Capacity</td>
                                <td style="text-align:left;"></td>
                            </tr>
                            <tr>
                                <td style="text-align:left;">Configuration Snapshoot</td>
                                <td style="text-align:left;"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="margin-content margin-body mb-large">
                    <p class="font-weight-bold font-size-12">2. Remarks</p>
                    <table class="table-kelengkapan" cellpadding="0" cellspacing="0">
                        <tbody>
                            <tr>
                                <td style="padding-bottom:70px;"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="margin-content margin-body mb-small">
                    <table class="table-site" cellpadding="0" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Document Prepared by:</th>
                                <th>Measurement Prepared by:</th>
                                <th>Approved by:</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="padding-bottom:70px;"></td>
                                <td style="padding-bottom:70px;"></td>
                                <td style="padding-bottom:70px;"></td>
                            </tr>
                            <tr>
                                <td style="text-align:left; font-weight:bold; border-bottom:none;">TELKOM</td>
                                <td style="text-align:left; font-weight:bold; border-bottom:none;">TELKOMSEL RTSO REGION</td>
                                <td style="text-align:left; font-weight:bold; border-bottom:none;">TELKOMSEL PROJECT</td>
                            </tr>
                            <tr>
                                <td style="text-align:left; font-weight:bold; border-bottom: none; border-top:none;"></td>
                                <td style="text-align:left; font-weight:bold; border-bottom: none; border-top:none;"></td>
                                <td style="text-align:left; font-weight:bold; border-bottom: none; border-top:none;">DEPLOYMENT REGION</td>
                            </tr>
                            <tr>
                                <td style="text-align:left; font-weight:bold; border-bottom: none; border-top:none;">Name: Teguh Cipto E.</td>
                                <td style="text-align:left; font-weight:bold; border-bottom: none; border-top:none;">Name: Hanif Zakarya</td>
                                <td style="text-align:left; font-weight:bold; border-bottom: none; border-top:none;">Name: Didin Kuswoyo</td>
                            </tr>
                            <tr>
                                <td style="text-align:left; font-weight:bold; border-top:none;">Date: 22-03-2021</td>
                                <td style="text-align:left; font-weight:bold; border-top:none;">Date: 22-03-2021</td>
                                <td style="text-align:left; font-weight:bold; border-top:none;">Date: 22-03-2021</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- halaman konfigurasi  -->
            @for($a=1; $a<=@count($data_site); $a+=2)
                <div class="page_break_after">
                    <div class="margin-header-logo">
                        <table style="width:100%;">
                            <tr>
                                <td style="width:50%"><img src="{{ public_path('/assets/images/telkomsel.png') }}" style="width:100px;"></td>
                                <td style="width:50%; text-align: right;"><img src="{{ public_path('/assets/images/telkom.png') }}" style="width:100px;"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="margin-content margin-body mb-small">
                        <table class="table-kelengkapan cellpadding="0" cellspacing="0">
                            <tbody>
                                <tr>
                                    <td colspan="2" style="padding-bottom: 15px; font-weight:bold;"><span style="font-style: italic;">Lampiran BAUT Nomor</span> : TEL.248/YN.000/DR5-11000000/2021</td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="padding-bottom: 15px;"></td>
                                </tr>
                                @for ($b=$a; $b<=$a+1; $b++)
                                    @if ($b <= @count($data_site))
                                        <tr>
                                            <td colspan="2" style="text-align: left !important; font-weight:bold;">Data Konfigurasi Site {{$data_site[$b-1]}}</td>
                                        </tr>
                                        <tr>
                                            <td width="5%"></td>
                                            <td>
                                                <img src="{{ public_path('/assets/images/test.png') }}" style="width:500px;">
                                            </td>
                                        </tr>
                                    @endif
                                @endfor
                            </tbody>
                        </table>
                    </div>
                </div>
            @endfor

            <!-- halaman topologi -->

            @for($a=1; $a<=10; $a+=3)
                @if ($a <= @count($data_site))
                    <div class="page_break_after">
                        <div class="margin-header-logo">
                            <table style="width:100%;">
                                <tr>
                                    <td style="width:50%"><img src="{{ public_path('/assets/images/telkomsel.png') }}" style="width:100px;"></td>
                                    <td style="width:50%; text-align: right;"><img src="{{ public_path('/assets/images/telkom.png') }}" style="width:100px;"></td>
                                </tr>
                            </table>
                        </div>
                        <div class="margin-content margin-body mb-small">
                            <table class="table-kelengkapan cellpadding="0" cellspacing="0">
                                <tbody>
                                    <tr>
                                        <td colspan="2" style="padding-bottom: 15px; font-weight:bold;"><span style="font-style: italic;">Lampiran BAUT Nomor</span> : TEL.248/YN.000/DR5-11000000/2021</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="padding-bottom: 15px;"></td>
                                    </tr>
                                    @for ($b=$a; $b<=$a+2; $b++)
                                        @if ($b <= @count($data_site))
                                            <tr>
                                                <td colspan="2" style="text-align: left !important; font-weight:bold;">Data Topologi Site {{$data_site[$b-1]}}</td>
                                            </tr>
                                            <tr>
                                                <td width="5%"></td>
                                                <td>
                                                    <img src="{{ public_path('/assets/images/topology.jpg') }}" style="width:600px; height:250px; object-fit: cover;">
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

            <!-- halaman traffic  -->
            
            @for($a=1; $a<=10; $a+=3)
                @if ($a <= @count($data_site))
                    <div class="page_break_after">
                        <div class="margin-header-logo">
                            <table style="width:100%;">
                                <tr>
                                    <td style="width:50%"><img src="{{ public_path('/assets/images/telkomsel.png') }}" style="width:100px;"></td>
                                    <td style="width:50%; text-align: right;"><img src="{{ public_path('/assets/images/telkom.png') }}" style="width:100px;"></td>
                                </tr>
                            </table>
                        </div>
                        <div class="margin-content margin-body mb-small">
                            <table class="table-kelengkapan cellpadding="0" cellspacing="0">
                                <tbody>
                                    <tr>
                                        <td colspan="2" style="padding-bottom: 15px; font-weight:bold;"><span style="font-style: italic;">Lampiran BAUT Nomor</span> : TEL.248/YN.000/DR5-11000000/2021</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="padding-bottom: 15px;"></td>
                                    </tr>
                                    @for ($b=$a; $b<=$a+2; $b++)
                                        @if ($b <= @count($data_site))
                                            <tr>
                                                <td colspan="2" style="text-align: left !important; font-weight:bold;">Data Trafik Site {{$data_site[$b-1]}}</td>
                                            </tr>
                                            <tr>
                                                <td width="5%"></td>
                                                <td>
                                                    <img src="{{ public_path('/assets/images/network.png') }}" style="width:600px; height:250px; object-fit: cover;">
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

            <!-- Halaman lampiran order -->
            
            @for($a=1; $a<=2; $a++)
                @if ($a < 2)
                    <div class="page_break_after">
                @else
                    <div>
                @endif

                        <div class="margin-header-logo">
                            <table style="width:100%;">
                                <tr>
                                    <td style="width:50%"><img src="{{ public_path('/assets/images/telkomsel.png') }}" style="width:100px;"></td>
                                    <td style="width:50%; text-align: right;"><img src="{{ public_path('/assets/images/telkom.png') }}" style="width:100px;"></td>
                                </tr>
                            </table>
                        </div>
                        <div class="margin-content margin-body mb-small">
                            <table class="table-kelengkapan cellpadding="0" cellspacing="0">
                                <tbody>
                                    <tr>
                                        <td colspan="2" style="padding-bottom: 15px; font-weight:bold;"><span style="font-style: italic;">Lampiran BAUT Nomor</span> : TEL.248/YN.000/DR5-11000000/2021</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="padding-bottom: 15px;"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="text-align: left !important; font-weight:bold;">Work Order Site KED418, KED419, KED449, KED415, BLR295, GSK168, SBY270</td>
                                    </tr>
                                    <tr>
                                        <td width="5%"></td>
                                        <td>
                                            <img src="{{ public_path('/assets/images/work-order.png') }}" style="width:600px; object-fit: cover;">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div> 
            @endfor
        </main>
    </body>
</html>
</span>
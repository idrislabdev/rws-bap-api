<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Berita Acara Upgarde</title>

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
                    <h3 class="header-margin font-weight-bold"><center>BERITA ACARA UJI TERIMA (BAUT) REBALANCING</center></h3>
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
                    <p class="mt-3">TELKOM dan TELKOMSEL Menyatakan bahwa sebagai berikut :</p>
                </div>
                <div class="margin-content margin-body">
                    <table>
                        <tr>
                            <td style="width:288px">Dasar Permintaan</td>
                            <td style="width:10px">:</td>
                            <td>Terlampir</td>
                        </tr>
                        <tr>
                            <td style="width:288px">Produk</td>
                            <td style="width:10px">:</td>
                            <td>Akses Link</td>
                        </tr>
                        <tr>
                            <td style="width:288px">Jenis Layanan</td>
                            <td style="width:10px">:</td>
                            <td>FO & Radio</td>
                        </tr>
                        <tr>
                            <td style="width:288px">Lingkup Pekerjaan</td>
                            <td style="width:10px">:</td>
                            <td>Rebalancing</td>
                        </tr>
                        <tr>
                            <td style="width:288px">Jumlah Upgrade (Jumlah BW)</td>
                            <td style="width:10px">:</td>
                            <td>{{$total_site}} Upgrade dengan BW {{$total_bw}} Mbps</td>
                        </tr>
                    </table>
                </div>
                <div class="margin-content margin-body">
                    <table style="width:100%;">
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
                <div class="margin-body">
                    <p>
                        Demikian Berita Acara ini dibuat dengan sebenarnya dalam rangkap 2 (dua) yang sama bunyinya dan mempunyai
                        kekuatan hukum yang sama setelah ditanda tangani ke dua belah Pihak.
                    </p>
                </div>
                <div class="margin-body">
                    <table style="width:100%;">
                        <tr class="text-center">
                            <td style="padding-bottom: 70px; width:40%; font-weight:bold;">TELKOM</td>
                            <td style="width:20%;"></td>
                            <td style="padding-bottom: 70px; width:40%; font-weight:bold;">TELKOMSEL</td>
                        </tr>
                        <tr class="text-center">
                            <td style="font-weight:bold; text-decoration: underline">
                                <table style="width:100%;position:relative;">
                                    <tr>
                                        <td style="font-weight:bold; text-decoration: underline">
                                            {{$people_ttd->osm_regional->nilai}}
                                            @if($manager_wholesale !== null)
                                                <img src="{{ public_path().'/ttd/'.  $manager_wholesale->paraf_image }}"  style="height:40px;position:absolute;right:10px;top:-10px;">
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td style="width:20%"></td>
                            <td style="font-weight:bold; text-decoration: underline">{{$people_ttd->gm_core_transport->nilai}}</td>
                        </tr>
                        <tr class="text-center">
                            <td style="vertical-align: top;">{{$people_ttd->osm_regional->detail_nilai}}</td>
                            <td style="width:20%"></td>
                            <td>{{$people_ttd->gm_core_transport->detail_nilai}}</td>
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
                    <h3 class="header-margin font-underline"><center>Kelengkapan Dokument BAUT</center></h3>
                    <h4 class="header-margin"><center><span class="font-italic">Lampiran Baut</span> Nomor : {{$data_ba->no_dokumen}}</center></h4>
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
                                        <img src="{{ public_path('/assets/images/square.png') }}"  style="width:14px; margin-left: 15px;"> NOT OK
                                        <img src="{{ public_path('/assets/images/square.png') }}"  style="width:14px; margin-left: 15px;"> <span>N/A</span>
                                    </td>
                                    <td></td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
                <div class="margin-body mb-large">
                    <p>
                        Catatan : * hanya untuk upgrade
                    </p>
                </div>
                <div class="margin-body">
                    <table style="width:100%;">
                        <tr class="text-center">
                            <td style="width:40%; font-weight:bold;">TELKOM</td>
                            <td style="width:20%;"></td>
                            <td style="width:40%; font-weight:bold;">TELKOMSEL</td>
                        </tr>
                        <tr class="text-center">
                            @if($manager_wholesale !== null)
                                <td style="width:40%; font-weight:bold; text-decoration: underline;">
                                    <img src="{{ public_path().'/ttd/'.  $manager_wholesale->ttd_image }}" style="height:100px;">
                                </td>
                            @else
                                <td style="height:70px;width:40%; font-weight:bold;">
                                </td>
                            @endif
                            <td style="height:70px;width:20%;"></td>
                            <td style="height:70px;width:40%; font-weight:bold;"></td>
                        </tr>
                        <tr class="text-center">
                            <td style="font-weight:bold; text-decoration: underline">
                                <table style="width:100%;position:relative;">
                                    <tr>
                                        <td style="font-weight:bold; text-decoration: underline">
                                            {{$people_ttd->manager_wholesale->nilai}}
                                            @if($paraf_wholesale !== null)
                                                <img src="{{ public_path().'/ttd/'.  $paraf_wholesale->paraf_wholesale }}"  style="height:40px;position:absolute;right:10px;top:-10px;">
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td style="width:20%;"></td>
                            <td style="font-weight:bold; text-decoration: underline">{{(strtoupper($data_ba->tsel_reg) == 'JAWA TIMUR') ? $people_ttd->manager_pm_jatim->nilai : $people_ttd->manager_pm_balnus->nilai}}</td>
                        </tr>
                        <tr class="text-center">
                            <td style="vertical-align: top;">{{$people_ttd->manager_wholesale->detail_nilai}}</td>
                            <td style="width:20%;"></td>
                            <td>{{(strtoupper($data_ba->tsel_reg) == 'JAWA TIMUR') ? $people_ttd->manager_pm_jatim->detail_nilai : $people_ttd->manager_pm_balnus->detail_nilai}}</td>
                        </tr>
                    </table>            
                </div>
            </div>

            <!-- halaman 4 (keempat) -->
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
                <div class="margin-content margin-body header">
                    <h4 class="header-margin"><span class="font-italic">Lampiran Baut</span> Nomor : {{$data_ba->no_dokumen}}</h4>
                    <h4 class="header-margin"><span class="font-italic">Data Konfigurasi Order Upgrade</h4>
                </div>
                <div class="margin-content margin-body mb-xl">
                    <table class="table-site" cellpadding="0" cellspacing="0">
                        <thead>
                            <tr>
                                <th style="width:4%" rowspan="2" class="td-center">No. </th>
                                <th style="width:10%" rowspan="2" class="td-center">SITE ID</th>
                                <th style="width:18%" rowspan="2" class="td-center">SITE NAME</th>
                                <th style="width:18%" rowspan="2" class="td-center">POSITION</th>
                                <th style="width:10%" rowspan="2" class="td-center">WITEL</th>
                                <th style="width:13%" rowspan="2" class="td-center">ON DATE</th>
                                <th style="width:13%" rowspan="2" class="td-center">ALPRO SITE</th>
                                <th style="width:20%" rowspan="2 style="text-align:center;">Order BW</th>
                                <th style="width:15%" rowspan="2" class="td-center">NO. ORDER</th>
                                <th style="width:15%" rowspan="2" class="td-center">KETERANGAN</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for ($a=0; $a<@count($data_site); $a++)
                                <tr>
                                    <td>{{$a+1}}</td>
                                    <td>{{$data_site[$a]->site_id}}</td>
                                    <td class="wrapword">{{$data_site[$a]->site_name}}</td>
                                    <td>END SITE</td>
                                    <td>{{$data_site[$a]->site_witel}}</td>
                                    <td>{{strtoupper(date('d-M-y', strtotime($data_site[$a]->tgl_on_air)))}}</td>
                                    <td>{{$data_site[$a]->alpro_site}}</td>
                                    <td>{{$data_site[$a]->jumlah}}</td>
                                    <td>{{$data_site[$a]->dasar_order}}</td>
                                    <td>{{$data_site[$a]->keterangan}}</td>
                                </tr>
                            @endfor 
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- halaman konfigurasi  -->
            @if (@count($data_site) > 1)
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
                                        <td colspan="2" style="padding-bottom: 15px; font-weight:bold;"><span style="font-style: italic;">Lampiran BAUT Nomor</span> : {{$data_ba->no_dokumen}}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="padding-bottom: 15px;"></td>
                                    </tr>
                                    @for ($b=$a; $b<=$a+1; $b++)
                                        @if ($b <= @count($data_site))
                                            <tr>
                                                <td colspan="2" style="text-align: left !important; font-weight:bold;">Data Konfigurasi Site {{$data_site[$b-1]->site_id}}</td>
                                            </tr>
                                            @php($image = getimagesize(public_path().'/lampirans/'.  $data_site[$b-1]->konfigurasi ))
                                            @if($image[1] < 300)
                                                <tr style="height:300px;">
                                                    <td width="5%"></td>
                                                    <td>
                                                        <img src="{{ public_path().'/lampirans/'.  $data_site[$b-1]->konfigurasi }}" style="width:700px; object-fit: cover;">
                                                    </td>
                                                </tr>
                                            @else
                                                <tr>
                                                    <td width="5%"></td>
                                                    <td>
                                                        <img src="{{ public_path().'/lampirans/'.  $data_site[$b-1]->konfigurasi }}" style="width:700px; height:300px; object-fit: cover;">
                                                    </td>
                                                </tr>
                                            @endif
                                        @endif
                                    @endfor
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endfor

                <!-- halaman topologi -->

                @for($a=1; $a<=@count($data_site); $a+=2)
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
                                            <td colspan="2" style="padding-bottom: 15px; font-weight:bold;"><span style="font-style: italic;">Lampiran BAUT Nomor</span> : {{$data_ba->no_dokumen}}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="padding-bottom: 15px;"></td>
                                        </tr>
                                        @for ($b=$a; $b<=$a+1; $b++)
                                            @if ($b <= @count($data_site))
                                                <tr>
                                                    <td colspan="2" style="text-align: left !important; font-weight:bold;">Data Topologi Site {{$data_site[$b-1]->site_id}}</td>
                                                </tr>
                                                @php($image = getimagesize(public_path().'/lampirans/'.  $data_site[$b-1]->topologi ))
                                                @if($image[1] < 300)
                                                    <tr style="height:300px;">
                                                        <td width="5%"></td>
                                                        <td>
                                                            <img src="{{ public_path().'/lampirans/'.  $data_site[$b-1]->topologi }}" style="width:700px; object-fit: cover;">
                                                        </td>
                                                    </tr>
                                                @else
                                                    <tr>
                                                        <td width="5%"></td>
                                                        <td>
                                                            <img src="{{ public_path().'/lampirans/'.  $data_site[$b-1]->topologi }}" style="width:700px; height:300px; object-fit: cover;">
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endif
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div> 
                    @endif
                @endfor

                <!-- halaman traffic  -->
                
                @for($a=1; $a<=@count($data_site); $a+=2)
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
                                            <td colspan="2" style="padding-bottom: 15px; font-weight:bold;"><span style="font-style: italic;">Lampiran BAUT Nomor</span> : {{$data_ba->no_dokumen}}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="padding-bottom: 15px;"></td>
                                        </tr>
                                        @for ($b=$a; $b<=$a+1; $b++)
                                            @if ($b <= @count($data_site))
                                                <tr>
                                                    <td colspan="2" style="text-align: left !important; font-weight:bold;">Data Trafik Site {{$data_site[$b-1]->site_id}}</td>
                                                </tr>
                                                @php($image = getimagesize(public_path().'/lampirans/'.  $data_site[$b-1]->trafik ))
                                                @if($image[1] < 300)
                                                    <tr style="height:300px;">
                                                        <td width="5%"></td>
                                                        <td>
                                                            <img src="{{ public_path().'/lampirans/'.  $data_site[$b-1]->trafik }}" style="width:700px; object-fit: cover;">
                                                        </td>
                                                    </tr>
                                                @else
                                                    <tr>
                                                        <td width="5%"></td>
                                                        <td>
                                                            <img src="{{ public_path().'/lampirans/'.  $data_site[$b-1]->trafik }}" style="width:700px; height:300px; object-fit: cover;">
                                                        </td>
                                                    </tr>
                                                @endif
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
                                    <td colspan="2" style="padding-bottom: 15px; font-weight:bold;"><span style="font-style: italic;">Lampiran BAUT Nomor</span> : {{$data_ba->no_dokumen}}</td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="padding-bottom: 15px;"></td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="text-align: left !important; font-weight:bold;">Data Konfigurasi Site {{$data_site[0]->site_id}}</td>
                                </tr>
                                @php($image = getimagesize(public_path().'/lampirans/'. $data_site[0]->konfigurasi ))
                                @if($image[1] < 300)
                                    <tr style="height:300px;">
                                        <td width="5%"></td>
                                        <td>
                                            <img src="{{ public_path().'/lampirans/'. $data_site[0]->konfigurasi }}" style="width:700px; object-fit: cover;">
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td width="5%"></td>
                                        <td>
                                            <img src="{{ public_path().'/lampirans/'. $data_site[0]->konfigurasi }}" style="width:700px; height:300px; object-fit: cover;">
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <td colspan="2" style="text-align: left !important; font-weight:bold;">Data Topologi Site {{$data_site[0]->site_id}}</td>
                                </tr>
                                @php($image = getimagesize(public_path().'/lampirans/'. $data_site[0]->topologi ))
                                @if($image[1] < 300)
                                    <tr style="height:300px;">
                                        <td width="5%"></td>
                                        <td>
                                            <img src="{{ public_path().'/lampirans/'. $data_site[0]->topologi }}" style="width:700px; object-fit: cover;">
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td width="5%"></td>
                                        <td>
                                            <img src="{{ public_path().'/lampirans/'. $data_site[0]->topologi }}" style="width:700px; height:300px; object-fit: cover;">
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <td colspan="2" style="text-align: left !important; font-weight:bold;">Data Trafik Site {{$data_site[0]->site_id}}</td>
                                </tr>
                                @php($image = getimagesize(public_path().'/lampirans/'. $data_site[0]->trafik ))
                                @if($image[1] < 300)
                                    <tr style="height:300px;">
                                        <td width="5%"></td>
                                        <td>
                                            <img src="{{ public_path().'/lampirans/'. $data_site[0]->trafik }}" style="width:700px; object-fit: cover;">
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td width="5%"></td>
                                        <td>
                                            <img src="{{ public_path().'/lampirans/'. $data_site[0]->trafik }}" style="width:700px; height:300px; object-fit: cover;">
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div> 
            @endif

            <!-- Halaman lampiran order -->
            
            @for($a=1; $a<=count($data_wo); $a++)
                @if ($a < count($data_wo))
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
                                        <td colspan="2" style="padding-bottom: 15px; font-weight:bold;"><span style="font-style: italic;">Lampiran BAUT Nomor</span> : {{$data_ba->no_dokumen}}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="padding-bottom: 15px;"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="text-align: left !important; font-weight:bold;">Work Order Site {{$data_wo[$a-1]->daftar_site}}</td>
                                    </tr>
                                    <tr>
                                        <td width="5%"></td>
                                        <td>
                                            <img src="{{ public_path().'/lampirans/'. $data_wo[$a-1]->lampiran_url }}" style="width:600px; object-fit: cover;">
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
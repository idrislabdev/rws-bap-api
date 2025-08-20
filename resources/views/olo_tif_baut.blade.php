@php 
    use App\Http\Controllers\OLO\Transaksi\BeritaAcaraController; 
    use App\Helper\UtilityHelper;
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Berita Acara</title>

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
            @page { margin: 10px; }
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
                font-size: 15px;
            }
            h3 {    
                font-size: 20px;
                font-weight: normal;
            }
            h4 {
                font-size: 18px;
            }
            td, th {
                font-size: 15px;
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
            .table-dokumen {
                width: 100%;
                border-collapse: collapse;
                /* border: 1px solid black; */
            }
            .table-dokumen td{
                font-size: 15px;
                padding: 4px;
                vertical-align: middle;
            }
            .table-dokumen th{
                font-size: 15px;
                padding : 5px;
            }
            .table-kelengkapan {
                width: 100%;
                border-collapse: collapse;
                /* border: 1px solid black; */
            }
            .table-kelengkapan td{
                border: thin solid black;
                font-size: 15px;
                padding: 4px;
                vertical-align: middle;
            }
            .table-kelengkapan th{
                border: thin solid black;
                font-size: 15px;
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
                font-size: 15px;
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
                margin-left: 0px;
                margin-right: 0px;
                margin-bottom: 0px;
                top: 0px;
            }
            .margin-footer-logo {
                margin-left: 40px;
                margin-right: 30px;
                margin-bottom: 0px;
                bottom: 0px;
            }
            .margin-body {
                margin-left: 0px;
                margin-right: 0px;
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
            <div class="page_break_before">
                <div class="margin-header-logo">
                    <table style="width:100%;">
                        <tr>
                            <td style="width:50%; text-align: right;"><img src="{{ public_path('/assets/images/telkom-infra.jpg') }}" style="width:200px;"></td>
                        </tr>
                    </table>
                </div>
                <div class="header">
                    <h3 class="header-margin font-weight-bold"><center>BERITA ACARA {{$data->jenis_order}}</center></h3>
                </div>
                <hr style="border : 2px solid #000">
                <h4 class="header-margin font-weight-bold"><center>No.{{$data->no_dokumen_baut}}</center></h4>
                <div class="margin-body">
                    <p>
                        Pada hari ini <span style="text-transform: capitalize; font-weight: bold">{{$format_tanggal->hari}}</span> tanggal <span style="text-transform: capitalize; font-weight: bold">{{$format_tanggal->tgl}}</span> bulan <span style="text-transform: capitalize; font-weight: bold">{{$format_tanggal->bulan}}</span> Tahun <span style="text-transform: capitalize; font-weight: bold">{{$format_tanggal->tahun}}</span>, 
                        kami yang bertanda tangan di bawah ini :
                    </p>
                </div>
                <div class="content-ttd margin-body">
                    <table>
                        <tr>
                            <td style="width:50px">I. </td>
                            <td style="width:200px">Nama/NIK</td>
                            <td style="width:10px">:</td>
                            <td style="width:600px">{{$data->ttd_nama}}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Jabatan</td>
                            <td>:</td>
                            <td>{{$data->ttd_jabatan}}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Perusahaan</td>
                            <td>:</td>
                            <td>{{$data->ttd_lokasi_kerja}}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td colspan="3">Selanjutnya disebut <span class="font-weight-bold">[TIF]</span></td>
                        </tr>
                    </table>
                </div>
                <div class="content-ttd margin-body">
                    <table>
                        <tr>
                            <td style="width:50px">II. </td>
                            <td style="width:200px">Nama/NIK</td>
                            <td style="width:10px">:</td>
                            <td style="width:600px">{{$data->klien_penanggung_jawab_baut}}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Jabatan</td>
                            <td>:</td>
                            <td>{{$data->klien_jabatan_penanggung_jawab_baut}}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Perusahaan</td>
                            <td>:</td>
                            <td>{{$data->klien_lokasi_kerja_baut}}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td colspan="3">Selanjutnya disebut <span class="font-weight-bold">{{$data->klien_nama_baut}}</span></td>
                        </tr>
                    </table>
                </div>
                <div class="margin-body">
                    <p class="mt-3">Menyatakan bahwa layanan sebagai berikut selesai diintergrasi serta dinyatakan siap untuk <span style="text-transform: uppercase; font-weight: bold">{{$data->jenis_order}}</span></p>
                </div>
                <div class="margin-content margin-body">
                    <table class="table-site" cellpadding="0" cellspacing="0">
                        <thead>
                            <tr>
                                <th style="width:5%"  class="td-center">No. </th>
                                <th style="width:12%"  class="td-center">Layanan</th>
                                <th style="width:15%"  class="td-center">Layanan Lokasi</th>
                                <th style="width:10%"  class="td-center">Kapasitas</th>
                                <th style="width:12%"  class="td-center">Order</th>
                                <th style="width:10%"  class="td-center">SID</th>
                                <th style="width:15%"  class="td-center">Add On</th>
                                <th style="width:15%"  class="td-center">Tanggal {{$data->jenis_order}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for ($a=0; $a<@count($detail); $a++)
                                <tr>
                                    <td>{{$a+1}}</td>
                                    <td>{{$detail[$a]->produk}}</td>
                                    <td class="wrapword">{{$detail[$a]->alamat_instalasi}}</td>
                                    <td>{{$detail[$a]->bandwidth_mbps}} Mbps</td>
                                    <td>{{$detail[$a]->ao_sc_order}}</td>
                                    <td>{{$detail[$a]->sid}}</td>
                                    <td>
                                        {{BeritaAcaraController::formatAddOn($detail[$a]->id, $detail[$a]->olo_ba_id)}}
                                    </td>
                                    <td>{{UtilityHelper::formatDateIndo(date('Y-m-d', strtotime($detail[$a]->tgl_order)))}}</td>
                                </tr>
                            @endfor 
                        </tbody>
                    </table>
                </div>
                <div class="margin-body mt-xl">
                    <p>
                        Demikian Berita Acara ini dibuat <span class="font-weight-bold">dalam rangkap 2 (dua) asli</span> 
                        yang sama bunyinya dan mempunyai kekuatan hukum yang sama setelah ditandatangani oleh kedua belah pihak.
                    </p>
                </div>
                <div class="margin-body mt-xl">
                    <table style="width:100%;">
                        <tr class="text-center">
                            <td style="vertical-align: top;"></td>
                            <td style="width:20%;"></td>
                            <td style="font-weight: bold;">Surabaya, {{UtilityHelper::formatDateIndo(date('Y-m-d', strtotime($data->tgl_dokumen)))}}</td>
                        </tr>
                        <tr class="text-center">
                            <td style="width:40%; font-weight:bold;">
                                PT. TELKOM INFRASTRUKTUR INDONESIA
                            </td>
                            <td style="width:20%;"></td>
                            <td style="width:40%; font-weight:bold;">
                                {{$data->klien_nama_baut}}
                            </td>
                        </tr>
                        <tr class="text-center">
                            <td style="height:100px;width:40%; font-weight:bold;">
                                @if($manager_wholesale !== null)
                                    <img src="{{ public_path().'/ttd/'.  $manager_wholesale->ttd_image }}" style="height:150px;">
                                @endif
                            </td>
                            <td style="height:100px;width:20%;"></td>
                            <td style="height:100px;width:40%; font-weight:bold;"></td>
                        </tr>
                        <tr class="text-center">
                            <td>
                                <table style="width:100%;position:relative;">
                                    <tr>
                                        <td style="font-weight:bold; text-decoration: underline">
                                            {{$data->ttd_nama}}
                                            @if($paraf_wholesale !== null)
                                                <img src="{{ public_path().'/ttd/'.  $paraf_wholesale->paraf_image }}"  style="height:40px;position:absolute;right:10px;top:-10px;">
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td style="width:20%;"></td>
                            <td style="font-weight:bold; text-decoration: underline">{{$data->klien_penanggung_jawab_baut}}</td>
                        </tr>
                        <tr class="text-center">
                            <td style="vertical-align: top;">{{$data->ttd_jabatan}}</td>
                            <td style="width:20%;"></td>
                            <td>{{$data->klien_jabatan_penanggung_jawab_baut}}</td>
                        </tr>
                    </table>            
                </div>
            </div>
            @for($a=0; $a<@count($lampiran_dokumen); $a++)
                @if ($a < @count($lampiran_dokumen))
                    <div class="page_break_before">
                        <div class="margin-header-logo">
                            <table style="width:100%;">
                                <tr>
                                    <td style="width:50%"><img src="{{ public_path('/assets/images/iot-telkomsel.png') }}" style="width:100px;"></td>
                                    <td style="width:50%; text-align: right;"><img src="{{ public_path('/assets/images/telkom-infra.jpg') }}" style="width:100px;"></td>
                                </tr>
                            </table>
                        </div>
                        <div class="margin-content margin-body mb-small">
                            <div>
                                <h5 style="font-size: 24px; margin-top:0px; margin-bottom:0px; line-height: 0px; font-weight: 500; text-align: center;">LAMPIRAN</h5>
                                <p style="font-size: 17px;">Dokumen:</p>
                            </div>
                            <table class="table-dokumen cellpadding="0" cellspacing="0">
                                <tbody>
                                    <tr>
                                        {{-- @php($image = getimagesize(public_path().'/lampirans/'. $lampiran_dokumen[$a]->url ))
                                        @if($image[0] < $image[1])
                                            <td style="text-align: center">
                                                <img src="{{ public_path().'/lampirans/'.  $lampiran_dokumen[$a]->url }}" style="height:90%;">
                                            </td>
                                        @else
                                            <td style="text-align: center">
                                                <img src="{{ public_path().'/lampirans/'.  $lampiran_dokumen[$a]->url }}" style="width:100%;">
                                            </td>
                                        @endif --}}
                                        <td style="text-align: center">
                                            <img src="{{ public_path().'/lampirans/'.  $lampiran_dokumen[$a]->url }}" style="max-width:100%; height:auto; max-height: 1000px;">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div> 
                @endif
            @endfor
            @for($a=0; $a<@count($lampiran_other); $a+=2)
                @if (@count($lampiran_other) > 0)
                    <div class="page_break_before">
                        <div class="margin-header-logo">
                            <table style="width:100%;">
                                <tr>
                                    <td style="width:50%"><img src="{{ public_path('/assets/images/iot-telkomsel.png') }}" style="width:100px;"></td>
                                    <td style="width:50%; text-align: right;"><img src="{{ public_path('/assets/images/telkom-infra.jpg') }}" style="width:100px;"></td>
                                </tr>
                            </table>
                        </div>
                        <div class="margin-content margin-body mb-small">
                            <div>
                                <h5 style="font-size: 24px; margin-top:0px; margin-bottom:0px; line-height: 0px; font-weight: 500; text-align: center;">LAMPIRAN</h5>
                                <p style="font-size: 17px;">Lokasi:</p>
                            </div>
                            <table class="table-kelengkapan cellpadding="0" cellspacing="0">
                                <tbody>
                                        <tr>
                                            @if (isset($lampiran_other[$a+1]))
                                                <td style="text-align: center; padding:20px; width:50%">
                                                    <img src="{{ public_path().'/lampirans/'.  $lampiran_other[$a]->url }}" style="width: 50%; height:auto; max-height: 500px;">
                                                </td>
                                                <td style="text-align: center; padding:20px; width:50%">
                                                    <img src="{{ public_path().'/lampirans/'.  $lampiran_other[$a+1]->url }}" style="width: 50%; height:auto; max-height: 500px;">
                                                </td>
                                            @else
                                                <td colspan="2" style="text-align: center; padding:20px; width:100%">
                                                    <img src="{{ public_path().'/lampirans/'.  $lampiran_other[$a]->url }}" style="width: 50%; height:auto; max-height: 500px;">
                                                </td>
                                            @endif
                                        </tr>
                                        <tr>
                                            @if (isset($lampiran_other[$a+1]))
                                                <td style="text-align: center; width:50%">{{$lampiran_other[$a]->label }}</td>
                                                <td style="text-align: center; width:50%">{{$lampiran_other[$a+1]->label }}</td>
                                            @else
                                                <td colspan="2" style="text-align: center; width:100%">{{$lampiran_other[$a]->label }}</td>
                                            @endif
                                        </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            @endfor
        </main>
    </body>
</html>
</span>
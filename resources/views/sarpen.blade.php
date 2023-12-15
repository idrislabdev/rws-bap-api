@php 
    use App\Http\Controllers\OLO\Transaksi\BeritaAcaraController; 
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Berita Acara Sarpen</title>

        <!-- <link href="{{ public_path('/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" /> -->
        <link href="{{ public_path('/assets/css/font.css') }}" rel="stylesheet" type="text/css" />



        <style>
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
                font-size: 17px;
            }
            h3 {    
                font-size: 20px;
                font-weight: normal;
            }
            h4 {
                font-size: 18px;
            }
            td, th {
                font-size: 17px;
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
            .table-lampiran {
                width: 100%;
                border-collapse: collapse;
                position: relative;
                /* border: 1px solid black; */
            }
            .table-lampiran td{
                border: thin solid black;
                font-size: 11px;
                line-height: 11px;
                padding: 4px;
                vertical-align: middle;
                font-weight: 400;
            }
            .table-lampiran th{
                border: thin solid black;
                font-size: 11px;
                line-height: 11px;
                padding : 5px;
                font-weight: 400;
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
            .table-gambar {
                width: 100%;
                border-collapse: collapse;
                /* border: 1px solid black; */
            }
            .table-gambar td{
                border: thin solid black;
                font-size: 15px;
                padding: 4px;
                vertical-align: middle;
            }
            .table-gambar th{
                border: thin solid black;
                font-size: 15px;
                padding : 5px;
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
            .mb-50 {
                margin-bottom: 50px;
            }
            .text-telkom {
                color : #ff0000;
            }
            .text-center {
                text-align: center
            }
            .title-lampiran {
                margin-bottom: 10px;
            }
            h5 {
                margin-bottom: 0px;
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
            <div class="">
                <div class="header">
                    <h3 class="header-margin font-weight-bold mb-50"><center>BERITA ACARA SEWA SARANA PENUNJANG</center></h3>
                    <div style="margin-left: 200px">
                        <table>
                            <tr>
                                <td style="width:100px">No. Telkom</td>
                                <td style="width:10px">:</td>
                                <td style="width:275px; border-bottom:1px solid #000">
                                    {{ $no_dokumen }}
                                </td>
                            </tr>
                            @if($setting->group == 'TELKOM')
                                <tr>
                                    <td style="width:100px">No. Telkomsel</td>
                                    <td style="width:10px">:</td>
                                    <td style="width:275px; border-bottom:1px solid #000">
                                        {{ $no_dokumen_klien }}
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
                </div>
                <div class="margin-body">
                    <p>
                        Pada hari ini <stron>{{$format_tanggal->hari}}</stron> tanggal <strong>{{$format_tanggal->tgl}}</strong> Bulan <strong>{{strtoupper($format_tanggal->bulan)}}</strong> Tahun <strong>{{$format_tanggal->tahun}} ({{strtoupper(date('d/m/Y', strtotime($tgl_dokumen)))}})</strong>, 
                        kami yang bertanda tangan di bawah ini :
                    </p>
                </div>
                <div class="content-ttd margin-body">
                    <table>
                        <tr>
                            <td></td>
                            <td style="width:200px">Nama</td>
                            <td style="width:10px">:</td>
                            <td style="width:600px">{{ $manager_witel !== null ? $manager_witel->nama_lengkap : '' }}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Jabatan</td>
                            <td>:</td>
                            <td style="width:600px">{{ $manager_witel !== null ? $manager_witel->jabatan : '' }}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Lokasi Kerja</td>
                            <td>:</td>
                            <td style="width:600px">{{ $manager_witel !== null ? $manager_witel->lokasi_kerja : '' }}</td>
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
                            <td></td>
                            <td style="width:200px">Nama</td>
                            <td style="width:10px">: {{ $klien != null ? $klien->nama : '' }} </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Jabatan</td>
                            <td style="width:600px">: {{ $klien != null ? $klien->jabatan : '' }}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Lokasi Kerja</td>
                            <td style="width:600px">: {{ $klien != null ? $klien->lokasi_kerja : '' }}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td colspan="3">
                                @if($setting->group == 'TELKOM')
                                    Selanjutnya disebut Telkomsel
                                @else
                                    Selanjutnya disebut {{ $klien->nama_perusahaan }}
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="margin-body">
                    <p class="mt-3">Menyatakan bahwa telah dilaksanakan Site Sarpen di lokasi:</p>
                </div>
                <div class="margin-content margin-body">
                    <table>
                        <tr>
                            @if($setting->sto_site === 'SITE')
                                <td style="width:35%">Nama Site / ID Telkomsel</td>
                                <td style="width:65%">: {{ $site_survey ? $site_survey->nama_site : '' }}</td>
                            @elseif ($setting->sto_site === 'STO')
                                <td style="width:35%">Nama STO</td>
                                <td style="width:65%">: {{ $site_survey ? $site_survey->nama_sto : '' }}</td>
                            @elseif ($setting->sto_site === 'NO_ORDER')
                                <td style="width:35%">Nomor Order</td>
                                <td style="width:65%">: {{ $site_survey ? $site_survey->nomor_order : '' }}</td>
                            @endif
                        </tr>
                        <tr>
                            <td style="width:35%">Longitude / Latitude</td>
                            <td style="width:65%">: 
                                @if($site_survey)
                                    {{ $site_survey->longitude }},
                                    {{ $site_survey->latitude }}
                                @endif                       
                            </td>
                        </tr>
                        <tr>
                            <td style="width:35%">Alamat</td>
                            <td style="width:65%">: {{ $site_survey ? $site_survey->alamat : '' }}</td>
                        </tr>
                        @if($setting->group == 'TELKOM')
                        <tr>
                            <td style="padding-top:20px;width:35%">Regional Telkomsel</td>
                            <td style="padding-top:20px;width:65%">: {{ $site_survey ? $site_survey->regional : '' }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td style="width:35%">Dengan data terlampir.</td>
                            <td style="width:65%"></td>
                        </tr>
                    </table>
                </div>
                <div class="margin-body mt-xl">
                    <p>
                        Demikian Berita Acara Survey ini dibuat dengan benar dalam rangkap 2 (dua) asli yang sama bunyinya dan 
                        mempunyai kekuatan hukum yang sama setelah ditandatangani kedua belah pihak.
                    </p>
                </div>
                <div class="margin-body mt-xl">
                    <table style="width:100%;">
                        <tr class="text-center">
                            <td style="width:40%;">
                                @if($setting->group == 'TELKOM')
                                    PT. Telekomunikasi Selular
                                @else
                                    PT. {{ $klien->nama_perusahaan}}
                                @endif
                            </td>
                            <td style="width:20%;"></td>
                            <td style="width:40%;text-transform:uppercase;">
                                @if($site_survey->site_witel !== null)
                                    TELKOM WITEL {{ $site_survey->site_witel }}
                                @else
                                    TELKOM REGIONAL WHOLSALE
                                @endif
                            </td>
                        </tr>
                        <tr class="text-center">
                            <td style="width:40%;"></td>
                            <td style="width:20%;"></td>
                            <td style="width:40%;">
                                @if($manager_witel !== null && $manager_witel->status_dokumen !== null)
                                    <img src="{{ public_path().'/ttd/'.  $manager_witel->ttd_image }}" style="height:120px;width:50%">
                                @else
                                    <div style="height:100px;"></div>
                                @endif
                            </td>
                        </tr>
                        <tr class="text-center">
                            <td style="text-transform:uppercase; border-bottom:1px solid #000">
                                {{ $klien != null ? $klien->nama : '' }}
                            </td>
                            <td style="width:20%;"></td>
                            <td style="text-transform:uppercase; border-bottom:1px solid #000">
                                {{ $manager_witel !== null ? $manager_witel->nama_lengkap : '' }}
                            </td>
                        </tr>
                    </table>            
                </div>
            </div>
            <div class="page_break_before">
                <div class="header">
                    <h3 class="header-margin font-weight-bold mb-50"><center>LAMPIRAN BERITA ACARA SURVEY</center></h3>
                    <div style="margin-left: 200px">
                        <table>
                            <tr>
                                <td style="width:100px">No. Telkom</td>
                                <td style="width:10px">:</td>
                                <td style="width:350px; border-bottom:1px solid #000">
                                    {{ $no_dokumen }}
                                </td>
                            </tr>
                            @if($setting->group == 'TELKOM')
                                <tr>
                                    <td style="width:100px">No. Telkomsel</td>
                                    <td style="width:10px">:</td>
                                    <td style="width:350px; border-bottom:1px solid #000">
                                        {{ $no_dokumen_klien }}
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
                <div class="margin-content margin-body">
                    <table>
                        <tr>
                            @if($setting->sto_site === 'SITE')
                                <td style="width:30%">Nama Site / Site ID Telkomsel</td>
                                <td style="width:70%">: {{ $site_survey ? $site_survey->nama_site : '' }}</td>
                            @elseif ($setting->sto_site === 'STO')
                                <td style="width:30%">Nama STO</td>
                                <td style="width:70%">: {{ $site_survey ? $site_survey->nama_sto : '' }}</td>
                            @elseif ($setting->sto_site === 'NO_ORDER')
                                <td style="width:30%">Nomor Order</td>
                                <td style="width:70%">: {{ $site_survey ? $site_survey->nomor_order : '' }}</td>
                            @endif

                        </tr>
                        <tr>
                            <td style="width:30%">Longitude / Latitude</td>
                            <td style="width:70%">:
                                @if($site_survey)
                                    {{ $site_survey->longitude }},
                                    {{ $site_survey->latitude }}
                                @endif  
                            </td>
                        </tr>
                        <tr>
                            <td style="width:30%">Alamat</td>
                            <td style="width:70%">: {{ $site_survey ? $site_survey->alamat : '' }}</td>
                        </tr>
                        @if($setting->group == 'TELKOM')
                        <tr>
                            <td style="width:30%">Regional Telkomsel</td>
                            <td style="width:70%">: {{ $site_survey ? $site_survey->regional : '' }}</td>
                        </tr>
                        @endif
                    </table>
                </div>

                @if(isset($setting->ne_iptv))
                <div class="margin-content margin-body mb-small">
                    <div class="title-lampiran">
                        <h5>NE IPTV</h5>
                    </div>
                    <table class="table-lampiran cellpadding="0" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Perangkat</th>
                                <th>Type Perangkat</th>
                                <th>Merek</th>
                                <th>Model</th>
                                <th>Spesifkasi Teknis</th>
                                <th>Rack</th>
                                <th>Ruang Rack</th>
                                <th>Lantai</th>
                                <th>Space Lokasi</th>
                                <th>Power Catu Daya</th>
                                <th>Catuan (AC / DC)</th>
                                <th>Ruangan (Shared / Dedicated)</th>
                                <th>IPTV Platform</th>
                                <th>Jumlah Perangkat</th>
                                <th>Validasi</th>
                            </tr>
                        </thead>
                        @if($towers == null)
                        <tbody>
                            @for($a=1; $a<=10; $a++)
                            <tr>
                                <td style="height:15px;"></td>
                                <td style="height:15px;"></td>
                                <td style="height:15px;"></td>
                                <td style="height:15px;"></td>
                                <td style="height:15px;"></td>
                                <td style="height:15px;"></td>
                                <td style="height:15px;"></td>
                                <td style="height:15px;"></td>
                                <td style="height:15px;"></td>
                                <td style="height:15px;"></td>
                                <td style="height:15px;"></td>
                                <td style="height:15px;"></td>
                                <td style="height:15px;"></td>
                                <td style="height:15px;"></td>
                                <td style="height:15px;"></td>
                                <td style="height:15px;"></td>
                            </tr>
                            @endfor
                        </tbody>
                        @else
                            @if (count($ne_iptvs) > 0)
                            <tbody>
                                @foreach($ne_iptvs as $item)
                                <tr>
                                    <td style="height:15px; text-align:center">{{ $item->no }}</td>
                                    <td style="height:15px;">{{ $item->nama_perangkat }}</td>
                                    <td style="height:15px;">{{ $item->type_perangkat }}</td>
                                    <td style="height:15px;">{{ $item->merk }}</td>
                                    <td style="height:15px;">{{ $item->model }}</td>
                                    <td style="height:15px;">{{ $item->spesifikasi_teknis }}</td>
                                    <td style="height:15px;">{{ $item->rack }}</td>
                                    <td style="height:15px;">{{ $item->ruang_rack }}</td>
                                    <td style="height:15px;">{{ $item->lantai }}</td>
                                    <td style="height:15px;">{{ $item->space_lokasi }}</td>
                                    <td style="height:15px;">{{ $item->power_catu_daya }}</td>
                                    <td style="height:15px;">{{ $item->catuan_ac_dc }}</td>
                                    <td style="height:15px;">{{ $item->ruangan_share_dedicated }}</td>
                                    <td style="height:15px;">{{ $item->iptv_platform }}</td>
                                    <td style="height:15px;">{{ $item->jumlah_perangkat }}</td>
                                    <td style="height:15px;">{{ $item->validasi }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            @endif
                        @endif
                    </table>
                </div>
                @endif

                @if($setting->tower)
                <div class="margin-content margin-body mb-small">
                    <div class="title-lampiran">
                        <h5>TOWER</h5>
                    </div>
                    <table class="table-lampiran cellpadding="0" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Type / Jenis Antenna</th>
                                <th>Status Antenna</th>
                                <th>Ketinggian (meter)</th>
                                <th>Diameter (meter)</th>
                                <th>Jumlah Antenna</th>
                                <th>Tower Leg mounting Position</th>
                            </tr>
                        </thead>
                        @if($towers == null)
                        <tbody>
                            @for($a=1; $a<=10; $a++)
                            <tr>
                                <td style="height:15px;"></td>
                                <td style="height:15px;"></td>
                                <td style="height:15px;"></td>
                                <td style="height:15px;"></td>
                                <td style="height:15px;"></td>
                                <td style="height:15px;"></td>
                                <td style="height:15px;"></td>
                            </tr>
                            @endfor
                        </tbody>
                        @else
                            @if (count($towers) > 0)
                            <tbody>
                                @foreach($towers as $item)
                                <tr>
                                    <td style="height:15px; text-align:center">{{ $item->no }}</td>
                                    <td style="height:15px;">{{ $item->type_jenis_antena }}</td>
                                    <td style="height:15px;">{{ $item->status_antena }}</td>
                                    <td style="height:15px;">{{ $item->ketinggian_meter }}</td>
                                    <td style="height:15px;">{{ $item->diameter_meter }}</td>
                                    <td style="height:15px;">{{ $item->jumlah_antena }}</td>
                                    <td style="height:15px;">{{ $item->tower_leg_mounting_position }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            @endif
                        @endif
                    </table>
                </div>
                @endif

                @if($setting->rack)
                <div class="margin-content margin-body mb-small">
                    <div class="title-lampiran">
                        <h5>RACK</h5>
                    </div>
                    <table class="table-lampiran cellpadding="0" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nomor Rack</th>
                                <th>Type Rack</th>
                                <th>Jumlah Perangkat</th>
                                <th>Tipe Perangkat</th>
                            </tr>
                        </thead>
                        @if($racks == null)
                        <tbody>
                            @for($a=1; $a<=10; $a++)
                            <tr>
                                <td style="height:15px;"></td>
                                <td style="height:15px;"></td>
                                <td style="height:15px;"></td>
                                <td style="height:15px;"></td>
                                <td style="height:15px;"></td>
                            </tr>
                            @endfor
                        </tbody>
                        @else
                            @if (count($racks) > 0)
                            <tbody>
                                @foreach($racks as $item)
                                <tr>
                                    <td style="height:15px;">{{ $item->no }}</td>
                                    <td style="height:15px;">{{ $item->nomor_rack }}</td>
                                    <td style="height:15px;">{{ $item->type_rack }}</td>
                                    <td style="height:15px;">{{ $item->jumlah_perangkat }}</td>
                                    <td style="height:15px;">{{ $item->type_perangkat }} </td>
                                </tr>
                                @endforeach
                            </tbody>
                            @endif
                        @endif
                    </table>
                </div>
                @endif

                @if($setting->ruangan)
                <div class="margin-content margin-body mb-small">
                    <div class="title-lampiran">
                        <h5>RUANGAN</h5>
                    </div>
                    <table class="table-lampiran cellpadding="0" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Ruangan</th>
                                <th>Peruntukan Ruangan</th>
                                <th>Bersama / Tersendiri</th>
                                <th>Terkondisi / Tidak</th>
                                <th>Status Kepemilikan AC</th>
                                <th>Panjang (meter)</th>
                                <th>Lebar (meter)</th>
                            </tr>
                        </thead>
                        @if($ruangans == null)
                        <tbody>
                            @for($a=1; $a<=6; $a++)
                            <tr>
                                <td style="height:15px;"></td>
                                <td style="height:15px;"></td>
                                <td style="height:15px;"></td>
                                <td style="height:15px;"></td>
                                <td style="height:15px;"></td>
                                <td style="height:15px;"></td>
                                <td style="height:15px;"></td>
                                <td style="height:15px;"></td>
                            </tr>
                            @endfor
                        </tbody>
                        @else
                            @if (count($ruangans) > 0)
                            <tbody>
                                @foreach($ruangans as $item)
                                <tr>
                                    <td style="height:15px;">{{ $item->no }}</td>
                                    <td style="height:15px;">{{ $item->nama_ruangan }}</td>
                                    <td style="height:15px;">{{ $item->peruntukan_ruangan }}</td>
                                    <td style="height:15px;">{{ $item->bersama_tersendiri }}</td>
                                    <td style="height:15px;">{{ $item->terkondisi }}</td>
                                    <td style="height:15px;">{{ $item->status_kepemilikan_ac }}</td>
                                    <td style="height:15px;">{{ $item->panjang_meter }}</td>
                                    <td style="height:15px;">{{ $item->lebar_meter }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            @endif
                        @endif
                    </table>
                </div>
                @endif

                @if($setting->lahan)
                <div class="margin-content margin-body mb-small">
                    <div class="title-lampiran">
                        <h5>LAHAN</h5>
                    </div>
                    <table class="table-lampiran cellpadding="0" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Lahan</th>
                                <th>Peruntukan Lahan</th>
                                <th>Panjang (meter)</th>
                                <th>Lebar (meter)</th>
                            </tr>
                        </thead>
                        @if($lahans == null)
                        <tbody>
                            @for($a=1; $a<=4; $a++)
                            <tr>
                                <td style="height:15px;"></td>
                                <td style="height:15px;"></td>
                                <td style="height:15px;"></td>
                                <td style="height:15px;"></td>
                                <td style="height:15px;"></td>
                            </tr>
                            @endfor
                        </tbody>
                        @else
                            @if (count($lahans) > 0)
                                <tbody>
                                    @foreach($lahans as $item)
                                    <tr>
                                        <td style="height:15px;">{{ $item->no }}</td>
                                        <td style="height:15px;">{{ $item->nama_lahan }} </td>
                                        <td style="height:15px;">{{ $item->peruntukan_lahan }}</td>
                                        <td style="height:15px;">{{ $item->panjang_meter }}</td>
                                        <td style="height:15px;">{{ $item->lebar_meter }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            @endif
                        @endif
                    </table>
                </div>
                @endif

                @if($setting->catu_daya_mcb)
                <div class="margin-content margin-body mb-small">
                    <div class="title-lampiran">
                        <h5>CATU DAYA(MCB)</h5>
                    </div>
                    <table class="table-lampiran cellpadding="0" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Peruntukan</th>
                                <th>MCB (Amp)</th>
                                <th>Jumlah Phase</th>
                                <th>Voltage</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        @if($catu_daya_mcbs == null)
                        <tbody>
                            @for($a=1; $a<=10; $a++)
                            <tr>
                                <td style="height:15px;"></td>
                                <td style="height:15px;"></td>
                                <td style="height:15px;"></td>
                                <td style="height:15px;"></td>
                                <td style="height:15px;"></td>
                                <td style="height:15px;"></td>
                            </tr>
                            @endfor
                        </tbody>
                        @else
                            @if (count($catu_daya_mcbs) > 0)
                            <tbody>
                                @foreach($catu_daya_mcbs as $item)
                                <tr>
                                    <td style="height:15px;">{{ $item->no }}</td>
                                    <td style="height:15px;">{{ $item->peruntukan }}</td>
                                    <td style="height:15px;">{{ $item->mcb_amp }}</td>
                                    <td style="height:15px;">{{ $item->jumlah_phase }}</td>
                                    <td style="height:15px;">{{ $item->voltage }}</td>
                                    <td style="height:15px;">{{ $item->catatan }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            @endif
                        @endif
                    </table>
                </div>
                @endif

                @if($setting->catu_daya_genset)
                <div class="margin-content margin-body mb-small">
                    <div class="title-lampiran">
                        <h5>CATU DAYA(GENSET)</h5>
                    </div>
                    <table class="table-lampiran cellpadding="0" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Merk / Type Genset</th>
                                <th>Kapasitas (KVA)</th>
                                <th>Utilisasi Beban</th>
                                <th>Pemilik Genset</th>
                                <th>Koneksi Ke Telkomsel</th>
                            </tr>
                        </thead>
                        @if($catu_daya_gensets == null)
                        <tbody>
                            @for($a=1; $a<=10; $a++)
                            <tr>
                                <td style="height:15px;"></td>
                                <td style="height:15px;"></td>
                                <td style="height:15px;"></td>
                                <td style="height:15px;"></td>
                                <td style="height:15px;"></td>
                                <td style="height:15px;"></td>
                            </tr>
                            @endfor
                        </tbody>
                        @else
                            @if (count($catu_daya_gensets) > 0)
                            <tbody>
                                @foreach($catu_daya_gensets as $item)
                                <tr>
                                    <td style="height:15px;">{{ $item->no }}</td>
                                    <td style="height:15px;">{{ $item->merk_type_genset }}</td>
                                    <td style="height:15px;">{{ $item->kapasitas_kva }}</td>
                                    <td style="height:15px;">{{ $item->utilisasi_beban }}</td>
                                    <td style="height:15px;">{{ $item->pemilik_genset }}</td>
                                    <td style="height:15px;">{{ $item->koneksi_ke_telkomsel }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            @endif
                        @endif
                    </table>
                </div>
                @endif

                @if($setting->akses)
                <div class="margin-content margin-body mb-small">
                    <div class="title-lampiran">
                        <h5>AKSES</h5>
                    </div>
                    <table class="table-lampiran cellpadding="0" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Peruntukan Akses</th>
                                <th>Panjang</th>
                                <th>Arah Akses</th>
                            </tr>
                        </thead>
                        @if($akseses == null)
                        <tbody>
                            @for($a=1; $a<=10; $a++)
                            <tr>
                                <td style="height:15px;"></td>
                                <td style="height:15px;"></td>
                                <td style="height:15px;"></td>
                                <td style="height:15px;"></td>
                            </tr>
                            @endfor
                        </tbody>
                        @else
                            @if (count($akseses) > 0)
                            <tbody>
                                @foreach($akseses as $item)
                                <tr>
                                    <td style="height:15px;">{{ $item->no }}</td>
                                    <td style="height:15px;">{{ $item->peruntukan_akses }}</td>
                                    <td style="height:15px;">{{ $item->panjang_meter }}</td>
                                    <td style="height:15px;">{{ $item->arah_akses }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            @endif
                        @endif
                    </table>
                </div>
                @endif

                @if($setting->service)
                <div class="margin-content margin-body mb-small">
                    <div class="title-lampiran">
                        <h5>SERVICE</h5>
                    </div>
                    <table class="table-lampiran cellpadding="0" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Service</th>
                                <th>PortPE</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        @if($services == null)
                        <tbody>
                            @for($a=1; $a<=10; $a++)
                            <tr>
                                <td style="height:15px;"></td>
                                <td style="height:15px;"></td>
                                <td style="height:15px;"></td>
                                <td style="height:15px;"></td>
                            </tr>
                            @endfor
                        </tbody>
                        @else
                            @if (count($services) > 0)
                            <tbody>
                                @foreach($services as $item)
                                <tr>
                                    <td style="height:15px;">{{ $item->no }}</td>
                                    <td style="height:15px;">{{ $item->nama_service }}</td>
                                    <td style="height:15px;">{{ $item->port_pe }}</td>
                                    <td style="height:15px;">{{ $item->keterangan }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            @endif
                        @endif
                    </table>
                </div>
                @endif

                <div class="page_break_before">
                    @if($setting->catatan)
                    <div class="margin-content margin-body mb-small mt-xl">
                        <table class="table-lampiran cellpadding="0" cellspacing="0">
                            <tbody>
                                <tr>
                                    <td style="height:150px;vertical-align:top; font-size:17px; lineh-height: 17px; padding-top:10px;">Catatan / Keterangan Tambahan :
                                    @if($catatan)
                                        {{ $catatan }}
                                    @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    @endif
                    <div class="margin-body mt-lg">
                        <p>
                            Dengan ini kami menyatakan data tersebut di atas adalah <strong>Valid</strong>
                        </p>
                    </div>
                    <div class="margin-content margin-body mb-small mt-xl">
                        <table class="table-lampiran cellpadding="0" cellspacing="0">
                            <tbody>
                                <tr style="border-bottom:none">
                                    <td style="width:50%;vertical-align:top;text-align:center; font-size:17px;font-weight:bold;border-bottom:none; padding-top:10px;">
                                        TELKOM REGIONAL WHOLSALE
                                    </td>
                                    <td style="width:50%;vertical-align:top;text-align:center; font-size:17px;font-weight:bold;border-bottom:none; padding-top:10px;">
                                        @if($setting->group == 'TELKOM')
                                            TELKOMSEL
                                        @else
                                            PT. {{ $klien->nama_perusahaan }}
                                        @endif
                                    </td>
                                </tr>
                                <tr style="border-top:none;border-bottom:none"> 
                                    <td style="text-transform:uppercase;text-align:center;border-top:none;border-bottom:none;">
                                        @if($manager_wholesale !== null && $manager_wholesale->status_dokumen != null)
                                            <img src="{{ public_path().'/ttd/'.  $manager_wholesale->ttd_image }}" style="height:120px;width:50%">
                                        @else
                                            <div style="height:100px;"></div>
                                        @endif
                                    </td>
                                    <td style="border-top:none;border-bottom:none"></td>
                                </tr>
                                <tr style="border-top:none">
                                    <td style="text-transform:uppercase;text-align:center;border-top:none">
                                        <table style="width:100%;position:relative">
                                            <tr>
                                                <td style="text-align:center;border:none;font-size:17px;">
                                                    {{ $manager_wholesale !== null ? $manager_wholesale->nama_lengkap : ''}}
                                                    @if($paraf_wholesale !== null && $paraf_wholesale->status_dokumen !== null)
                                                        <img src="{{ public_path().'/ttd/'.  $paraf_wholesale->ttd_image }}" style="height:40px;position:absolute;right:20px;top:-10px;">
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td style="text-transform:uppercase;text-align:center;border-top:none; font-size:17px">{{ $klien !== null ? $klien->nama : '' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> 
            <div class="page_break_before">
                <div class="header">
                    <h3 class="header-margin font-weight-bold mb-50"><center>LAMPIRAN DOKUMENTASI SURVEY</center></h3>
                    <div style="margin-left: 200px">
                        <table>
                            <tr>
                                <td style="width:100px">No. Telkom</td>
                                <td style="width:10px">:</td>
                                <td style="width:350px; border-bottom:1px solid #000">
                                    {{ $no_dokumen }}
                                </td>
                            </tr>
                            @if($setting->group == 'TELKOM')
                                <tr>
                                    <td style="width:100px">No. Telkomsel</td>
                                    <td style="width:10px">:</td>
                                    <td style="width:350px; border-bottom:1px solid #000">
                                        {{ $no_dokumen_klien }}
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
                <div class="margin-content margin-body" style="margin-bottom:20px;">
                    <table>
                        <tr>
                            @if($setting->sto_site === 'SITE')
                                <td style="width:30%">Nama Site / Site ID Telkomsel</td>
                                <td style="width:70%">: {{ $site_survey ? $site_survey->nama_site : '' }}</td>
                            @elseif ($setting->sto_site === 'STO')
                                <td style="width:30%">Nama STO</td>
                                <td style="width:70%">: {{ $site_survey ? $site_survey->nama_sto : '' }}</td>
                            @elseif ($setting->sto_site === 'NO_ORDER')
                                <td style="width:30%">Nomor Order</td>
                                <td style="width:70%">: {{ $site_survey ? $site_survey->nomor_order : '' }}</td>
                            @endif

                        </tr>
                        <tr>
                            <td style="width:30%">Longitude / Latitude</td>
                            <td style="width:70%">:
                                @if($site_survey)
                                    {{ $site_survey->longitude }},
                                    {{ $site_survey->latitude }}
                                @endif  
                            </td>
                        </tr>
                        <tr>
                            <td style="width:30%">Alamat</td>
                            <td style="width:70%">: {{ $site_survey ? $site_survey->alamat : '' }}</td>
                        </tr>
                        @if($setting->group == 'TELKOM')
                        <tr>
                            <td style="width:30%">Regional Telkomsel</td>
                            <td style="width:70%">: {{ $site_survey ? $site_survey->regional : '' }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
                <div class="margin-content margin-body">
                    <table style="width:100%" cellpadding="0" cellspacing="0">
                        <tbody>
                            @php
                                $rows = ceil(count($gambars) / 3);
                                $start = 0;
                            @endphp
                            @for($a=1; $a<=$rows; $a++)
                            <tr>
                                @php
                                    $end = 3 * $a;
                                @endphp
                                @for($b=$start; $b<$end; $b++)
                                    @if($b < count($gambars))
                                    <td style="text-align:center;padding-bottom:10px;">
                                        <table style="width:100%;">
                                            <tbody>
                                                <tr><td style="text-align:center; border:1px solid #000; padding: 5px;"><img src="{{ public_path().'/sarpen-gambar/'. $gambars[$b]->gambar_url }}" style="width:200px; height:300px; object-fit: cover;"></td></tr>
                                                <tr>
                                                    <td style="text-align:center; border:1px solid #000;"> 
                                                        Gambar {{ $b + 1 }}. {{ $gambars[$b]->keterangan }}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    @endif
                                @endfor
                                @php
                                    $start = $start + 3;
                                @endphp
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
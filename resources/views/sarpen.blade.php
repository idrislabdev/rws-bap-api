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
            .table-lampiran {
                width: 100%;
                border-collapse: collapse;
                /* border: 1px solid black; */
            }
            .table-lampiran td{
                border: thin solid black;
                font-size: 14px;
                line-height: 14px;
                padding: 4px;
                vertical-align: middle;
                font-weight: 400;
            }
            .table-lampiran th{
                border: thin solid black;
                font-size: 14px;
                line-height: 14px;
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
                margin-left: 30px;
                margin-right: 30px;
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
                    <h3 class="header-margin font-weight-bold"><center>BERITA ACARA SEWA SARANA PENUNJANG</center></h3>
                    <div style="margin-left: 200px">
                        <table>
                            <tr>
                                <td style="width:100px">No. Telkom</td>
                                <td style="width:10px">:</td>
                                <td style="width:250px; border-bottom:1px solid #000"></td>
                            </tr>
                            @if($setting->group == 'TELKOM')
                                <tr>
                                    <td style="width:100px">No. Telkomsel</td>
                                    <td style="width:10px">:</td>
                                    <td style="width:250px; border-bottom:1px solid #000"> </td>
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
                            <td style="width:600px">{{ $pejabat->nama_lengkap }}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Jabatan</td>
                            <td>:</td>
                            <td style="width:600px">{{ $pejabat->jabatan }}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Lokasi Kerja</td>
                            <td>:</td>
                            <td style="width:600px">{{ $pejabat->lokasi_kerja }}</td>
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
                            <td style="width:10px">:</td>
                            <td style="width:600px"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Jabatan</td>
                            <td>:</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Lokasi Kerja</td>
                            <td>:</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td colspan="3">Selanjutnya disebut </td>
                        </tr>
                    </table>
                </div>
                <div class="margin-body">
                    <p class="mt-3">Menyatakan bahwa telah dilaksanakan Site Survey di lokasi:</p>
                </div>
                <div class="margin-content margin-body">
                    <table>
                        <tr>
                            @if($setting->sto_site === 'SITE')
                                <td style="width:200px">Nama Site / ID Telkomsel</td>
                                <td style="width:10px">:</td>
                                <td style="width:600px"></td>
                            @elseif ($setting->sto_site === 'STO')
                                <td style="width:200px">Nama STO</td>
                                <td style="width:10px">:</td>
                                <td style="width:600px"></td>
                            @elseif ($setting->sto_site === 'NO_ORDER')
                                <td style="width:200px">Nomor Order</td>
                                <td style="width:10px">:</td>
                                <td style="width:600px"></td>
                            @endif
                        </tr>
                        <tr>
                            <td>Longitude / Latitude</td>
                            <td>:</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td>:</td>
                            <td></td>
                        </tr>
                    </table>
                </div>
                <div class="margin-content margin-body">
                    <table>
                        <tr>
                            <td style="width:200px">Regional Telkomsel</td>
                            <td style="width:10px">:</td>
                            <td style="width:600px"></td>
                        </tr>
                        <tr>
                            <td style="width:200px">Dengan data terlampir.</td>
                            <td style="width:10px"></td>
                            <td style="width:600px"></td>
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
                            <td style="width:40%;">PT. Telekomunikasi Selular</td>
                            <td style="width:20%;"></td>
                            <td style="width:40%;">TELKOM</td>
                        </tr>
                        <tr class="text-center">
                            <td style="width:40%;"></td>
                            <td style="width:20%;"></td>
                            <td style="width:40%;">
                                <img src="{{ public_path().'/ttd/'.  $pejabat->ttd_image }}" style="height:100px;">
                            </td>
                        </tr>
                        <tr class="text-center">
                            <td style="font-weight:bold; text-decoration: underline"></td>
                            <td style="width:20%;"></td>
                            <td style="text-transform:uppercase; text-decoration: underline">
                                {{ $pejabat->nama_lengkap }}
                            </td>
                        </tr>
                    </table>            
                </div>
            </div>
            <div class="page_break_before">
                <div class="header">
                    <h3 class="header-margin font-weight-bold"><center>LAMPIRAN BERITA ACARA SURVEY</center></h3>
                    <div style="margin-left: 200px">
                        <table>
                            <tr>
                                <td style="width:100px">No. Telkom</td>
                                <td style="width:10px">:</td>
                                <td style="width:250px; border-bottom:1px solid #000"> </td>
                            </tr>
                            @if($setting->group == 'TELKOM')
                                <tr>
                                    <td style="width:100px">No. Telkomsel</td>
                                    <td style="width:10px">:</td>
                                    <td style="width:250px; border-bottom:1px solid #000"> </td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
                <div class="margin-content margin-body">
                    <table>
                        <tr>
                            @if($setting->sto_site === 'SITE')
                                <td style="width:200px">Nama Site / Site ID Telkomsel</td>
                                <td style="width:10px">:</td>
                                <td style="width:600px"></td>
                            @elseif ($setting->sto_site === 'STO')
                                <td style="width:200px">Nama STO</td>
                                <td style="width:10px">:</td>
                                <td style="width:600px"></td>
                            @elseif ($setting->sto_site === 'NO_ORDER')
                                <td style="width:200px">Nomor Order</td>
                                <td style="width:10px">:</td>
                                <td style="width:600px"></td>
                            @endif

                        </tr>
                        <tr>
                            <td>Longitude / Latitude</td>
                            <td>:</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td>:</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Regional Telkomsel</td>
                            <td>:</td>
                            <td></td>
                        </tr>
                    </table>
                </div>
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
                    </table>
                </div>
                @endif

                @if($setting->catatan)
                <div class="margin-content margin-body mb-small mt-xl">
                    <table class="table-lampiran cellpadding="0" cellspacing="0">
                        <tbody>
                            <tr>
                                <td style="height:150px;vertical-align:top">Catatan / Keterangan Tambahan :</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                @endif
                <div class="margin-content margin-body mb-small mt-xl">
                    <table class="table-lampiran cellpadding="0" cellspacing="0">
                        <tbody>
                            <tr>
                                <td style="width:50%;height:150px;vertical-align:top;text-align:center; font-size:17px;font-weight:bold">TELKOM</td>
                                <td style="width:50%;height:150px;vertical-align:top;text-align:center; font-size:17px;font-weight:bold">PT</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div> 
        </main>
    </body>
</html>
</span>
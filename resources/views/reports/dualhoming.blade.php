<html>
<link href="/css/table-report.css" rel="stylesheet">

<table>
    <thead>
        <tr>
            <th colspan="13" style="font-size: 14px; text-align: center">
                @if($site_witel == 'ALL')
                    @if ($status == "")
                        List Site Dual Homing Tahun Order {{$tahun_order}} Semua Witel
                    @else
                        @if ($ba)
                            List Site Dual Homing Tahun Order {{$tahun_order}} Semua Witel Status {{$status}} {{$status == "OA"  ? ($ba == 1 || $progress == 'Completed' ? "(Evidence Completed)" : "(Evidence Not  Completed)") : "(Evidence Not Completed)"}} {{$status == "OA" && $sirkulir == 1 ? "(Full Signed)" : ""}}
                        @else
                            @if ($progress != "")
                                List Site Dual Homing Tahun Order {{$tahun_order}} Semua Witel Status {{$status}} (Evidence {{$progress}})
                            @else
                                List Site Dual Homing Tahun Order {{$tahun_order}} Semua Witel Status {{$status}}
                            @endif
                        @endif
                    @endif
                @else
                    @if ($status == "")
                        List Site Dual Homing Tahun Order {{$tahun_order}} Witel {{$site_witel}}
                    @else
                        @if ($ba) 
                            List Site Dual Homing Tahun Order {{$tahun_order}} Witel {{$site_witel}} {{$status != ""  ? "Status $status" : ""}} {{$status == "OA"  ? ($ba == 1 || $progress == 'Completed' ? "(Evidence Completed)" : "(Evidence Not  Completed)") : "(Evidence Not Completed)"}} {{$status == "OA" && $sirkulir == 1 ? "(Full Signed)" : ""}}
                        @else
                            @if ($progress != "")
                                List Site Dual Homing Tahun Order {{$tahun_order}} Witel {{$site_witel}}  Status {{$status}} (Evidence {{$progress}})
                            @else
                                List Site Dual Homing Tahun Order {{$tahun_order}} Witel {{$site_witel}} Status {{$status}}
                            @endif
                        @endif
                    @endif
                @endif
            </th>
        </tr>
        <tr>
            <th>Dasar Order</th>
            <th>Site ID</th>
            <th>Site Name</th>
            <th>Site Witel</th>
            <th>Wilayah</th>
            <th>Program</th>
            <th>Order BW</th>
            <th>Status</th>
            <th>Progress</th>
            <th>Alpro Site</th>
            <th>Tgl OA</th>
            <th>Presentase</th>
            <th>Nomor BA</th>
        </tr>
    </thead>
    <tbody>
    @foreach($data as $data)
        <tr>
            <td>{{ $data->dasar_order }}</td>
            <td>{{ $data->site_id }}</td>
            <td>{{ $data->site_name }}</td>
            <td>{{ $data->site_witel }}</td>
            <td>{{ $data->tsel_reg }}</td>
            <td>{{ $data->program }}</td>
            <td>{{ $data->jumlah }}</td>
            <td>{{ $data->status }}</td>
            <td>{{ $data->progress == true ? 'Complete' : 'Not Complete'}}</td>
            <td>{{ $data->alpro_site }}</td>
            <td>{{strtoupper(date('d-M-y', strtotime($data->tgl_on_air)))}}</td>
            <td>
                {{ 
                    ((($data->konfigurasi > 0 ? 1 : 0) + 
                    ($data->topologi > 0 ? 1 : 0) + 
                    ($data->capture_trafik > 0 ? 1 : 0) + 
                    ($data->lampiran_url != null ? 1 : 0)) / 4) * 100
                    .'%'
                     
                }}
            </td>     
            <td>{{ $data->no_dokumen }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</html>
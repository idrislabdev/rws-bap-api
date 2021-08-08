<html>
<link href="/css/table-report.css" rel="stylesheet">

<table>
    <thead>
    <tr>
        <th colspan="8" style="font-size: 14px; text-align: center">
            List Site New Link
        </th>
    </tr>
    <tr>
        <th style="border: 1px solid #000000; width: 30px; background-color: aqua; font-size: 13px;">Dasar Order</th>
        <th style="border: 1px solid #000000; width: 10px; background-color: aqua; font-size: 13px;">Site ID</th>
        <th style="border: 1px solid #000000; width: 40px; background-color: aqua; font-size: 13px;">Site Name</th>
        <th style="border: 1px solid #000000; width: 20px; background-color: aqua; font-size: 13px;">Site Witel</th>
        <th style="border: 1px solid #000000; width: 15px; background-color: aqua; font-size: 13px;">Wilayah</th>
        <th style="border: 1px solid #000000; width: 15px; background-color: aqua; font-size: 13px;">Program</th>
        <th style="border: 1px solid #000000; width: 10px; background-color: aqua; font-size: 13px;">Status</th>
        <th style="border: 1px solid #000000; width: 15px; background-color: aqua; font-size: 13px;">Progress</th>
        <th style="border: 1px solid #000000; width: 10px; background-color: aqua; font-size: 13px;">Presentase</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $data)
        <tr>
            <td style="border: 1px solid #000000">{{ $data->dasar_order }}</td>
            <td style="border: 1px solid #000000">{{ $data->site_id }}</td>
            <td style="border: 1px solid #000000">{{ $data->site_name }}</td>
            <td style="border: 1px solid #000000">{{ $data->site_witel }}</td>
            <td style="border: 1px solid #000000">{{ $data->tsel_reg }}</td>
            <td style="border: 1px solid #000000">{{ $data->program }}</td>
            <td style="border: 1px solid #000000">{{ $data->status }}</td>
            <td style="border: 1px solid #000000">{{ $data->progress == true ? 'Complete' : 'Not Complete'}}</td>
            <td style="border: 1px solid #000000">
                {{ 
                    ((($data->konfigurasi > 0 ? 1 : 0) + 
                    ($data->topologi > 0 ? 1 : 0) + 
                    ($data->capture_trafik > 0 ? 1 : 0) + 
                    ($data->lv > 0 ? 1 : 0) + 
                    ($data->lv_image > 0 ? 1 : 0) + 
                    ($data->qc > 0 ? 1 : 0) + 
                    ($data->qc_image > 0 ? 1 : 0) +
                    ($data->lampiran_url != null ? 1 : 0)) / 8) * 100
                    .'%'
                     
                }}
            </td>     
        </tr>
    @endforeach
    </tbody>
</table>
</html>
@php 
    use App\Http\Controllers\OLO\Transaksi\BeritaAcaraController; 
@endphp
<html>
<link href="/css/table-report.css" rel="stylesheet">

<table>
    <thead>
    <tr>
        <th colspan="8" style="font-size: 14px; text-align: center">
            Daftar Order OLO
        </th>
    </tr>
    <tr>
        <th style="border: 1px solid #000000; width: 5px; background-color: aqua; font-size: 13px;">No</th>
        <th style="border: 1px solid #000000; width: 25px; background-color: aqua; font-size: 13px;">AO SC Order</th>
        <th style="border: 1px solid #000000; width: 25px; background-color: aqua; font-size: 13px;">SID</th>
        <th style="border: 1px solid #000000; width: 25px; background-color: aqua; font-size: 13px;">Produk</th>
        <th style="border: 1px solid #000000; width: 25px; background-color: aqua; font-size: 13px;">Bandwidth (Mbps)</th>
        <th style="border: 1px solid #000000; width: 40px; background-color: aqua; font-size: 13px;">Add On</th>
        <th style="border: 1px solid #000000; width: 15px; background-color: aqua; font-size: 13px;">Jenis Order</th>
        <th style="border: 1px solid #000000; width: 15px; background-color: aqua; font-size: 13px;">Alamat Instalasi</th>
        <th style="border: 1px solid #000000; width: 15px; background-color: aqua; font-size: 13px;">Tanggal Aktivasi</th>
    </tr>
    </thead>
    <tbody>
    
    @for($a=0; $a<@count($data) ; $a++)
        <tr>
            <td style="border: 1px solid #000000">{{ $a+1 }}</td>
            <td style="border: 1px solid #000000">{{ $data[$a]->ao_sc_order }}</td>
            <td style="border: 1px solid #000000">{{ $data[$a]->sid }}</td>
            <td style="border: 1px solid #000000">{{ $data[$a]->produk }}</td>
            <td style="border: 1px solid #000000">{{ $data[$a]->bandwidth_mbps }}</td>
            <td style="border: 1px solid #000000">
                {{BeritaAcaraController::formatAddOn($data[$a]->id, $data[$a]->olo_ba_id)}}    
            </td>
            <td style="border: 1px solid #000000">{{ $data[$a]->jenis_order }}</td>
            <td style="border: 1px solid #000000">{{ $data[$a]->alamat_instalasi }}</td>
            <td style="border: 1px solid #000000">{{strtoupper(date('d-M-y', strtotime($data[$a]->tgl_aktivasi)))}}</td>
        </tr>
    @endfor
    </tbody>
</table>
</html>
@php 
    use App\Helpers\UtilityHelper; 
@endphp
<html>
    <table>
        <tr>
            <td colspan="10">Laporan B.A Sarpen Witel {{ $site_witel }} Tahun {{ $year }}</td>
        </tr>
        <tr>
           <td colspan="10"></td>
        </tr>
        <tr>
            <td>No</td>
            <td>Group</td>
            <td>Nama STO / Site / No Order</td>
            <td>Latitude</td>
            <td>Longitude</td>
            <td>Alamat</td>
            <td>Regional Telkomsel</td>
            <td>Witel Telkom</td>
            <td>Nomor Telkom</td>
            <td>Posisi</td>
        </tr>
        @php
            $no = 0;
        @endphp    
        @foreach ($data as $data )
            @php
                $no++;
            @endphp    
        <tr>
            <td>{{ $no }}</td>
            <td>{{ $data->group }}</td>
            @if ($data->group == 'TELKOM')
                @if($data->type == 'SITE')
                    <td>SITE: {{ $data->nama_site }}</td>
                @else
                    <td>STO: {{ $data->nama_sto }}</td>
                @endif
            @else
                <td>STO: {{ $data->nomor_order }}</td>
            @endif
            <td>{{ $data->latitude }}</td>
            <td>{{ $data->longitude }}</td>
            <td>{{ $data->alamat }}</td>
            <td>{{ $data->regional }}</td>
            <td>{{ $data->site_witel }}</td>
            <td>{{ $data->no_dokumen }}</td>
            @if($data->status == 'proposed')
                <td>Proses Manager Witel</td>
            @elseif($data->status == 'ttd_witel')
                <td>Proses Officer Wholesale</td>
            @elseif($data->status == 'paraf_wholesale')
                <td>Proses Manager Wholesale</td>
            @elseif($data->status == 'ttd_wholesale')
                <td>TTD. Lengkap</td>
            @elseif($data->status == 'finished')
                <td>Selesai / Sirkulir</td>
            @endif
        </tr>
        @endforeach
    </table>
</html>

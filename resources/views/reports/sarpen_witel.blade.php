@php 
    use App\Helpers\UtilityHelper; 
@endphp
<html>
    <table>
        <tr>
            <td colspan="6">Laporan B.A Sarpen Per Witel Tahun {{ $year }}</td>
        </tr>
        <tr>
           <td colspan="6"></td>
        </tr>
        <tr>
            <td>Witel</td>
            <td>Need Approval Manager Witel</td>
            <td>Need Approval Officer Wholesale</td>
            <td>Need Approval Manager Wholesale</td>
            <td>Need T.SEL Sign</td>
            <td>Completed</td>
        </tr>
        @php
            $proposed = 0;
            $ttd_witel = 0;
            $paraf_wholesale = 0;
            $ttd_wholesale = 0;
            $finished = 0;
        @endphp    
        @foreach ($data as $data )
        @php
            $proposed           = $proposed +  $data->proposed;
            $ttd_witel          = $ttd_witel +  $data->ttd_witel;
            $paraf_wholesale    = $paraf_wholesale +  $data->paraf_wholesale;
            $ttd_wholesale      = $ttd_wholesale +  $data->ttd_wholesale;
            $finished           = $finished +  $data->finished;
        @endphp    
        <tr>
            <td>{{ $data->witel }}</td>
            <td>{{ $data->proposed }}</td>
            <td>{{ $data->ttd_witel }}</td>
            <td>{{ $data->paraf_wholesale }}</td>
            <td>{{ $data->ttd_wholesale }}</td>
            <td>{{ $data->finished }}</td>
        </tr>
        @endforeach
        <tr>
            <td>Total</td>
            <td>{{ $proposed }}</td>
            <td>{{ $ttd_witel }}</td>
            <td>{{ $paraf_wholesale }}</td>
            <td>{{ $ttd_wholesale }}</td>
            <td>{{ $finished }}</td>
        </tr>
    </table>
</html>

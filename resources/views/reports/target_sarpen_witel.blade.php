@php 
    use App\Helpers\UtilityHelper; 
@endphp
<html>
    <table>
        <tr>
            <td colspan="4">Realisasi Target Sarpen Per Witel</td>
        </tr>
        <tr>
           <td colspan="4"></td>
        </tr>
        <tr>
            <td>Witel</td>
            <td>Target</td>
            <td>Realisasi</td>
            <td>Presentase</td>
        </tr>
        @php
            $target = 0;
            $realisasi = 0;
        @endphp    
        @foreach ($data as $data )
        @php
            $target     = $target +  $data->target;
            $realisasi   = $realisasi +  $data->realisasi;
        @endphp    
        <tr>
            <td>{{ $data->witel }}</td>
            <td>{{ $data->target }}</td>
            <td>{{ $data->realisasi }}</td>
            @if ( $data->realisasi != 0 && $data->target != 0)
                <td>{{ number_format($data->realisasi / $data->target * 100, 1)}}%</td>
            @else
                <td>0%</td>
            @endif
        </tr>
        @endforeach
        <tr>
            <td>Total</td>
            <td>{{ $target }}</td>
            <td>{{ $realisasi }}</td>
            <td>{{ number_format($realisasi / $target * 100, 1) }}%</td>
        </tr>
    </table>
</html>

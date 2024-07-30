@php 
    use App\Helpers\UtilityHelper; 
@endphp
<html>
    <table>
        <tr>
            <td colspan="12">{{ $title }}</td>
        </tr>
        <tr>
           <td colspan="12"></td>
        </tr>
        <tr>
            <td>NO</td>
            <td>NIK</td>
            <td>Nama</td>
            <td>Tgl Lahir</td>
            <td>Alamat Email</td>
            <td>No. HP</td>
            <td>Posisi/Jabatan</td>
            <td>Witel</td>
            <td>Nama Atasan</td>
            <td>NIK Atasan</td>
            <td>Jabatan Atasan</td>
            <td>Telegram ID</td>
            <td>Telegram Uer</td>
            <td>Profile</td>
        </tr>
        @php
            $counter = 0;
        @endphp
        @foreach ($data as $data )
            @php
                $profile = join(" + ", json_decode($data->profiles[0]->profiles)) ;
                $counter++;
            @endphp
            <tr>
                <td>{{ $counter }}</td>
                <td>{{ $data->nik }}</td>
                <td>{{ $data->nama }}</td>
                <td>{{ $data->tanggal_lahir }}</td>
                <td>{{ $data->email }}</td>
                <td>{{ $data->no_handphone }}</td>
                <td>{{ $data->jabatan }}</td>
                <td>{{ $data->site_witel }}}</td>
                <td>{{ $data->nama_atasan }}</td>
                <td>{{ $data->nik_atasan }}</td>
                <td>{{ $data->jabatan_atasan }}</td>
                <td>{{ $data->telegram_id }}</td>
                <td>{{ $data->telegram_user }}</td>
                <td>{{ $profile }}</td>
            </tr>
        @endforeach
    </table>
</html>

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
            <td>Witel/Unit</td>
            <td>Nama Atasan</td>
            <td>NIK Atasan</td>
            <td>Jabatan Atasan</td>
            <td>Telegram ID</td>
            <td>Keterangan</td>
        </tr>
        @php
            $counter = 0;
        @endphp
        @foreach ($data as $data )
            @php
                $jenis_pengajuan;
                $user_account_pengajuan = json_decode($data->user_account_pengajuan);
                $account_profile = $data->accountProfile;
                $counter++;
            @endphp
            <tr>
                <td>{{ $counter }}</td>
                <td>{{ $user_account_pengajuan->nik }}</td>
                <td>{{ $user_account_pengajuan->nama }}</td>
                <td>{{ $user_account_pengajuan->tanggal_lahir }}</td>
                <td>{{ $user_account_pengajuan->email }}</td>
                <td>{{ $user_account_pengajuan->no_handphone }}</td>
                <td>{{ $user_account_pengajuan->jabatan }}</td>
                <td>{{ $user_account_pengajuan->site_witel }}/{{ $user_account_pengajuan->unit }}</td>
                <td>{{ $user_account_pengajuan->nama_atasan }}</td>
                <td>{{ $user_account_pengajuan->nik_atasan }}</td>
                <td>{{ $user_account_pengajuan->jabatan_atasan }}</td>
                <td>{{ $user_account_pengajuan->telegram_id }}</td>
                <td>{{ $data->keterangan }}</td>
            </tr>
        @endforeach
        @for ($a=0; $a<6; $a++)
            <tr>
                @for ($b=0; $b<12; $b++)
                    <td></td>
                @endfor
            </tr>
        @endfor
    </table>
</html>

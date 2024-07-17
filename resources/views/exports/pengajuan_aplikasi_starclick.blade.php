@php 
    use App\Helpers\UtilityHelper; 
@endphp
<html>
    <table>
        <tr>
            <td colspan="3">{{ $title }}</td>
        </tr>
        <tr>
           <td colspan="18"></td>
        </tr>
        <tr>
            <td>Username</td>
            <td>NIK</td>
            <td>Nama</td>
            <td>Alamat Email</td>
            <td>Nomor Handphone</td>
            <td>Jabatan</td>
            <td>PIC</td>
            <td>APLIKASI</td>
            <td>CHANNEL</td>
            <td>PROFILE</td>
            <td>STATUS</td>
            <td>TREG</td>
            <td>WITEL</td>
            <td>DATEL</td>
            <td>PLAZA</td>
            <td>DIVISI</td>
            <td>ID TELEGRAM</td>
            <td>USERNAME TELEGRAM</td>
        </tr>
        <tr>
            @for ($a=0; $a<17; $a++)
                <td></td>
            @endfor
        </tr>
        @foreach ($data as $data )
            @php
                $jenis_pengajuan;
                $user_account_pengajuan = json_decode($data->user_account_pengajuan);
                $account_profile = $data->accountProfile;
            @endphp
            @if ($data->jenis_pengajuan = 'baru')
                @php
                    $jenis_pengajuan = 'USER BARU';
                @endphp
            @else
                @php
                    $jenis_pengajuan = 'USER BARU';
                @endphp
            @endif
            <tr>
                <td>{{ $account_profile->username }}</td>
                <td>{{ $user_account_pengajuan->nik }}</td>
                <td>{{ $user_account_pengajuan->nama }}</td>
                <td>{{ $user_account_pengajuan->email }}</td>
                <td>{{ $user_account_pengajuan->no_handphone }}</td>
                <td>{{ $user_account_pengajuan->jabatan }}</td>
                <td>{{ $user_account_pengajuan->nik_atasan }}</td>
                <td>STARCLICK</td>
                <td>{{ $user_account_pengajuan->channel }}</td>
                <td>{{ join(" + ", json_decode($data->profiles)) }}</td>
                <td>{{ $jenis_pengajuan}} </td>
                <td>5</td>
                <td>{{ $user_account_pengajuan->site_witel }}</td>
                <td>{{ $user_account_pengajuan->datel }}</td>
                <td>{{ $user_account_pengajuan->plaza }}</td>
                <td>{{ $user_account_pengajuan->divisi }}</td>
                <td>{{ $user_account_pengajuan->telegram_id }}</td>
                <td>{{ $user_account_pengajuan->telegram_user }}</td>
            </tr>
        @endforeach
        @for ($a=0; $a<6; $a++)
            <tr>
                @for ($b=0; $b<17; $b++)
                    <td></td>
                @endfor
            </tr>
        @endfor

        <tr>
            <td>NOTE:</td>
        </tr>
        <tr>
            <td colspan="7" rowspan="6">Untuk ID Telegram bisa didapatkan melalui @cekTeleID_bot</td>
        </tr>
    </table>
</html>

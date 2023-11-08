@php 
    use App\Helpers\UtilityHelper; 
@endphp
<html>
    <table>
        <tr>
            <td colspan="10">Laporan B.A Sarpen Tahun {{ $year }}</td>
        </tr>
        <tr>
           <td colspan="10"></td>
        </tr>
        <tr>
            <th rowspan="3">No</th>
            <th rowspan="3">Pelanggan</th>
            <th rowspan="3">Nama STO / Site / No Order</th>
            <th rowspan="3">Latitude</th>
            <th rowspan="3">Longitude</th>
            <th rowspan="3">Alamat</th>
            <th rowspan="3">Regional Telkomsel</th>
            <th rowspan="3">Witel Telkom</th>
            <th rowspan="3">Nomor Telkom</th>
            <th rowspan="3">Revenue</th>
            <th rowspan="3">Posisi</th>
            <th colspan="42">Data Tower</th>
            <th colspan="28">Data Rack</th>
            <th colspan="49">Data Ruangan</th>
            <th colspan="28">Data Lahan</th>
            <th colspan="35">Data Catu Daya (MCB)</th>
            <th colspan="35">Data Catu Daya (Genset)</th>
            <th colspan="21">Data Service</th>
            <th colspan="21">Data Akses</th>
        </tr>
        <tr>
            @for ($a=0; $a<=6 ; $a++)
                <th colspan="6">Tower {{ $a + 1 }}</th>
            @endfor
            @for ($a=0; $a<=6 ; $a++)
                <th colspan="4">Rack {{ $a + 1 }}</th>
            @endfor
            @for ($a=0; $a<=6 ; $a++)
                <th colspan="7">Ruangan {{ $a + 1 }}</th>
            @endfor
            @for ($a=0; $a<=6 ; $a++)
                <th colspan="4">Lahan {{ $a + 1 }}</th>
            @endfor
            @for ($a=0; $a<=6 ; $a++)
                <th colspan="5">Catu Daya (MCB) {{ $a + 1 }}</th>
            @endfor
            @for ($a=0; $a<=6 ; $a++)
                <th colspan="5">Catu Daya (Genset) {{ $a + 1 }}</th>
            @endfor
            @for ($a=0; $a<=6 ; $a++)
                <th colspan="3">Service {{ $a + 1 }}</th>
            @endfor
            @for ($a=0; $a<=6 ; $a++)
                <th colspan="3">Akses {{ $a + 1 }}</th>
            @endfor
        </tr>
        <tr>
            @for ($a=0; $a<=6 ; $a++)
                <th>Tipe / Jenis Antena</th>
                <th>Status Antena</th>
                <th>Ketinggian (Meter)</th>
                <th>Diamter (Meter)</th>
                <th>Jumlah Antena</th>
                <th>Tower Leg Mounting Position	</th>
            @endfor
            @for ($a=0; $a<=6 ; $a++)
                <th>Nomor Rack</th>
                <th>Type Rack</th>
                <th>Jumlah Perangkat</th>
                <th>Tipe Perangkat</th>
            @endfor
            @for ($a=0; $a<=6 ; $a++)
                <th>Nama Ruangan</th>
                <th>Peruntukan Ruangan</th>
                <th>Bersama / Tersendiri</th>
                <th>Terkondisi / Tidak</th>
                <th>Status Kepemilikan AC</th>
                <th>Panjang (Meter)</th>
                <th>Lebar (Meter)</th>
            @endfor
            @for ($a=0; $a<=6 ; $a++)
                <th>Nama Lahan</th>
                <th>Peruntukan Lahan</th>
                <th>Panjang (Meter)</th>
                <th>Lebar (Meter)</th>
            @endfor
            @for ($a=0; $a<=6 ; $a++)
                <th>Peruntukan</th>
                <th>MCB (Amp)</th>
                <th>Jumlah Phase</th>
                <th>Voltage</th>
                <th>Catatan</th>
            @endfor
            @for ($a=0; $a<=6 ; $a++)
                <th>Merk &amp; Type Genset</th>
                <th>Kapasitas KVA</th>
                <th>Utilisasi Beban</th>
                <th>Pemilik Genset</th>
                <th>Koneksi Ke Telkomsel</th>
            @endfor
            @for ($a=0; $a<=6 ; $a++)
                <th>Nama Service</th>
                <th>Port PE</th>
                <th>Keterangan</th>
            @endfor
            @for ($a=0; $a<=6 ; $a++)
                <th>Peruntukan Akses</th>
                <th>Panjang (Meter)</th>
                <th>Arah Akses</th>
            @endfor
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
            <td>{{ $data->klienObj->nama_perusahaan }}</td>
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
            <td data-format="0,0">{{ $data->revenue_per_bulan }}</td>
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
            @for ($a=0; $a<=6 ; $a++)
                <td>{{ !empty($data->towers[$a]) ? $data->towers[$a]->type_jenis_antena : '' }}</td>
                <td>{{ !empty($data->towers[$a]) ? $data->towers[$a]->status_antena : '' }}</td>
                <td>{{ !empty($data->towers[$a]) ? $data->towers[$a]->ketinggian_meter : '' }}</td>
                <td>{{ !empty($data->towers[$a]) ? $data->towers[$a]->diameter_meter : '' }}</td>
                <td>{{ !empty($data->towers[$a]) ? $data->towers[$a]->jumlah_antena : '' }}</td>
                <td>{{ !empty($data->towers[$a]) ? $data->towers[$a]->tower_leg_mounting_position : '' }}</td>
            @endfor
            @for ($a=0; $a<=6 ; $a++)
                <td>{{ !empty($data->racks[$a]) ? $data->racks[$a]->nomor_rack : '' }}</td>
                <td>{{ !empty($data->racks[$a]) ? $data->racks[$a]->type_rack : '' }}</td>
                <td>{{ !empty($data->racks[$a]) ? $data->racks[$a]->jumlah_perangkat : '' }}</td>
                <td>{{ !empty($data->racks[$a]) ? $data->racks[$a]->type_perangkat : '' }}</td>
            @endfor
            @for ($a=0; $a<=6 ; $a++)
                <td>{{ !empty($data->ruangans[$a]) ? $data->ruangans[$a]->nama_ruangan : '' }}</td>
                <td>{{ !empty($data->ruangans[$a]) ? $data->ruangans[$a]->peruntukan_ruangan : '' }}</td>
                <td>{{ !empty($data->ruangans[$a]) ? $data->ruangans[$a]->bersama_tersendiri : '' }}</td>
                <td>{{ !empty($data->ruangans[$a]) ? $data->ruangans[$a]->terkondisi : '' }}</td>
                <td>{{ !empty($data->ruangans[$a]) ? $data->ruangans[$a]->status_kepemilikan_ac : '' }}</td>
                <td>{{ !empty($data->ruangans[$a]) ? $data->ruangans[$a]->panjang_meter : '' }}</td>
                <td>{{ !empty($data->ruangans[$a]) ? $data->ruangans[$a]->lebar_meter : '' }}</td>
            @endfor
            @for ($a=0; $a<=6 ; $a++)
                <td>{{ !empty($data->lahans[$a]) ? $data->lahans[$a]->nama_lahan : '' }}</td>
                <td>{{ !empty($data->lahans[$a]) ? $data->lahans[$a]->peruntukan_lahan : '' }}</td>
                <td>{{ !empty($data->lahans[$a]) ? $data->lahans[$a]->panjang_meter : '' }}</td>
                <td>{{ !empty($data->lahans[$a]) ? $data->lahans[$a]->lebar_meter : '' }}</td>
            @endfor
            @for ($a=0; $a<=6 ; $a++)
                <td>{{ !empty($data->catu_daya_mcbs[$a]) ? $data->catu_daya_mcbs[$a]->peruntukan : '' }}</td>
                <td>{{ !empty($data->catu_daya_mcbs[$a]) ? $data->catu_daya_mcbs[$a]->mcb_amp : '' }}</td>
                <td>{{ !empty($data->catu_daya_mcbs[$a]) ? $data->catu_daya_mcbs[$a]->jumlah_phase : '' }}</td>
                <td>{{ !empty($data->catu_daya_mcbs[$a]) ? $data->catu_daya_mcbs[$a]->voltage : '' }}</td>
                <td>{{ !empty($data->catu_daya_mcbs[$a]) ? $data->catu_daya_mcbs[$a]->catatan : '' }}</td>
            @endfor
            @for ($a=0; $a<=6 ; $a++)
                <td>{{ !empty($data->catu_daya_gensets[$a]) ? $data->catu_daya_gensets[$a]->merk_type_genset : '' }}</td>
                <td>{{ !empty($data->catu_daya_gensets[$a]) ? $data->catu_daya_gensets[$a]->kapasitas_kva : '' }}</td>
                <td>{{ !empty($data->catu_daya_gensets[$a]) ? $data->catu_daya_gensets[$a]->utilisasi_beban : '' }}</td>
                <td>{{ !empty($data->catu_daya_gensets[$a]) ? $data->catu_daya_gensets[$a]->pemilik_genset : '' }}</td>
                <td>{{ !empty($data->catu_daya_gensets[$a]) ? $data->catu_daya_gensets[$a]->koneksi_ke_telkomsel : '' }}</td>
            @endfor
            @for ($a=0; $a<=6 ; $a++)
                <td>{{ !empty($data->service[$a]) ? $data->service[$a]->nama_service : '' }}</td>
                <td>{{ !empty($data->service[$a]) ? $data->service[$a]->port_pe : '' }}</td>
                <td>{{ !empty($data->service[$a]) ? $data->service[$a]->keterangan : '' }}</td>
            @endfor
            @for ($a=0; $a<=6 ; $a++)
                <td>{{ !empty($data->service[$a]) ? $data->service[$a]->peruntukan_akses : '' }}</td>
                <td>{{ !empty($data->service[$a]) ? $data->service[$a]->panjang_meter : '' }}</td>
                <td>{{ !empty($data->service[$a]) ? $data->service[$a]->arah_akses : '' }}</td>
            @endfor
        </tr>
        @endforeach
    </table>
</html>

<?php

namespace App\Http\Controllers\AccountCenter\Transaksi;

use App\Http\Controllers\Controller;
use App\Http\Resources\MaUserAccountResource;
use App\Http\Resources\TrPengajuanAplikasiResource;
use App\Models\MaPengguna;
use App\Models\MaUserAccount;
use App\Models\MaUserAccountProfile;
use App\Models\TrPengajuanAplikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class PengajuanAplikasiController extends Controller
{
    private $_hari = ['SENIN', 'SELASA', 'RABU', 'KAMIS', 'JUMAT', 'SABTU', 'MINGGU'];
    private $_month = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

    public function index()
    {
        $data = TrPengajuanAplikasi::with(['proposedBy', 'rejectedBy', 'approvedBy', 'processBy'])
        ->with(['userAccount' => function ($q) {
            if (isset($_GET['q']) && $_GET['q'] !== '') {
                $query = $_GET['q'];
                $q->whereRaw("(nama like '%$query%' or email like '%$query%' or nik like '%$query%')");
            }
        }]);
        if (isset($_GET['page'])) {

            if (isset($_GET['q']) && $_GET['q'] !== '') {
                $q = $_GET['q'];
                $data = $data->whereRaw("(no_dokumen like '%$q%' or 
                    nama_sto like '%$q%' or nama_site like '%$q%' or
                    regional like '%$q%' or nama_klien like '%$q%')");
            }

            if (isset($_GET['jenis_pengajuan'])) {
                $data = $data->where('jenis_pengajuan', $_GET['jenis_pengajuan']);
            }

            if (isset($_GET['status_pengajuan'])) {
                $data = $data->where('status_pengajuan', $_GET['status_pengajuan']);
            }

            if (Auth::user()->role === 'WITEL') {
                $data = $data->where('site_witel', Auth::user()->site_witel);
            } else if (Auth::user()->role === 'RWS' || Auth::user()->role === 'ROOT') {
                if (isset($_GET['site_witel']))
                    $data = $data->where('site_witel', $_GET['site_witel']);
            }

            $per_page = 10;
            if (isset($_GET['per_page']))
                $per_page = $_GET['per_page'];

            $data = $data->orderByDesc('created_at')->paginate($per_page)->onEachSide(5);
        } else {
            if (isset($_GET['group'])) {
                $data = $data->where('group', $_GET['group']);
            }
            $data = $data->get();
        }

        return TrPengajuanAplikasiResource::collection(($data))->additional([
            'success' => true,
            'message' => null,
        ]);
    }

    public function store(Request $request)
    {
        $v = Validator::make($request->all(), [
            'jenis_aplikasi' => 'required',
            'jenis_pengajuan' => 'required|in:BARU,REAKTIVASI,TAMBAH-FITUR,HAPUS',
        ]);

        DB::beginTransaction();
        try {
            $user_account = new MaUserAccount();
            if ($user_account->id != null) {
                $check = MaUserAccount::find($user_account->id);
                if ($check) {
                    MaUserAccount::where('id', $user_account->id)
                        ->update(array(
                            'nama' => $request->nama,
                            'tanggal_lahir' => $request->tanggal_lahir,
                            'nik' => $request->nik,
                            'status_pegawai' => $request->status_pegawai,
                            'email' => $request->email,
                            'jabatan' => $request->jabatan,
                            'unit' => $request->unit,
                            'site_witel' => $request->site_witel,
                            'datel' => $request->datel,
                            'plaza' => $request->plaza,
                            'divisi' => $request->divisi,
                            'telegram_id' => $request->telegram_id,
                            'telegram_user' => $request->telegram_user,
                            'channel' => $request->channel,
                            'nama_atasan' => $request->nama_atasan,
                            'jabatan_atasan' => $request->nik_atasan,
                            'is_deleted' => $request->is_deleted,
                    ));
                }
            } else {
                $url_ktp = $this->prosesUploadKtp($request->file('image_ktp'));
                $url_pakta = $this->prosesUploadPakta($request->file('file_pakta'));

                $user_account_id = Uuid::uuid4()->toString();
                $user_account->id = $user_account_id;
                $user_account->nama = $request->nama;
                $user_account->tanggal_lahir = $request->tanggal_lahir;
                $user_account->nik = $request->nik;
                $user_account->status_pegawai = $request->status_pegawai;
                $user_account->email = $request->email;
                $user_account->jabatan = $request->jabatan;
                $user_account->unit = $request->unit;
                $user_account->site_witel = $request->site_witel;
                $user_account->datel = $request->datel;
                $user_account->plaza = $request->plaza;
                $user_account->divisi = $request->divisi;
                $user_account->telegram_id = $request->telegram_id;
                $user_account->telegram_user = $request->telegram_user;
                $user_account->channel = $request->channel;
                $user_account->nama_atasan = $request->nama_atasan;
                $user_account->nik_atasan = $request->nik_atasan;
                $user_account->jabatan_atasan = $request->jabatan_atasan;
                $user_account->image_ktp_url = $url_ktp;
                $user_account->file_pakta_url = $url_pakta;
            
                $user_account->is_deleted = false;
                $user_account->save();
            }
            $pengajuans = json_decode($request->pengajuans);
            foreach ($pengajuans as $pengajuan) {
                $data_pengajuan = new TrPengajuanAplikasi();
                $data_pengajuan->id = Uuid::uuid4()->toString();
                $data_pengajuan->user_account_id = $user_account_id;
                $data_pengajuan->aplikasi = $pengajuan->aplikasi;
                $data_pengajuan->jenis_pengajuan = $pengajuan->jenis_pengajuan;
                $data_pengajuan->profiles  = json_encode($pengajuan->profiles, JSON_PRETTY_PRINT);
                $data_pengajuan->user_account_pengajuan  = json_encode($user_account, JSON_PRETTY_PRINT);
                $data_pengajuan->proposed_by = Auth::user()->id;
                $data_pengajuan->proposed_by_data = json_encode(MaPengguna::find(Auth::user()->id), JSON_PRETTY_PRINT);
                $data_pengajuan->site_witel = 'Surabaya utara'; //Auth::user()->site_witel;
                $data_pengajuan->proposed_date = date('Y-m-d H:m:s');
                $data_pengajuan->status_pengajuan = 'proposed';
                $data_pengajuan->save();

                $user_profile = MaUserAccountProfile::where('user_account_id', $user_account_id)->where('aplikasi', $pengajuan->aplikasi)->first();
                if ($user_profile) {
                    MaUserAccountProfile::where('user_account_id', $user_account->id)
                    ->where('aplikasi', $pengajuan->aplikasi)
                    ->update(array(
                        'profile' => json_encode($pengajuan->profile, JSON_PRETTY_PRINT),
                        'pengajuan_aplikasi_id' => $data_pengajuan->id
                    ));
                } else {
                    $user_profile = new MaUserAccountProfile();
                    $user_profile->user_account_id = $user_account_id;
                    $user_profile->aplikasi = $pengajuan->aplikasi;
                    $user_profile->profiles  = json_encode($pengajuan->profiles, JSON_PRETTY_PRINT);
                    $user_profile->pengajuan_aplikasi_id = $data_pengajuan->id;
                    $user_profile->save();
                }
            }

            DB::commit();


            return (new MaUserAccountResource($user_account))->additional([
                'success' => true,
                'message' => 'Data Baru Telah Dibuat'
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'data' => null,
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }

        if ($v->fails()) {
            return response()->json([
                'data' => null,
                'succes' => false,
                'message' => $v->errors()
            ], 422);
        }
    }

    public function updateStatus($id, Request $request)
    {
        $data = TrPengajuanAplikasi::find($id);

        if (!$data) {
            return response()->json([
                'status' => false,
                'message' => 'Data Tidak Ditemukan',
                'data' => null
            ], 404);
        }

        DB::beginTransaction();
        try {

            $data->status_pengajuan = $request->status;
            $data->rejected_by = Auth::user()->id;

            if($request->status == 'rejected')
                $data->rejected_note = $request->note;

            $data->save();

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'success',
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'data' => $e->getMessage(),
                'success' => true,
                'message' => 'error',
            ], 400);
        }
    }

    private function prosesUploadKtp($file)
    {
        $nama_file = Uuid::uuid4()->toString();

        $file->move('data-ktp/', $nama_file . '.' . $file->getClientOriginalExtension());

        return $nama_file . '.' . $file->getClientOriginalExtension();
    }

    private function prosesUploadPakta($file)
    {
        $nama_file = Uuid::uuid4()->toString();

        $file->move('file-pakta/', $nama_file . '.' . $file->getClientOriginalExtension());

        return $nama_file . '.' . $file->getClientOriginalExtension();
    }
}

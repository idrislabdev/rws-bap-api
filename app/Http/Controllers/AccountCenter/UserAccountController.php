<?php

namespace App\Http\Controllers\AccountCenter;

use App\Http\Controllers\Controller;
use App\Http\Resources\MaJabatanResource;
use App\Http\Resources\MaUserAccountResource;
use App\Models\MaJabatan;
use App\Models\MaUserAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class UserAccountController extends Controller
{
    public function index()
    {
        $data = MaUserAccount::with('profiles.pengajuan');
        if (isset($_GET['page'])) {

            if (isset($_GET['q'])) {
                $q = $_GET['q'];
                $data = $data->whereRaw("(nama like '%$q%')");
            }
            if (isset($_GET['status_pegawai'])) {
                $data = $data->where('status_pegawai', $_GET['status_pegawai']);
            }

            if (Auth::user()->role === 'WITEL') {
                $data = $data->where('site_witel', Auth::user()->site_witel);
            } else if (Auth::user()->role === 'RWS' || Auth::user()->role === 'ROOT') {
                if (isset($_GET['site_witel']))
                    $data = $data->where('site_witel', $_GET['site_witel']);
            }

            $data = $data->orderBy('nama')->paginate(25)->onEachSide(5);
        } else {
            $data = $data->get();
        }

        return MaUserAccountResource::collection($data)->additional([
            'success' => true,
            'message' => null
        ]);
    }

    public function update($id, Request $request)
    {
        $v = Validator::make($request->all(), [
            'ids' => 'required',
            'file' => 'required',
        ]);

        $check = MaUserAccount::findOrFail($id);
        if (!$check) {
            return response()->json([
                'status' => false,
                'message' => 'Data Tidak Ditemukan',
                'data' => null
            ], 404);
        }

        try { 
            DB::commit();

            MaUserAccount::where('id', $id)
                ->update(array(
                    'nama' => $request->nama,
                    'tanggal_lahir' => $request->tanggal_lahir,
                    'nik' => $request->nik,
                    'status_pegawai' => $request->status_pegawai,
                    'email' => $request->email,
                    'jabatan_id' => $request->jabatan_id,
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
                    'is_deleted' => false,
                    // 'image_ktp_url' => $url_ktp,
                    // 'file_pakta_url' => $url_pakta
            ));

            return (new MaUserAccountResource($check))->additional([
                'success' => true,
                'message' => 'suksess'
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


}

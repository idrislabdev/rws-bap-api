<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Resources\MaPenggunaResource;
use App\Models\MaPengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class PenggunaController extends Controller
{
    public function index()
    {
        $user = MaPengguna::find(Auth::user()->id);
        if (isset($_GET['q'])){
            $q = $_GET['q'];
            if ($user->role == 'ROOT'){
                $data = MaPengguna::where('nama_lengkap', 'like', '%' . $q . '%')
                                // ->orWhere('username', 'like', '%' . $q . '%')
                                ->paginate(10)
                                ->onEachSide(5);
            } else {
                $data = MaPengguna::where('role', '<>', 'ROOT')
                            ->where('nama_lengkap', 'like', '%' . $q . '%')
                            // ->orWhere('username', 'like', '%' . $q . '%')
                            ->paginate(10)
                            ->onEachSide(5);
            }

        } else {
            if ($user->role =='ROOT') {
                $data = MaPengguna::paginate(10)->onEachSide(5);
            } else   {
                $data = MaPengguna::where('role','<>','ROOT')->paginate(10)->onEachSide(5);
            }
            
        }

        
        return MaPenggunaResource::collection(($data))->additional([
            'success' => true,
            'message' => null,
        ]);
    }

    public function store(Request $request)
    {
        $v = Validator::make($request->all(), [
            'nama_lengkap'  => 'required',
            'username'      => 'required|unique:ma_penggunas',
            'peran'      => 'required|exists:ma_perans,id',
            // 'role' => 'in:ADMIN,RWS,MSO,WITEL',
        ]);

        if ($v->fails())
        {
            return response()->json([
                'status' => false,
                'message' => 'error',
                'data' => $v->errors()
            ], 422);
        }   
        DB::beginTransaction();
        try {

            $url = null;
            if ($request->file('ttd_image')) 
                $url = $this->prosesUpload($request->file('ttd_image'));

            $data = new MaPengguna;
            $data->id =  Uuid::uuid1()->toString();
            $data->nama_lengkap = $request->nama_lengkap;
            $data->username = $request->username;
            $data->password = bcrypt('12345');
            $data->peran = $request->peran;
            $data->jabatan = $request->jabatan;
            $data->lokasi_kerja = $request->lokasi_kerja;
            $data->nik = $request->nik;
            $data->role = $request->role;
            $data->klien_id = $request->klien_id;
            // $data->role = ($request->witel_id) ? $request->witel_id : null;
            $data->witel_id = ($request->witel_id) ? $request->witel_id : null;
            $data->site_witel = ($request->site_witel) ? $request->site_witel : null;
            $data->ttd_image = $url;
            $data->status = 'AKTIF';
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
            ], 500);
        }
    }

    public function show($id)
    {
        $data = MaPengguna::find($id);

        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => $data
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $v = Validator::make($request->all(), [
            'nama_lengkap' => 'required',
            'role' => 'in:ADMIN,RWS,MSO,WITEL',
        ]);

        if ($v->fails())
        {
            return response()->json([
                'status' => false,
                'message' => 'error',
                'data' => $v->errors()
            ], 422);
        }   

        
        $data = MaPengguna::find($id);

        if(!$data)
        {
            return response()->json([
                'status' => false,
                'message' => 'Data Tidak Ditemukan',
                'data' => null
            ], 404);
        }


        if ($request->nama_lengkap != null || $request->nama_lengkap != "")
            $data->nama_lengkap = $request->nama_lengkap;

        if ($request->role != null || $request->role != "")
            $data->role = $request->role;

        if ($request->peran != null || $request->peran != "")
            $data->peran = $request->peran;

        if ($request->lokasi_kerja != null || $request->lokasi_kerja != "")
            $data->lokasi_kerja = $request->lokasi_kerja;

        if ($request->nik != null || $request->nik != "")
            $data->nik = $request->nik;

        if ($request->jabatan != null || $request->jabatan != "")
            $data->jabatan = $request->jabatan;

        if ($request->password != null || $request->password != "")
            $data->password = $request->password;

        if ($request->site_witel != null || $request->site_witel != "")
            $data->site_witel = $request->site_witel;

        if ($request->klien_id != null || $request->klien_id != "")
            $data->klien_id = $request->klien_id;

        $url = '';
        if ($request->file('ttd_image')) {
            // $path = public_path().'/ttd/'.$data->ttd_image;
            // if($data->ttd_image && file_exists($path))
            //     unlink($path);
            
            $url = $this->prosesUpload($request->file('ttd_image'));
            $data->ttd_image = $url;
        }

        if ($request->file('paraf_image')) {
            $url = $this->prosesUpload($request->file('paraf_image'));
            $data->paraf_image = $url;
        }

        $data->update();

        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => $data
        ], 200);
    }

    public function destroy($id)
    {
        $data = MaPengguna::find($id);

        if(!$data)
        {
            return response()->json([
                'status' => false,
                'message' => 'Data Tidak Ditemukan',
                'data' => null
            ], 404);
        }

        $data->delete();

        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => $data
        ], 200);
    }
    
    private function prosesUpload($file)
    {
        $nama_file = Uuid::uuid4()->toString();


        $file->move('ttd/', $nama_file . '.' . $file->getClientOriginalExtension());

        return $nama_file . '.' . $file->getClientOriginalExtension();
    }

    public function fileTTD($name)
    {
        $storagePath = public_path().'/ttd/'.$name;
        return response()->file($storagePath);
    }

    public function pejabatSarpen()
    {
        $query = $_GET['query'];
        $data = MaPengguna::whereHas('peran.detail.hakAkses', function ($q) use ($query) {
            $q->where('nama', $query);
        });

        if (Auth::user()->role == 'WITEL')
            $data = $data = $data->where('site_witel', Auth::user()->site_witel);
        
        $data = $data->get();

        return MaPenggunaResource::collection(($data))->additional([
            'success' => true,
            'message' => null,
        ]);
    }
}

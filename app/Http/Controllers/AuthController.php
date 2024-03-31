<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;
use App\Models\MaPengguna;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use function PHPUnit\Framework\isEmpty;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $v = Validator::make($request->all(), [
            'nama_lengkap' => 'required',
            'username' => 'required|unique:ma_pengguna',
            'password'  => 'required|min:3|confirmed',
            'role' => 'in:ROOT,ADMIN,RWS,WITEL,MSO',
        ]);

        if ($v->fails())
        {
            return response()->json([
                'success' => false,
                'message' => 'error',
                'data' => $v->errors()
            ], 422);
        }

        $user = new MaPengguna;
        $user->id =  Uuid::uuid1()->toString();
        $user->nama_lengkap = $request->nama_lengkap;
        $user->username = $request->username;
        $user->password = bcrypt($request->password);
        $user->role = $request->role;
        $user->status = 'AKTIF';
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'success',
            'data' => $user
        ], 200);
    }

    public function login(Request $request)
    {
        $credentials = [
            'username' => $request->username, 
            'password'=> $request->password, 
            'status' => 'AKTIF',
            //'role' => 'admin'
        ];

        if ($token = $this->guard()->attempt($credentials)) {
            $user = MaPengguna::find(Auth::user()->id);
            return response()->json([
                'success' => true,
                'message' => 'success',
                'data' => ['token' => $token, 'user' => $user]
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'login_failed',
            'data' => null
        ], 401);
    }

    public function logout()
    {
        $this->guard()->logout();

        return response()->json([
            'success' => true,
            'message' => 'success',
            'data' => null
        ], 200);
    }

    public function user (Request $request)
    {
        $user = MaPengguna::find(Auth::user()->id);
        $data_akses = DB::table(DB::raw('ma_hak_akseses mh, ma_peran_details mp'))
        ->select(DB::raw('mh.nama'))
        ->whereRaw("mh.id = mp.hak_akses_id")
        ->where('peran_id', $user->peran)
        ->get();

        $hak_akses = array();
        
        foreach ($data_akses as $key => $data_akses) {
            array_push($hak_akses, $data_akses->nama);
        }

        $user->hak_akses = $hak_akses;
        return response()->json([
            'success' => true,
            'message' => 'success',
            'data' => ['user' => $user]
        ]);
    }

    public function refresh()
    {
        if ($token = $this->guard()->refresh()) {
            return response()->json([
                'success' => true,
                'message' => 'success', 
                'data' => $token
            ], 200);
        }
        
        return response()->json([
            'success' => false, 
            'message' => 'refresh_token_error',
            'data' => null
        ], 401);
    }

    public function changePassword(Request $request)
    {
        $v = Validator::make($request->all(), [
            'password'  => 'required|min:3|confirmed',
        ]);

        if ($v->fails())
        {
            return response()->json([
                'success' => false,
                'message' => 'error',
                'data' => $v->errors()
            ], 422);
        }

        $user = MaPengguna::find(Auth::user()->id);
        $user->password = bcrypt($request->password);
        $user->update();

        return response()->json([
            'success' => true,
            'message' => 'success',
            'data' => $user
        ], 200);
    }

    public function updateProfile(Request $request)
    {

        $user = MaPengguna::find(Auth::user()->id);
        if ($request->nama_lengkap != null || $request->nama_lengkap != "")
            $user->nama_lengkap = $request->nama_lengkap;

        $url = '';
        if ($request->file('ttd_image')) {
            $path = public_path().'/ttd/'.$user->ttd_image;
            if($user->ttd_image && file_exists($path))
                unlink($path);
            
            $url = $this->prosesUpload($request->file('ttd_image'));
            $user->ttd_image = $url;
        }

        if (!$request->file('ttd_image')) {
            $url  = $this->prosesUploadBase64($request->ttd_image);
            $user->ttd_image = $url;
        }

        if ($request->file('paraf_image')) {
            $path = public_path().'/ttd/'.$user->paraf_image;
            if($user->paraf_image && file_exists($path))
                unlink($path);
            
            $url = $this->prosesUpload($request->file('paraf_image'));
            $user->paraf_image = $url;
        }

        if (!$request->file('paraf_image')) {
            $url  = $this->prosesUploadBase64($request->paraf_image);
            $user->paraf_image = $url;
        }

        $user->update();

        return response()->json([
            'success' => true,
            'message' => 'success',
            'data' => $user
        ], 200);
    }

    private function prosesUpload($file)
    {
        $nama_file = Uuid::uuid4()->toString();


        $file->move('ttd/', $nama_file . '.' . $file->getClientOriginalExtension());

        return $nama_file . '.' . $file->getClientOriginalExtension();
    }

    private function prosesUploadBase64($image)
    {
        $nama_file = Uuid::uuid4()->toString();


        // $image = str_replace('data:image/png;base64,', '', $image);
        // $image = str_replace(' ', '+', $image);

        $folderPath = "ttd/"; //path location
            
        $image_parts = explode(";base64,", $image);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $file = $folderPath . $nama_file . '.'.$image_type;
        file_put_contents($file, $image_base64);
    
        // $file->move('ttd/' . $nama_file.'.png', base64_decode($image));
        return $nama_file . '.'.$image_type;
    }


    private function guard()
    {
        return Auth::guard();
    }
}

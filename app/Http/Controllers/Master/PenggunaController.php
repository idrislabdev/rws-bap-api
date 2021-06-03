<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Resources\MaPenggunaResource;
use App\Models\MaPengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
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
                                ->orWhere('username', 'like', '%' . $q . '%')
                                ->paginate(10)
                                ->onEachSide(5);
            } else {
                $data = MaPengguna::where('role', '<>', 'ROOT')
                            ->where('nama_lengkap', 'like', '%' . $q . '%')
                            ->orWhere('username', 'like', '%' . $q . '%')
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
            'nama_lengkap' => 'required',
            'username' => 'required|unique:ma_penggunas',
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

        $data = new MaPengguna;
        $data->id =  Uuid::uuid1()->toString();
        $data->nama_lengkap = $request->nama_lengkap;
        $data->username = $request->username;
        $data->password = bcrypt($request->password);
        $data->role = $request->role;
        $data->status = $request->status;
        $data->save();

        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => $data
        ], 200);
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
            'role' => 'in:ADMIN,GURU,SISWA',
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

        $data->nama_lengkap = $request->nama_lengkap;
        $data->role = $request->role;
        $data->status = $request->status;
        $data->username = $request->username;
        $data->password = bcrypt($request->password);
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
}

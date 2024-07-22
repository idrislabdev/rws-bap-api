<?php

namespace App\Http\Controllers\AccountCenter;

use App\Http\Controllers\Controller;
use App\Http\Resources\MaJabatanResource;
use App\Http\Resources\MaUserAccountResource;
use App\Models\MaJabatan;
use App\Models\MaUserAccount;
use Illuminate\Http\Request;

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

            $data = $data->orderBy('nama')->paginate(25)->onEachSide(5);
        } else {
            $data = $data->get();
        }

        return MaUserAccountResource::collection($data)->additional([
            'success' => true,
            'message' => null
        ]);
    }
}

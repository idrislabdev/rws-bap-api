<?php

namespace App\Http\Controllers\AccountCenter;

use App\Http\Controllers\Controller;
use App\Http\Resources\MaProfileNcxConsResource;
use App\Models\MaProfileNcxCons;
use Illuminate\Http\Request;

class ProfileNcxController extends Controller
{
    public function index()
    {
        $data = new MaProfileNcxCons();
        if (isset($_GET['page'])) {

            if (isset($_GET['q'])) {
                $q = $_GET['q'];
                $data = $data->whereRaw("(nama like '%$q%')");
            }

            $data = $data->orderBy('nama')->paginate(25)->onEachSide(5);
        } else {
            $data = $data->get();
        }

        return MaProfileNcxConsResource::collection($data)->additional([
            'success' => true,
            'message' => null
        ]);
    }
}

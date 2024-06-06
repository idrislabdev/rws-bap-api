<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrBaSarpen extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $keyType = 'string';

    public function pembuat()
    {
        return $this->hasOne('App\Models\MaPengguna', 'id', 'created_by');
    }

    public function managerWholesale()
    {
        return $this->hasOne('App\Models\MaPengguna', 'id', 'manager_wholesale');
    }

    public function parafWholesale()
    {
        return $this->hasOne('App\Models\MaPengguna', 'id', 'paraf_wholesale');
    }

    public function managerWitel()
    {
        return $this->hasOne('App\Models\MaPengguna', 'id', 'manager_witel');
    }

    public function klienObj()
    {
        return $this->hasOne('App\Models\MaOloKlien', 'id', 'klien');
    }

    public function neIptvs()
    {
        return $this->hasMany('App\Models\TrBaSarpenNeIptv', 'sarpen_id');
    }

    public function towers()
    {
        return $this->hasMany('App\Models\TrBaSarpenTower', 'sarpen_id');
    }

    public function ruangans()
    {
        return $this->hasMany('App\Models\TrBaSarpenRuangan', 'sarpen_id');
    }

    public function lahans()
    {
        return $this->hasMany('App\Models\TrBaSarpenLahan', 'sarpen_id');
    }

    public function services()
    {
        return $this->hasMany('App\Models\TrBaSarpenService', 'sarpen_id');
    }

    public function catuDayaGensets()
    {
        return $this->hasMany('App\Models\TrBaSarpenCatuDayaGenset', 'sarpen_id');
    }

    public function catuDayaMcbs()
    {
        return $this->hasMany('App\Models\TrBaSarpenCatuDayaMcb', 'sarpen_id');
    }

    public function akseses()
    {
        return $this->hasMany('App\Models\TrBaSarpenAkses', 'sarpen_id');
    }

    public function racks()
    {
        return $this->hasMany('App\Models\TrBaSarpenRack', 'sarpen_id');
    }

    public function gambars()
    {
        return $this->hasMany('App\Models\TrBaSarpenGambar', 'sarpen_id');
    }

    public function dataSto()
    {
        return $this->hasOne('App\Models\MaSto', 'id', 'sto');
    }

    public function dataSite()
    {
        return $this->hasOne('App\Models\MaSite', 'id', 'site');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrOloBa extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $keyType = 'string';

    public function dibuatOleh()
    {
        return $this->hasOne('App\Models\MaPengguna', 'id', 'dibuat_oleh');
    }

    public function klien()
    {
        return $this->hasOne('App\Models\MaOloKlien', 'id', 'klien_id');
    }

    public function detail()
    {
        return $this->hasMany('App\Models\TrOloBaDetail', 'olo_ba_id');
    }

    public function lampiran()
    {
        return $this->hasMany('App\Models\TrOloBaLampiran', 'olo_ba_id');
    }
}

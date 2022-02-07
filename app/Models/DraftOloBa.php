<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DraftOloBa extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $keyType = 'string';

    public function dibuat()
    {
        return $this->hasOne('App\Models\MaPengguna', 'id', 'dibuat_oleh');
    }

    public function klien()
    {
        return $this->hasOne('App\Models\MaOloKlien', 'id', 'klien_id');
    }

    public function detail()
    {
        return $this->hasMany('App\Models\DraftOloBaDetail', 'olo_ba_id');
    }
}
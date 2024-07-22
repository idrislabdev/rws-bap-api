<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaUserAccountProfile extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $keyType = 'string';

    public function pengajuan()
    {
        return $this->hasOne('App\Models\TrPengajuanAplikasi', 'id', 'pengajuan_aplikasi_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrHistoryPengajuan extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $keyType = 'string';

    public function createdBy()
    {
        return $this->hasOne('App\Models\MaPengguna', 'id', 'created_by');
    }

    public function detailPengajuan()
    {
        return $this->hasMany('App\Models\TrPengajuanAplikasi', 'history_id', 'id');
    }
}

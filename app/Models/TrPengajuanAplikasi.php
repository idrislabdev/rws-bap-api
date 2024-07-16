<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrPengajuanAplikasi extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $keyType = 'string';

    public function userAccount()
    {
        return $this->hasOne('App\Models\MaUserAccount', 'id', 'user_account_id');
    }

    public function proposedBy()
    {
        return $this->hasOne('App\Models\MaPengguna', 'id', 'proposed_by');
    }

    public function rejectedBy()
    {
        return $this->hasOne('App\Models\MaPengguna', 'id', 'ditolak_oleh');
    }

    public function approvedBy()
    {
        return $this->hasOne('App\Models\MaPengguna', 'id', 'diterima_oleh');
    }

    public function processBy()
    {
        return $this->hasOne('App\Models\MaPengguna', 'id', 'diproses_oleh');
    }

    public function accountProfile()
    {
        return $this->hasOne('App\Models\MaUserAccountProfile', 'pengajuan_aplikasi_id', 'id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaPeranDetail extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $keyType = 'string';
    public function hakAkses()
    {
        return $this->hasOne('App\Models\MaHakAkses', 'id',  'hak_akses_id');
    }
}

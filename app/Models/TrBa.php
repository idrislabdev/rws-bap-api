<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrBa extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $keyType = 'string';

    public function dibuatOleh()
    {
        return $this->belongsTo('App\Models\MaPengguna', 'dibuat_oleh');
    }
}

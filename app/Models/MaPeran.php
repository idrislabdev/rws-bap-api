<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaPeran extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $keyType = 'string';

    public function detail()
    {
        return $this->hasMany('App\Models\MaPeranDetail', 'peran_id', 'id');
    }
}

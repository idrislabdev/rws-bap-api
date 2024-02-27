<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrBaSarpenTargetWitel extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    use \Awobaz\Compoships\Compoships;

    public function details()
    {
        return $this->hasMany('App\Models\TrBaSarpenTargetWitelDetail', ['sarpen_target_detail_id', 'detail_no'], ['sarpen_target_id', 'no']);
    }
}

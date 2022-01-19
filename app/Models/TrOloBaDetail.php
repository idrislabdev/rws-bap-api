<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrOloBaDetail extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';
    use \Awobaz\Compoships\Compoships;

    public function addOn()
    {
        
        return $this->hasMany('App\Models\TrOloBaDetailAddOn', ['olo_ba_id', 'id'], ['olo_ba_id', 'id']);
    }

    public function main()
    {
        return $this->hasOne('App\Models\TrOloBa', 'id', 'olo_ba_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrBaSarpenTarget extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $keyType = 'string';

    public function witels()
    {
        return $this->hasMany('App\Models\TrBaSarpenTargetWitel', 'sarpen_target_id');
    }
}

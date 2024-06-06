<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrWoSite extends Model
{
    use HasFactory;

    public function order()
    {
        return $this->belongsTo('App\Models\TrWo', 'wo_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaUserAccount extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $keyType = 'string';

    public function profiles()
    {
        return $this->hasMany('App\Models\MaUserAccountProfile', 'user_account_id', 'id');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gasto extends Model
{
    use SoftDeletes;

    public function user()
    {
        return $this->belongsTo(\App\Models\user::class, 'user_id');
    }
    
}

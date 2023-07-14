<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class PrestamoBono extends Model
{
    use SoftDeletes;

    public function estado()
    {
        return $this->belongsTo(\App\PrestamoEstado::class, 'estado_id');
    }
}

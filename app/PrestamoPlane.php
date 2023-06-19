<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PrestamoPlane extends Model
{
    use SoftDeletes;
    protected $fillable = ['mes', 'nro','monto','interes','capital','cuota','deuda','pagado', 'observacion', 'prestamo_id'];

}

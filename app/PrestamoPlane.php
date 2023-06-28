<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class PrestamoPlane extends Model
{
    use SoftDeletes;
    protected $fillable = ['mes', 'nro','monto','interes','capital','cuota','deuda','pagado', 'observacion', 'prestamo_id', 'fecha', 'pasarela_id', 'fecha_pago', 'user_id'];

    // protected $appends=['published'];
	// public function getPublishedAttribute(){
	// 	return Carbon::createFromTimeStamp(strtotime($this->attributes['created_at']) )->diffForHumans();
	// }

    public function pasarelas()
    {
        return $this->belongsTo(\App\Pasarela::class, 'pasarela_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\user::class, 'user_id');
    }

}
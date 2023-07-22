<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prestamo extends Model
{
    // use SoftDeletes;
    protected $cascadeSoftDeletes = ['prestamo_planes'];
    protected $fillable = ['cliente_id', 'tipo_id','user_id','plazo','monto','interes','cuota','observacion', 'mes_inicio', 'estado_id', 'fecha_prestamos', 'codigo'];
    
    // protected $appends=['published'];
	// public function getPublishedAttribute(){
	// 	return Carbon::createFromTimeStamp(strtotime($this->attributes['created_at']) )->diffForHumans();
	// }
    public function user()
    {
        return $this->belongsTo(\App\Models\user::class, 'user_id');
    }
    public function cliente()
    {
        return $this->belongsTo(\App\Cliente::class, 'cliente_id');
    }
    public function tipo()
    {
        return $this->belongsTo(\App\PrestamoTipo::class, 'tipo_id');
    }
    public function estado()
    {
        return $this->belongsTo(\App\PrestamoEstado::class, 'estado_id');
    }
}

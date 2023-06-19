<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prestamo extends Model
{
    use SoftDeletes;
    protected $cascadeSoftDeletes = ['prestamo_planes'];
    protected $fillable = ['cliente_id', 'tipo_id','user_id','plazo','monto','interes','cuota','observacion', 'mes_inicio', 'estado_id'];
    
    protected $appends=['published'];
	public function getPublishedAttribute(){
		return Carbon::createFromTimeStamp(strtotime($this->attributes['created_at']) )->diffForHumans();
	}
}

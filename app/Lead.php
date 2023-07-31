<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Lead extends Model
{
    protected $fillable = ['phone', 'message', 'categoria'];
}

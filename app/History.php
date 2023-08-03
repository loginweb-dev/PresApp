<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class History extends Model
{
    protected $table = 'history';

    protected $fillable = ['created_at'];
}

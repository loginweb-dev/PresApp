<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use TCG\Voyager\Traits\Spatial;

class Cliente extends Model
{
    use SoftDeletes;
    use Spatial;
}

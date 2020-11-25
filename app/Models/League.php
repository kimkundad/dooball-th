<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class League extends Model
{
    public $timestamps = false;

    
    public static function highLightLeague()
    {
        $leagues = League::where('highlight', 1)->where('active_status', 1);

        return $leagues;
    }
}

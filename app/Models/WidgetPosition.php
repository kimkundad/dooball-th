<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WidgetPosition extends Model
{
    protected $table = 'widget_positions';

    protected $fillable = ['position_dom_id', 'position_name'];
    public $timestamps = false;
}

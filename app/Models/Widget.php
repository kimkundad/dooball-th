<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Widget extends Model
{
    protected $table = 'widgets';

    protected $fillable = ['widget_dom_id', 'widget_name'];
    public $timestamps = false;
}

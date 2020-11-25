<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WidgetOrder extends Model
{
    protected $table = 'widget_orders';

    protected $fillable = ['title', 'detail'];
}

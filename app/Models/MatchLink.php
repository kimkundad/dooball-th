<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatchLink extends Model
{
    protected $table = 'match_links';

    protected $fillable = ['link_type', 'name', 'url', 'desc'];
    public $timestamps = false;
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $table = 'teams';

    protected $fillable = ['team_name_th', 'team_name_en', 'short_name_th', 'long_name_th', 'search_dooball', 'search_graph', 'league_url'];
    public $timestamps = false;
}

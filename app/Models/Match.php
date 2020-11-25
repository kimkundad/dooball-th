<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Team;

class Match extends Model
{
    protected $table = 'matches';

    protected $fillable = ['match_name', 'match_time', 'home_team', 'away_team', 'match_result', 'bargain_price', 'channels', 'channels', 'more_detail'];

    public static function teamNameList()
    {
        $nameList = array();
        $teamNameList = array();
        $count = 1;

        $matches = DB::table('matches')->where('home_team', '<>', '')->whereNotNull('home_team')->groupBy('home_team');

        if ($matches->count() > 0) {
            foreach($matches->get() as $match) {
                $findIn = Team::where('team_name_th', $match->home_team);
                if ($findIn->count() == 0) {
                    $teamNameList[] = array('id' => $count, 'name' => $match->home_team);
                    $nameList[] = $match->home_team;
                    $count++;
                }
            }
        }

        $matches = DB::table('matches')->where('away_team', '<>', '')->whereNotNull('away_team')->groupBy('away_team');

        if ($matches->count() > 0) {
            foreach($matches->get() as $match) {
                $findIn = Team::where('team_name_th', $match->away_team);
                if ($findIn->count() == 0 && ! in_array($match->away_team, $nameList)) {
                    $teamNameList[] = array('id' => $count, 'name' => $match->away_team);
                    $nameList[] = $match->away_team;
                    $count++;
                }
            }
        }

        if (count($teamNameList) > 0) {
            foreach($teamNameList as $name) {
                $findIn = Team::where('team_name_th', $name['name']);
                if ($findIn->count() == 0) {
                    $teamId = DB::table('teams')->insertGetId(
                        ['team_name_th' => $name['name'], 'team_name_en' => '---']
                    );
                }
            }
        }

        return $teamNameList;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\League;
use App\Models\LeagueDecorationItem;

class LeagueDecoration extends Model
{
    public static function leagueSubPage($leagueName = '', $pageUrl = '')
    {
        $wglList = array();

        $leagueId = 0;

        $leagueData = League::where('name_en', $leagueName)->first();

        if ($leagueData) {
            $leagueId = $leagueData->id;
        }

        $mainWgl = self::where('widget_title', 'สถิตินักเตะลีกต่างๆ');
        $mainWgl->orWhere(function ($query) use ($leagueId, $pageUrl) {
            $query->where('league_id', $leagueId)
            ->where('page_url', $pageUrl);
        });

        if ($mainWgl->count() > 0) {
            $mainList = $mainWgl->get();

            foreach($mainList as $data) {
                $liList = array();

                $liDatas = LeagueDecorationItem::where('decoration_id', $data->id);

                if ($liDatas->count() > 0) {
                    foreach($liDatas->get() as $li) {
                        $liList[] = $li;
                    }
                }

                $wglList[] = array('name' => $data->widget_title, 'li' => $liList);
            }
        }

        return $wglList;
    }
}

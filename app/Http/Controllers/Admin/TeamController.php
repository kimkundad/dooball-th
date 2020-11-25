<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\CheckSystemController;
use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Team;
use App\Models\Media;
use App\Models\Match;
use App\Models\League;
use App\Models\OnPage;
use \stdClass;

class TeamController extends Controller
{
    private $conn;
    private $menus;
    private $team_path;

    public function __construct()
    {
        $this->conn = CheckSystemController::checkTableExist('teams');

        if ($this->conn['table']) {
            $this->menus = Menu::allMenus();
            $this->team_path = 'teams';
        }
    }

    public function index()
    {
        if ($this->conn['table']) {
            return view('backend/team/index', ['menus' => $this->menus]);
        } else {
            $message = ($this->conn['table'])? 'Found table: "teams"' : 'Not found table: "teams"';
            abort(403, $message);
        }
    }

    public function create()
    {
        $nameList = Match::teamNameList();
        $leagueList = League::highLightLeague();
        $onpages = OnPage::where('active_status', 1);

        return view('backend/team/create', ['menus' => $this->menus, 'match_list' => $nameList, 'leagues' => $leagueList, 'onpages' => $onpages]);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $form = new stdClass();
        $form->id = $id;
        $form->api_id = 0;
        $form->team_name_th = ''; // in matches
        $form->team_name_en = ''; // in api-football.com
        $form->short_name_th = '';
        $form->long_name_th = '';
        $form->search_dooball = '';
        $form->search_graph = '';
        $form->league_url = '';
        $form->onpage_id = 0;
        // $form->active_status = 1;

        $form->media_id = 0;
        $form->media_name = '';
        $form->path = '';
        $form->alt = '';
        $form->witdh = 0;
        $form->height = 0;
        $form->showImage = '';

        $team = Team::find($id);
        if ($team) {
            $form = $team;

            $form->media_name = '';
            $form->path = '';
            $form->alt = '';
            $form->witdh = 0;
            $form->height = 0;
            $form->showImage = '';

            $media = Media::find($team->media_id);
            if ($media) {
                $form->media_id = $media->id;
                $form->media_name = $media->media_name;
                $form->path = $media->path;
                $form->alt = $media->alt;
                $form->witdh = $media->witdh;
                $form->height = $media->height;
                $form->showImage = asset('storage/' . $this->team_path . '/' . $media->media_name);
            }
        }

        $leagueList = League::highLightLeague();
        $onpages = OnPage::where('active_status', 1);

        return view('backend/team/edit', ['menus' => $this->menus, 'form' => $form, 'leagues' => $leagueList, 'onpages' => $onpages]);
    }
}

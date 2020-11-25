<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\CheckSystemController;
use App\Models\Menu;
use App\Models\Match;
use App\Models\MatchLink;
use \stdClass;

class MatchController extends Controller
{
    private $conn;
    private $menus;

    public function __construct()
    {
        $this->conn = CheckSystemController::checkTableExist('menus');
        
        if ($this->conn['table']) {
            $this->menus = Menu::allMenus();
        }
    }

    public function index()
    {
        return view('backend/match/index', ['menus' => $this->menus]);
    }

    public function create()
    {
        return view('backend/match/create', ['menus' => $this->menus]);
    }

    public function edit($id)
    {
        $form = new stdClass();
        $form->id = $id;
        $form->match_name = '';
        $form->match_time = '';
        $form->home_team = '';
        $form->away_team = '';
        $form->channels = '';
        $form->more_detail = '';
        $form->active_status = '';
    
        $match = Match::find($id);
        $links = array();
        $spon_links = array();

        if ($match) {
            $form = $match;
            $form->match_time = substr($form->match_time, 0, 16);

            $tspon_links = MatchLink::where('match_id', $form->id)->where('link_type', 'Sponsor')->get();
            if ($tspon_links) {
                $spon_links = collect($tspon_links)->sortBy('link_seq');
            }

            $tlinks = MatchLink::where('match_id', $form->id)->where('link_type', 'Normal')->get();
            if ($tlinks) {
                $links = collect($tlinks)->sortBy('link_seq');
            }
        }

        return view('backend/match/edit', ['menus' => $this->menus, 'form' => $form, 'links' => $links, 'spon_links' => $spon_links]);
    }
}

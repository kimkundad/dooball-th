<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\CheckSystemController;
use App\Models\Menu;
use App\Models\League;
use App\Models\LeagueSubpage;
use App\Models\OnPage;
use \stdClass;

class LeagueSubpageController extends Controller
{
    private $conn;
    private $menus;

    public function __construct()
    {
        $this->conn = CheckSystemController::checkTableExist('articles');

        if ($this->conn['table']) {
            $this->menus = Menu::allMenus();
        }
    }

    public function index()
    {
        if ($this->conn['table']) {
            return view('backend/leaguesubpage/index', ['menus' => $this->menus]);
        } else {
            $message = ($this->conn['table'])? 'Found table: "articles"' : 'Not found table: "articles"';
            abort(403, $message);
        }
    }

    public function create()
    {
        $leagueList = League::highLightLeague();
        $onpages = OnPage::where('active_status', 1);
        return view('backend/leaguesubpage/create', ['menus' => $this->menus, 'leagues' => $leagueList, 'onpages' => $onpages]);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $form = new stdClass();
        $form->id = $id;
        $form->name_th = '';
        $form->name_en = '';
        $form->api_name = '';
        $form->short_name = '';
        $form->long_name = '';
        $form->url = '';
        $form->years = '';
        $form->active_status = 1;
    
        $user = LeagueSubpage::find($id);
        if ($user) {
            $form = $user;
        }

        $leagueList = League::highLightLeague();
        $onpages = OnPage::where('active_status', 1);
        return view('backend/leaguesubpage/edit', ['menus' => $this->menus, 'form' => $form, 'leagues' => $leagueList, 'onpages' => $onpages]);
    }
}

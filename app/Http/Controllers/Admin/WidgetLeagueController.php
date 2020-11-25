<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\League;
use App\Models\LeagueDecoration;
use App\Models\LeagueDecorationItem;
use \stdClass;

class WidgetLeagueController extends Controller
{
    private $menus;

    public function __construct()
    {
        $this->menus = Menu::allMenus();
    }

    public function index()
    {
        return view('backend/widget-league/index', ['menus' => $this->menus]);
    }

    public function create()
    {
        $leagues = League::highLightLeague();
        return view('backend/widget-league/create', ['menus' => $this->menus, 'leagues' => $leagues]);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $form = new stdClass();
        $form->id = $id;
        $form->widget_title = '';
        $form->league_id = 0;
        $form->page_url = '';
        $form->dec_seq = 0;
    
        $wgl = LeagueDecoration::find($id);

        if ($wgl) {
            $form = $wgl;
        }

        $leagues = League::highLightLeague();
        return view('backend/widget-league/edit', ['menus' => $this->menus, 'leagues' => $leagues, 'form' => $form]);
    }

    public function items($id = 0, $item_id = 0)
    {
        $form = new stdClass();
        $form->decoration_id = $id;
        $form->decoration_title = '';
        $form->items = '';

        // find decoration data
        $decorationData = LeagueDecoration::find($id);

        if ($decorationData) {
            $form->decoration_title = $decorationData->widget_title;
        }

        if ($item_id == 0) {
            return view('backend/widget-league/li-list', ['menus' => $this->menus, 'form' => $form]);
        } else {
            // li list
            $allLi = LeagueDecorationItem::where('id', $item_id)->first();
            $form->id = $item_id;
            $form->title = '';
            $form->slug = '';

            if ($allLi) {
                $form->title = $allLi->title;
                $form->slug = $allLi->slug;
            }

            return view('backend/widget-league/edit-li', ['menus' => $this->menus, 'form' => $form]);
        }
    }

    public function addItem($id = 0)
    {
        $form = new stdClass();
        $form->decoration_id = $id;
        $form->decoration_title = '';
        
        // find decoration data
        $decorationData = LeagueDecoration::find($id);

        if ($decorationData) {
            $form->decoration_title = $decorationData->widget_title;
            return view('backend/widget-league/add-li', ['menus' => $this->menus, 'form' => $form]);
        } else {
            echo 'Not found This ID.';
            // return redirect()->route();
        }
    }
}

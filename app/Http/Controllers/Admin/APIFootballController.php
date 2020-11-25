<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;
use \stdClass;

class APIFootballController extends Controller
{
    private $menus;

    public function __construct()
    {
        $this->menus = Menu::allMenus();
    }

    public function index()
    {
        return view('backend/api-football/index', ['menus' => $this->menus]);
    }

    public function addItem($id = 0)
    {
        $apiPages = DB::table('api_page')->get();
        return view('backend/api-football/create', ['menus' => $this->menus, 'api_pages' => $apiPages, 'page_id' => $id]);
    }

    public function editItem($id = 0, $item_id = 0)
    {
        $form = new stdClass();
        $form->id = $id;
        $form->api_page_id = 0;
        $form->api_url = '';
        $form->api_desc = '';
        $form->params_count = 0;
        $form->params_type = '';
        $form->params_mask = '';

        $api = DB::table('api_page_items')->where('id', $item_id);
        if ($api->count() > 0) {
            $apiRows = $api->get();
            $form = $apiRows[0];
        }

        $apiPages = DB::table('api_page')->get();
        return view('backend/api-football/edit', ['menus' => $this->menus, 'form' => $form, 'api_pages' => $apiPages, 'page_id' => $id]);
    }
}

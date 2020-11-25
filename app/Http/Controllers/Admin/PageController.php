<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\CommonController;
use App\Http\Controllers\Admin\CheckSystemController;
use App\Http\Controllers\API\Admin\KeyController;
use App\Models\Menu;
use App\Models\Page;
// use App\Models\PageLanguage;
use App\Models\Media;
use \stdClass;

class PageController extends Controller
{
    private $conn;
    private $menus;
    private $page_path;
    private $key;
    private $common;
    private $page_filter;

    public function __construct()
    {
        $this->conn = CheckSystemController::checkTableExist('pages');

        if ($this->conn['table']) {
            $this->menus = Menu::allMenus();
            $this->page_path = 'pages';
        }

        $this->key = new KeyController();
        $this->common = new CommonController();
        $this->page_filter = $this->common->getTableColumns('matches');
    }

    public function index()
    {
        if ($this->conn['table']) {
            return view('backend/page/index', ['menus' => $this->menus]);
        } else {
            $message = ($this->conn['table'])? 'Found table: "pages"' : 'Not found table: "pages"';
            abort(403, $message);
        }
    }

    public function create()
    {
        return view('backend/page/create', ['menus' => $this->menus]);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $dateTime = Date('Y-m-d H:i:s');

        $form = new stdClass();
        $form->page_id = $id;
        $form->page_name = '';
        $form->slug = '';
        $form->page_condition = '';
        $form->league_name = '';
        $form->team_name = '';
        $form->title = '';
        $form->description = '';
        $form->detail = '';
        $form->seo_title = '';
        $form->seo_description = '';
        $form->created_at = $dateTime;
        $form->show_on_menu = 0;

        $form->keys = array();

        $page = Page::find($id);
        if ($page) {
            $form = $page;
            $form->page_id = $id;
            $form->keys = $this->key->keyList('page', $id);
            // dd($form->keys);
        }

        /*
        $langs = PageLanguage::where('page_id', $id)->get();
        if (count($langs) > 0) {
            foreach($langs as $v) {
                // dd($v->language);
            }
        }*/

        $filters = $this->page_filter;
        return view('backend/page/edit', ['menus' => $this->menus, 'form' => $form, 'filters' => $filters]);
    }
}

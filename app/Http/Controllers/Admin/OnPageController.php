<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\OnPage;
use App\Models\Page;
use \stdClass;

class OnPageController extends Controller
{
    private $menus;

    public function __construct()
    {
        // $this->conn = CheckSystemController::checkTableExist('onpage');

        // if ($this->conn['table']) {
            $this->menus = Menu::allMenus();
        // }
    }

    public function index()
    {
        // if ($this->conn['table']) {
            return view('backend/on-page/index', ['menus' => $this->menus]);
        // } else {
        //     $message = ($this->conn['table'])? 'Found table: "articles"' : 'Not found table: "articles"';
        //     abort(403, $message);
        // }
    }
    

    public function create()
    {
        $pageList = Page::where('active_status', 1);

        return view('backend/on-page/create', ['menus' => $this->menus, 'page_list' => $pageList]);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $form = new stdClass();
        $form->id = $id;
        $form->code_name = '';
        $form->seo_title = '';
        $form->seo_description = '';
        $form->page_top = 0;
        $form->page_bottom = 0;
        
        $data = OnPage::find($id);

        if ($data) {
            $form = $data;
        }

        $pageList = Page::where('active_status', 1);

        return view('backend/on-page/edit', ['menus' => $this->menus, 'form' => $form, 'page_list' => $pageList]);
    }
}

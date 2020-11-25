<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use \stdClass;

class MenuController extends Controller
{
    private $menus;

    public function __construct()
    {
        $this->menus = Menu::allMenus();
    }

    public function index()
    {
        return view('backend/menu/index', ['menus' => $this->menus]);
    }

    public function create()
    {
        $parents = Menu::allParents();
        return view('backend/menu/create', ['menus' => $this->menus, 'parents' => $parents]);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $form = new stdClass();
        $form->id = $id;
        $form->parent_id = 0;
        $form->menu_name = '';
        $form->menu_url = '';
        $form->menu_icon = '';
        $form->menu_seq = 0;
        $form->menu_status = 1;
    
        $menu = Menu::find($id);
        if ($menu) {
            $form = $menu;
        }

        $parents = Menu::allParents();
        return view('backend/menu/edit', ['menus' => $this->menus, 'parents' => $parents, 'form' => $form]);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Menu;

class WidgetController extends Controller
{
    private $menus;

    public function __construct()
    {
        $this->menus = Menu::allMenus();
    }

    public function index()
    {
        return view('backend/widget/index', ['menus' => $this->menus]);
    }
}

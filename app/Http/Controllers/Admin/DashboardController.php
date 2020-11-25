<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\Admin\DashboardController as DashboardAPI;
use App\Models\Menu;

class DashboardController extends Controller
{
    private $menus;
    private $dashboard;

    public function __construct()
    {
        $this->menus = Menu::allMenus();
        $this->dashboard = new DashboardAPI();
    }

    public function index()
    {
        $datas = array();

        return view('backend/dashboard', ['menus' => $this->menus, 'datas' => $datas]);
    }
}

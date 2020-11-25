<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\CheckSystemController;
use App\Models\Menu;

class FFPController extends Controller
{
    private $conn;
    private $menus;

    public function __construct()
    {
        $this->conn = CheckSystemController::checkTableExist('ffp_detail');

        if ($this->conn['table']) {
            $this->menus = Menu::allMenus();
        }
    }

    public function index()
    {
        if ($this->conn['table']) {
            return view('backend/ffp/index', ['menus' => $this->menus]);
        } else {
            $message = ($this->conn['table'])? 'Found table: "ffp_detail"' : 'Not found table: "articles"';
            abort(403, $message);
        }
    }

    public function create()
    {
        return view('backend/ffp/create', ['menus' => $this->menus]);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        return view('backend/article/edit', ['menus' => $this->menus, 'id' => $id]);
    }
}

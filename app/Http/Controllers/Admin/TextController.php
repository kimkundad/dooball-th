<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\CheckSystemController;
use App\Models\Menu;
use App\Models\Text;
use \stdClass;

class TextController extends Controller
{
    private $conn;
    private $menus;
    private $tableName;

    public function __construct()
    {
        $this->tableName = 'texts';
        $this->conn = CheckSystemController::checkTableExist($this->tableName);

        if ($this->conn['table']) {
            $this->menus = Menu::allMenus();
        }
    }

    public function index()
    {
        if ($this->conn['table']) {
            return view('backend/text/index', ['menus' => $this->menus]);
        } else {
            $message = ($this->conn['table'])? 'Found table: "' . $this->tableName . '"' : 'Not found table: "' . $this->tableName . '"';
            abort(403, $message);
        }
    }

    public function create()
    {
        return view('backend/text/create', ['menus' => $this->menus]);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $form = new stdClass();
        $form->id = $id;
        $form->text = '';
        $form->active_status = 1;

        $team = Text::find($id);

        if ($team) {
            $form = $team;
        }

        return view('backend/text/edit', ['menus' => $this->menus, 'form' => $form]);
    }
}

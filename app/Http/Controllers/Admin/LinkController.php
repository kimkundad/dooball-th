<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Link;
use \stdClass;

class LinkController extends Controller
{
    private $conn;
    private $link_table;
    private $menus;

    public function __construct()
    {
        $this->conn = CheckSystemController::checkTableExist('menus');
        $this->link_table = CheckSystemController::checkTableExist('links');
        
        if ($this->conn['table'] && $this->link_table['table']) {
            $this->menus = Menu::allMenus();
        }
    }

    public function index()
    {
        if ($this->conn['table'] && $this->link_table['table']) {
            return view('backend/link/index', ['menus' => $this->menus]);
        } else {
            $message = '';
            if (! $this->conn['table']) {
                $message = ($this->conn['table'])? 'Found table: "articles"' : 'Not found table: "articles"';
            }
            if (! $this->link_table['table']) {
                $message = ($this->conn['table'])? 'Found table: "links"' : 'Not found table: "links"';
            }

            abort(403, $message);
        }
    }

    public function create()
    {
        return view('backend/link/create', ['menus' => $this->menus]);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $form = new stdClass();
        $form->id = $id;
        $form->link_url = '';
        $form->link_name = '';
    
        $link = Link::find($id);
        if ($link) {
            $form = $link;
        }

        return view('backend/link/edit', ['menus' => $this->menus, 'form' => $form]);
    }
}

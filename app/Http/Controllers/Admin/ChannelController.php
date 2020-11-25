<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\CheckSystemController;
use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Channel;
use \stdClass;

class ChannelController extends Controller
{
    private $conn;
    private $menus;

    public function __construct()
    {
        $this->conn = CheckSystemController::checkTableExist('channels');

        if ($this->conn['table']) {
            $this->menus = Menu::allMenus();
        }
    }

    public function index()
    {
        if ($this->conn['table']) {
            return view('backend/channel/index', ['menus' => $this->menus]);
        } else {
            $message = ($this->conn['table'])? 'Found table: "channels"' : 'Not found table: "channels"';
            abort(403, $message);
        }
    }
    
    public function create()
    {
        return view('backend/channel/create', ['menus' => $this->menus]);
    }
    
    public function show($id)
    {
        //
    }
    
    public function edit($id)
    {
        $form = new stdClass();
        $form->id = $id;
        $form->channel_name = '';
        $form->active_status = 1;
    
        $user = Channel::find($id);
        if ($user) {
            $form = $user;
        }

        return view('backend/channel/edit', ['menus' => $this->menus, 'form' => $form]);
    }
}

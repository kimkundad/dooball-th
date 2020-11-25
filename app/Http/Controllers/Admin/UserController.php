<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\User;
use \stdClass;

class UserController extends Controller
{
    private $menus;

    public function __construct()
    {
        $this->menus = Menu::allMenus();
    }

    public function index()
    {
        return view('backend/user/index', ['menus' => $this->menus]);
    }

    public function create()
    {
        return view('backend/user/create', ['menus' => $this->menus]);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $form = new stdClass();
        $form->id = $id;
        $form->role = 'Member';
        $form->username = '';
        $form->email = '';
        $form->first_name = '';
        $form->last_name = '';
        $form->user_status = 1;
    
        $user = User::find($id);
        if ($user) {
            $form = $user;
        }

        return view('backend/user/edit', ['menus' => $this->menus, 'form' => $form]);
    }

    public function resetPassword($id)
    {
        $form = new stdClass();
        $form->id = $id;
        $form->role = 'Member';
        $form->username = '';
        $form->email = '';
        $form->first_name = '';
        $form->last_name = '';
        $form->user_status = 1;
    
        $user = User::find($id);
        if ($user) {
            $form = $user;
        }

        return view('backend/user/reset-password', ['menus' => $this->menus, 'form' => $form]);

    }
}

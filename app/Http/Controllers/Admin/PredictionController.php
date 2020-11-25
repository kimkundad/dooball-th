<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\CheckSystemController;
use App\Models\Menu;
use App\Models\Prediction;
use \stdClass;

class PredictionController extends Controller
{
    private $conn;
    private $menus;
    // private $article_path;

    public function __construct()
    {
        $this->conn = CheckSystemController::checkTableExist('articles');

        if ($this->conn['table']) {
            $this->menus = Menu::allMenus();
            // $this->article_path = 'articles';
        }
    }

    public function index()
    {
        return view('backend/prediction/index', ['menus' => $this->menus]);
    }

    public function create()
    {
        return view('backend/prediction/create', ['menus' => $this->menus]);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $form = new stdClass();
        $form->id = 0;
        
        $prediction = Prediction::find($id);

        if ($prediction) {
            $form = $prediction;
        }

        return view('backend/prediction/edit', ['menus' => $this->menus, 'form' => $form]);
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\CheckSystemController;
use App\Models\Menu;
use App\Models\Article;
use App\Models\ArticleTag;
use App\Models\Tag;
use App\Models\ArticleLanguage;
use App\Models\Media;
use App\Models\Team;
use App\Models\Tournament;
use App\Models\Channel;
use \stdClass;

class TagController extends Controller
{
    private $conn;
    private $menus;
    private $tableName;

    public function __construct()
    {
        $this->tableName = 'tags';
        $this->conn = CheckSystemController::checkTableExist($this->tableName);

        if ($this->conn['table']) {
            $this->menus = Menu::allMenus();
        }
    }

    public function index()
    {
        if ($this->conn['table']) {
            return view('backend/tag/index', ['menus' => $this->menus]);
        } else {
            $message = ($this->conn['table'])? 'Found table: "' . $this->tableName . '"' : 'Not found table: "' . $this->tableName . '"';
            abort(403, $message);
        }
    }

    public function create()
    {
        return view('backend/tag/create', ['menus' => $this->menus]);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $form = Tag::find($id);
        return view('backend/tag/edit', ['menus' => $this->menus, 'form' => $form]);
    }

    public function tagName($tagId)
    {
        $tagName = '';
        $tagData = Tag::select('tag_name')->where('id', $tagId);
        if ($tagData->count() > 0) {
            $tag = $tagData->get();
            $tagName = $tag[0]->tag_name;
        }

        return $tagName;
    }

    public function related($id)
    {
        $form = new stdClass();
        $form->related = '';
        
        $article = Article::find($id);
        if ($article) {
            $form = $article;
        }

        return view('backend/article/related', ['menus' => $this->menus, 'form' => $form]);
    }
}

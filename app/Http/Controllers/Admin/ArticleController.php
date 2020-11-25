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

class ArticleController extends Controller
{
    private $conn;
    private $menus;
    private $article_path;

    public function __construct()
    {
        $this->conn = CheckSystemController::checkTableExist('articles');

        if ($this->conn['table']) {
            $this->menus = Menu::allMenus();
            $this->article_path = 'articles';
        }
    }

    public function index()
    {
        if ($this->conn['table']) {
            return view('backend/article/index', ['menus' => $this->menus]);
        } else {
            $message = ($this->conn['table'])? 'Found table: "articles"' : 'Not found table: "articles"';
            abort(403, $message);
        }
    }

    public function create()
    {
        $teams = Team::all();
        $tournaments = Tournament::all();
        $channels = Channel::all();

        return view('backend/article/create', ['menus' => $this->menus, 'teams' => $teams, 'tournaments' => $tournaments, 'channels' => $channels]);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $dateTime = Date('Y-m-d H:i:s');

        $form = new stdClass();
        $form->article_id = $id;
        $form->title = '';
        $form->slug = '';
        $form->description = '';
        $form->team_id = 0;
        $form->tournament_id = 0;
        $form->channel_id = 0;
        $form->detail = '';
        $form->seo_title = '';
        $form->seo_description = '';
        $form->user_id = 0;
        $form->created_at = $dateTime;
        $form->article_status = 0;
        // $form->active_status = 1;

        $form->media_id = 0;
        $form->media_name = '';
        $form->img_name = '';
        $form->img_ext = '';
        $form->path = '';
        $form->alt = '';
        $form->witdh = 0;
        $form->height = 0;
        $form->showImage = '';

        $article = Article::find($id);
        if ($article) {
            $form = $article;
            $form->article_id = $id;

            $media = Media::find($article->media_id);
            if ($media) {
                $form->media_id = $media->id;
                $form->media_name = $media->media_name;

                $img_data = explode('.', $media->media_name);
                $form->img_name = $img_data[0];
                $form->img_ext = $img_data[1];

                $form->path = $media->path;
                $form->alt = $media->alt;
                $form->witdh = $media->witdh;
                $form->height = $media->height;
                $form->showImage = asset('storage/' . $this->article_path . '/' . $media->media_name);
            }
        }

        $tags = '';

        $atcTag = ArticleTag::where('article_id', $id);
        if ($atcTag->count() > 0) {
            $artTagList = $atcTag->get();
            $tagNameList = array();

            foreach($artTagList as $val) {
                $tagNameList[] = $this->tagName($val->tag_id);
            }

            $tags = implode(',', $tagNameList);
        }

        $langs = ArticleLanguage::where('article_id', $id)->get();
        if (count($langs) > 0) {
            foreach($langs as $v) {
                // dd($v->language);
            }
        }

        $teams = Team::all();
        $tournaments = Tournament::all();
        $channels = Channel::all();

        return view('backend/article/edit', ['menus' => $this->menus, 'form' => $form, 'tags' => $tags, 'teams' => $teams, 'tournaments' => $tournaments, 'channels' => $channels]);
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

<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\CheckConnectionController;
use App\Http\Controllers\API\Front\WelcomeController as WelcomeAPI;
use App\Http\Controllers\API\Front\ArticleController as ArticleAPI;
use App\Http\Controllers\API\Front\WidgetController as WidgetAPI;
use App\Http\Controllers\API\Front\PageController as PageAPI;
use App\Http\Controllers\API\MockupController;

class ArticleController extends Controller
{
    private $connDatas;
    private $welcome;
    private $article;
    private $widget;
    private $page;
    private $mockup;

    public function __construct()
    {
        $this->mockup = new MockupController();
        $conn = new CheckConnectionController();
        $this->connDatas = $conn->checkConnServer();

        if ($this->connDatas['connected']) {
            //     $table = $conn->checkTableExist('generals');

            //     if ($table['table']) {
            $this->welcome = new WelcomeAPI();
            $this->article = new ArticleAPI();
            $this->widget = new WidgetAPI();
            $this->page = new PageAPI();
            //     } else {
            //         abort(500, 'Not found table: generals.');
            //     }
            // } else {
            //     abort(500, 'Cannot connect database.');
        }
    }

    public function articleList(Request $request, $slug = '')
    {
        $currentPage = ((int) $request->page != 0) ? (int)$request->page : 1;

        // $cateData = $this->category->cateDataFromSlug($slug, 'id');
        $cateId = 0; // $cateData->id;
        // $cateName = $cateData->category_name;

        $connectionStatus = 0;
        $pageList = array();
        $website_robot = 0;
        $web_image = asset('images/logo.png');
        $seo_title = 'รวมบทความ สอนดูราคาบอลไหล สอนดูบอล บทความสอนเรื่องฟุตบอล';
        $seo_description = 'สอนการใช้งานเว็บไซต์ สอนการวิเคราะห์ราคาบอลไหล สอนวิธี ทริก เทคนิค ดูบอล สอนเรื่องทั่วไปเกี่ยวกับฟุตบอล';
        $articles = array();
        $latest = array();
        $popular = array();
        $widget = $this->mockup->widgetMock();
        $social = $this->mockup->socialMock();
        // $matches = array();

        if ($this->connDatas['connected']) {
            $connectionStatus = 1;
            $articles = $this->article->articleList($currentPage, $cateId);
            $web = $this->welcome->generalData();
            $website_robot = ($web->website_robot) ? (int) $web->website_robot : 0;
            $web_image = ($web->web_image) ? $web->web_image : $web_image;
            $pageList = $this->page->list();
            $widget = $this->widget->widgetData();
            // $matches = $this->welcome->matchDatas();
            $social = $this->widget->socialData();
            $latest = $this->article->articleLatest();
            $popular = $this->article->articlePopular();
        }

        $respDatas = array(
            'pages' => $pageList,
            'website_robot' => $website_robot,
            'web_image' => $web_image,
            'seo_title' => $seo_title,
            'seo_description' => $seo_description,
            'articles' => $articles,
            'widget' => $widget,
            'social' => $social,
            // 'matches' => $matches,
            'latest' => $latest,
            'popular' => $popular,
            'connection_status' => $connectionStatus,
        );

        return view('frontend/article-list', $respDatas);
    }

    public function articleTag(Request $request, $tag_name = '')
    {
        $currentPage = ((int) $request->page != 0) ? (int)$request->page : 1;

        $connectionStatus = 0;
        $pageList = array();
        $website_robot = 0;
        $web_image = asset('images/logo.png');
        $seo_title = '';
        $seo_description = '';
        $tag_content = '';
        $articles = array();
        $latest = array();
        $popular = array();
        $widget = $this->mockup->widgetMock();
        $social = $this->mockup->socialMock();

        if ($this->connDatas['connected']) {
            $connectionStatus = 1;

            $tagOnpage = $this->article->tagOnpage($tag_name);
            $seo_title = $tagOnpage['title'];
            $seo_description = $tagOnpage['description'];
            $tag_content = $tagOnpage['detail'];

            $articles = $this->article->articleTag($currentPage, $tag_name);
            $web = $this->welcome->generalData();
            $website_robot = ($web->website_robot) ? (int) $web->website_robot : 0;
            $web_image = ($web->web_image) ? $web->web_image : $web_image;
            $pageList = $this->page->list();
            $widget = $this->widget->widgetData();
            $social = $this->widget->socialData();
            $latest = $this->article->articleLatest();
            $popular = $this->article->articlePopular();
        }

        $respDatas = array(
            'pages' => $pageList,
            'website_robot' => $website_robot,
            'web_image' => $web_image,
            'tag_name' => $tag_name,
            'seo_title' => $seo_title,
            'seo_description' => $seo_description,
            'tag_content' => $tag_content,
            'articles' => $articles,
            'widget' => $widget,
            'social' => $social,
            'latest' => $latest,
            'popular' => $popular,
            'connection_status' => $connectionStatus,
        );

        return view('frontend/tag', $respDatas);
    }
}

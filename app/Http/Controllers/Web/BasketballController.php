<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\CommonController;
use App\Http\Controllers\API\CheckConnectionController;
use App\Http\Controllers\API\Front\WelcomeController as WelcomeAPI;
use App\Http\Controllers\API\Front\ArticleController as ArticleAPI;
use App\Http\Controllers\API\Front\WidgetController as WidgetAPI;
use App\Http\Controllers\API\Front\PageController as PageAPI;
use App\Http\Controllers\API\Front\OnPageController as OnPageAPI;
use App\Http\Controllers\API\Front\PredictionController as PredictionAPI;
use App\Http\Controllers\API\Front\ContentDetailController as CTDetailAPI;
use App\Http\Controllers\API\MockupController;
use App\Http\Controllers\API\LogFileController;
use Illuminate\Support\Facades\DB;

use App\Models\DirList;
use App\Models\ContentDetail;
use App\Models\Match;
use App\Models\LeagueDecoration;

class BasketballController extends Controller
{
    private $common;
    private $connDatas;
    private $welcome;
    private $article;
    private $widget;
    private $page;
    private $onPage;
    private $prediction;
    private $ctDetail;
    private $mockup;
    private $logAsFile;

    public function __construct()
    {
        $this->common = new CommonController();
        $this->mockup = new MockupController();
        $conn = new CheckConnectionController();
        $this->connDatas = $conn->checkConnServer();

        $this->welcome = new WelcomeAPI();
        $this->article = new ArticleAPI();
        $this->widget = new WidgetAPI();
        $this->page = new PageAPI();
        $this->onPage = new OnPageAPI();
        $this->prediction = new PredictionAPI();
        $this->ctDetail = new CTDetailAPI();
        $this->logAsFile = new LogFileController;
    }

    public function index()
    {
        $connectionStatus = 0;
        $website_robot = 0;
        $web_image = asset('images/logo.png');
        $seoTitle = 'ดูบาสสด 7m 4k ดูบาสออนไลน์ วันนี้ คมชัด HD สำรองคู่ละ 30 ลิ้ง';
        $seoDescription = 'Dooball เว็บรวมลิ้งดูบาสออนไลน์วันนี้ ครบทุกคู่ทั่วโลกฟรี ดูบาสสด 7m 4k HD อัพเดตลิ้งดูบาส youtube facebook ระหว่างการแข่งขันสด รองรับ ดูบาสผ่านเน็ตมือถือ';
        $topContent = '';
        $bottomContent = '';

        $articles = array();
        // $pageList = array();
        // $widget = $this->mockup->widgetMock();
        // $social = $this->mockup->socialMock();
        $matches = array();
        $betList = array();

        if ($this->connDatas['connected']) {
            $connectionStatus = 1;
            $articles = $this->article->articlePage();
            $web = $this->welcome->generalData();
            $website_robot = ($web->website_robot) ? (int) $web->website_robot : 0;
            $web_image = ($web->web_image) ? $web->web_image : $web_image;

            $pageContent = $this->onPage->pageContentByCodeName('บาส - ลิ้งดูบาส');
            $seoTitle = ($pageContent['seo_title']) ? $pageContent['seo_title'] : $seoTitle;
            $seoDescription = ($pageContent['seo_description']) ? $pageContent['seo_description'] : $seoDescription;
            $topContent = $pageContent['top'];
            $bottomContent = $pageContent['bottom'];

            $matches = $this->welcome->matchDatas('บาส NBA');

            // $pageList = $this->page->list();
            // $widget = $this->widget->widgetData();
            // $social = $this->widget->socialData();
            $betDatas = $this->prediction->betList(1, 10);
            $betList = $betDatas['datas'];
        }

        $domain = request()->getHttpHost();
        $own = env('APP_ENV');
        $httpHost = ($own == 'production') ? 'https://' : 'http://';
        $thisHost = $httpHost . $domain;

        $respDatas = array(
            // 'pages' => $pageList,
            // 'widget' => $widget,
            // 'social' => $social,
            'website_robot' => $website_robot,
            'web_image' => $web_image,
            'seo_title' => $seoTitle,
            'seo_description' => $seoDescription,
            'top_content' => $topContent,
            'bottom_content' => $bottomContent,
            'articles' => $articles,
            'matches' => $matches,
            'bets' => $betList,
            'this_host' => $thisHost,
            'connection_status' => $connectionStatus,
        );

        return view('frontend/basketball/index', $respDatas);
    }

    public function tdedBas($date = '')
    {
        $connectionStatus = 0;
        $website_robot = 0;
        $web_image = asset('images/logo.png');
        $seoTitle = 'ทีเด็ดบาส';
        $seoDescription = '';
        $topContent = '';
        $bottomContent = '';
        $prev = '';
        $next = '';

        $tdBasDatas = array();

        $dateParams = ($date) ? $date : Date('Y-m-d');

        if ($this->connDatas['connected']) {
            $connectionStatus = 1;
            $web = $this->welcome->generalData();
            $website_robot = ($web->website_robot) ? (int) $web->website_robot : 0;
            $web_image = ($web->web_image) ? $web->web_image : $web_image;

            $tdBasDatas = $this->prediction->tdedBallDatas(7, $dateParams);
            $prev = $this->prediction->prevNextTdedLink($dateParams, 'prev', 7);
            $next = $this->prediction->prevNextTdedLink($dateParams, 'next', 7);

            $pageContent = $this->onPage->pageContentByCodeName('บาส : ทีเด็ด');
            $seoTitle = ($pageContent['seo_title']) ? $pageContent['seo_title'] : $seoTitle;
            $seoDescription = ($pageContent['seo_description']) ? $pageContent['seo_description'] : $seoDescription;
            $topContent = $pageContent['top'];
            $bottomContent = $pageContent['bottom'];

        }

        $respDatas = array(
            'website_robot' => $website_robot,
            'web_image' => $web_image,
            'tded_datas' => $tdBasDatas,
            'seo_title' => $seoTitle,
            'seo_description' => $seoDescription,
            'top_content' => $topContent,
            'bottom_content' => $bottomContent,
            'prev' => $prev,
            'next' => $next,
            'connection_status' => $connectionStatus
        );

        return view('frontend/tdedbas/tdedbas', $respDatas);
    }
    
    public function tdedBasPerDay($month = '')
    {
        $connectionStatus = 0;
        $website_robot = 0;
        $web_image = asset('images/logo.png');
        $seoTitle = 'ทีเด็ดบาสวันละตัว';
        $seoDescription = '';
        $topContent = '';
        $bottomContent = '';
        $prev = '';
        $next = '';
        $tdedBallDatas = array();

        $monthParams = ($month) ? $month : Date('Y-m');

        if ($this->connDatas['connected']) {
            $connectionStatus = 1;
            $web = $this->welcome->generalData();
            $website_robot = ($web->website_robot) ? (int) $web->website_robot : 0;
            $web_image = ($web->web_image) ? $web->web_image : $web_image;

            $tdedBallDatas = $this->prediction->tdedballOneMatch(8, $monthParams);
            $prev = $this->prediction->prevPageTdedContLink(8, $monthParams, 'prev');
            $next = $this->prediction->prevPageTdedContLink(8, $monthParams, 'next');

            $pageContent = $this->onPage->pageContentByCodeName('บาส : ทีเด็ดวันละตัว');
            $seoTitle = ($pageContent['seo_title']) ? $pageContent['seo_title'] : $seoTitle;
            $seoDescription = ($pageContent['seo_description']) ? $pageContent['seo_description'] : $seoDescription;
            $topContent = $pageContent['top'];
            $bottomContent = $pageContent['bottom'];
        }

        $respDatas = array(
            'website_robot' => $website_robot,
            'web_image' => $web_image,
            'tded_datas' => $tdedBallDatas,
            'seo_title' => $seoTitle,
            'seo_description' => $seoDescription,
            'top_content' => $topContent,
            'bottom_content' => $bottomContent,
            'prev' => $prev,
            'next' => $next,
            'connection_status' => $connectionStatus
        );

        return view('frontend/tdedbas/tdedbas-perday', $respDatas);
    }
}

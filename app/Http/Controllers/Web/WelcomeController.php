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

class WelcomeController extends Controller
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

    /*
    public function index()
    {
        $connectionStatus = 0;
        $pageList = array();
        $website_robot = 0;
        $web_image = asset('frontend/images/logo.png');
        $seo_title = '';
        $seo_description = '';
        $page_topic = '';
        $page_description = '';
        $page_detail = '';
        $articles = array();
        $widget = $this->mockup->widgetMock();
        $social = $this->mockup->socialMock();
        $matches = array();

        if ($this->connDatas['connected']) {
            $connectionStatus = 1;
            $articles = $this->article->articlePage();
            $web = $this->welcome->generalData();
            $website_robot = ($web->website_robot) ? (int) $web->website_robot : 0;
            $web_image = ($web->web_image) ? $web->web_image : $web_image;
            $homeContent = $this->page->pageContentBySlug('home');
            $seo_title = ($homeContent->seo_title) ? trim($homeContent->seo_title) : '';
            $seo_description = ($homeContent->seo_description) ? trim($homeContent->seo_description) : '';
            $page_topic = ($homeContent->title) ? trim($homeContent->title) : '';
            $page_description = ($homeContent->description) ? trim($homeContent->description) : '';
            $page_detail = ($homeContent->detail) ? trim($homeContent->detail) : '';
            $pageList = $this->page->list();
            $widget = $this->widget->widgetData();
            $matches = $this->welcome->matchDatas();
            $social = $this->widget->socialData();
        } else {
            $stringRealMatches = file_get_contents(env('SCRAP_LINK'));

            if ($stringRealMatches) {
                $matches = json_decode($stringRealMatches);
            }
        }

        $respDatas = array(
            'pages' => $pageList,
            'website_robot' => $website_robot,
            'web_image' => $web_image,
            'seo_title' => $seo_title,
            'seo_description' => $seo_description,
            'page_topic' => $page_topic,
            'page_description' => $page_description,
            'page_detail' => $page_detail,
            'articles' => $articles,
            'widget' => $widget,
            'social' => $social,
            'matches' => $matches,
            'connection_status' => $connectionStatus,
        );

        return view('frontend/welcome', $respDatas);
    }*/

    public function index()
    {
        $connectionStatus = 0;
        $website_robot = 0;
        $web_image = asset('images/logo.png');
        $seoTitle = 'ดูบอลสด 7m 4k ดูบอลออนไลน์ วันนี้ คมชัด HD สำรองคู่ละ 30 ลิ้ง';
        $seoDescription = 'Dooball เว็บรวมลิ้งดูบอลออนไลน์วันนี้ ครบทุกคู่ทั่วโลกฟรี ดูบอลสด 7m 4k HD อัพเดตลิ้งดูบอล youtube facebook ระหว่างการแข่งขันสด รองรับ ดูบอลผ่านเน็ตมือถือ';
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

            $pageContent = $this->onPage->pageContentByCodeName('index');
            $seoTitle = ($pageContent['seo_title']) ? $pageContent['seo_title'] : $seoTitle;
            $seoDescription = ($pageContent['seo_description']) ? $pageContent['seo_description'] : $seoDescription;
            $topContent = $pageContent['top'];
            $bottomContent = $pageContent['bottom'];

            $matches = $this->welcome->matchDatas();
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

        return view('frontend/index', $respDatas);
    }

    public function analysis()
    {
        return redirect()->route('index');

        /*
        $connectionStatus = 0;
        $website_robot = 0;
        $web_image = asset('frontend/images/logo.png');
        $seo_title = 'Analysis';
        $seo_description = '';

        if ($this->connDatas['connected']) {
            $connectionStatus = 1;
            $web = $this->welcome->generalData();
            $website_robot = ($web->website_robot) ? (int) $web->website_robot : 0;
            $web_image = ($web->web_image) ? $web->web_image : $web_image;
        }

        $respDatas = array(
            'website_robot' => $website_robot,
            'web_image' => $web_image,
            'seo_title' => $seo_title,
            'seo_description' => $seo_description,
            'connection_status' => $connectionStatus
        );

        return view('frontend/analysis', $respDatas);*/
    }

    public function dooball()
    {
        return redirect()->route('index');

        /*
        $connectionStatus = 0;
        $website_robot = 0;
        $web_image = asset('frontend/images/logo.png');
        $seo_title = 'Dooball';
        $seo_description = '';

        if ($this->connDatas['connected']) {
            $connectionStatus = 1;
            $web = $this->welcome->generalData();
            $website_robot = ($web->website_robot) ? (int) $web->website_robot : 0;
            $web_image = ($web->web_image) ? $web->web_image : $web_image;
        }

        $respDatas = array(
            'website_robot' => $website_robot,
            'web_image' => $web_image,
            'seo_title' => $seo_title,
            'seo_description' => $seo_description,
            'connection_status' => $connectionStatus
        );

        return view('frontend/dooball', $respDatas);*/
    }

    public function highlights()
    {
        return redirect()->route('index');

        /*
        $connectionStatus = 0;
        $website_robot = 0;
        $web_image = asset('frontend/images/logo.png');
        $seo_title = 'Highlights';
        $seo_description = '';

        if ($this->connDatas['connected']) {
            $connectionStatus = 1;
            $web = $this->welcome->generalData();
            $website_robot = ($web->website_robot) ? (int) $web->website_robot : 0;
            $web_image = ($web->web_image) ? $web->web_image : $web_image;
        }

        $respDatas = array(
            'website_robot' => $website_robot,
            'web_image' => $web_image,
            'seo_title' => $seo_title,
            'seo_description' => $seo_description,
            'connection_status' => $connectionStatus
        );

        return view('frontend/highlights', $respDatas);*/
    }

    public function score()
    {
        return redirect()->route('index');

        /*
        $connectionStatus = 0;
        $website_robot = 0;
        $web_image = asset('frontend/images/logo.png');
        $seo_title = 'Score';
        $seo_description = '';

        if ($this->connDatas['connected']) {
            $connectionStatus = 1;
            $articles = $this->article->articlePage();
            $web = $this->welcome->generalData();
            $website_robot = ($web->website_robot) ? (int) $web->website_robot : 0;
            $web_image = ($web->web_image) ? $web->web_image : $web_image;
        }

        $respDatas = array(
            'website_robot' => $website_robot,
            'web_image' => $web_image,
            'seo_title' => $seo_title,
            'seo_description' => $seo_description,
            'connection_status' => $connectionStatus
        );

        return view('frontend/score', $respDatas);*/
    }

    public function odds()
    {
        return redirect()->route('index');

        /*
        $connectionStatus = 0;
        $website_robot = 0;
        $web_image = asset('frontend/images/logo.png');
        $seo_title = 'บอลไหล';
        $seo_description = '';

        if ($this->connDatas['connected']) {
            $connectionStatus = 1;
            $web = $this->welcome->generalData();
            $website_robot = ($web->website_robot) ? (int) $web->website_robot : 0;
            $web_image = ($web->web_image) ? $web->web_image : $web_image;
        }

        $respDatas = array(
            'website_robot' => $website_robot,
            'web_image' => $web_image,
            'seo_title' => $seo_title,
            'seo_description' => $seo_description,
            'connection_status' => $connectionStatus
        );

        return view('frontend/odds', $respDatas);*/
    }

    public function programs()
    {
        return redirect()->route('index');

        /*
        $connectionStatus = 0;
        $website_robot = 0;
        $web_image = asset('frontend/images/logo.png');
        $seo_title = 'Programs';
        $seo_description = '';

        if ($this->connDatas['connected']) {
            $connectionStatus = 1;
            $web = $this->welcome->generalData();
            $website_robot = ($web->website_robot) ? (int) $web->website_robot : 0;
            $web_image = ($web->web_image) ? $web->web_image : $web_image;
        }

        $respDatas = array(
            'website_robot' => $website_robot,
            'web_image' => $web_image,
            'seo_title' => $seo_title,
            'seo_description' => $seo_description,
            'connection_status' => $connectionStatus
        );

        return view('frontend/programs', $respDatas);*/
    }

    public function team()
    {
        return redirect()->route('index');

        /*
        $connectionStatus = 0;
        $website_robot = 0;
        $web_image = asset('frontend/images/logo.png');
        $seo_title = 'Team';
        $seo_description = '';

        if ($this->connDatas['connected']) {
            $connectionStatus = 1;
            $web = $this->welcome->generalData();
            $website_robot = ($web->website_robot) ? (int) $web->website_robot : 0;
            $web_image = ($web->web_image) ? $web->web_image : $web_image;
        }

        $respDatas = array(
            'website_robot' => $website_robot,
            'web_image' => $web_image,
            'seo_title' => $seo_title,
            'seo_description' => $seo_description,
            'connection_status' => $connectionStatus
        );

        return view('frontend/team', $respDatas);*/
    }

    public function player()
    {
        return redirect()->route('index');

        /*
        $connectionStatus = 0;
        $website_robot = 0;
        $web_image = asset('frontend/images/logo.png');
        $seo_title = 'Player';
        $seo_description = '';

        if ($this->connDatas['connected']) {
            $connectionStatus = 1;
            $web = $this->welcome->generalData();
            $website_robot = ($web->website_robot) ? (int) $web->website_robot : 0;
            $web_image = ($web->web_image) ? $web->web_image : $web_image;
        }

        $respDatas = array(
            'website_robot' => $website_robot,
            'web_image' => $web_image,
            'seo_title' => $seo_title,
            'seo_description' => $seo_description,
            'connection_status' => $connectionStatus
        );

        return view('frontend/player', $respDatas);*/
    }

    public function prediction(Request $request)
    {
        $page = ($request->page) ? $request->page : 1;
        $userBetPerpage = 50;

        $connectionStatus = 0;
        $website_robot = 0;
        $web_image = asset('frontend/images/logo.png');
        $seoTitle = 'ทายผลบอลวันนี้ แจกเครดิตฟรี ไม่ต้องฝาก ไม่ต้องแชร์ 2020';
        $seoDescription = 'เข้าทายผลบอล วันละ 1 คู่ถูก 4 วัน รับเครดิตฟรี 100 ไม่จำกัดรางวัล ทายผลบอลได้เงิน ไม่ต้องฝาก ไม่ต้องแชร์ เครดิตฟรี เล่นได้ทุกเกมส์ สล็อต ตาสิโน ถอนได้';

        $topContent = '';
        $bottomContent = '';

        $betPaging = null;
        $betList = array();
        $textList = array();

        if ($this->connDatas['connected']) {
            $connectionStatus = 1;
            $web = $this->welcome->generalData();
            $website_robot = ($web->website_robot) ? (int) $web->website_robot : 0;

            $pageContent = $this->onPage->pageContentByCodeName('game');
            $seoTitle = ($pageContent['seo_title']) ? $pageContent['seo_title'] : $seoTitle;
            $seoDescription = ($pageContent['seo_description']) ? $pageContent['seo_description'] : $seoDescription;
            $topContent = $pageContent['top'];
            $bottomContent = $pageContent['bottom'];

            $betDatas = $this->prediction->betList($page, $userBetPerpage);
            $betPaging = $betDatas['bets'];
            $betList = $betDatas['datas'];
            $textList = $this->prediction->textList();
        }

        // dd($predictionList);
        $domain = request()->getHttpHost();

        $respDatas = array(
            'website_robot' => $website_robot,
            'web_image' => $web_image,
            'seo_title' => $seoTitle,
            'seo_description' => $seoDescription,
            'top_content' => $topContent,
            'bottom_content' => $bottomContent,
            'bets' => $betList,
            'bet_paging' => $betPaging,
            'current_page' => $page,
            'perpage' => $userBetPerpage,
            'texts' => $textList,
            'domain' => $domain,
            'connection_status' => $connectionStatus
        );

        return view('frontend/prediction', $respDatas);
    }

    public function betStats(Request $request)
    {
        $connectionStatus = 0;
        // $pageList = array();
        $website_robot = 0;
        $seo_title = 'สถิติเกมทายผลบอล ของ ' . $request->user;
        $seo_description = 'สถิติย้อนหลังการทายผลบอล พร้อม ผลสถานะ แพ้ ชนะ เสมอ ของสมาชิก ' . $request->user . ' จัดแสดงผลเป็นแบบรายเดือน และรายวัน';

        $monthlyList = array();
        $statList = array();

        if ($this->connDatas['connected']) {
            $connectionStatus = 1;
            $web = $this->welcome->generalData();
            $website_robot = ($web->website_robot) ? (int) $web->website_robot : 0;
            // $statsContent = $this->page->pageContentBySlug('stats');
            // $seo_title = ($homeContent->seo_title) ? trim($homeContent->seo_title) : '';
            // $pageList = $this->page->list();

            $userScreenName = $this->common->displayNameFromUsername($request->user);
            
            $seo_title = 'สถิติเกมทายผลบอล ของ ' . $userScreenName;
            $seo_description = 'สถิติย้อนหลังการทายผลบอล พร้อม ผลสถานะ แพ้ ชนะ เสมอ ของสมาชิก ' . $userScreenName . ' จัดแสดงผลเป็นแบบรายเดือน และรายวัน';

            $monthlyList = $this->prediction->betMonthly($request->user, 'username');
            $statList = $this->prediction->betHistory($request->user, $request->month);
        }

        $domain = request()->getHttpHost();
        $own = env('APP_ENV');
        $httpHost = ($own == 'production') ? 'https://' : 'http://';
        $thisHost = $httpHost . $domain;

        $respDatas = array(
            // 'pages' => $pageList,
            'website_robot' => $website_robot,
            'seo_title' => $seo_title,
            'seo_description' => $seo_description,
            'monthly_list' => $monthlyList,
            'month' => $request->month,
            'stats' => $statList,
            'user_name' => $request->user,
            'user_id' => 0,
            'this_host' => $thisHost,
            'connection_status' => $connectionStatus
        );

        return view('frontend/stats', $respDatas);
    }

    public function betStatsUser(Request $request)
    {
        $connectionStatus = 0;
        $website_robot = 0;
        $seo_title = 'สถิติเกมทายผลบอล ของ ผู้ใช้งาน ' . $request->user_id . ' (ID)';
        $seo_description = 'สถิติย้อนหลังการทายผลบอล พร้อม ผลสถานะ แพ้ ชนะ เสมอ ของสมาชิก ' . $request->user_id . ' (ID) จัดแสดงผลเป็นแบบรายเดือน และรายวัน';

        $monthlyList = array();
        $statList = array();

        if ($this->connDatas['connected']) {
            $connectionStatus = 1;
            $web = $this->welcome->generalData();
            $website_robot = ($web->website_robot) ? (int) $web->website_robot : 0;
            // $homeContent = $this->page->pageContentBySlug('stats');
            // $seo_title = ($homeContent->seo_title) ? trim($homeContent->seo_title) : '';
            
            $userScreenName = $this->common->displayNameFromId($request->user_id);
            
            $seo_title = 'สถิติเกมทายผลบอล ของ ' . $userScreenName;
            $seo_description = 'สถิติย้อนหลังการทายผลบอล พร้อม ผลสถานะ แพ้ ชนะ เสมอ ของสมาชิก ' . $userScreenName . ' จัดแสดงผลเป็นแบบรายเดือน และรายวัน';

            $monthlyList = $this->prediction->betMonthly($request->user_id, 'id');
            $statList = $this->prediction->betHistoryUser($request->user_id, $request->month);
        }

        $domain = request()->getHttpHost();
        $own = env('APP_ENV');
        $httpHost = ($own == 'production') ? 'https://' : 'http://';
        $thisHost = $httpHost . $domain;

        $respDatas = array(
            'website_robot' => $website_robot,
            'seo_title' => $seo_title,
            'seo_description' => $seo_description,
            'monthly_list' => $monthlyList,
            'month' => $request->month,
            'stats' => $statList,
            'user_name' => '',
            'user_id' => $request->user_id,
            'this_host' => $thisHost,
            'connection_status' => $connectionStatus
        );

        return view('frontend/stats', $respDatas);
    }

    public function footballPrice()
    {
        $connectionStatus = 0;
        $website_robot = 0;
        $web_image = asset('images/logo.png');
        $seoTitle = 'ราคาบอลไหล ล่าสุดวันนี้ ดูง่ายในรูปแบบกราฟ';
        $seoDescription = 'เรตบอล ราคาบอลไหลล่าสุด อัตราต่อรอง ราคาสูงต่ำ ราคาแพ้ชนะ(1x2) แสดงในรูปแบบกราฟ ราคาบอลไหลขึ้น ไหลลง วิเคราะห์ราคา ล้มโต๊ะ 888 เข้าใจง่าย';

        $topContent = '';
        $bottomContent = '';

        if ($this->connDatas['connected']) {
            $connectionStatus = 1;
            $web = $this->welcome->generalData();
            $website_robot = ($web->website_robot) ? (int) $web->website_robot : 0;
            $web_image = ($web->web_image) ? $web->web_image : $web_image;

            $pageContent = $this->onPage->pageContentByCodeName('ราคาบอลไหล');
            $seoTitle = ($pageContent['seo_title']) ? $pageContent['seo_title'] : $seoTitle;
            $seoDescription = ($pageContent['seo_description']) ? $pageContent['seo_description'] : $seoDescription;
            $topContent = $pageContent['top'];
            $bottomContent = $pageContent['bottom'];
        }

        $domain = request()->getHttpHost();
        $own = env('APP_ENV');
        $httpHost = ($own == 'production') ? 'https://' : 'http://';
        $thisHost = $httpHost . $domain;

        $respDatas = array(
            'website_robot' => $website_robot,
            'web_image' => $web_image,
            'seo_title' => $seoTitle,
            'seo_description' => $seoDescription,
            'top_content' => $topContent,
            'bottom_content' => $bottomContent,
            'this_host' => $thisHost,
            'connection_status' => $connectionStatus
        );

        return view('frontend/ffp', $respDatas);
    }

    public function ffpDetail(Request $request)
    {
        // error_reporting(E_ALL);
        // ini_set('display_errors', 1);
        // header("Content-Type: text/plain");
        // date_default_timezone_set("Asia/Bangkok");

        $connectionStatus = 0;
        $website_robot = 0;
        $web_image = asset('images/logo.png');
        $pageList = array();
        $seo_title = '';
        $seo_description = '';
        $hOne = '';
        $bottomContentHTwo = '';
        $bottomContentFirst = '';
        $bottomContentSecond = '';
        $lastContent = '';

        $leagueName = '';
        $homeTeamName = '';
        $awayTeamName = '';
        $eventTime = '';

        $thisLink = $request->link;

        if ($this->connDatas['connected']) {
            $connectionStatus = 1;
            $web = $this->welcome->generalData();
            $website_robot = ($web->website_robot) ? (int) $web->website_robot : 0;
            $web_image = ($web->web_image) ? $web->web_image : $web_image;
            $pageList = $this->page->list();

            $latestCurrentDatas = ContentDetail::select(['dir_name', 'league_name', 'vs', 'event_time', 'link'])->where('id', $thisLink);

            if ($latestCurrentDatas->count() > 0) {
                $latestDatas = $latestCurrentDatas->get();
                $firstItem = $latestDatas[0];
    
                $vs = $firstItem->vs;
                if (trim($vs) && !empty($vs)) {
                    $vsList = preg_split('/-vs-/', $vs);
                    $homeTeamName = $vsList[0];
                    $awayTeamName = array_key_exists(1, $vsList) ? $vsList[1] : '-';
                }
    
                $dirName = $firstItem->dir_name;
                $fullLink = $firstItem->link;
                $leagueName = $firstItem->league_name;
                $time = $firstItem->event_time;

                // --- start time -1 hr --- //
                if ($time != '-- no time --' && $time != 'null' && $time != null && strpos($time, '*') === false) {
                    $eventTime = $this->common->skudTimeBeforeOneHr($time, ' ');
                } else {
                    if (strpos($time, '*') !== false) {
                        $eventTime = $time;
                    }
                }
                // --- end time -1 hr --- //

                // --- start fill blank info --- //
                if (empty(trim($leagueName)) || empty(trim($homeTeamName)) || $awayTeamName == '-') {
                    $tempData = DB::table('ffp_list_temp')->select(['content'])->where('dir_name', $dirName)->first();
                    if ($tempData) {
                        $leagueList = json_decode($tempData->content);

                        $lIndex = 0;
                        $tIndex = 0;
                        $l = 0;
                        $t = 0;

                        if ($leagueList) {
                            if (count($leagueList) > 0) {
                                foreach($leagueList as $k => $league) {
                                    if (count($league->match_datas) > 0) {
                                        foreach($league->match_datas as $n => $match) {
                                            if ($match->detail_id == $thisLink) {
                                                $lIndex = $k;
                                                $tIndex = $n;
                                                $l++;
                                                $t++;
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        if ($l > 0 && $t > 0) {
                            $leagueName = $leagueList[$lIndex]->league_name;
                            $homeTeamName = $leagueList[$lIndex]->match_datas[$tIndex]->left[0];
                            $awayTeamName = $leagueList[$lIndex]->match_datas[$tIndex]->right[0];
                            // echo $leagueName . ', ' . $homeTeamName . ', ' . $awayTeamName;
    
                            $ffpDetail = ContentDetail::find($thisLink);
                            $ffpDetail->league_name = $leagueName;
                            $ffpDetail->vs = $homeTeamName . '-vs-' . $awayTeamName;
                            $ffpDetail->save();
                        }
                    }
                }
                // --- end fill blank info --- //

                $seo_title = 'ราคาบอลไหล ล่าสุด ' . $homeTeamName . ' พบ ' . $awayTeamName . ' รายการ ' . $leagueName;
                $seo_description = $leagueName . ' ' . $homeTeamName . ' พบ ' . $awayTeamName;
                $seo_description .= ' รวมราคาบอลไหลตั้งแต่ราคาเปิด ในรูปแบบกราฟ ราคาบอลไหล สูงต่ำ 1x2(ราคาแพ้ชนะ) อัตราต่อรอง เริ่มแข่ง เวลา ' . $eventTime . ' วันนี้';
                
                $hOne = 'กราฟราคาบอล ' . $homeTeamName . ' vs ' . $awayTeamName;
                $bottomContentHTwo = 'ราคาบอลไหล ' . $leagueName;
                $bottomContentFirst = 'การดูราคาบอลไหล ลีกเล็กลีกใหญ่ มีความสำคัญ อย่างที่ทราบกัน ราคาบอลเปลี่ยนแปลงตามจำนวนผู้เดิมพัน ฝั่งไหนฝั่งหนึ่งมีการเดิมพันมาก
                ราคาบอลจะไหลลง ฉะนั้น การที่บอลลีกเล็กลีกใหญ่ ความตรงที่ ราคาจะมีการไหลที่น้อยกว่าเพราะมีคนเดิมพันทั้งสองฝ่าย แต่ถ้าเมื่อไร
                ราคาไหลขึ้นไหลลง แล้วนั่นแสดงว่า คนนิยม หรือมั่นใจในฝั่งนั่นมากกว่า ราคาบอลไหล ' . $leagueName . ' ดูยังไงเป็นลีกเล็กหรือลีกใหญ่?
                หากเป็น ราคาบอลที่อยู่ใน 5 ลีกหลัก นั่นถึงเป็นราคาบอลลีกใหญ่ มีดังนี้
                ราคาบอลไหลพลีเมียร์ลีก ราคาบอลไหลลาลีกา ราคาบอลไหลกัลโช่ ราคาบอลไหลบุนเดสลีกา ราคาบอลไหลลีกเอิง';
                $bottomContentSecond = 'ราคาบอลไหล ' . $homeTeamName . ' และ ราคาบอลไหล ' . $awayTeamName . ' จะไหลมาไหลน้อยดูยังไง?
                กราฟบอลไหลจะแสดง อัตราต่อรอง ราคาบอลสูงต่ำ และราคาแพ้ฃนะ หากกราฟเป็นเส้นตรงๆ
                ไม่มีการ ขยับนั่นคือ ไม่มีการไหลของราคา โดยกราฟแต่ละอันจะบ่งบอกดังนี้';
                $lastContent = 'สำหรับคู่นี้ แนะนำเช็คราคาบอลไหล ' . $homeTeamName . ' Vs ' . $awayTeamName . ' จนถึงเวลาก่อนแข่ง ' . $eventTime . ' จะดีที่ที่สุด
                ราคาบอลตั้งแต่ราคาเปิด สามารถบ่งบอก อะไรได้หลายอย่าง ที่สำคัญ
                ช่วยลดความเสี่ยงจากการเดิมพันได้เป็นอย่างดี <span class="text-danger">ราคาบอลไหล ' . $leagueName . '</span> ลีกเล็กลีกใหญ่
                ก็เป็นอีกจุดนึงที่สามารถบอกความสำคัญในการผันผวนของราคาได้';
            }
        }

        $domain = request()->getHttpHost();
        $own = env('APP_ENV');
        $httpHost = ($own == 'production') ? 'https://' : 'http://';
        $thisHost = $httpHost . $domain;

        $respDatas = array(
            'pages' => $pageList,
            'website_robot' => $website_robot,
            'web_image' => $web_image,
            'seo_title' => $seo_title,
            'seo_description' => $seo_description,
            'connection_status' => $connectionStatus,
            'link' => $thisLink,
            'league_name' => $leagueName,
            'home_team' => $homeTeamName,
            'away_team' => $awayTeamName,
            'event_time' => $eventTime,
            'h_one' => $hOne,
            'bottom_htwo' => $bottomContentHTwo,
            'bottom_content_first' => $bottomContentFirst,
            'bottom_content_second' => $bottomContentSecond,
            'last_content' => $lastContent,
            'this_host' => $thisHost
        );

        return view('frontend/ffp-detail', $respDatas);
    }

    public function tdedBall($date = '')
    {
        $connectionStatus = 0;
        $website_robot = 0;
        $web_image = asset('images/logo.png');
        $seoTitle = 'ทีเด็ดบอล';
        $seoDescription = '';
        $topContent = '';
        $bottomContent = '';
        $prev = '';
        $next = '';

        $tdedBallDatas = array();

        $dateParams = ($date) ? $date : Date('Y-m-d');

        if ($this->connDatas['connected']) {
            $connectionStatus = 1;
            $web = $this->welcome->generalData();
            $website_robot = ($web->website_robot) ? (int) $web->website_robot : 0;
            $web_image = ($web->web_image) ? $web->web_image : $web_image;

            $tdedBallDatas = $this->prediction->tdedBallDatas(6, $dateParams);
            $prev = $this->prediction->prevNextTdedLink($dateParams, 'prev', 6);
            $next = $this->prediction->prevNextTdedLink($dateParams, 'next', 6);

            $pageContent = $this->onPage->pageContentByCodeName('ทีเด็ดบอล');
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

        return view('frontend/tdedball/tdedball', $respDatas);
    }

    public function tdedBallCont($month = '')
    {
        $connectionStatus = 0;
        $website_robot = 0;
        $web_image = asset('images/logo.png');
        $seoTitle = 'ทีเด็ดบอลต่อ';
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

            $tdedBallDatas = $this->prediction->tdedballOneMatch(1, $monthParams);

            $prev = $this->prediction->prevPageTdedContLink(1, $monthParams, 'prev');
            $next = $this->prediction->prevPageTdedContLink(1, $monthParams, 'next');

            $pageContent = $this->onPage->pageContentByCodeName('ทีเด็ดบอลต่อ');
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

        return view('frontend/tdedball/tdedball-cont', $respDatas);
    }

    public function tdedBallNotCont($month = '')
    {
        $connectionStatus = 0;
        $website_robot = 0;
        $web_image = asset('images/logo.png');
        $seoTitle = 'ทีเด็ดบอลรอง';
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

            $tdedBallDatas = $this->prediction->tdedballOneMatch(2, $monthParams);
            $prev = $this->prediction->prevPageTdedContLink(2, $monthParams, 'prev');
            $next = $this->prediction->prevPageTdedContLink(2, $monthParams, 'next');

            $pageContent = $this->onPage->pageContentByCodeName('ทีเด็ดบอลรอง');
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

        return view('frontend/tdedball/tdedball-not-cont', $respDatas);
    }

    public function tdedBallTeng($month = '')
    {
        $connectionStatus = 0;
        $website_robot = 0;
        $web_image = asset('images/logo.png');
        $seoTitle = 'ทีเด็ดบอลเต็ง';
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

            $tdedBallDatas = $this->prediction->tdedballOneMatch(3, $monthParams);
            $prev = $this->prediction->prevPageTdedContLink(3, $monthParams, 'prev');
            $next = $this->prediction->prevPageTdedContLink(3, $monthParams, 'next');

            $pageContent = $this->onPage->pageContentByCodeName('ทีเด็ดบอลเต็ง');
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

        return view('frontend/tdedball/tdedball-teng', $respDatas);
    }

    public function tdedBallStepTwo($month = '')
    {
        $connectionStatus = 0;
        $website_robot = 0;
        $web_image = asset('images/logo.png');
        $seoTitle = 'ทีเด็ดบอลสเต็ป2';
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

            $tdedBallDatas = $this->prediction->tdedtepTwo(4, $monthParams);
            $prev = $this->prediction->prevPageTdedContLink(4, $monthParams, 'prev');
            $next = $this->prediction->prevPageTdedContLink(4, $monthParams, 'next');

            $pageContent = $this->onPage->pageContentByCodeName('ทีเด็ดบอลสเต็ป2');
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

        return view('frontend/tdedball/tdedball-step-two', $respDatas);
    }

    public function tdedBallStepThree($month = '')
    {
        $connectionStatus = 0;
        $website_robot = 0;
        $web_image = asset('images/logo.png');
        $seoTitle = 'ทีเด็ดบอลสเต็ป3';
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

            $tdedBallDatas = $this->prediction->tdedtepThree(5, $monthParams);
            $prev = $this->prediction->prevPageTdedContLink(5, $monthParams, 'prev');
            $next = $this->prediction->prevPageTdedContLink(5, $monthParams, 'next');

            $pageContent = $this->onPage->pageContentByCodeName('ทีเด็ดบอลสเต็ป3');
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

        return view('frontend/tdedball/tdedball-step-three', $respDatas);
    }

    public function google()
    {
        $google = file_get_contents('googleb49fba0c8d22dcb2.html');

        return $google;
    }

    public function seeSitemap()
    {
        $sitemap = $this->common->autoUpdateSitemap();

        $xml = simplexml_load_string($sitemap);

        echo '<pre>';
        print_r($xml);
        echo '</pre>';
    }

    public function robots()
    {
        header("Content-Type: text/html; charset=UTF-8");

        $robotDatas = file_get_contents('robot.txt');

        echo '<pre>';
        echo $robotDatas;
        echo '</pre>';
    }

    public function logFile($file_name = '')
    {
        if ($file_name) {
            $path = '../storage/files/' . $file_name;
            $logDatas = file_get_contents($path);
            $datas = array('log_content' => $logDatas);
            return view('log-html', $datas);
        }
    }

    public function testQuery()
    {
        $exceptList = DirList::where('except_this', 1)->where('content', '<>', '[]');
        
        $this->logAsFile->logAsFile('except-this.html', 'Start at: ' . Date('Y-m-d H:i:s'));

        if ($exceptList->count() > 0) {
            $datas = $exceptList->take(25)->get();

            foreach($datas as $dir) {
                $dirName = $dir->dir_name;
                $latestContent = $dir->content;
                $contentList = json_decode($latestContent);
    
                $resultList = $this->common->rawLeagueList($contentList, $dirName);
                $totalMatch = $resultList['total_match'];
    
                $this->logAsFile->logAsFile('except-this.html', '<br>' . $dirName . ' : Total match: ' . $totalMatch, 'append');

                if ($totalMatch > 0) {
                    DB::table('ffp_list')->where('dir_name', $dirName)->update(array('except_this' => 0));
                    $this->logAsFile->logAsFile('except-this.html', '<br>Set ' . $dirName . ' to 0', 'append');
                }

                echo $dirName;
                echo '<br>';
            }
        }

        // return view('test-query');

        // $this->common->scanExceptDir();

        // DB::enableQueryLog();
        // $findDup = DB::table('tdedball_list')
        //             ->where('type_id', 1)
        //             ->whereRaw("DATE(created_at) = '" . Date('Y-m-d') . "'")
        //             ->where('team_selected', 'like', '%' . $params . '_%')
        //             ->count();
        // $q = DB::getQueryLog()[0]['query'];
        // echo $findDup;
        // dd($q);

        /*
        $timeWrong = array();
        $timeRight = array();

        $dtDatas = ContentDetail::select(['id', 'event_time'])->whereRaw('LENGTH(event_time) = 11'); // Jun 23 11:00

        if ($dtDatas->count() > 0) {
            $detailList = $dtDatas->skip(0)->take(50)->get();

            foreach($detailList as $detail) {
                $detailId = $detail->id;
                $rightTime = substr($detail->event_time, 0, 6) . ' ' . substr($detail->event_time, 6, 5);

                $timeWrong[] = $detail->event_time;
                $timeRight[] = $rightTime;

                $dtData = ContentDetail::find($detailId);
                $dtData->event_time = $rightTime;
                $saved = $dtData->save();
            }
        }
        return array('wrong' => $timeWrong, 'right' => $timeRight);
        */
    }
}

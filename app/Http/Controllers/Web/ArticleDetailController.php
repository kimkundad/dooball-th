<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\CheckConnectionController;
use App\Http\Controllers\API\Front\ArticleController as ArticleAPI;
use App\Http\Controllers\API\Front\WelcomeController as WelcomeAPI;
use App\Http\Controllers\API\Front\WidgetController as WidgetAPI;
use App\Http\Controllers\API\Front\PageController as PageAPI;
use App\Http\Controllers\API\Front\OnPageController as OnPageAPI;
use App\Http\Controllers\API\Front\PredictionController as PredictionAPI;
use App\Http\Controllers\API\Front\ContentDetailController as CTDetailAPI;
use App\Http\Controllers\API\CommonController;
use App\Http\Controllers\API\MockupController;
use App\Http\Controllers\API\LogFileController;
use Illuminate\Support\Facades\DB;
use App\Models\League;
use App\Models\LeagueSubpage;
use App\Models\LeagueDecoration;

class ArticleDetailController extends Controller
{
    private $connDatas;
    private $common;
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
        $this->mockup = new MockupController();
        $conn = new CheckConnectionController();
        $this->common = new CommonController();
        $this->welcome = new WelcomeAPI();
        $this->article = new ArticleAPI();
        $this->onPage = new OnPageAPI();
        $this->prediction = new PredictionAPI();
        $this->ctDetail = new CTDetailAPI();
        $this->logAsFile = new LogFileController;

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

    public function index($slug = '')
    {
        $pageList = array();
        $website_robot = 0;
        $web_image = asset('images/logo.png');
        $isArticle = false;
        $isPage = false;
        $isLeague = false;
        $article = null;
        $seo_title = '';
        $seo_description = '';
        $page_topic = '';
        $page_description = '';
        $page_detail = '';
        $notFoundMessage = '';
        $widget = $this->mockup->widgetMock();
        $social = $this->mockup->socialMock();
        $pageSlugList = array();
        $matchDatas = array();

        $latest = array();
        $popular = array();

        if ($this->connDatas['connected']) {
            $pageList = $this->page->list();
            $web = $this->welcome->generalData();
            $website_robot = ($web->website_robot) ? (int) $web->website_robot : 0;
            $web_image = ($web->web_image) ? $web->web_image : $web_image;

            $leagueData = League::where('url', $slug)->first();
            $article = $this->article->articleDetail($slug);

            if ($leagueData) {
                // return redirect()->route('league', ['url' => $slug]);
                return $this->league($leagueData, $slug);
            } else if ((int) $article->id > 0) {
                $isArticle = true;

                $web_image = $article->showImage;
                $seo_title = $article->seo_title;
                $seo_description = $article->seo_description;

                $latest = $this->article->articleLatest();
                $popular = $this->article->articlePopular();
            } else {
                return redirect()->route('index');

                $isPage = true;
                $page = $this->page->pageData($slug);
                $page = $this->page->replaceKeyWithValue($page);

                $seo_title = ($page->seo_title) ? trim($page->seo_title) : '';
                $seo_description = ($page->seo_description) ? trim($page->seo_description) : '';
                $page_topic = ($page->title) ? trim($page->title) : '';
                $page_description = ($page->description) ? trim($page->description) : '';
                $page_detail = ($page->detail) ? trim($page->detail) : '';

                $matchDatas = $this->welcome->filterMatchDatas($page);
            }

            $widget = $this->widget->widgetData();
            $social = $this->widget->socialData();
        } else {
            return redirect()->route('index');
        }

        $datas = array();

        if ($isArticle) {
            $datas = array(
                'pages' => $pageList,
                'website_robot' => $website_robot,
                'web_image' => $web_image,
                'slug' => $slug,
                'seo_title' => $seo_title,
                'seo_description' => $seo_description,
                'article' => $article,
                'widget' => $widget,
                'latest' => $latest,
                'popular' => $popular,
                'social' => $social);
    
            return view('frontend/article-detail', $datas);
        } else {
            $datas = array(
                'pages' => $pageList,
                'website_robot' => $website_robot,
                'web_image' => $web_image,
                'seo_title' => $seo_title,
                'seo_description' => $seo_description,
                'page_topic' => $page_topic,
                'page_description' => $page_description,
                'page_detail' => $page_detail,
                'not_found_message' => $notFoundMessage,
                'widget' => $widget,
                'social' => $social,
                'matchDatas' => $matchDatas);

            return view('frontend/page', $datas);
        }

    }

    public function league($leagueData = '', $slug)
    {
        $connectionStatus = 0;
        $website_robot = 0;
        $web_image = asset('images/logo.png');
        $seoTitle = '';
        $seoDescription = '';
        $topContent = '';
        $bottomContent = '';

        $matches = array();

        $tabList = array();
        $years = '';

        $leagueName = ''; // matches
        $leagueShortName = '';
        $leagueScraperName = ''; // scraper
        $leagueIds = array(Date('Y') => 0);

        if ($this->connDatas['connected']) {
            $connectionStatus = 1;
            $web = $this->welcome->generalData();
            $website_robot = ($web->website_robot) ? (int) $web->website_robot : 0;
            $web_image = ($web->web_image) ? $web->web_image : $web_image;

            if ($leagueData) {
                $leagueName = $leagueData->name_th; // matches
                $leagueShortName = $leagueData->short_name;
                $leagueScraperName = $leagueData->name_en; // scraper
    
                if ($leagueData->years) {
                    $leagueIds = (array) json_decode($leagueData->years);
                }

                $pageContent = $this->onPage->pageContentById($leagueData->onpage_id);
                $seoTitle = ($pageContent['seo_title']) ? $pageContent['seo_title'] : $seoTitle;
                $seoDescription = ($pageContent['seo_description']) ? $pageContent['seo_description'] : $seoDescription;
                $topContent = $pageContent['top'];
                $bottomContent = $pageContent['bottom'];
    
                $allMatches = $this->welcome->matchDatas();
                $matches['records'] = $this->common->filterMatches($allMatches['records'], $leagueName);
                $matches['total'] = count($matches['records']);
    
                $tabList = $this->mockup->playerYearList();
                $years = implode(',', $tabList);
                
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
                    'matches' => $matches,
                    'this_host' => $thisHost,
                    'connection_status' => $connectionStatus,
                    'tab_list' => $tabList,
                    'years' => $years,
                    'hist_one' => Date('Y-m-d', strtotime("-1 days")),
                    'hist_two' => Date('Y-m-d', strtotime("-2 days")),
                    'hist_three' => Date('Y-m-d', strtotime("-3 days")),
                    'hist_four' => Date('Y-m-d', strtotime("-4 days")),
                    'hist_five' => Date('Y-m-d', strtotime("-5 days")),
                    'hist_one_text' => $this->common->showWeekDate(strtotime("-1 days")),
                    'hist_two_text' => $this->common->showWeekDate(strtotime("-2 days")),
                    'hist_three_text' => $this->common->showWeekDate(strtotime("-3 days")),
                    'hist_four_text' => $this->common->showWeekDate(strtotime("-4 days")),
                    'hist_five_text' => $this->common->showWeekDate(strtotime("-5 days")),
                    'adv_one' => Date('Y-m-d', strtotime("+1 days")),
                    'adv_two' => Date('Y-m-d', strtotime("+2 days")),
                    'adv_three' => Date('Y-m-d', strtotime("+3 days")),
                    'adv_four' => Date('Y-m-d', strtotime("+4 days")),
                    'adv_five' => Date('Y-m-d', strtotime("+5 days")),
                    'adv_one_text' => $this->common->showWeekDate(strtotime("+1 days")),
                    'adv_two_text' => $this->common->showWeekDate(strtotime("+2 days")),
                    'adv_three_text' => $this->common->showWeekDate(strtotime("+3 days")),
                    'adv_four_text' => $this->common->showWeekDate(strtotime("+4 days")),
                    'adv_five_text' => $this->common->showWeekDate(strtotime("+5 days")),
                    'league_name' => $leagueName,
                    'league_short_name' => $leagueShortName,
                    'league_scraper_name' => $leagueScraperName,
                    'league_url' => $slug,
                    'league_id' => $leagueIds[Date('Y')]
                );

                return view('frontend/leagues/index', $respDatas);
            } else {
                return redirect()->route('index');
            }
        } else {
            echo 'Please check DB Connection.';
        }
    }

    public function leaguePage($slug = '', $page_url = '')
    {
        $connectionStatus = 0;
        $website_robot = 0;
        $web_image = asset('images/logo.png');
        $seoTitle = '';
        $seoDescription = '';
        $topContent = '';
        $bottomContent = '';

        $leagueUrl = $slug;
        $leagueName = ''; // matches
        $leagueShortName = '';
        $leagueScraperName = ''; // scraper
        $leagueIds = array(Date('Y') => 0);
        
        $latest = array();
        $tabListStructure = array();
        $months = '';
        $years = '';
        $widgetList = array();

        if ($this->connDatas['connected']) {
            $connectionStatus = 1;
            $web = $this->welcome->generalData();
            $website_robot = ($web->website_robot) ? (int) $web->website_robot : 0;
            $web_image = ($web->web_image) ? $web->web_image : $web_image;

            $leagueData = League::where('url', $leagueUrl)->first();

            if ($leagueData) {
                $leagueName = $leagueData->name_th; // matches
                $leagueShortName = $leagueData->short_name;
                $leagueScraperName = $leagueData->name_en; // scraper
    
                if ($leagueData->years) {
                    $leagueIds = (array) json_decode($leagueData->years);
                }
    
                $subpageData = LeagueSubpage::where('league_url', $slug)->where('page_url', $page_url)->first();
    
                if ($subpageData) {
                    $pageContent = $this->onPage->pageContentById($subpageData->onpage_id);
                    $seoTitle = ($pageContent['seo_title']) ? $pageContent['seo_title'] : $seoTitle;
                    $seoDescription = ($pageContent['seo_description']) ? $pageContent['seo_description'] : $seoDescription;
                    $topContent = $pageContent['top'];
                    $bottomContent = $pageContent['bottom'];
                }
            } else {
                return redirect()->route('index');
            }

            if ($page_url == 'odds') {
                $tabListStructure = $this->ctDetail->leagueOdds($leagueScraperName);
            } else if ($page_url == 'program') {
                $tabList = $this->mockup->programMonthList();
                $tabListStructure = $this->mockup->textAndNumMonth($tabList);
                $months = implode(',', $tabList);
            } else if ($page_url == 'result') {
                $tabList = $this->mockup->resultMonthList();
                $tabListStructure = $this->mockup->textAndNumMonth($tabList);
                $months = implode(',', $tabList);
            } else if ($page_url == 'transfer') {
                $tabListStructure = $this->mockup->playerYearList();
                $years = implode(',', $tabListStructure);
            }

            $latest = $this->article->articleLatest();
            $widgetList = LeagueDecoration::leagueSubPage($leagueScraperName, $page_url);
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
            'connection_status' => $connectionStatus,
            'latest' => $latest,
            'widget_list' => $widgetList,
            'league_name' => $leagueName,
            'league_short_name' => $leagueShortName,
            'league_scraper_name' => $leagueScraperName,
            'league_url' => $leagueUrl,
            'league_ids' => json_encode($leagueIds),
            'league_id' => $leagueIds[Date('Y')]
        );

        if ($page_url == 'result' || $page_url == 'program' || $page_url == 'odds' || $page_url == 'transfer') {
            $respDatas['tab_list'] = $tabListStructure;
        }

        if ($page_url == 'result' || $page_url == 'program') {
            $respDatas['months'] = $months;
        }

        if ($page_url == 'transfer') {
            $respDatas['years'] = $years;
        }

        return view('frontend/leagues/' . $page_url, $respDatas);
    }
}

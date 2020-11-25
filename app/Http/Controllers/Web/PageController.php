<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\CheckConnectionController;
use App\Http\Controllers\API\Front\WelcomeController as WelcomeAPI;
use App\Http\Controllers\API\Front\WidgetController as WidgetAPI;
use App\Http\Controllers\API\Front\PageController as PageAPI;
use App\Http\Controllers\API\MockupController;

class PageController extends Controller
{
    private $connDatas;
    private $welcome;
    private $widget;
    private $page;
    private $mockup;

    public function __construct()
    {
        $this->mockup = new MockupController();
        $conn = new CheckConnectionController();
        $this->connDatas = $conn->checkConnServer();

        if ($this->connDatas['connected']) {
            $this->welcome = new WelcomeAPI();
            $this->widget = new WidgetAPI();
            $this->page = new PageAPI();
        }
    }

    public function index($slug = '')
    {
        $pageList = array();
        $website_robot = 0;
        $web_image = asset('images/logo.png');
        $seo_title = '';
        $seo_description = '';
        $page_topic = '';
        $page_description = '';
        $page_detail = '';
        $notFoundMessage = '';
        $widget = $this->mockup->widgetMock();
        $social = $this->mockup->socialMock();
        $matchDatas = array();

        if ($this->connDatas['connected']) {
            $web = $this->welcome->generalData();
            $website_robot = ($web->website_robot) ? (int) $web->website_robot : 0;
            $web_image = ($web->web_image) ? $web->web_image : $web_image;
            $widget = $this->widget->widgetData();
            $social = $this->widget->socialData();
            $pageList = $this->page->list();

            if ($slug == '') {
                // ราคาบอล
            } else {
                //...
            }

            $page = $this->page->pageData($slug);

            if ($page) {
                $page = $this->page->replaceKeyWithValue($page);

                $seo_title = ($page->seo_title) ? trim($page->seo_title) : '';
                $seo_description = ($page->seo_description) ? trim($page->seo_description) : '';
                $page_topic = ($page->title) ? trim($page->title) : '';
                $page_description = ($page->description) ? trim($page->description) : '';
                $page_detail = ($page->detail) ? trim($page->detail) : '';

                $notFoundMessage = $this->page->makeMessage($page->page_name);

                $matchDatas = $this->welcome->filterMatchDatas($page);

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
            } else {
                abort(404);
                // next
            }
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
}

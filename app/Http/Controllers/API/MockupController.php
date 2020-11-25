<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\CommonController;
use \stdClass;

class MockupController extends Controller
{
    private $common;

    public function __construct()
    {
        $this->common = new CommonController();
    }

    public function widgetMock()
    {
        $widget = new stdClass();
        $widget->homepage_block_one = null; // 1
        $widget->homepage_block_two = null; // 2
        $widget->top_navigation_left = null; // 3
        $widget->top_navigation_right = null; // 4
        $widget->main_footer = null; // 5
        $widget->sub_footer = null; // 6
        $widget->top_banner = null; // 7
        $widget->floating_left = null; // 8
        $widget->floating_right = null; // 9
        $widget->floating_bottom = null; // 10
        $widget->footer_banner = null; // 11
        $widget->home_top_banner = null; // 12
        $widget->home_aside_banner = null; // 13
        $widget->home_between_match = null; // 14
        $widget->home_between_match_two = null; // 15
        $widget->home_after_match = null; // 16
        $widget->home_after_article = null; // 17
        $widget->article_sidebar = null; // 18

        return $widget;
    }

    public function socialMock()
    {
        $social = new stdClass();
        $social->social_facebook = '';
        $social->social_twitter = '';
        $social->social_linkedin = '';
        $social->social_youtube = '';
        $social->social_instagram = '';
        $social->social_pinterest = '';

        return $social;
    }

    public function playerYearList()
    {
        $yearStart = (int) Date('Y') - 9;
        $years = range(Date('Y'), $yearStart);

        return $years;
    }

    public function resultMonthList()
    {
        $months = [];
        $monthNum = 0;

        for ($i = 1; $i <= (int) Date('m'); $i++) {
            $monthNum = $i;

            $months[] = $monthNum;
        }

        return $months;
    }

    public function programMonthList()
    {
        $months = [];
        $monthNum = 0;

        for ($i = (int) Date('m'); $i < ((int) Date('m') + 12); $i++) {
            if ($i <= 12) {
                $monthNum = $i;
            }
            //  else {
            //     $monthNum = $i - 12;
            // }

            $months[] = $monthNum;
        }

        return $months;
    }

    public function textAndNumMonth($monthList) {
        $monthStructure = array();

        if (count($monthList) > 0) {
            foreach($monthList as $monthNum) {
                $monthStructure[$monthNum] = $this->common->monthTextThFromNum($monthNum);
            }
        }

        return $monthStructure;
    }

    public function dayList($date = '')
    {
        $fstActive = ($date == Date('Y-m-d', strtotime("-2 days"))) ? 'active' : '';
        $sActive = ($date == Date('Y-m-d', strtotime("-1 days"))) ? 'active' : '';
        $tActive = ($date == Date('Y-m-d')) ? 'active' : '';
        $forActive = ($date == Date('Y-m-d', strtotime("+1 days"))) ? 'active' : '';
        $fiftActive = ($date == Date('Y-m-d', strtotime("+2 days"))) ? 'active' : '';

        $first = array('n' => Date('D', strtotime("-2 days")), 'd' => Date('j M', strtotime("-2 days")), 'a' => $fstActive, 'link' => route('livescore', Date('Y-m-d', strtotime("-2 days"))));
        $second = array('n' => Date('D', strtotime("-1 days")), 'd' => Date('j M', strtotime("-1 days")), 'a' => $sActive, 'link' => route('result'));
        $today = array('n' => 'Today', 'd' => Date('j M'), 'a' => $tActive, 'link' =>  route('livescore'));
        $fourth = array('n' => Date('D', strtotime("+1 days")), 'd' => Date('j M', strtotime("+1 days")), 'a' => $forActive, 'link' =>  route('livescore', Date('Y-m-d', strtotime("+1 days"))));
        $fifth = array('n' => Date('D', strtotime("+2 days")), 'd' => Date('j M', strtotime("+2 days")), 'a' => $fiftActive, 'link' =>  route('livescore', Date('Y-m-d', strtotime("+2 days"))));

        $dayListDatas = array($first, $second, $today, $fourth, $fifth);

        return $dayListDatas;
    }
}

<?php

namespace App\Http\Controllers\API\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\WidgetOrder;
use App\Models\General;
use \stdClass;

class WidgetController extends Controller
{
    public function widgetData()
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

        $hbo = WidgetOrder::where('position_dom_id', 'homepage_block_one');
        if ($hbo->count() > 0) {
            $widget->homepage_block_one = $hbo->get();
        } // 1
        $hbt = WidgetOrder::where('position_dom_id', 'homepage_block_two');
        if ($hbt->count() > 0) {
            $widget->homepage_block_two = $hbt->get();
        } // 2
        $tnl = WidgetOrder::where('position_dom_id', 'top_navigation_left');
        if ($tnl->count() > 0) {
            $widget->top_navigation_left = $tnl->get();
        } // 3
        $tnl = WidgetOrder::where('position_dom_id', 'top_navigation_left');
        if ($tnl->count() > 0) {
            $widget->top_navigation_left = $tnl->get();
        } // 4
        $mf = WidgetOrder::where('position_dom_id', 'main_footer');
        if ($mf->count() > 0) {
            $widget->main_footer = $mf->get();
        } // 5
        $sf = WidgetOrder::where('position_dom_id', 'sub_footer');
        if ($sf->count() > 0) {
            $widget->sub_footer = $sf->get();
        } // 6
        $tb = WidgetOrder::where('position_dom_id', 'top_banner');
        if ($tb->count() > 0) {
            $widget->top_banner = $tb->get();
        } // 7
        $fl = WidgetOrder::where('position_dom_id', 'floating_left');
        if ($fl->count() > 0) {
            $widget->floating_left = $fl->get();
        } // 8
        $fr = WidgetOrder::where('position_dom_id', 'floating_right');
        if ($fr->count() > 0) {
            $widget->floating_right = $fr->get();
        } // 9
        $fb = WidgetOrder::where('position_dom_id', 'floating_bottom');
        if ($fb->count() > 0) {
            $widget->floating_bottom = $fb->get();
        } // 10
        $fbn = WidgetOrder::where('position_dom_id', 'footer_banner');
        if ($fbn->count() > 0) {
            $widget->footer_banner = $fbn->get();
        } // 11
        $htb = WidgetOrder::where('position_dom_id', 'home_top_banner');
        if ($htb->count() > 0) {
            $widget->home_top_banner = $htb->get();
        } // 12
        $hab = WidgetOrder::where('position_dom_id', 'home_aside_banner');
        if ($hab->count() > 0) {
            $widget->home_aside_banner = $hab->get();
        } // 13
        $hbm = WidgetOrder::where('position_dom_id', 'home_between_match');
        if ($hbm->count() > 0) {
            $widget->home_between_match = $hbm->get();
        } // 14
        $hbmt = WidgetOrder::where('position_dom_id', 'home_between_match_two');
        if ($hbmt->count() > 0) {
            $widget->home_between_match_two = $hbmt->get();
        } // 15
        $ham = WidgetOrder::where('position_dom_id', 'home_after_match');
        if ($ham->count() > 0) {
            $widget->home_after_match = $ham->get();
        } // 16
        $haa = WidgetOrder::where('position_dom_id', 'home_after_article');
        if ($haa->count() > 0) {
            $widget->home_after_article = $haa->get();
        } // 17
        $as = WidgetOrder::where('position_dom_id', 'article_sidebar');
        if ($as->count() > 0) {
            $widget->article_sidebar = $as->get();
        } // 18
        
        return $widget;
    }

    public function socialData()
    {
        $social = new stdClass();
        $social->social_facebook = '';
        $social->social_twitter = '';
        $social->social_linkedin = '';
        $social->social_youtube = '';
        $social->social_instagram = '';
        $social->social_pinterest = '';

        $scData = General::find(1);
        if ($scData) {
            $social = $scData;
        }

        return $social;
    }
}

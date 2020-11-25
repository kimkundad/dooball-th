<?php

namespace App\Http\Controllers\API\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Key;
use \stdClass;

class PageController extends Controller
{

    public function list()
    {
        $pages = array();

        $mnData = Page::where('show_on_menu', 1)->where('active_status', 1);
    
        if ($mnData->count() > 0) {
            $pages = $mnData->get();
        }

        return $pages;
    }

    public function keyInfo($keyType = '', $pageData = null, $keyName = '')
    {
        $keyVal = '';

        if ($pageData) {
            $keyData = Key::where('key_type', $keyType)->where('key_type_id', $pageData->id)->where('key_name', $keyName)->first();
            if ($keyData) {
                if (trim($keyData->key_value)) {
                    $keyVal = trim($keyData->key_value);
                }
            }
        }

        return $keyVal;
    }

    public function pageData($slug = '')
    {
        $page = null;

        if (trim($slug)) {
            $page = Page::where('slug', trim($slug))->first();
        }

        return $page;
    }

    public function pageContentBySlug($slug = '')
    {
        $page = new stdClass();
        $page->id = 0;
        $page->page_name = '';
        $page->slug = '';
        $page->page_condition = '';
        $page->league_name = '';
        $page->team_name = '';
        $page->title = '';
        $page->description = '';
        $page->detail = '';
        $page->seo_title = '';
        $page->seo_description = '';
        $page->count_view = 0;
        $page->count_like = 0;
        $page->user_id = 0;
        $page->show_on_menu = 0;
        $page->active_status = 0;

        $slugData = Page::where('slug', $slug)->first();

        if ($slugData) {
            $page = $slugData;
            // $page->detail = $this->filterRealContent($page->detail, 'under_match');
        }

        return $page;
    }

    public function filterRealContent($detail = null, $idName = '')
    {
        $value = '';
        $pattern = '/<p[^>]*id=\"' . $idName . '\">(.*?)<\\/p>/si';

        if ($detail) {
            preg_match($pattern, trim($detail), $output);
            $value = (array_key_exists(1, $output)) ? $output[1] : '';
        }

        return $value;
    }

    // $pageData = null
    public function makeMessage($page_name = null)
    {
        $retMessage = '';

        // if ($pageData) {
            // $name = ($pageData->page_name) ? trim($pageData->page_name) : '';
            $name = ($page_name) ? trim($page_name) : '';
            if ($name) {
                $retMessage = str_replace("ดูบอล", "", $name);
            }
        // }

        return $retMessage;
    }
    
    public function replaceKeyWithValue($page = null)
    {
        $retPage = $page;

        // --- start key filter --- //
        $home_team = $this->keyInfo('page', $page, 'home_team');
        $away_team = $this->keyInfo('page', $page, 'away_team');
        $key_date = $this->keyInfo('page', $page, 'key_date');
        $key_time = $this->keyInfo('page', $page, 'key_time');
        $key_program = $this->keyInfo('page', $page, 'key_program');
        // --- end key filter --- //

        if ($home_team) {
            $retPage->title = preg_replace('/home_team/', $home_team, $retPage->title);
            $retPage->description = preg_replace('/home_team/', $home_team, $retPage->description);
            $retPage->seo_title = preg_replace('/home_team/', $home_team, $retPage->seo_title);
            $retPage->seo_description = preg_replace('/home_team/', $home_team, $retPage->seo_description);
        }

        if ($away_team) {
            $retPage->title = preg_replace('/away_team/', $away_team, $retPage->title);
            $retPage->description = preg_replace('/away_team/', $away_team, $retPage->description);
            $retPage->seo_title = preg_replace('/away_team/', $away_team, $retPage->seo_title);
            $retPage->seo_description = preg_replace('/away_team/', $away_team, $retPage->seo_description);
        }

        if ($key_date) {
            $retPage->title = preg_replace('/key_date/', $key_date, $retPage->title);
            $retPage->description = preg_replace('/key_date/', $key_date, $retPage->description);
            $retPage->seo_title = preg_replace('/key_date/', $key_date, $retPage->seo_title);
            $retPage->seo_description = preg_replace('/key_date/', $key_date, $retPage->seo_description);
        }

        if ($key_time) {
            $retPage->title = preg_replace('/key_time/', $key_time, $retPage->title);
            $retPage->description = preg_replace('/key_time/', $key_time, $retPage->description);
            $retPage->seo_title = preg_replace('/key_time/', $key_time, $retPage->seo_title);
            $retPage->seo_description = preg_replace('/key_time/', $key_time, $retPage->seo_description);
        }

        if ($key_program) {
            $retPage->title = preg_replace('/key_program/', $key_program, $retPage->title);
            $retPage->description = preg_replace('/key_program/', $key_program, $retPage->description);
            $retPage->seo_title = preg_replace('/key_program/', $key_program, $retPage->seo_title);
            $retPage->seo_description = preg_replace('/key_program/', $key_program, $retPage->seo_description);
        }

        $retPage->title = str_replace(array('{', '}'), '', $retPage->title);
        $retPage->description = str_replace(array('{', '}'), '', $retPage->description);
        $retPage->seo_title = str_replace(array('{', '}'), '', $retPage->seo_title);
        $retPage->seo_description = str_replace(array('{', '}'), '', $retPage->seo_description);

        return $retPage;
    }
}

<?php

namespace App\Http\Controllers\API\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\OnPage;
use App\Models\Page;

class OnPageController extends Controller
{
    public function pageContentByCodeName($codeName = '')
    {
        $onpageData = OnPage::where('code_name', $codeName)->first();
        return $this->onpageInfo($onpageData);
    }

    public function pageContentById($id = '')
    {
        $onpageData = OnPage::where('id', $id)->first();
        return $this->onpageInfo($onpageData);
    }

    public function onpageInfo($onpageData = null)
    {
        $top = '';
        $bottom = '';
        $seoTitle = '';
        $seoDescription = '';

        if ($onpageData) {
            $topId = $onpageData->page_top;
            $bottomId = $onpageData->page_bottom;

            if ($topId) {
                $topData = Page::find($topId);
                $top = $topData->detail;
            }

            if ($bottomId) {
                $bottomData = Page::find($bottomId);
                $bottom = $bottomData->detail;
            }
            
            $seoTitle = ($onpageData->seo_title) ? trim($onpageData->seo_title) : '';
            $seoDescription = ($onpageData->seo_description) ? trim($onpageData->seo_description) : '';
        }

        return array('top' => $top, 'bottom' => $bottom, 'seo_title' => $seoTitle, 'seo_description' => $seoDescription);
    }
}

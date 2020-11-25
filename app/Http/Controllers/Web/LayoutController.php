<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\CheckConnectionController;
use App\Http\Controllers\API\Front\WelcomeController as WelcomeAPI;
use App\Http\Controllers\API\Front\PageController as PageAPI;
use Storage;

class LayoutController extends Controller
{
    private $connDatas;
    private $welcome;
    private $page;

    public function __construct()
    {
        $conn = new CheckConnectionController();
        $this->connDatas = $conn->checkConnServer();

        if ($this->connDatas['connected']) {
            $this->welcome = new WelcomeAPI();
            $this->page = new PageAPI();
        }
    }

    public function renderHeaderHtml(Request $request)
    {
        $protocol = (env('APP_ENV') === 'production') ? 'https://' : 'http://';
        $host = $request->getHttpHost();
        $url = $protocol . $host . '/header';
        // dd($url);
        $htmlContent = file_get_contents($url);
        Storage::disk('special')->put('header.html', $htmlContent);
        // dd($htmlContent);

        echo $htmlContent;
        echo    '<main>';
        echo        '<div class="container-sm">';
        echo            '--- some content ---';
        echo        '<div>';
        echo    '</main>';
        echo    '</div>';
        echo '</body>';
        echo '</html>';
    }
}

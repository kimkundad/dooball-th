<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\CommonController;
use App\Http\Controllers\API\LogFileController;
use App\Http\Controllers\API\Front\WelcomeController as WelcomeAPI;
use App\Http\Controllers\API\Front\OnPageController as OnPageAPI;
use App\Http\Controllers\API\MockupController;

use App\Http\Controllers\API\CheckConnectionController;

use App\Models\League;
use App\Models\Team;
use App\Models\OnPage;
use App\Models\Page;
use App\Models\HeadToHead;
use Illuminate\Support\Facades\DB;

class FootballController extends Controller
{
    private $common;
    private $welcome;
    private $onPage;
    private $mockup;
    private $rapidApiHost;

    private $connDatas;
    private $apiFootballHeaders;
    private $logAsFile;

    public function __construct()
    {
        $this->common = new CommonController();
        $this->welcome = new WelcomeAPI();
        $this->onPage = new OnPageAPI();
        $this->mockup = new MockupController();
		$this->logAsFile = new LogFileController;

        $conn = new CheckConnectionController();
        $this->connDatas = $conn->checkConnServer();

        $this->rapidApiHost = env('RapidAPI_Host');

        $this->apiFootballHeaders = array(
            'X-RapidAPI-Host' => env('RapidAPI_Host'),
            'X-RapidAPI-Key' => env('RapidAPI_Key')
        );
    }

    public function live($date = '')
    {
        $website_robot = 0;
        $web_image = asset('images/logo.png');
        $seoTitle = '';
        $seoDescription = '';
        $topContent = '';
        $bottomContent = '';

        if ($this->connDatas['connected']) {
            $web = $this->welcome->generalData();
            $website_robot = ($web->website_robot) ? (int) $web->website_robot : 0;
            
            $pageContent = $this->onPage->pageContentByCodeName('ผลบอลสด');
            $seoTitle = ($pageContent['seo_title']) ? $pageContent['seo_title'] : $seoTitle;
            $seoDescription = ($pageContent['seo_description']) ? $pageContent['seo_description'] : $seoDescription;
            $topContent = $pageContent['top'];
            $bottomContent = $pageContent['bottom'];
        }

        $date = ($date) ? $date : Date('Y-m-d');

        $dayList = $this->mockup->dayList($date);

        $domain = request()->getHttpHost();
        $own = env('APP_ENV');
        $httpHost = ($own == 'production') ? 'https://' : 'http://';
        $thisHost = $httpHost . $domain;

        $respDatas = array(
            'website_robot' => $website_robot,
            'web_image' => $web_image,
            'day_list' => $dayList,
            'seo_title' => $seoTitle,
            'seo_description' => $seoDescription,
            'top_content' => $topContent,
            'bottom_content' => $bottomContent,
            'this_host' => $thisHost
        );

        return view('frontend/livescore/live', $respDatas);
    }

    public function livescore($date = '')
    {
        $website_robot = 0;
        $web_image = asset('images/logo.png');
        $seoTitle = '';
        $seoDescription = '';
        $topContent = '';
        $bottomContent = '';
        $livescoreList = array();

        if ($this->connDatas['connected']) {
            $web = $this->welcome->generalData();
            $website_robot = ($web->website_robot) ? (int) $web->website_robot : 0;

            $pageContent = $this->onPage->pageContentByCodeName('ผลบอลสด');
            $seoTitle = ($pageContent['seo_title']) ? $pageContent['seo_title'] : $seoTitle;
            $seoDescription = ($pageContent['seo_description']) ? $pageContent['seo_description'] : $seoDescription;
            $topContent = $pageContent['top'];
            $bottomContent = $pageContent['bottom'];
        }

        $date = ($date) ? $date : Date('Y-m-d');

        $showDate = $this->common->showDate($date);

        $dayList = $this->mockup->dayList($date);

        $inputDate = $date;

        if (strtotime(Date("H:i:s")) < strtotime(Date("10:00:00"))) {
            $inputDate = Date('Y-m-d', strtotime($date . " -1 days"));
        }

        $domain = request()->getHttpHost();
        $own = env('APP_ENV');
        $httpHost = ($own == 'production') ? 'https://' : 'http://';
        $thisHost = $httpHost . $domain;

        $respDatas = array(
            'website_robot' => $website_robot,
            'web_image' => $web_image,
            'show_date' => $showDate,
            'input_date' => $inputDate,
            'day_list' => $dayList,
            'seo_title' => $seoTitle,
            'seo_description' => $seoDescription,
            'top_content' => $topContent,
            'bottom_content' => $bottomContent,
            // 'call_new' => $callNew,
            'this_host' => $thisHost
        );

        return view('frontend/livescore/index', $respDatas);
    }

    public function resultScore()
    {
        $website_robot = 0;
        $web_image = asset('images/logo.png');
        $seoTitle = '';
        $seoDescription = '';
        $topContent = '';
        $bottomContent = '';

        if ($this->connDatas['connected']) {
            $web = $this->welcome->generalData();
            $website_robot = ($web->website_robot) ? (int) $web->website_robot : 0;

            $pageContent = $this->onPage->pageContentByCodeName('ผลบอลเมื่อคืน');
            $seoTitle = ($pageContent['seo_title']) ? $pageContent['seo_title'] : $seoTitle;
            $seoDescription = ($pageContent['seo_description']) ? $pageContent['seo_description'] : $seoDescription;
            $topContent = $pageContent['top'];
            $bottomContent = $pageContent['bottom'];
        }

        $date = Date('Y-m-d', strtotime("-1 days"));

        $showDate = $this->common->showDate($date);

        $dayList = $this->mockup->dayList($date);

        $inputDate = $date;

        if (strtotime(Date("H:i:s")) < strtotime(Date("10:00:00"))) {
            $inputDate = Date('Y-m-d', strtotime($date . " -1 days"));
        }

        $domain = request()->getHttpHost();
        $own = env('APP_ENV');
        $httpHost = ($own == 'production') ? 'https://' : 'http://';
        $thisHost = $httpHost . $domain;

        $respDatas = array(
            'website_robot' => $website_robot,
            'web_image' => $web_image,
            'show_date' => $showDate,
            'input_date' => $inputDate,
            'day_list' => $dayList,
            'seo_title' => $seoTitle,
            'seo_description' => $seoDescription,
            'top_content' => $topContent,
            'bottom_content' => $bottomContent,
            'this_host' => $thisHost
        );

        return view('frontend/livescore/result', $respDatas);
    }

    public function headToHead($fixtureId = 0)
    {
        $website_robot = 0;
        $web_image = asset('images/logo.png');
        $lName = '';
        $seoTime = '';
        $homeTeamName = '';
        $awayTeamName = '';

        $callNew = false;
        $notFoundInDb = false;

        if ($this->connDatas['connected']) {
            $web = $this->welcome->generalData();
            $website_robot = ($web->website_robot) ? (int) $web->website_robot : 0;

            $row = HeadToHead::where('fixture_id', $fixtureId)->first();

            if ($row) {
                $lName = $row->league_name;
                $seoTime = Date('H:i', $row->event_timestamp);
                $homeTeamName = $row->home_name;
                $awayTeamName = $row->away_name;
            } else {
                $callNew = true;
                $notFoundInDb = true;
            }
        } else {
            $callNew = true;
        }

        $this->logAsFile->logAsFile('debug-api-football.html', 'fixtures/id/' . $fixtureId . ': Start calling at: ' . Date('Y-m-d H:i:s'));

        if ($callNew) {
            $apiUrl = 'https://' . $this->rapidApiHost . '/v2/fixtures/id/' . $fixtureId;
            $response = \Unirest\Request::get($apiUrl, $this->apiFootballHeaders);

            if ($response->code == 200) {
                $datas = $response->body;

                if ($datas->api->results > 0) {
                    $headToHeadRow = $datas->api->fixtures;

                    if (array_key_exists(0, $headToHeadRow)) {
                        $apiRow = $headToHeadRow[0];

                        $lName = $apiRow->league->name;
                        $seoTime = Date('H:i', $apiRow->event_timestamp);
                        $homeTeamName = $apiRow->homeTeam->team_name;
                        $awayTeamName = $apiRow->awayTeam->team_name;

                        if ($notFoundInDb) {
                            // insert
                        }
                    }
                }
            } else {
                $this->logAsFile->logAsFile('debug-api-football.html', '<br>API: fixtures/id/' . $fixtureId, 'append');
                $this->logAsFile->logAsFile('debug-api-football.html', '<br>Code status: ' . $response->code . ' (' . $response->body->message . ')', 'append');
            }
        }

        // start onpage set
        $seoTitle = 'ผลบอล ' . $homeTeamName . ' พบ ' . $awayTeamName . ' คืนนี้';
        $seoDescription = 'ผลบอลสด ' . $lName . ' ข้อมูลสถิติบอล วิเคราะห์เกมส์ ' . $homeTeamName . ' พบ ' . $awayTeamName . ' แข่ง ' . $seoTime . ' คืนนี้ กราฟบอลไหล Lineup สถิติการพบกัน';

        $hOne = 'ผลบอล ' . $homeTeamName . ' พบ ' . $awayTeamName . ' ' . $lName;
        $hTwo = 'วิธีวิเคราะห์ ผลบอลสด ' . $homeTeamName . ' พบ ' . $awayTeamName;

        $bottomContent = 'ผลบอลสด <span class="text-bold text-white">' . $lName . '</span> ข้อมูลสถิติวิเคราะห์บอล ครอบคุมข้อมูลที่สำคัญในการวิเคราะห์บอล ทั้งหมด ไม่ว่าจะเป็น';
        $bottomContent .=    'สถิติทีมเหย้า ทีมเยือน สถิติการพับ 5 นัดหลังสุด ฟอร์มเจ้าบ้าน เฉพาะที่เป็นเจ้าบ้าน 5นัดหลังสุด ฟอร์มทีมเยือน';
        $bottomContent .=    'เฉพาะที่เยือน 5นัดหลังสุด สถิติตารางคะแนน ประจำลีก ทีมเหย้า ทีมเยือน สถิติดาวซัลโว ประจำทีม';
        $bottomContent .=    'สถิติผู้เล่นบาดเจ็บ ผู้เล่นพร้อมลงสนาม กราฟราคาบอลไหล รวมบทวิเคราะห์บอล ทำนายบอล';
        $bottomContent .=    'ทุกข้อมูลสำคัญสำหรับการ วิเคราะห์ผลบอลสด <span class="text-bold text-white">' . $homeTeamName . '</span> พบ <span class="text-bold text-white">' . $awayTeamName . '</span> ก่อนการแข่งขัน <span class="text-bold text-white">' . $seoTime . '</span> ';
        $bottomContent .=    'จะสรุปข้อมูลที่น่าสนใจ หลักๆนำไปใช้ประโยชน์ อะไรได้บ้าง';

        $ulLast = '<li>สถิติ ' . $homeTeamName . ' พบ ' . $awayTeamName . ' บอกได้ถึง ฟอร์มการแข่งในอดีต</li>';
        $ulLast .= '<li>สถิติผลบอล ' . $homeTeamName . ' ทีมเหย้า 5 นัด ที่เป็นเจ้าบ้านล่าสุด และสถิติผลบอล ' . $awayTeamName . ' ทีมเยือน 5</li>';
        $ulLast .= '<li>นัดที่เป็นทีมที่ไปเยือนล่าสุด สามารถใช้บอกได้ถึง ฟอร์มการเล่นในบ้านและนอกบ้าน</li>';
        $ulLast .= '<li>ความฟอร์มทีมของแนวรับแบะการทำประตูได้เป็นอย่างดี</li>';
        $ulLast .= '<li>สถิติตารางคะแนน ' . $lName . ' ลำดับในลีก สถิติลำดับเฉพาะที่เป็นเจ้าบ้าน ลำดับสถิติทีมเยือน</li>';
        $ulLast .= '<li>สถิติดาวซัลโวประจำลีก ' . $lName . ' ทีมไหนมีดาวซัลโว จำนวนลูกมากกกว่าย่อมได้เปรียบ</li>';
        $ulLast .= '<li>ข้อมูลผู้เล่นตัวจริงที่ลงสนาม ความพร้อมของทีม ' . $homeTeamName . ' และทีม ' . $awayTeamName . '</li>';
        $ulLast .= '<li>กราฟราคาบอลไหล ตั้งแต่ ราคาเปิด มีข้อมูลวิเคราะห์ให้เฉพาะลีกใหญ่</li>';
        $ulLast .= '<li>กราฟจะแสดงการเปลี่ยนแปลงของราคา ในแต่ละช่วงเวลา</li>';
        $ulLast .= '<li>รวมบทวิเคราะห์บอล ' . $homeTeamName . ' ' . $awayTeamName . ' จากทุกสำนัก</li>';

        $pLast = 'ข้อมูลทีมทั้งหมดนี้ถูกรวมมาไว้มีผลกับการวิเคราะห์ ราคาบอลสด <span class="text-bold text-white">' . $lName . '</span> <span class="text-bold text-white">' . $homeTeamName . '</span> พบ <span class="text-bold text-white">' . $awayTeamName . '</span> อย่างครบถ้วนแล้ว สามารถนำมาปรับใช้ ดูราคาบอลได้ครบทุกลีก';
        // end onpage set

        $dayList = $this->mockup->dayList(Date('Y-m-d'));

        $domain = request()->getHttpHost();
        $own = env('APP_ENV');
        $httpHost = ($own == 'production') ? 'https://' : 'http://';
        $thisHost = $httpHost . $domain;

        $respDatas = array(
            'website_robot' => $website_robot,
            'web_image' => $web_image,
            'seo_title' => $seoTitle,
            'seo_description' => $seoDescription,
            'fixture_id' => $fixtureId,
            'day_list' => $dayList,
            'h_one' => $hOne,
            'h_two' => $hTwo,
            'bottom_content' => $bottomContent,
            'ul_last' => $ulLast,
            'p_last' => $pLast,
            'this_host' => $thisHost
        );

        return view('frontend/livescore/h2h', $respDatas);
    }

    public function leagueTeams($name)
    {
        $connectionStatus = 0;
        $website_robot = 0;
        $web_image = asset('images/logo.png');
        $seoTitle = $name . ' ลิ้งดูบอลสด โปรแกรม ตาราง ผลบอล ราคาบอลไหล ล่าสุด คืนนี้';
        $seoDescription = 'ลิ้งดูบอลออนไลน์ ' . $name . ' ดูบอลสด ผลบอลย้อนหลัง โปรแกรมบอลล้วงหน้า ตารางบอล วิเคราะห์บอล จากกราฟราคาบอลไหล';
        $topContent = '';
        $bottomContent = '';

        $matches = array();
        $tabList = array();
        $years = '';
        // $widgetList = array();

        $realTeamName = preg_replace('/-/', ' ', $name);

        $teamId = 0;
        $thaiName = $realTeamName;
        $thaiMatchName = $realTeamName;
        $enName = $realTeamName;
        $thaiShortName = $realTeamName;
        $thaiLongName = $realTeamName;
        $searchDooball = '';
        $searchGraph = '';

        $leagueId = 0; // api_id
        $leagueIds = array(); // years
        $leagueApiName = ''; // api_name
        $leagueThisWebShortName = ''; // short_name
        $leagueThisWebLongName = ''; // long_name
        $leagueThaiName = ''; // matches: name_th
        $leagueScraperName = ''; // scraper: name_en
        $leagueUrl = ''; // url

        if ($this->connDatas['connected']) {
            $connectionStatus = 1;
            $web = $this->welcome->generalData();
            $website_robot = ($web->website_robot) ? (int) $web->website_robot : 0;
            $web_image = ($web->web_image) ? $web->web_image : $web_image;

            // --- start get team info --- //
            $teamInfoByName = Team::where('team_name_en', $realTeamName)->first();

            if ($teamInfoByName) {
                $teamId = $teamInfoByName->api_id;
                $thaiName = $teamInfoByName->team_name_th;
                $thaiMatchName = $thaiName; // for find match link
                $enName = $teamInfoByName->team_name_en;
                $thaiShortName = $teamInfoByName->short_name_th;
                $thaiLongName = $teamInfoByName->long_name_th;
                $searchDooball = $teamInfoByName->search_dooball;
                $searchGraph = $teamInfoByName->search_graph;
                $leagueUrl = $teamInfoByName->league_url;

                $seoTitle = $thaiName . ' ลิ้งดูบอลสด โปรแกรม ตาราง ผลบอล ราคาบอลไหล ล่าสุด คืนนี้';

                if ($leagueUrl) {
                    $leagueData = League::where('url', $leagueUrl)->first();

                    if ($leagueData) {
                        $leagueApiName = $leagueData->api_name; // api_name
                        $leagueThisWebShortName = $leagueData->short_name;
                        $leagueThisWebLongName = $leagueData->long_name;
                        $leagueThaiName = $leagueData->name_th; // matches
                        $leagueScraperName = $leagueData->name_en; // scraper

                        $seoDescription = 'ลิ้งดูบอลออนไลน์ ' . $thaiName . ' ' . $leagueThaiName . ' ดูบอลสด ผลบอลย้อนหลัง โปรแกรมบอลล้วงหน้า ตารางบอล วิเคราะห์บอล จากกราฟราคาบอลไหล';

                        if ($leagueData->years) {
                            $leagueIds = (array) json_decode($leagueData->years);
                            $leagueId = $leagueIds[Date('Y')];
                        }
                    }
                }

                if ((int) $teamInfoByName->onpage_id != 0) {
                    $onpageData = OnPage::find((int) $teamInfoByName->onpage_id);

                    if ($onpageData) {
                        // $seoTitle = $onpageData->seo_title;
                        // $seoDescription = $onpageData->seo_description;

                        if ((int) $onpageData->page_top != 0) {
                            $pageRow = Page::find((int) $onpageData->page_top);
                            if ($pageRow) {
                                $topContent = $pageRow->page_name;
                            }
                        }

                        if ((int) $onpageData->page_bottom != 0) {
                            $pageRow = Page::find((int) $onpageData->page_bottom);
                            if ($pageRow) {
                                $bottomContent = $pageRow->page_name;
                            }
                        }
                    }
                }
            }
            // --- end get team info --- //

            $tabList = $this->mockup->playerYearList();
            $years = implode(',', $tabList);
            // $widgetList = LeagueDecoration::leagueSubPage($leagueScraperName, 'team-' . $name); // widget for this team ?

            $allMatches = $this->welcome->matchDatas();
            $matches['records'] = $this->common->filterTeamInMatches($allMatches['records'], $thaiMatchName);
            $matches['total'] = count($matches['records']);
        }

        if (count($leagueIds) == 0) {
            $latestYear = (int) Date('Y');

            for ($y = 0; $y < 10; $y++) {
                $leagueIds[$latestYear] = 0;
                $latestYear--;
            }
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
            'matches' => $matches,
            'tab_list' => $tabList,
            'years' => $years,
            // 'widget_list' => $widgetList,
            'team_id' => $teamId,
            'team_name' => $name,
            'real_team_name' => $realTeamName,
            'thai_name' => $thaiName,
            'en_name' => $enName,
            'thai_short_name' => $thaiShortName,
            'thai_long_name' => $thaiLongName,
            // 'league_api_name' => $leagueApiName,
            'league_name' => $leagueThisWebShortName,
            'league_this_web_short_name' => $leagueThisWebShortName,
            'league_this_web_long_name' => $leagueThisWebLongName,
            'league_thai_name' => $leagueThaiName, // name_th
            'league_scraper_name' => $leagueScraperName, // scraper: name_en
            'search_dooball' => $searchDooball,
            'search_graph' => $searchGraph,
            'league_url' => $leagueUrl,
            'league_id' => $leagueId,
            'league_ids' => $leagueIds
        );

        return view('frontend/teams/index', $respDatas);
    }

    public function matches()
    {
        $apiUrl = 'https://www.ballzaa.com/linkdooball.php';

        $response = \Unirest\Request::get($apiUrl);

        if ($response->code == 200) {
            $matches = $response->body;

            preg_match("'<body>(.*?)</body>'si", $matches, $raws);

            $datas = $raws[1];
            $arr = explode('<div class="link_rows open-close">', $datas);
            // dd($arr);
            
            $last_ele = end($arr);
            array_shift($arr);
            array_pop($arr);

            $contents = array();
            if (count($arr) != 0) {
                foreach($arr as $content) {
                    $data = explode('<div class="desc">', $content);
                    $main = $data[0];
                    $link = $data[1];
                    $contents[] = array('main' => $main, 'link' => $link);
                }
            }

            $all = array();

            $last_content = explode('<div class="banner-right">', $last_ele);
            $last_data = array();
            if (array_key_exists(0, $last_content)) {
                $aaa = $last_content[0];
                $last_arr = explode('<div class="desc">', $aaa);
                if (array_key_exists(0, $last_arr)) {
                    $last_data['main'] = $last_arr[0];
                }
                if (array_key_exists(1, $last_arr)) {
                    $last_data['link'] = $last_arr[1];
                }

                $contents[] = $last_data;
            }

            $today = Date('Y-m-d');
            $tomorrow = Date('Y-m-d', strtotime("+1 days"));
            
            if (
                (strtotime(Date("Y-m-d H:i:s")) > strtotime(Date("Y-m-d 00:00:00")))
                &&
                (strtotime(Date("Y-m-d H:i:s")) <= strtotime(Date("Y-m-d 08:00:00")))
                ) {
                $today = Date('Y-m-d', strtotime("-1 days"));
                $tomorrow = Date('Y-m-d');
            }

            $timeList = array(strtotime('08:00:00'));
            $maxTime = 0;
            $theDay = '';

            $all = array();

            if (count($contents) != 0) {
                foreach($contents as $key => $row) {
                    if (array_key_exists('main', $row)) {
                        preg_match("'<div class=\"l_time\"><strong>(.*?)</strong></div>'si", $row['main'], $match);
                        $time = (array_key_exists(1, $match)) ? $match[1] : '';

                        $thisTime = strtotime($time);
                        if (count($timeList) > 0) {
                            $maxTime = max($timeList);
                        }

                        if ($thisTime >= $maxTime) {
                            $timeList[] = $thisTime;
                            $theDay = $today . ' ' . $time . ':00';
                        } else {
                            $theDay = $tomorrow . ' ' . $time . ':00';
                        }

                        $all[] = $this->filterDataBallZaa($row, ($key + 1), $theDay);
                    }
                }
            }

            return response()->json($all);
        } else {
            echo $response->code;
        }
    }

    public function filterDataBallZaa($row = array(), $key = 0, $theDay = '')
    {
        $id = '0' . $key;
        // $time = '';
        $home = '';
        $away = '';
        $home_logo = '';
        $away_logo = '';
        $program = '';

        if (array_key_exists('main', $row)) {
            preg_match("'<div class=\"l_time\"><strong>(.*?)</strong></div>'si", $row['main'], $match);
            // $time = (array_key_exists(1, $match)) ? $match[1] : '';

            preg_match("'<div class=\"l_team1\">(.*?)</div>'si", $row['main'], $match);
			$home = (array_key_exists(1, $match)) ? $match[1] : '';
			$home = strip_tags($home);
			$home = str_replace("\n", "", $home);

            preg_match("'<div class=\"l_team2\">(.*?)</div>'si", $row['main'], $match);
			$away = (array_key_exists(1, $match)) ? $match[1] : '';
			$away = strip_tags($away);
			$away = str_replace("\n", "", $away);

            // preg_match_all("'<div class=\"l_logo\">(.*?)</div>'si", $row['main'], $match_logo);
            // $home_logo = '';
            // $away_logo = '';

            // if (array_key_exists(1, $match_logo)) {
            //     $text = $match_logo[1];
            //     if (array_key_exists(0, $text)) {
            //         $home_logo = $text[0];
            //     }
            //     if (array_key_exists(1, $text)) {
            //         $away_logo = $text[1];
            //     }
            // }

            preg_match("'<div class=\"l_program\"><strong>(.*?)</strong></div>'si", $row['main'], $match);
            $program = (array_key_exists(1, $match)) ? $match[1] : '';
        }

        $links = array();

        if (array_key_exists('link', $row)) {
            preg_match_all("'<div class=\"link_right\">(.*?)</div>'si", $row['link'], $match_link);
            if (count($match_link) != 0) {
                if (array_key_exists(1, $match_link)) {
                    if (count($match_link[1]) != 0) {
                        $link_data = $match_link[1];
                        array_shift($link_data);

                        foreach($link_data as $k => $link) {
                            preg_match_all("'<a href=\"(.*?)\" target=\"(.*?)\" rel=\"(.*?)\">(.*?)</a>'si", $link, $match);
                            $name = '';
                            if (array_key_exists(4, $match)) {
                                if (array_key_exists(0, $match[4])) {
                                    $rawName = $match[4][0];
                                    preg_match("'<strong>(.*?)</strong>'si", $rawName, $n);
                                    $name = $n[1];
                                }
                            }
                            $url = '';
                            if (array_key_exists(1, $match)) {
                                if (array_key_exists(0, $match[1])) {
                                    $url = $match[1][0];
                                }
                            }
                            
                            $links[] = array(
                                "id" => ($k+1),
                                "name" => $name,
                                "url" => $url
                            );
                        }
                    }
                }
            }
        }

        $noi = array('id' => $id,
                        'program_name' => $program,
                        'kickoff_on' => $theDay,
                        'team_home_name' => trim($home),
                        'team_away_name' => trim($away),
                        // 'team_home_logo' => $home_logo,
                        // 'team_away_logo' => $away_logo,
                        'links' => $links);

        return $noi;
    }

    /*
    public function scheduledGame()
    {
        $website_robot = 0;
        $web_image = asset('images/logo.png');
        $seo_title = 'Scheduled Game';
        $seo_description = '';

        if ($this->connDatas['connected']) {
            $web = $this->welcome->generalData();
            $website_robot = ($web->website_robot) ? (int) $web->website_robot : 0;
        }

        $respDatas = array(
            'website_robot' => $website_robot,
            'web_image' => $web_image,
            'seo_title' => $seo_title,
            'seo_description' => $seo_description
        );

        return view('frontend/scheduled-game', $respDatas);
    }*/
}

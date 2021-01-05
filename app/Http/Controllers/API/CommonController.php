<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\LogFileController;
use App\User;
use App\Models\Article;
use App\Models\Media;
use App\Models\ContentDetail;
use App\Models\DirList;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use File;
use Storage;

class CommonController extends Controller
{
    private $ati_filter_match;
    private $logAsFile;

    public function __construct()
    {
        $this->ati_filter_match = array('id', 'match_name', 'match_time', 'match_seq', 'match_result', 'channels', 'bargain_price', 'more_detail', 'active_status', 'created_at', 'updated_at');
		$this->logAsFile = new LogFileController;
    }

    public function getTableColumns($table = '')
    {
        $filterList = array();
        $filterCols = Schema::getColumnListing($table);
        // return DB::getSchemaBuilder()->getColumnListing($table);
        $name = '';

        if (count($filterCols) > 0) {
            foreach($filterCols as $col) {
                if (! in_array($col, $this->ati_filter_match)) {
                    if ($col == 'home_team') {
                        $name = 'ทีมเจ้าบ้าน';
                    }
                    if ($col == 'away_team') {
                        $name = 'ทีมเยือน';
                    }
                    // if ($col == 'match_time') {
                    //     $name = 'เวลาแข่ง';
                    // }
                    $filterList[] = array('value' => $col, 'name' => $name);
                }
            }

            krsort($filterList);
        }

        $filterList[] = array('value' => 'key_date', 'name' => 'วันที่แข่ง');
        $filterList[] = array('value' => 'key_time', 'name' => 'เวลาแข่ง');
        $filterList[] = array('value' => 'key_program', 'name' => 'ชื่อโปรแกรมการแข่ง');

        return $filterList;
    }

    public function displayNameFromUsername($username = '')
    {
        $screenName = $username;

        if (trim($username)) {
            $user = User::where('username', trim($username))->first();
            if ($user) {
                $screenName = $user->screen_name;
            }
        }

        return $screenName;
    }

    public function displayNameFromId($id = 0)
    {
        $screenName = $id . ' (ID)';

        if ((int) $id != 0) {
            $user = User::where('id', (int) $id)->first();
            if ($user) {
                $screenName = $user->screen_name;
            }
        }

        return $screenName;
    }

    public function uploadImage($img_name, $alt, $witdh, $height, $ext, $team_path)
    {
        $media_id = 0;
        $sDateTime = Date('ymd-His');
        $file_name = (trim($img_name)) ? trim($img_name) . '-' . $sDateTime : $sDateTime;
        $file_name = $file_name . $ext;

        $media = new Media;
        $media->media_name = $file_name;
        $media->path = $team_path;
        $media->alt = trim($alt);
        $media->witdh = (int) $witdh;
        $media->height = (int) $height;
        $media->created_at = Date('Y-m-d H:i:s');
        $media_saved = $media->save();

        if ($media_saved) {
            $media_id = $media->id;
        }

        $media_data = array('id' => $media_id, 'file_name' => $file_name);
        return $media_data;
    }

    public function uploadWebsiteLogo($img_name, $alt, $witdh, $height, $ext, $team_path)
    {
        $media_id = 0;
        $file_name = trim($img_name);

        $media = new Media;
        $media->media_name = $file_name;
        $media->path = $team_path;
        $media->alt = trim($alt);
        $media->witdh = (int) $witdh;
        $media->height = (int) $height;
        $media->created_at = Date('Y-m-d H:i:s');
        $media_saved = $media->save();

        if ($media_saved) {
            $media_id = $media->id;
        }

        $media_data = array('id' => $media_id, 'file_name' => $file_name);
        return $media_data;
    }

    public function storeImage($file, $folder = '', $file_name = '')
    {
        $path = $file->storeAs($folder, $file_name);
        return $path;
    }

    public function deleteImageFromId($media_id = 0)
    {
        $del_file = false;
        if ($media_id != 0) {
            $media_data = Media::find($media_id);
            if ($media_data) {
                $del_path = $media_data->path . '/' . $media_data->media_name;
                $exists = Storage::exists($del_path);

                if (trim($media_data->media_name) && $exists) {
                    $del_file = Storage::delete($del_path);
                    if ($del_file) {
                        $del_db = DB::table('media')->where('id', $media_id)->delete();
                    }
                }
            }
        }

        return $del_file;
    }

    public function getImage($media_id = 0)
    {
        $img_path = '';
        $media_data = Media::find($media_id);

        if ($media_data) {
            $chk_path = $media_data->path . '/' . $media_data->media_name;
            $exists = Storage::exists($chk_path);

            if (trim($media_data->media_name) && $exists) {
                $pth = preg_replace('/\bpublic\b/', '', $media_data->path);
                $img_path = $pth . '/' . $media_data->media_name;
            }
        }

        return $img_path;
    }

    public function convertDateFormat($date = '', $dlmt = '-', $year_format = 'thai')
    {
        $ret_format = '0000-00-00';

        if (trim($date)) {
            $arr_date = preg_split('/[-\/]/', $date);
            if ($year_format == 'thai' && strlen($arr_date[0]) == 4) {
                $arr_date[0] = (int) $arr_date[0] + 543;
            }

            $ret_format = $arr_date[2] . $dlmt . $arr_date[1] . $dlmt . $arr_date[0];
        }

        $ret_format = ($dlmt == '/' && $date == '0000-00-00') ? '' : $ret_format;
        return $ret_format;
    }

    public function formToSqlDate($form_dash = '')
    {
        $sql_date = '0000-00-00';
        if (trim($form_dash) && (int) $form_dash > 0) {
            $exp_date = explode('/', $form_dash);
            // $y = ((int)$exp_date[0] - 543);
            $d = $exp_date[0];
            $m = $exp_date[1];
            $y = $exp_date[2];
            $sql_date = $y . '-' . $m . '-' . $d;
        }

        return $sql_date;
    }

    public function sqlToFormDate($sql_date_time = '')
    {
        $formDate = '00/00/0000';
        $sql_date = substr($sql_date_time, 0, 10);

        if (trim($sql_date)) {
            $exp_date = explode('-', trim($sql_date));
            // $y = ((int)$exp_date[0] - 543);
            $d = $exp_date[2];
            $m = $exp_date[1];
            $y = $exp_date[0];
            $formDate = $d . '/' . $m . '/' . $y;
        }

        return $formDate;
    }

    public function showDate($date = '', $format = 0)
    {
        $ret_date = $date;
        if (!in_array($date, array('', '0000-00-00'))) {
            list($year, $month, $day) = preg_split('/[-\/]/', $date);
            $thaiyear = $year + 543;
            $tmp_t_short = array("ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค.");
            $tmp_t_long = array("มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
            if ($format == 0) {
                $ret_date = (int) $day . ' ' . $tmp_t_long[$month - 1] . ' ' . $thaiyear;
            } else if ($format == 2) {
                $y = substr($thaiyear, 2, 2);
                $ret_date = (int) $day . ' ' . $tmp_t_short[$month - 1] . ' ' . $y;
            } else {
                $ret_date = (int) $day . ' ' . $tmp_t_short[$month - 1] . ' ' . $thaiyear;
            }

        } else {
            $ret_date = '';
        }

        return $ret_date;
    }

    public function showWeekDate($time = '')
    {
        $thai_day_arr = array("อาทิตย์", "จันทร์", "อังคาร", "พุธ", "พฤหัสบดี", "ศุกร์", "เสาร์");
        $thai_month_arr = array("0" => "", "1" => "มกราคม", "2" => "กุมภาพันธ์", "3" => "มีนาคม", "4" => "เมษายน", "5" => "พฤษภาคม", "6" => "มิถุนายน",
            "7" => "กรกฎาคม", "8" => "สิงหาคม", "9" => "กันยายน", "10" => "ตุลาคม", "11" => "พฤศจิกายน", "12" => "ธันวาคม");

        $dateText = "วัน" . $thai_day_arr[date("w", $time)];
        $dateText .= "ที่ " . date("j", $time);
        $dateText .= " เดือน" . $thai_month_arr[date("n", $time)];
        $dateText .= " พ.ศ." . (date("Y", $time) + 543);
        // $dateText.= "  ".date("H:i",$time)." น.";
        return $dateText;
    }

    public function showDayOnly($strtotime = '')
    {
        $thai_day_arr = array("อาทิตย์", "จันทร์", "อังคาร", "พุธ", "พฤหัสบดี", "ศุกร์", "เสาร์");
        $thai_month_arr = array("0" => "", "1" => "มกราคม", "2" => "กุมภาพันธ์", "3" => "มีนาคม", "4" => "เมษายน", "5" => "พฤษภาคม", "6" => "มิถุนายน",
            "7" => "กรกฎาคม", "8" => "สิงหาคม", "9" => "กันยายน", "10" => "ตุลาคม", "11" => "พฤศจิกายน", "12" => "ธันวาคม");

        $dateText = $thai_day_arr[date('w', $strtotime)];
        $dateText .= ',  ' . date('H:i', $strtotime);
        return $dateText;
    }

    public function showDateMonth($date = '', $format = 0)
    {
        $ret_date = $date;
        if (!in_array($date, array('', '0000-00-00'))) {
            list($year, $month, $day) = preg_split('/[-\/]/', $date);
            $thaiyear = $year + 543;
            $tmp_t_short = array("ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค.");
            $tmp_t_long = array("มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
            if ($format == 0) {
                $ret_date = (int) $day . ' ' . $tmp_t_long[$month - 1]; // . ' ' . $thaiyear;
            } else if ($format == 2) {
                $y = substr($thaiyear, 2, 2);
                $ret_date = (int) $day . ' ' . $tmp_t_short[$month - 1]; // . ' ' . $y;
            } else {
                $ret_date = (int) $day . ' ' . $tmp_t_short[$month - 1]; // . ' ' . $thaiyear;
            }
        } else {
            $ret_date = '';
        }

        return $ret_date;
    }

	public function monthNumFromText($monthText)
	{
        $monthTextList = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
        $monthNumList = array("01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");

        $key = array_search($monthText, $monthTextList);
        return $monthNumList[$key];
    }

	public function monthTextFromNum($monthNum)
	{
        $monthNumList = array("01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");
        $monthTextList = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");

        $key = array_search($monthNum, $monthNumList);
        return $monthTextList[$key];
    }

	public function monthTextThFromNum($monthNum)
	{
        $monthNumList = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12);
        $monthTextListTh = array("ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค.");
        // $tmp_t_long = array("มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");

        $key = array_search($monthNum, $monthNumList);

        return $monthTextListTh[$key];
    }

	public function leftToMatch($match_time = '')
	{
		$strStart = Date('Y-m-d H:i');
		$strEnd = substr($match_time, 0, 16);
		$strtt = strtotime($strEnd) - strtotime($strStart);
		$left = $strtt / 60;

		return $left;
	}

    public function paddNum($num = '')
    {
        return ((int) $num < 10) ? '0' . ((int) $num) : $num;
    }

    public function showDateTime($dateTime = '', $format = 1)
    {
        $ret = '';
        if ($dateTime != '' && $dateTime != '0000-00-00 00:00:00' && $dateTime != null) {
            $ret = $this->showDate(substr($dateTime, 0, 10), $format);
            $ret .= ' ' . substr($dateTime, 11, 5) . ' น.';
        }
        return $ret;
    }

    public function showMonthYearOnly($month = '', $format = 0)
    {
        $ret_month = '';
        if (trim($month)) {
            list($year, $month) = preg_split('/[-\/]/', $month);
            $thaiyear = $year + 543;
            $tmp_t_short = array("ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค.");
            $tmp_t_long = array("มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");

            if ($format == 0) {
                $ret_month = $tmp_t_long[$month - 1] . ' ' . $thaiyear;
            } else {
                $ret_month = $tmp_t_short[$month - 1] . ' ' . $thaiyear;
            }
        }

        return $ret_month;
    }

    public function skudTimeBeforeOneHr($fullDateTime = '', $separate = '')
    {
        $newTime = $fullDateTime;

        // --- start time -1 hr --- //
        $timeTextList = explode($separate, $fullDateTime);

        $monthDayText = '';

        if ($separate == '<br>') {
            $monthDayText = $timeTextList[0];
            $timeText = $timeTextList[1];
        } else if ($separate == ' ') {
            $monthTextOnly = $timeTextList[0];
            $dayTextOnly = (array_key_exists(1, $timeTextList)) ? $timeTextList[1] : Date('d');
            $monthDayText = $monthTextOnly . ' ' . $dayTextOnly;

            $timeText = (array_key_exists(2, $timeTextList)) ? $timeTextList[2] : Date('H:i');
        }

        $dateTime = Date('Y-m-d');

        if ($monthDayText) {
            $monthTextList = explode(' ', $monthDayText);
            $monthText = $monthTextList[0];
            $monthNum = $this->monthNumFromText($monthText);

            if (array_key_exists(1, $monthTextList)) {
                $day = $monthTextList[1];
                $dateNum = $this->paddNum($day);
                $dateTime = Date('Y-' . $monthNum . '-' . $dateNum);

                $dateTime .= ' ' . $timeText .':00';
                $timestamp = strtotime('-1 hours', strtotime($dateTime));

                $newDate = $this->monthTextFromNum(Date('m', $timestamp)) . ' ' . Date('d', $timestamp);
                $newTime = $newDate . $separate . Date('H:i', $timestamp);
            }
        }

        // --- end time -1 hr --- //

        return $newTime;
    }

    public function getTopHeadType($top_head = '')
    {
        $tphdType = 0;

        if ($top_head == 'Asian Handicap') {
            $tphdType = 1;
        } else if ($top_head == 'Over Under') {
            $tphdType = 2;
        } else if ($top_head == '1X2') {
            $tphdType = 3;
        }

        return $tphdType;
    }

    public function recursiveActiveDir()
    {
        $finalList = array();
        $totalMatch = 0;

        // --- start find final list --- //
        $curDatas = $this->realCurrentContent();
        $dirName = $curDatas['dirName'];

        if ($dirName) {
            $successList = $this->findSuccessList();

            $latestContent = $curDatas['latestContent'];
            $contentList = json_decode($latestContent);

            $resultList = $this->rawLeagueList($contentList, $dirName);
            $uniqueLeagueList = $resultList['unique'];
            $structureList = $resultList['structure'];
            $totalMatch = $resultList['total_match'];

            $finalList = $this->finalListStructure($uniqueLeagueList, $structureList, $successList);
        }
        // --- end find final list --- //

        if ($totalMatch == 0) {
            DB::table('ffp_list')->where('dir_name', $dirName)->update(array('except_this' => 1));
            return $this->recursiveActiveDir();
        } else {
            return array('final_list' => $finalList, 'dir_name' => $dirName);
        }
    }

    public function recursiveActiveDirCustom($filterName = '', $teamName = null)
    {
        $finalList = array();
        $totalMatch = 0;

        // --- start new algorithm --- //
        $mid_this_date = Date('Y-m-d 10:00:01');
        $ten_tomorrow_date = Date('Y-m-d 10:00:00', strtotime("+1 days"));

        if (strtotime(date("Y-m-d H:i:s")) < strtotime(date("Y-m-d 10:00:00"))) {
            $mid_this_date = Date('Y-m-d 12:00:00', strtotime("-1 days"));
            $ten_tomorrow_date = Date('Y-m-d 10:00:00');
        } else {
            // $mid_this_date = Date('Y-m-d H:i:s', strtotime("-150 minutes")); => ถ้าไม่เทียบกับ match_time แต่ดันไปเทียบกับ created_at ข้อมูลจะออกมาไม่กี่ row
        }
        // --- end new algorithm --- //

        $this->logAsFile->logAsFile('debug-ffp-team.html', 'Start at: ' . Date('Y-m-d H:i:s'));
        $this->logAsFile->logAsFile('debug-ffp-team.html', '<br>Get Between: ' . $mid_this_date . ' - ' . $ten_tomorrow_date, 'append');

        // --- start find final list --- //
        $curDatas = $this->realCurrentContentCustom($mid_this_date, $ten_tomorrow_date, $filterName, $teamName);
        $dirName = $curDatas['dirName'];

        if ($dirName) {
            $successList = $this->findSuccessList();

            $latestContent = $curDatas['latestContent'];
            $contentList = json_decode($latestContent);

            $resultList = $this->rawLeagueListCustom($contentList, $dirName, $successList, $filterName, $teamName);
            $uniqueLeagueList = $resultList['unique'];
            $structureList = $resultList['structure'];
            $totalMatch = $resultList['total_match'];

            $finalList = $this->finalListStructure($uniqueLeagueList, $structureList, $successList);
        }
        // --- end find final list --- //

        return array('final_list' => $finalList, 'dir_name' => $dirName);
    }

    public function scanExceptDir()
    {
        $exceptList = DirList::where('except_this', 1)->where('content', '<>', '[]');

        $this->logAsFile->logAsFile('except-this.html', 'Start at: ' . Date('Y-m-d H:i:s'));

        if ($exceptList->count() > 0) {
            $datas = $exceptList->take(50)->get();

            foreach($datas as $dir) {
                $dirName = $dir->dir_name;
                $latestContent = $dir->content;
                $contentList = json_decode($latestContent);

                $resultList = $this->rawLeagueList($contentList, $dirName);
                $totalMatch = $resultList['total_match'];

                $this->logAsFile->logAsFile('except-this.html', '<br>' . $dirName . ' : Total match: ' . $totalMatch, 'append');

                if ($totalMatch > 0) {
                    DB::table('ffp_list')->where('dir_name', $dirName)->update(array('except_this' => 0));
                    $this->logAsFile->logAsFile('except-this.html', '<br>Set ' . $dirName . ' to 0', 'append');
                }
            }
        }
    }

    public function realCurrentContent()
    {
        $dirName = '';
        $latestContent = '';

        $latestDir = DirList::select(['dir_name', 'content'])->where('scraping_status', '0')->where('except_this', '0')->where('content', '<>', '[]');
        $latestDir->orderBy('dir_name', 'desc');

        if ($latestDir->count() > 0) {
            $rows = $latestDir->get();
            $dirName = $rows[0]->dir_name;

            $detailDatas = ContentDetail::select('id')->where('dir_name', $dirName)->orderBy('code', 'asc');

            if ($detailDatas->count() > 0) {
                $latestContent = $rows[0]->content;
            }
        }

        return array('dirName' => $dirName, 'latestContent' => $latestContent);
    }

    public function realCurrentContentCustom($dateFrom = '', $dateTo = '', $filterName = '', $teamName = null)
    {
        $dirName = '';
        $latestContent = '';

        $latestDir = DirList::select(['dir_name', 'content'])->where('scraping_status', '0')->where('except_this', '0')->where('content', '<>', '[]');
        $latestDir->whereBetween('created_at', [$dateFrom, $dateTo]);
        $latestDir->orderBy('dir_name', 'desc');

        $this->logAsFile->logAsFile('debug-ffp-team.html', '<br>Yes filter between: ' . $dateFrom . ' - ' . $dateTo . ', Found: ' . $latestDir->count(), 'append');

        if ($latestDir->count() > 0) {
            $rows = $latestDir->get();

            foreach($rows as $data) {
                $dir = $data->dir_name;

                $detailDatas = ContentDetail::select('id')->where('dir_name', $dir)->where('league_name', $filterName);

                // $this->logAsFile->logAsFile('debug-ffp-team.html', '<br>Filter league: ' . $filterName, 'append');

                if ($teamName) {
                    $detailDatas->where('vs', 'like', '%' . $teamName . '%');
                    // $this->logAsFile->logAsFile('debug-ffp-team.html', '<br>Filter vs: ' . $teamName , 'append');
                }

                $detailDatas->orderBy('code', 'asc');

                if ($detailDatas->count() > 0) {
                    $dirName = $dir;
                    $latestContent = $rows[0]->content;

                    $this->logAsFile->logAsFile('debug-ffp-team.html', '<br>Dir name is: ' . $dirName , 'append');
                }
            }
        }

        return array('dirName' => $dirName, 'latestContent' => $latestContent);
    }

    public function findSuccessList() {
        $successList = array();

        $dayList = ContentDetail::select('dir_name')->groupBy('dir_name')->orderBy('dir_name', 'asc');

        if ($dayList->count() > 0) {
            $dirList = $dayList->get();
            foreach($dirList as $key => $dName) {
                $dlDatas = dirList::select('dir_name')->where('scraping_status', '0')->where('dir_name', $dName->dir_name);

                if ($dlDatas->count() > 0) {
                    $successList[] = $dName->dir_name;
                }
            }
        }

        return $successList;
    }

    public function rawLeagueList($contentList, $dirName = '')
    {
        $structureList = array();
        $uniqueLeagueList = array();
        $totalMatch = 0;

        if ($contentList) {
            if (count($contentList) > 0) {
                foreach($contentList as $data) {
                    $topHead = $data->top_head;
                    $leagueList = $data->datas;

                    $newLeagueList = array();

                    if (count($leagueList) > 0) {
                        foreach($leagueList as $league) {
                            $lName = $league->league_name;

                            if (! in_array($lName, $uniqueLeagueList)) {
                                $uniqueLeagueList[] = $lName;
                            }

                            $matchDatas = $league->match_datas;

                            $leftTeamGroup = array();
                            $matchList = array();

                            if (count($matchDatas) > 0) {
                                foreach($matchDatas as $match) {
                                    $findDetail = ContentDetail::select('content')->where('link', $match->link)->where('dir_name', $dirName)->first();

                                    if ($findDetail) {
                                        $content = $findDetail->content;

                                        if (trim($content) && trim($content) != '-- no content --') {
                                            $leftTeam = $match->left[0];
                                            $rightTeam = $match->right[0];
                                            $nameCheck = $leftTeam . '_' . $rightTeam;
                                            if (! in_array($nameCheck, $leftTeamGroup)) {
                                                $leftTeamGroup[] = $nameCheck;
                                                $matchList[] = (array) $match;
                                            }
                                        }

                                    }
                                }

                                foreach($matchList as $k => $match) {
                                    $leftTeam = $match['left'][0];
                                    $rightTeam = $match['right'][0];
                                    $nameCheck = $leftTeam . '_' . $rightTeam;
                                    foreach($matchDatas as $mData) {
                                        $lTeam = $mData->left[0];
                                        $rTeam = $mData->right[0];
                                        $nCheck = $lTeam . '_' . $rTeam;
                                        if ($nameCheck == $nCheck) {
                                            $matchList[$k]['left_list'][] = $mData->left;
                                            $matchList[$k]['right_list'][] = $mData->right;
                                        }
                                    }

                                    $matchList[$k]['detail_id'] = $this->detailIdFromLink($match['link'], $dirName);
                                    $totalMatch++;
                                }
                            }

                            $newLeagueList[] = array(
                                'league_name' => $lName,
                                'match_datas' => $matchList
                            );
                        }
                    }

                    $structureList[] = array('top_head' => $topHead, 'datas' => $newLeagueList);
                }
            }
        }

        return array('unique' => $uniqueLeagueList, 'structure' => $structureList, 'total_match' => $totalMatch);
    }

    public function rawLeagueListCustom($contentList = array(), $dirName = '', $successList = array(), $filterName = '', $teamSearch = null)
    {
        $structureList = array();
        $uniqueLeagueList = array();
        $totalMatch = 0;

        $this->logAsFile->logAsFile('debug-ffp-team.html', '<br>' . $dirName . ' : Total content list: ' . count($contentList), 'append');

        if (count($contentList) > 0) {
            foreach($contentList as $data) {
                $topHead = $data->top_head;
                $leagueList = $data->datas;

                $newLeagueList = array();

                if (count($leagueList) > 0) {
                    foreach($leagueList as $league) {
                        $lName = $league->league_name;

                        // if (strpos($lName, 'Premier League') !== false) {
                        if ($lName == $filterName) { // 'ENGLISH PREMIER LEAGUE',  'SPAIN LA LIGA'
                            if (! in_array($lName, $uniqueLeagueList)) {
                                $uniqueLeagueList[] = $lName;
                            }

                            $matchDatas = $league->match_datas;

                            $leftTeamGroup = array();
                            $matchList = array();
                            if (count($matchDatas) > 0) {
                                foreach($matchDatas as $match) {
                                    $findDetail = ContentDetail::select('content')->where('link', $match->link)->where('dir_name', $dirName)->first();

                                    if ($findDetail) {
                                        $content = $findDetail->content;

                                        if (trim($content) && trim($content) != '-- no content --') {
                                            $leftTeam = $match->left[0];
                                            $rightTeam = $match->right[0];
                                            $nameCheck = $leftTeam . '_' . $rightTeam;
                                            if (! in_array($nameCheck, $leftTeamGroup)) {
                                                $leftTeamGroup[] = $nameCheck;
                                                $matchList[] = (array) $match;
                                            }
                                        }

                                    }
                                }

                                foreach($matchList as $k => $match) {
                                    $leftTeam = $match['left'][0];
                                    $rightTeam = $match['right'][0];
                                    $nameCheck = $leftTeam . '_' . $rightTeam;
                                    foreach($matchDatas as $mData) {
                                        $lTeam = $mData->left[0];
                                        $rTeam = $mData->right[0];
                                        $nCheck = $lTeam . '_' . $rTeam;
                                        if ($nameCheck == $nCheck) {
                                            $matchList[$k]['left_list'][] = $mData->left;
                                            $matchList[$k]['right_list'][] = $mData->right;
                                        }
                                    }

                                    $matchList[$k]['detail_id'] = $this->detailIdFromLink($match['link'], $dirName);
                                    $scoreDatas = $this->scoreFromLink($match['link'], $successList);
                                    $matchList[$k]['score'] = $scoreDatas['asian']['score'];
                                    $totalMatch++;
                                }
                            }

                            $this->logAsFile->logAsFile('debug-ffp-team.html', '<br>Search: ' . $teamSearch . ' | count(matchList): '. count($matchList), 'append');

                            if ($teamSearch && (count($matchList) > 0)) {
                                $this->logAsFile->logAsFile('debug-ffp-team.html', '<br>Yes in if', 'append');

                                $teamMatchList = array();

                                foreach($matchList as $finalM) {
                                    $this->logAsFile->logAsFile('debug-ffp-team.html', '<br>All:: ' . $finalM['left'][0] . ' | ' . $finalM['right'][0] . ' | ' . $teamSearch, 'append');
                                    if ((trim($finalM['left'][0]) == trim($teamSearch)) || (trim($finalM['right'][0]) == trim($teamSearch))) {
                                        $this->logAsFile->logAsFile('debug-ffp-team.html', '<br>New:: ' . $finalM['left'][0] . ' | ' . $finalM['right'][0], 'append');
                                        $teamMatchList[] = $finalM;
                                    }
                                }

                                $matchList = $teamMatchList;
                            }

                            $newLeagueList[] = array(
                                'league_name' => $lName,
                                'match_datas' => $matchList
                            );
                        }
                    }
                }

                $structureList[] = array('top_head' => $topHead, 'datas' => $newLeagueList);
            }
        }

        return array('unique' => $uniqueLeagueList, 'structure' => $structureList, 'total_match' => $totalMatch);
    }

    public function detailIdFromLink($link = '', $dirName = '')
    {
        $detailId = '';
        $dtDatas = ContentDetail::select('id')->where('link', $link)->where('dir_name', $dirName);

        if ($dtDatas->count() > 0) {
            $rows = $dtDatas->get();
            $detailId = $rows[0]->id;
        }

        return $detailId;
    }

    public function finalListStructure($uniqueLeagueList = array(), $structureList = array(), $successList = array())
    {
        $finalList = array();

        if (count($uniqueLeagueList) > 0 && count($structureList) > 0) {
            foreach($uniqueLeagueList as $lName) {
                $matchList = array();
                $chkNameList = array();
                foreach($structureList as $data) {
                    $datas = $data['datas'];

                    if (count($datas) > 0) {
                        foreach ($datas as $lData) {
                            if ($lData['league_name'] == $lName) {
                                if (count($lData['match_datas']) > 0) {
                                    foreach($lData['match_datas'] as $mData) {
                                        $matchList[] = $mData;

                                        $leftTeam = $mData['left'][0];
                                        $rightTeam = $mData['right'][0];
                                        $nameCheck = $leftTeam . '_' . $rightTeam;
                                        if (! in_array($nameCheck, $chkNameList)) {
                                            $chkNameList[] = $nameCheck;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                $newMatchList = array();

                // Storage::put('file.html', '');

                if (count($chkNameList) > 0 && count($matchList) > 0) {
                    foreach($chkNameList as $name) {
                        // if ($name == 'e-Wolverhampton Wanderers_e-Arsenal') {
                            $detail_id = '';
                            $score = null;
                            $left = array();
                            $leftList = array();
                            $rightList = array();

                            foreach($matchList as $match) {
                                $leftTeam = $match['left'][0];
                                $rightTeam = $match['right'][0];
                                $nameCheck = $leftTeam . '_' . $rightTeam;

                                if ($nameCheck == $name) {
                                    $detail_id = $match['detail_id'];
                                    $score = (array_key_exists('score', $match)) ? $match['score'] : null;
                                    $left = $match['left'];
                                    $link = $match['link'];
                                    $mid = array_key_exists('mid', $match) ? $match['mid'] : [];
                                    $right = $match['right'];
                                    $time = $match['time'];

                                    $newTime = $this->skudTimeBeforeOneHr($time, '<br>');

                                    if (count($match['left_list']) > 0) {
                                        foreach($match['left_list'] as $lList) {
                                            $leftList[] = $lList;
                                        }
                                    }

                                    if (count($match['right_list']) > 0) {
                                        foreach($match['right_list'] as $rList) {
                                            $rightList[] = $rList;
                                        }
                                    }
                                }
                            }

                            $newMatchList[] = array(
                                'detail_id' => $detail_id,
                                'left' => $left,
                                'left_list' => $leftList,
                                'link' => $link,
                                'datas' => $this->graphFromLinkNew($link, $successList),
                                'score' => $score,
                                'mid' => $mid,
                                'right' => $right,
                                'right_list' => $rightList,
                                'time' => $time,
                                'date_time_before' => $newTime
                            );
                        // }
                    }
                }

                $finalList[] = array(
                        'league_name' => $lName,
                        'match_datas' => $newMatchList
                );
            }
        }

        return $finalList;
    }

    public function scoreFromLink($realLink, $successList)
    {
        $asianScore = null;
        $asianTeamContinue = 0;

        $this->logAsFile->logAsFile('debug-game.html', '<br>' . $realLink . ' [success list: ' . count($successList) . ']', 'append');

        if (count($successList) > 0) {
            $contentDatas = ContentDetail::select(['dir_name', 'content'])->where('link', $realLink)->where('content', 'like', '%Asian Handicap%');
            $contentDatas->whereNotNull('content');
            $contentDatas->whereIn('dir_name', $successList);
            $contentDatas->orderBy('dir_name', 'asc');

            $this->logAsFile->logAsFile('debug-game.html', '<br>found: ' . $contentDatas->count(), 'append');

            if ($contentDatas->count() > 0) {
                $datas = $contentDatas->get();

                $firstRow = $datas[0];
                $asianFirstDatas = $this->findTargetScore($firstRow);
                $asianScore = $asianFirstDatas['score'];
                $asianTeamContinue = $asianFirstDatas['team_cont'];
            }
        }

        $asianHandicap = array('score' => $asianScore, 'team_cont' => $asianTeamContinue);

        return array('asian' => $asianHandicap);
    }

    public function graphFromLinkNew($realLink, $successList)
    {
        // $realLink = '/euro/football/austria-2nd-liga/3016572/sk-austria-klagenfurt-vs-ksv-1919';
        $asianWater = -1;
        $asianLastWater = 0;
        $graphDatas = array();

        if (count($successList) > 0) {
            $contentDatas = ContentDetail::select(['dir_name', 'content'])->where('link', $realLink)->where('content', 'like', '%Asian Handicap%');
            $contentDatas->whereNotNull('content');
            $contentDatas->whereIn('dir_name', $successList);
            $contentDatas->orderBy('dir_name', 'asc');

            if ($contentDatas->count() > 0) {
                $datas = $contentDatas->get();

                foreach($datas as $val) {
                    $htmlContent = $val->content;
                    $dir_name = $val->dir_name;

                    $graphDatas[] = $this->arrangeGraphDatas($dir_name, $htmlContent);
                }
            }
        }

        $asianDatas = $this->graphLastArrange($graphDatas, 'asian');
        $teamSeries = $asianDatas['team_series'];

        if (count($teamSeries) > 0) {
            $keyFound = 0;

            foreach($teamSeries as $k => $team) {
                $datas = $team['data'];
                $zeroNum = $datas[0];

                if ($zeroNum > $asianWater && $zeroNum < 2 && $zeroNum != null && $zeroNum != 'null') {
                    $asianWater = $zeroNum;
                    $keyFound = $k;
                }
            }

            if ($asianWater == -1) {
                $asianWater = 3;

                foreach($teamSeries as $k => $team) {
                    $datas = $team['data'];
                    $zeroNum = $datas[0];

                    if ($zeroNum < $asianWater && $zeroNum != null && $zeroNum != 'null') {
                        $asianWater = $zeroNum;
                        $keyFound = $k;
                    }
                }
            }

            if (count($teamSeries[$keyFound]['data']) > 0) {
                foreach($teamSeries[$keyFound]['data'] as $k => $num) {
                    if ($num == null) {
                        unset($teamSeries[$keyFound]['data'][$k]);
                    }
                }
            }

            $asianLastWater = 0;

            $whatTheKey = count($teamSeries[$keyFound]['data']) - 1;

            if (array_key_exists($whatTheKey, $teamSeries[$keyFound]['data'])) {
                $asianLastWater = $teamSeries[$keyFound]['data'][$whatTheKey];
            }
        }

        $asianHandicap = array('water' => $asianWater, 'last_water' => $asianLastWater);

        return array('asian' => $asianHandicap);
    }

    public function arrangeGraphDatas($currentDir = '', $htmlContent = '')
    {
        $asianHandicap = array();
        $overUnder = array();
        $onePlusTwo = array();
        $countAsian = 0;
        $countOver = 0;
        $countOne = 0;

        if (trim($htmlContent) && trim($htmlContent) != '-- no content --') {
            $tableDatas = json_decode($htmlContent);
          //  dd($tableDatas);
            if (count($tableDatas) > 0) {
                foreach($tableDatas as $innerContent) {
                    $topHead = $innerContent->top_head;
                    if ($innerContent->datas) {
                        $matches = array();
                        if (count($innerContent->datas) > 0) {

                            foreach($innerContent->datas as $data) {
                                if ($data) {
                                    $row = (array) $data;
                                    $teamLeftName = '';
                                    $teamLeftRight = null;
                                    $teamRightName = '';
                                    $teamRightRight = null;

                                    if ($topHead == '1X2') {
                                        $teamDrawText = 'Draw';
                                        $teamDrawScore = null;
                                        dd($row);
                                        if (array_key_exists('left', $row)) {

                                            $teamLeftName = $row['left'][0];
                                            $teamLeftRight = $row['left'][1];
                                        }
                                        if (array_key_exists('mid', $row)) {
                                            $teamDrawText = $row['mid'][0];
                                            $teamDrawScore = $row['mid'][1];
                                        }
                                        if (array_key_exists('right', $row)) {
                                            $teamRightName = $row['right'][0];
                                            $teamRightRight = $row['right'][1];
                                        }

                                        $matches[] = array('team_name' => $teamLeftName, 'score' => 0, 'water' => (float) $teamLeftRight);
                                        $matches[] = array('team_name' => $teamDrawText, 'score' => 0, 'water' => (float) $teamDrawScore);
                                        $matches[] = array('team_name' => $teamRightName, 'score' => 0, 'water' => (float) $teamRightRight);
                                    } else {
                                        $teamLeftMid = null;
                                        $teamRightMid = null;

                                        if (array_key_exists('left', $row)) {
                                            $teamLeftName = $row['left'][0];
                                            $teamLeftMid = $row['left'][1];
                                            $teamLeftRight = $row['left'][2];
                                        }

                                        if (array_key_exists('right', $row)) {
                                            $teamRightName = $row['right'][0];
                                            $teamRightMid = $row['right'][1];
                                            $teamRightRight = $row['right'][2];
                                        }

                                        $teamName = '';
                                        $score = 0.00;
                                        $water = 0.00;

                                        if ($teamLeftMid && $teamRightMid) {
                                            if (((float) $teamLeftMid == 0.00 || (float) $teamLeftMid == 0.0 || (float) $teamLeftMid == 0) && ((float) $teamRightMid == 0.00 || (float) $teamRightMid == 0.0 || (float) $teamRightMid == 0)) {
                                                $teamName = $teamLeftName;
                                                $score = 0.00;
                                                $water = (float) $teamLeftRight;
                                            } else {
                                                if ((float) $teamLeftMid < (float) $teamRightMid) {
                                                    $teamName = $teamLeftName;
                                                    $score = (float) $teamLeftMid;
                                                    $water = (float) $teamLeftRight;
                                                } else {
                                                    $teamName = $teamRightName;
                                                    $score = (float) $teamRightMid;
                                                    $water = (float) $teamRightRight;
                                                }
                                            }
                                        } else {
                                            if ((float) $teamLeftRight < (float) $teamRightRight) {
                                                $teamName = $teamLeftName;
                                                $score = 0;
                                                $water = (float) $teamLeftRight;
                                            } else {
                                                $teamName = $teamRightName;
                                                $score = 0;
                                                $water = (float) $teamRightRight;
                                            }
                                        }

                                        $matches[] = array('team_name' => $teamName, 'score' => $score, 'water' => $water);
                                    }
                                }
                            }
                        }
                    }

                    if ($topHead == 'Asian Handicap') {
                        $asianHandicap = array('date_time' => $currentDir,
                                                'matches' => $matches);
                        $countAsian++;
                    }
                    if ($topHead == 'Over Under') {
                        $overUnder = array('date_time' => $currentDir,
                                                'matches' => $matches);
                        $countOver++;
                    }
                    if ($topHead == '1X2') {
                        $onePlusTwo = array('date_time' => $currentDir,
                                                'matches' => $matches);
                        $countOne++;
                    }
                }
            }
        }

        if ($countAsian == 0) {
            $asianHandicap = array('date_time' => $currentDir,
                                    'matches' => array());
        }
        if ($countOver == 0) {
            $overUnder = array('date_time' => $currentDir,
                                    'matches' => array());
        }
        if ($countOne == 0) {
            $onePlusTwo = array('date_time' => $currentDir,
                                    'matches' => array());
        }

        return array('asian' => $asianHandicap, 'over' => $overUnder, 'one' => $onePlusTwo);
    }

    public function graphLastArrange($graphDatas, $mode)
    {
        $timeList = array();
        $chkTeamNames = array();
        $teamSeries = array();
        // $teams = array();

        if (count($graphDatas) > 0) {
            foreach($graphDatas as $key => $graph) {
                $value = $graph[$mode];

                if (array_key_exists('date_time', $value)) {
                    $dTime = $value['date_time'];
                    if ($dTime) {
                        $timeList[] = $dTime;

                        if (count($value['matches']) > 0) {
                            foreach($value['matches'] as $val) {
                                $tempName = ($mode == 'one') ? $val['team_name'] : $val['team_name'] . ':' . $val['score'];
                                if (! in_array($tempName, $chkTeamNames)) {
                                    $chkTeamNames[] = $tempName;
                                    $teamSeries[] = array('name' => $tempName, 'data' => array());
                                }
                            }
                        }
                    }
                }
            }

            if (count($teamSeries) > 0) {
                foreach($teamSeries as $n => $team) {
                    $datas = array();
                    foreach($timeList as $tk => $time) {
                        $score = -999;
                        $water = -999;
                        foreach($graphDatas as $graph) {
                            $asian = $graph[$mode];
                            if (array_key_exists('matches', $asian)) {
                                foreach($asian['matches'] as $match) {
                                    $wdTeam = ($mode == 'one') ? $match['team_name'] : $match['team_name'] . ':' . $match['score'];
                                    if (($team['name'] == $wdTeam) && ($asian['date_time'] == $time)) {
                                        $score = $match['score'];
                                        $water = $match['water'];
                                    }
                                }
                            }
                        }

                        $score = ($score == -999) ? null : $score;
                        $water = ($water == -999) ? null : $water;
                        $datas[] = $water;
                    }

                    $teamSeries[$n]['data'] = $datas;
                }
            }
        }

        $timeKeyList = array();
        $timeKeyData = array();
        if (count($timeList) > 0 && count($teamSeries) > 0) {
            foreach($timeList as $key => $time) {
                $noTime = 0;
                foreach($teamSeries as $teamData) {
                    $data = $teamData['data'];
                    foreach($data as $k => $tm) {
                        if ($k == $key) {
                            if (!$tm) {
                                $noTime++;
                            }
                        }
                    }
                }

                if ($noTime == count($teamSeries)) {
                    if (! in_array($time, $timeKeyList)) {
                        $timeKeyList[] = $time;
                        $timeKeyData[] = array('k' => $key, 'v' => $time);
                    }
                }
            }

            if (count($timeKeyData) > 0) {
                foreach($timeList as $tm) {
                    foreach($timeKeyData as $tData) {
                        $rmKey = $tData['k'];
                        $rmVal = $tData['v'];
                        if ($rmVal == $tm) {
                            unset($timeList[$rmKey]);
                        }
                    }
                }

                foreach($teamSeries as $idx => $teamData) {
                    $data = $teamData['data'];
                    $newData = array();
                    foreach($data as $k => $tm) {
                        foreach($timeKeyData as $tData) {
                            $rmKey = $tData['k'];
                            if ($rmKey == $k) {
                                unset($teamSeries[$idx]['data'][$k]);
                            }
                        }
                    }
                }
            }
        }

        if (count($teamSeries) > 0) {
            foreach($teamSeries as $index => $tmData) {
                $data = $tmData['data'];
                $newData = array();
                if (count($data) > 0) {
                    foreach($data as $ntm) {
                        $newData[] = $ntm;
                    }
                }

                $teamSeries[$index]['data'] = $newData;
            }
        }

        $newTimeList = array();
        // $oldTimeList = array();
        if (count($timeList) > 0) {
            foreach($timeList as $time) {
                // $oldTimeList[] = $time;

                $dbFormat = substr($time, 0, 4) . '-' . substr($time, 4, 2) . '-' . substr($time, 6, 2);
                $timeInfo = substr($time, 9, 2) . ':' . substr($time, 11, 2); // 20200409-1856
                $newTimeList[] = $this->showDateMonth($dbFormat , 2) . ' ' . $timeInfo;
            }
        }

        // $min = $this->findMin($teamSeries);

        $name = ($mode == 'asian') ? 'Asian Handicap' : '';
        $name = ($mode == 'over') ? 'Over Under' : $name;
        $name = ($mode == 'one') ? '1X2' : $name;

        // , 'old_time_list' => $oldTimeList, 'teams' => $teams
        $datas = array('name' => $name, 'time_list' => $newTimeList, 'team_series' => $teamSeries);

        return $datas;
    }

    public function findTargetScore($rwData)
    {
        $foundAsian = 0;
        $asianScore = null;
        $asianWater = null;
        $asianTeamContinue = 0;

        $htmlContent = $rwData->content;

        // --- start skud score --- //
        if ($htmlContent) {
            $tableDatas = json_decode($htmlContent);

            if ($tableDatas) {
                if (count($tableDatas) > 0) {
                    foreach($tableDatas as $innerContent) {
                        $topHead = $innerContent->top_head;
                        if ($topHead == 'Asian Handicap') {
                            if ($innerContent->datas) {
                                if (count($innerContent->datas) > 0) {
                                    foreach($innerContent->datas as $data) {
                                        if ($data) {
                                            $row = (array) $data;

                                            $teamLeftMid = null;
                                            $teamRightMid = null;

                                            if (array_key_exists('left', $row)) {
                                                $teamLeftMid = $row['left'][1];
                                                $teamLeftRight = $row['left'][2];
                                            }

                                            if (array_key_exists('right', $row)) {
                                                $teamRightMid = $row['right'][1];
                                                $teamRightRight = $row['right'][2];
                                            }

                                            $score = 0.00;
                                            $water = 0.00;
                                            $teamCont = 0;

                                            if ($teamLeftMid && $teamRightMid) {
                                                if (((float) $teamLeftMid == 0.00 || (float) $teamLeftMid == 0.0 || (float) $teamLeftMid == 0) && ((float) $teamRightMid == 0.00 || (float) $teamRightMid == 0.0 || (float) $teamRightMid == 0)) {
                                                    $score = 0.00;
                                                    $water = (float) $teamLeftRight;
                                                    $teamCont = 1;
                                                } else {
                                                    if ((float) $teamLeftMid < (float) $teamRightMid) {
                                                        $score = (float) $teamLeftMid;
                                                        $water = (float) $teamLeftRight;
                                                        $teamCont = 1;
                                                    } else {
                                                        $score = (float) $teamRightMid;
                                                        $water = (float) $teamRightRight;
                                                        $teamCont = 2;
                                                    }
                                                }
                                            } else {
                                                if ((float) $teamLeftRight < (float) $teamRightRight) {
                                                    $score = 0;
                                                    $water = (float) $teamLeftRight;
                                                    $teamCont = 1;
                                                } else {
                                                    $score = 0;
                                                    $water = (float) $teamRightRight;
                                                    $teamCont = 2;
                                                }
                                            }

                                            if ($water > 0 && $water < 2) {
                                                if ($foundAsian == 0) {
                                                    $asianScore = $score;
                                                    $asianWater = $water;
                                                    $asianTeamContinue = $teamCont;
                                                    $foundAsian = 1;
                                                } else {
                                                    if ($water > $asianWater && $water < 2) {
                                                        $asianScore = $score;
                                                        $asianWater = $water;
                                                        $asianTeamContinue = $teamCont;
                                                    }
                                                }
                                            } else {
                                                // $water >= 2
                                                if (! $asianWater && ! $asianScore) {
                                                    // set to current water
                                                    $asianWater = $water;
                                                    $asianScore = $score;
                                                    $asianTeamContinue = $teamCont;
                                                } else {
                                                    if ($water < $asianWater) {
                                                        // near 2
                                                        $asianWater = $water;
                                                        $asianScore = $score;
                                                        $asianTeamContinue = $teamCont;
                                                    }
                                                }
                                            }

                                            // $html = '<ul>';
                                            // $html .=    '<li>';
                                            // $html .=        '<h4>' . $nameCheck . '</h4>';
                                            // $html .=        '<ul>';
                                            // $html .=            '<li>water: ' . $water . '</li>';
                                            // $html .=            '<li>score: ' . $score . '</li>';
                                            // $html .=            '<li>asianWater: ' . $asianWater . '</li>';
                                            // $html .=            '<li>asianScore: ' . $asianScore . '</li>';
                                            // $html .=        '</ul>';
                                            // $html .=    '</li>';
                                            // $html .= '</ul>';

                                            // Storage::append('file.html', $html . '<hr>');
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        // --- end skud score --- //

        return array('score' => $asianScore, 'water' => $asianWater, 'team_cont' => $asianTeamContinue);
    }

    public function findLeagueInfoFromDetailLink($dirName = '', $detailLink = '', $detailId = '')
    {
        $leagueName = '';
        $homeTeam = '';
        $awayTeam = '';
        $time = '';
        $found = false;

        if ($dirName && $detailLink) {
            $ffpListRow = DirList::where('dir_name', $dirName)->first();
            if ($ffpListRow) {
                $typeList = json_decode($ffpListRow->content);

                if ($typeList) {
                    if (count($typeList) > 0) {
                        foreach($typeList as $k => $val) {
                            // $topHead = $val->top_head;
                            if (count($val->datas) > 0 && $found == false) {
                                foreach($val->datas as $lgKey => $league) {
                                    if (count($league->match_datas) > 0 && $found == false) {
                                        foreach($league->match_datas as $match) {
                                            if ($match->link == $detailLink && $found == false) {
                                                $leagueName = $league->league_name;
                                                $homeTeam = $match->left[0];
                                                $awayTeam = $match->right[0];
                                                $time = $match->time;
                                                $found = true;
                                                break;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

            }
        }

        if ($found) {
            $eventTime = preg_replace('/<br>/', ' ', $time);

            $dtData = ContentDetail::find($detailId);
            if ($dtData) {
                $dtData->league_name = $leagueName;
                $dtData->vs = $homeTeam . '-' . $awayTeam;
                $dtData->event_time = $eventTime;
                $saved = $dtData->save();
            }
        }

        return array('league_name' => $leagueName, 'home_team' => $homeTeam, 'away_team' => $awayTeam);
    }

    public function filterMatches($matches, $filterName)
    {
        $premierMatches = array();

        if ($matches) {
            foreach($matches as $k => $value) {
                if ($value->match_name == $filterName) {
                    $premierMatches[] = $value;
                }
            }
        }

        return $premierMatches;
    }

    public function filterBasMatches($matches, $filterName)
    {
        $premierMatches = array();

        if ($matches) {
            foreach($matches as $k => $value) {
                $foundOrNot = strpos($value->match_name, $filterName);

                if ($foundOrNot != false) {
                    $premierMatches[] = $value;
                }
            }
        }

        return $premierMatches;
    }

    public function filterTeamInMatches($matches, $filterName)
    {
        $premierMatches = array();

        if ($matches) {
            foreach($matches as $k => $value) {
                if ($value->home_team == $filterName || $value->away_team == $filterName) {
                    $premierMatches[] = $value;
                }
            }
        }

        return $premierMatches;
    }

    public function groupLeagueLivescore($livescoreDatas = array(), $mode = '')
    {
        $names = array();
        $logos = array();

        $bigLeagueList = array();
        $smallLeagueList = array();

        if (count($livescoreDatas) > 0) {
            foreach($livescoreDatas as $row) {
                $leagueName = ($mode == 'new-call') ? $row->league->name : $row->league_name;
                $country = ($mode == 'new-call') ? $row->league->country : $row->country;
                $lCtry = ($leagueName == 'Premier League') ? $leagueName . ': ' . $country : $leagueName;

                if (! in_array($lCtry, $names)) {
                    $names[] = $lCtry;
                    $logos[] = ($mode == 'new-call') ? $row->league->logo : $row->league_logo_path;
                }
            }
        }

        if (count($names) > 0) {
            $leagueList = array();

            foreach($names as $k => $name) {
                $matches = array();
                foreach($livescoreDatas as $row) {
                    $leagueName = ($mode == 'new-call') ? $row->league->name : $row->league_name;
                    $country = ($mode == 'new-call') ? $row->league->country : $row->country;
                    $lCtry = ($leagueName == 'Premier League') ? $leagueName . ': ' . $country : $leagueName;

                    if ($lCtry == $name) {
                        $matches[] = $row;
                    }
                }

                $leagueList[] = array('league_name' => $name, 'league_logo' => $logos[$k], 'matches' => $matches);
            }

            foreach ($leagueList as $league) {
                if ($league['league_name'] == 'Premier League: England' || $league['league_name'] == 'Primera Division') {
                    $bigLeagueList[] = $league;
                } else {
                    $smallLeagueList[] = $league;
                }
            }
        }

        return array('big_league' => $bigLeagueList, 'small_league' => $smallLeagueList, 'mode' => $mode);
    }

    // --- start file manager --- //
    public function addDirectoryAPI(Request $request)
    {
        $total = 0;
        $message = '';
        $file_path = $request->directory_name;

        if (trim($file_path)) {
            File::makeDirectory(public_path().'/storage/' . trim($file_path), 0777, true);
            if (file_exists(public_path().'/storage/' . trim($file_path))) {
                $total = 1;
                $message = 'Add directory success.';
            }
        }

        $model = array('total' => $total, 'message' => $message);

        return response()->json($model);
    }

    public function deleteDirectoryAPI(Request $request)
    {
        $total = 0;
        $message = '';
        $file_path = $request->input('directory_name');

        File::deleteDirectory(public_path().'/storage/' . trim($file_path));

        if (! file_exists(public_path().'/storage/' . trim($file_path))) {
            $total = 1;
            $message = 'Add directory success.';
        }

        $model = array('total' => $total, 'message' => $message);

        return response()->json($model);
    }

    public function addFileAPI(Request $request)
    {
        $total = 0;
        $message = '';
        $file_path = $request->file_path;
        $need_db = $request->need_db;

        if ($request->hasFile('new_file')) {
            if ((int) $need_db == 1) {
                $media_data = $this->uploadImage(trim($request->img_name), '', 0, 0, $request->img_ext, $file_path);
                $media_id = $media_data['id'];
                if ($media_id) {
                    $this->storeImage($request->new_file, $file_path, $media_data['file_name']);

                    $total = 1;
                    $message = 'Add file success.';
                }
            } else {
                $this->storeImage($request->new_file, $file_path, trim($request->img_name) . $request->img_ext);

                $total = 1;
                $message = 'Save success';
            }

        }

        $model = array('total' => $total, 'message' => $message);

        return response()->json($model);
    }

    public function renameFileAPI(Request $request)
    {
        $directory = $request->input('directory');
        $old = $request->input('ren');
        $new = $request->input('to');
        $id = $request->input('id');

        //--------- root path ---------//
        $rpath = $_SERVER['DOCUMENT_ROOT'] . '/storage/' . $directory;
        $rpath = rtrim($rpath, '\\/');
        $rpath = str_replace('\\', '/', $rpath);
        //--------- root path ---------//

        $transaction_status = 0;
        $message = '';

        $old = self::fm_clean_path($old);
        $old = str_replace('/', '', $old);

        $new = self::fm_clean_path($new);
        $new = str_replace('/', '', $new);

        if (trim($old) && trim($new)) {
            if ($old != '' && $new != '') {
                if (!@is_dir($rpath)) {
                    $message = 'Root path ' . $rpath . ' not found!';
                } else {
                    if (self::fm_rename($rpath . '/' . $old, $rpath . '/' . $new)) {
                        $transaction_status = 1;
                        $message = 'เปลี่ยนชื่อจาก ' . self::fm_enc($old) .' เป็น ' . self::fm_enc($new);
                        if ($id != 0) {
                            $db_change = self::db_rename($id, $new);
                            $stt = ($db_change != 0) ? 'success.' : 'failed.';
                            $message .= '<br>(DB:' . $stt . ')';
                        }
                    } else {
                        $message = 'Error while renaming from ' . self::fm_enc($old) .' to ' . self::fm_enc($new);
                    }
                }
            } else {
                $message = 'กรุณาใส่ชื่อไฟล์';
            }
        }

        $model = array('transaction_status' => $transaction_status, 'message' => $message);

        return response()->json($model);
    }

    public function deleteFileAPI(Request $request)
    {
        // delete file / folder
        $directory = $request->input('directory');
        $filename = $request->input('filename');
        $media_id = $request->input('id');

        //--------- root path ---------//
        $rpath = $_SERVER['DOCUMENT_ROOT'] . '/storage/' . $directory;
        $rpath = rtrim($rpath, '\\/');
        $rpath = str_replace('\\', '/', $rpath);
        //--------- root path ---------//

        $transaction_status = 0;
        $message = '';

        $del = ($filename)? trim($filename) : '';

        if ($del) {
            $del = self::fm_clean_path($del);
            $del = str_replace('/', '', $del);
            if ($del != '..' && $del != '.') {
                if (!@is_dir($rpath)) {
                    $message = 'Root path ' . $rpath . ' not found!';
                } else {
                    $path = $rpath;

                    $is_dir = is_dir($path . '/' . $del);

                    if (self::fm_rdelete($path . '/' . $del)) {
                        $transaction_status = 1;
                        $message = $is_dir ? 'ลบโฟลเดอร์ <b>' . self::fm_enc($del) . '</b> สำเร็จ' : 'ลบไฟล์ <b>' . self::fm_enc($del) . '</b> สำเร็จ';

                        if ($media_id != 0) {
                            $db_change = self::db_delete($media_id);
                            $stt = ($db_change != 0) ? 'success.' : 'failed.';
                            $message .= '<br>(DB:' . $stt . ')';
                        }
                    } else {
                        $message = $is_dir ? 'ไม่สามารถลบโฟลเดอร์ <b>' . self::fm_enc($del) . '</b>' : 'ไม่สามารถลบไฟล์ <b>' . self::fm_enc($del) . '</b>';
                    }
                }
            } else {
                $message = 'Wrong file or folder name';
            }
        }

        $model = array('transaction_status' => $transaction_status, 'message' => $message);

        return response()->json($model);
    }

    public static function getImageFile($media_id = 0)
    {
        $img_path = '';
        $media_data = Media::find($media_id);

        if ($media_data) {
            $chk_path = $media_data->path . '/' . $media_data->media_name;
            $exists = Storage::exists($chk_path);

            if (trim($media_data->media_name) && $exists) {
                $pth = preg_replace('/\bpublic\b/', '', $media_data->path);
                $img_path = $pth . '/' . $media_data->media_name;
            }
        }

        return $img_path;
    }

    public static function findImageId($file_name = '')
    {
        $media_id = 0;

        $mData = Media::where('media_name', trim($file_name));
        if ($mData->count() > 0) {
            $media = $mData->first();
            $media_id = $media->id;
        }

        return $media_id;
    }

    public function sitemapTime($dbFormatTime = '')
    {
        $sitemapTime = '';

        if (trim($dbFormatTime)) {
            $dateDatas = explode(' ', $dbFormatTime);
            $sitemapTime = $dateDatas[0] . 'T' . $dateDatas[1] . '+07:00';
        }

        return $sitemapTime;
    }

    public function autoUpdateSitemap()
    {
        $sitemapList = array();

        $date = Date('Y-m-d');
        $time = Date('H:i:s');
        $lastMod = $date . 'T' . $time . '+07:00';

        $sitemapList[] = array('loc' => 'https://dooball-th.com', 'lastmod' => $lastMod, 'priority' => '1.00', 'changefreq' => 'Daily');
        $sitemapList[] = array('loc' => 'https://dooball-th.com/game', 'lastmod' => $lastMod, 'priority' => '1.00', 'changefreq' => 'Daily');
        $sitemapList[] = array('loc' => 'https://dooball-th.com/ราคาบอล', 'lastmod' => $lastMod, 'priority' => '1.00', 'changefreq' => 'Daily');

        $sitemapList[] = array('loc' => 'https://dooball-th.com/livescore', 'lastmod' => $lastMod, 'priority' => '1.00', 'changefreq' => 'Daily');
        $sitemapList[] = array('loc' => 'https://dooball-th.com/ผลบอลเมื่อคืน', 'lastmod' => $lastMod, 'priority' => '1.00', 'changefreq' => 'Daily');
        $sitemapList[] = array('loc' => 'https://dooball-th.com/ทีเด็ดบอล', 'lastmod' => $lastMod, 'priority' => '1.00', 'changefreq' => 'Daily');
        $sitemapList[] = array('loc' => 'https://dooball-th.com/ทีเด็ดบอลต่อ', 'lastmod' => $lastMod, 'priority' => '1.00', 'changefreq' => 'Daily');
        $sitemapList[] = array('loc' => 'https://dooball-th.com/ทีเด็ดบอลรอง', 'lastmod' => $lastMod, 'priority' => '1.00', 'changefreq' => 'Daily');
        $sitemapList[] = array('loc' => 'https://dooball-th.com/ทีเด็ดบอลเต็ง', 'lastmod' => $lastMod, 'priority' => '1.00', 'changefreq' => 'Daily');
        $sitemapList[] = array('loc' => 'https://dooball-th.com/ทีเด็ดบอลสเต็ป2', 'lastmod' => $lastMod, 'priority' => '1.00', 'changefreq' => 'Daily');
        $sitemapList[] = array('loc' => 'https://dooball-th.com/ทีเด็ดบอลสเต็ป3', 'lastmod' => $lastMod, 'priority' => '1.00', 'changefreq' => 'Daily');

        // --- start store sitemap from article --- //
        $articleUpdate = Article::where('active_status', 1)->select(['slug', 'created_at']);

        if ($articleUpdate->count() > 0) {
            foreach($articleUpdate->get() as $article) {
                $lastModAtc = $this->sitemapTime($article->created_at);
                $sitemapList[] = array('loc' => 'https://dooball-th.com/' . $article->slug, 'lastmod' => $lastModAtc, 'priority' => '1.00');
            }
        }
        // --- end store sitemap from article --- //

        // $sitemapList[] = array('loc' => 'https://dooball-th.com/blog', 'lastmod' => $lastMod, 'priority' => '1.00');

        // --- start store sitemap from tag-in-article --- //
        /*
        $tagUpdate = DB::table('article_tags')
                        ->leftJoin('articles', 'article_tags.article_id', '=', 'articles.id')
                        ->leftJoin('tags', 'article_tags.tag_id', '=', 'tags.id')
                        ->select(['tags.tag_name', 'tags.created_at'])
                        ->where('articles.active_status', 1)
                        ->groupBy('tag_id');

        if ($tagUpdate->count() > 0) {
            foreach($tagUpdate->get() as $tag) {
                $lastModTag = $this->sitemapTime($tag->created_at);
                $sitemapList[] = array('loc' => 'https://dooball-th.com/tags/' . $tag->tag_name, 'lastmod' => $lastModTag, 'priority' => '1.00', 'changefreq' => 'Daily');
            }
        }*/
        // --- end store sitemap from tag-in-article --- //

        // --- start store sitemap from ราคาบอล --- //
        /*
        $latestDir = DB::table('ffp_list_temp')->select(['content'])->whereNotNull('content')->orderBy('dir_name', 'desc');

        if ($latestDir->count() > 0) {
            $ffpList = $latestDir->get();

            foreach ($ffpList as $list) {
                $content = $list->content;
                $leagueList = json_decode($content);

                if (count($leagueList) > 0) {
                    foreach($leagueList as $league) {
                        if (count($league->match_datas) > 0) {
                            foreach($league->match_datas as $match) {
                                $sitemapList[] = array('loc' => 'https://dooball-th.com/ราคาบอลไหล?link=' . $match->detail_id, 'lastmod' => $lastMod);
                            }
                        }
                    }
                }
            }
        }*/
        // --- end store sitemap from ราคาบอล --- //

        // --- start looping all bet user link in game --- //
        /*
        $mnData = DB::table('bets')
            ->rightJoin('users', 'bets.user_id', '=', 'users.id')
            ->leftJoin('prediction_bets', 'bets.pre_bet_id', '=', 'prediction_bets.id')
			->select('bets.user_id', 'users.username', DB::raw('SUM((SELECT w.win_num FROM win_rate w WHERE w.match_continue=bets.match_continue AND w.match_result_status=prediction_bets.match_result_status)) AS total'));

        $mnData->where('bets.active_status', 1);
		$mnData->groupBy('bets.user_id');
        $mnData->orderByRaw('SUM((SELECT w.win_num FROM win_rate w WHERE w.match_continue=bets.match_continue AND w.match_result_status=prediction_bets.match_result_status)) desc');

		if ($mnData->count() > 0) {
            $allBetUser = $mnData->get();

            foreach($allBetUser as $bet) {
                $linkStats = ($bet->username)? 'bet-stats/' . $bet->username : 'bet-stats-user/' . $bet->user_id;
                $sitemapList[] = array('loc' => 'https://dooball-th.com/' . $linkStats, 'lastmod' => $lastMod);
            }
        }*/
        // --- end looping all bet user link in game --- //

        // --- start create sitemap --- //
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>';
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        if (count($sitemapList) > 0) {
            foreach($sitemapList as $site) {
                $sitemap .= '<url>';
                $sitemap .=     '<loc>' . $site['loc'] . '</loc>';
                $sitemap .=     '<lastmod>' . $site['lastmod'] . '</lastmod>';

                if (array_key_exists('priority', $site)) {
                    $sitemap .=     '<priority>' . $site['priority'] . '</priority>';
                }

                if (array_key_exists('changefreq', $site)) {
                    $sitemap .=     '<changefreq>' . $site['changefreq'] . '</changefreq>';
                }

                $sitemap .= '</url>';
            }
        }

        $sitemap .= '</urlset>';
        Storage::disk('sitemap')->put('sitemap.xml', $sitemap);
        // --- end create sitemap --- //

        return $sitemap;
    }

    public static function listAllFile($filePath = '')
    {
        $allFile = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($filePath),
            \RecursiveIteratorIterator::CHILD_FIRST
        );

        $ary_files = array();

        if ($allFile) {
            foreach ($allFile as $str_fullfilename => $cls_spl) {
                if ($cls_spl->isFile()) {
                    $ary_files[] = $str_fullfilename;
                }
            }
        }

        return $ary_files;
    }

    public static function listFileInDirectory($filePath = '')
    {
        $ary_files = self::listAllFile($filePath);

        $ary_files = array_combine(
            $ary_files,
            array_map( "filemtime", $ary_files )
        );

        arsort( $ary_files );
        $str_latest_file = key($ary_files);

        return $str_latest_file ;
    }

    public static function fm_rename($old = '', $new = '')
    {
        return (!file_exists($new) && file_exists($old)) ? rename($old, $new) : null;
    }

    public static function db_rename($id = 0, $new = '')
    {
        $db_change = 0;

        if ((int)$id != 0) {
            $media = Media::find($id);
            $media->media_name = trim($new);
            $saved = $media->save();

            if ($saved) {
                $db_change = 1;
            }
        }

        return $db_change;
    }

    public static function fm_rdelete($path)
    {
        if (is_link($path)) {
            return unlink($path);
        } elseif (is_dir($path)) {
            $objects = scandir($path);
            $ok = true;
            if (is_array($objects)) {
                foreach ($objects as $file) {
                    if ($file != '.' && $file != '..') {
                        if (!self::fm_rdelete($path . '/' . $file)) {
                            $ok = false;
                        }
                    }
                }
            }
            return ($ok) ? rmdir($path) : false;
        } elseif (is_file($path)) {
            return unlink($path);
        }
        return false;
    }

    public static function db_delete($media_id = 0)
    {
        $db_change = 0;
        $del_db = DB::table('media')->where('id', $media_id)->delete();

        if ($del_db) {
            $db_change = 1;
        }

        return $db_change;
    }

    public function fm_get_mime_type($file_path) {
        if (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $file_path);
            finfo_close($finfo);
            return $mime;
        } elseif (function_exists('mime_content_type')) {
            return mime_content_type($file_path);
        } elseif (!stristr(ini_get('disable_functions'), 'shell_exec')) {
            $file = escapeshellarg($file_path);
            $mime = shell_exec('file -bi ' . $file);
            return $mime;
        } else {
            return '--';
        }
    }

    public function fm_redirect($url, $code = 302) {
        header('Location: ' . $url, true, $code);
        exit;
    }

    public static function fm_clean_path($path = '')
    {
        $path = trim($path);
        $path = trim($path, '\\/');
        $path = str_replace(array('../', '..\\'), '', $path);
        if ($path == '..') {
            $path = '';
        }
        return str_replace('\\', '/', $path);
    }

    public function fm_get_parent_path($path) {
        $path = fm_clean_path($path);
        if ($path != '') {
            $array = explode('/', $path);
            if (count($array) > 1) {
                $array = array_slice($array, 0, -1);
                return implode('/', $array);
            }
            return '';
        }
        return false;
    }

    public static function fm_get_filesize($size = 0)
    {
        if ($size < 1000) {
            return sprintf('%s B', $size);
        } elseif (($size / 1024) < 1000) {
            return sprintf('%s KB', round(($size / 1024), 2));
        } elseif (($size / 1024 / 1024) < 1000) {
            return sprintf('%s MB', round(($size / 1024 / 1024), 2));
        } elseif (($size / 1024 / 1024 / 1024) < 1000) {
            return sprintf('%s GB', round(($size / 1024 / 1024 / 1024), 2));
        } else {
            return sprintf('%s TB', round(($size / 1024 / 1024 / 1024 / 1024), 2));
        }
    }

    public function fm_get_zif_info($path) {
        if (function_exists('zip_open')) {
            $arch = zip_open($path);
            if ($arch) {
                $filenames = array();
                while ($zip_entry = zip_read($arch)) {
                    $zip_name = zip_entry_name($zip_entry);
                    $zip_folder = substr($zip_name, -1) == '/';
                    $filenames[] = array(
                        'name' => $zip_name,
                        'filesize' => zip_entry_filesize($zip_entry),
                        'compressed_size' => zip_entry_compressedsize($zip_entry),
                        'folder' => $zip_folder
                        //'compression_method' => zip_entry_compressionmethod($zip_entry),
                    );
                }
                zip_close($arch);
                return $filenames;
            }
        }
        return false;
    }

    public static function fm_enc($text = '')
    {
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }

    public function fm_is_utf8($string) {
        return preg_match('//u', $string);
    }

    /*
    public static function fm_convert_win($filename = '')
    {
        if ((DIRECTORY_SEPARATOR == '\\') && function_exists('iconv')) {
            $filename = iconv('UTF-8', 'UTF-8//IGNORE', $filename);
        }

        return $filename;
    }*/
    // --- end file manager --- //
}

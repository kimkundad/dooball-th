<?php

namespace App\Http\Controllers\API\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\CommonController;
use App\Models\ContentDetail;
use App\Models\DirList;
use App\Models\FileDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Storage;

class ContentDetailController extends Controller
{
    private $common;
    public $attrPatterns;
    public $attrWithLinkPatterns;
    public $textInClasspatterns;
    public $textInClassReplaces;
    public $removeLink;
    public $rplBySharp;
    public $rplByScript;

    public function __construct()
    {
        $this->common = new CommonController();
        $this->attrPatterns = '#\s(id|title|onmouseover|onmouseout|border|cellpadding|cellspacing)="[^"]+"#';
        $this->attrWithLinkPatterns = '#\s(id|title|href|onmouseover|onmouseout|border|cellpadding|cellspacing)="[^"]+"#';
        $this->textInClasspatterns = array('/ Open/', '/ Closed/', '/<span class>/');
        $this->textInClassReplaces = array('', '', '/<span>/');
        $this->removeLink = "/(?<=href=(\"|'))[^\"']+(?=(\"|'))/";
        $this->rplBySharp = '#';
        $this->rplByScript = 'javascript:void(0);';

        ini_set('max_execution_time', 300);
        set_time_limit(300);
    }

    public function index()
    {
        $curDatas = $this->common->realCurrentContent();
        $dirName = $curDatas['dirName'];
        //dd($dirName);
        $finalList = array();
        $totalMatch = 0;

        if ($dirName) {
            // --- start find current ffp in temp --- //
            $latestDir = DB::table('ffp_list')->select(['content'])->where('dir_name', $dirName)->whereNotNull('content')->first();
            $finalList = json_decode($latestDir->content);
          /*  if ($latestDir) {
                $finalList = json_decode($latestDir->content);
                if (count($finalList) > 0) {
                    foreach($finalList as $league) {
                        $totalMatch += count($league->match_datas);
                    }
                }
            } */
        }
        $contentList = $finalList;

        if (count($contentList) > 0) {
                foreach($contentList as $data) {

                    $topHead = $data->top_head;
                    $leagueList = $data->datas;

                    $newLeagueList = array();

                    if (count($leagueList) > 0) {
                        foreach($leagueList as $league) {

                            $lName = $league->league_name;
                            $matchDatas = $league->match_datas;

                            $leftTeamGroup = array();
                            $matchList = array();

                            if (count($matchDatas) > 0) {
                                foreach($matchDatas as $match) {
                                    $leftTeam = $match->left[0];
                                    $rightTeam = $match->right[0];
                                    $nameCheck = $leftTeam . '_' . $rightTeam;
                                    if (! in_array($nameCheck, $leftTeamGroup)) {
                                        $leftTeamGroup[] = $nameCheck;
                                        $matchList[] = (array) $match;
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


        // --- end find current ffp in temp --- //

      /*  if ($totalMatch == 0) {
            $realFinalList = $this->common->recursiveActiveDir();
            $finalList = $realFinalList['final_list'];
            $dirName = $realFinalList['dir_name'];
        } */

        $domain = request()->getHttpHost();
        $mainDatas = array('raw_group' => $structureList, 'latest_dir' => $dirName, 'domain' => $domain);
        // 'raw_group' => $structureList,



        return response()->json($mainDatas);
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

    public function groupMatchlList($matchlList = array(), $dirName = '', $successList = array())
    {
        $matches = array();

        if (count($matchlList) > 0) {
            $names = array();
            foreach ($matchlList as $element) {
                if (! in_array($element['name'], $names)) {
                    $names[] = $element['name'];
                }
            }

            foreach ($names as $name) {
                $league_row = array();
                foreach ($matchlList as $ele) {
                    if ($ele['name'] == $name) {
                        $league_row[] = $ele['league_row'];
                    }
                }

                $matches[] = array('name' => $name, 'league_row' => $league_row);
            }

        }

        return $matches;
    }

    public function dataToGraph(Request $request)
    {
        // error_reporting(E_ALL);
        // ini_set('display_errors', 1);
        // header("Content-Type: text/plain");
        // date_default_timezone_set("Asia/Bangkok");

        $detailId = $request->detail_id;
        $datas = $this->dataToGraphWithDetailId($detailId);

        return response()->json($datas);
    }

    public function dataToGraphWithDetailId($detailId = 0)
    {
        $graphDatas = $this->prepareDataToPlotGraph($detailId);
        //dd($graphDatas); 210106003031

        $asianDatas = $this->common->graphLastArrange($graphDatas, 'asian');
        dd($asianDatas);
        $overDatas = $this->common->graphLastArrange($graphDatas, 'over');
        $oneDatas = $this->common->graphLastArrange($graphDatas, 'one');

        $datas = array('asian' => $asianDatas,
                        'over' => $overDatas,
                        'one' => $oneDatas);

        return $datas;
    }

    public function prepareDataToPlotGraph($id = '')
    {
        $graphDatas = array();
        $successList = array();

        $findDatas = ContentDetail::select(['link'])->where('id', $id);

        if ($findDatas->count() > 0) {
            $rows = $findDatas->get();
            $realLink = $rows[0]->link;

            // Storage::disk('local')->put('log.html', $realLink);

            $dayList = ContentDetail::select('dir_name')->groupBy('dir_name')->orderBy('dir_name', 'asc');
            $totalInner = $dayList->count();
            if ($totalInner > 0) {
                $dirList = $dayList->get();
                foreach($dirList as $key => $dName) {
                    $dlDatas = dirList::select('dir_name')->where('scraping_status', '0')->where('dir_name', $dName->dir_name);
                    if ($dlDatas->count() > 0) {
                        $successList[] = $dName->dir_name;
                    }
                }
            }
        }


        if (count($successList) > 0) {

            $contentDatas = ContentDetail::select(['content', 'dir_name'])->where('link', $realLink)->whereNotNull('content');
            $contentDatas->whereIn('dir_name', $successList);
            $contentDatas->orderBy('dir_name', 'asc');
            //dd($contentDatas);
            // $message = 'dir_name: ' . $day->dir_name . ', Total: ' . $contentDatas->count();
            // Log::info($message);

            if ($contentDatas->count() > 0) {
                foreach($contentDatas->get() as $val) {
                    $htmlContent = $val->content;
                    $dir_name = $val->dir_name;

                    $graphDatas[] = $this->common->arrangeGraphDatas($dir_name, $htmlContent);
                }
            }
        }
      //  dd($graphDatas);
        // return array('prepare_data' => $graphDatas, 'content' => $htmlContent);
        return $graphDatas;
    }

    public function skudTopHead($content = '')
    {
        preg_match('/<div class="SubHead">(.*?)<\/div>/s', $content, $datas);
        $dtHead = (array_key_exists(1, $datas)) ? $datas[1] : '';
        preg_match("'<span>(.*?)</span>'si", $dtHead, $rows);
        $topHead = (array_key_exists(1, $rows)) ? $rows[1] : '';

        return $topHead;
    }

    public function skudOutput($htmlContent = '')
    {
        $result = preg_replace($this->attrWithLinkPatterns, '', $htmlContent);
        $finalHtml = preg_replace($this->removeLink, $this->rplByScript, $result);
        $finalHtml = preg_replace($this->textInClasspatterns, $this->textInClassReplaces, $finalHtml);
        $patternToReplace = array('/title/', '/" >/', '/OddsL\//', '/\/Draw/');
        $replaceWith = array('', '/">/', 'OddsL', 'Draw');
        $htmlForGraph = preg_replace($patternToReplace, $replaceWith, $finalHtml);

        $MarketT = explode('class="MarketT">', $htmlForGraph);
        array_shift($MarketT);
        // echo count($MarketT);

        return $MarketT;
    }

    public function skudYen($innerContent, $topHead)
    {
        $debugDraw = '';
        $tds = explode('<tr>', $innerContent);
        // echo $innerContent;
        array_shift($tds);
        $tdDatas = $tds[0];
        $expTd = explode('</td>', $tdDatas);
        array_pop($expTd);
        // echo count($expTd);
        // debug($expTd);

        // start team left data
        $teamLeft = (array_key_exists(0, $expTd)) ? $expTd[0] : '';

        if ($topHead == '1X2') {
            // Log::info($teamLeft);
        }

        preg_match('/<span class="OddsL">(.*?)<\/span>/s', $teamLeft, $rwsLL);
        preg_match('/<span class="OddsM">(.*?)<\/span>/s', $teamLeft, $rwsLM);
        preg_match('/<span class="OddsR">(.*?)<\/span>/s', $teamLeft, $rwsLR);

        $teamLeftName = (array_key_exists(1, $rwsLL)) ? $rwsLL[1] : '';
        $teamLeftMid = (array_key_exists(1, $rwsLM)) ? $rwsLM[1] : '';
        $teamLeftRight = (array_key_exists(1, $rwsLR)) ? $rwsLR[1] : '';
        $teamDrawText = '';
        $teamDrawScore = '';
        $teamRightName = '';
        $teamRightMid = '';
        $teamRightRight = '';

        if (count($expTd) < 3) {
            // does not has draw
            $teamRight = (array_key_exists(1, $expTd)) ? $expTd[1] : '';

            // start team right data
            preg_match('/<span class="OddsL">(.*?)<\/span>/s', $teamRight, $rwsRL);
            preg_match('/<span class="OddsM">(.*?)<\/span>/s', $teamRight, $rwsRM);
            preg_match('/<span class="OddsR">(.*?)<\/span>/s', $teamRight, $rwsRR);

            $teamRightName = (array_key_exists(1, $rwsRL)) ? $rwsRL[1] : '';
            $teamRightMid = (array_key_exists(1, $rwsRM)) ? $rwsRM[1] : '';
            $teamRightRight = (array_key_exists(1, $rwsRR)) ? $rwsRR[1] : '';
            // end team right data
        } else {
            // has draw
            $teamDraw = (array_key_exists(1, $expTd)) ? $expTd[1] : '';
            $teamDraw = preg_replace('/title=\\"[^\\"]*\\"/', '', $teamDraw);
            // $teamDraw = preg_replace('/" >/', '">', $teamDraw);
            $teamDraw = preg_replace('/\s+/', '', $teamDraw);
            $teamDraw = preg_replace('/=""/', '', $teamDraw);
            $teamDraw = preg_replace('/spanclass/', 'span class', $teamDraw);
            $debugDraw = $teamDraw;

            // start team draw data
            preg_match('/<span class="OddsL">(.*?)<\/span>/s', $teamDraw, $rwsDL);
            preg_match('/<span class="OddsR">(.*?)<\/span>/s', $teamDraw, $rwsDR);

            $teamDrawText = (array_key_exists(1, $rwsDL)) ? $rwsDL[1] : '';
            $teamDrawScore = (array_key_exists(1, $rwsDR)) ? $rwsDR[1] : '';
            // end team draw data

            $teamRight = (array_key_exists(2, $expTd)) ? $expTd[2] : '';

            // start team right data
            preg_match('/<span class="OddsL">(.*?)<\/span>/s', $teamRight, $rwsRL);
            preg_match('/<span class="OddsM">(.*?)<\/span>/s', $teamRight, $rwsRM);
            preg_match('/<span class="OddsR">(.*?)<\/span>/s', $teamRight, $rwsRR);

            $teamRightName = (array_key_exists(1, $rwsRL)) ? $rwsRL[1] : '';
            $teamRightMid = (array_key_exists(1, $rwsRM)) ? $rwsRM[1] : '';
            $teamRightRight = (array_key_exists(1, $rwsRR)) ? $rwsRR[1] : '';
            // end team right data
        }

        $rowDatas = array('team_left' => $teamLeftName,
                            'score_left_mid' => $teamLeftMid,
                            'score_left_last' => $teamLeftRight,
                            'draw_text' => $teamDrawText,
                            'draw_score' => $teamDrawScore,
                            'team_right' => $teamRightName,
                            'score_right_mid' => $teamRightMid,
                            'score_right_last' => $teamRightRight,
                            'debugDraw' => $debugDraw);

        return $rowDatas;
    }

    public function dirList(Request $request)
    {
        $dirLatestList = array();

        $linkCode = $request->link;
        $dirName = substr($linkCode, 0, 13);
        $fileName = substr($linkCode, 14, (strlen($linkCode) - 1));

        $linkDatas = ContentDetail::select('link')->where('dir_name', $dirName)->where('file_name', $fileName);

        if ($linkDatas->count() > 0) {
            $links = $linkDatas->get();
            $link = $links[0]->link;

            $dirListDatas = ContentDetail::select('dir_name')->groupBy('dir_name');

            if ($dirListDatas->count() > 0) {
                $dirtList = $dirListDatas->get();

                foreach($dirtList as $row) {
                    $checkDirDatas = ContentDetail::select('dir_name')->where('dir_name', $row->dir_name)->where('link', $link);
                    if ($checkDirDatas->count() > 0) {
                        $dirLatestList[] = $row;
                    }
                }
            }
        }

        return response()->json($dirLatestList);
    }

    public function currentDetailcontent(Request $request)
    {
        $dirName = '';
        $asianContent = array();

        $detailId = $request->detail_id;

        $latestCurrentDatas = ContentDetail::select(['content', 'dir_name'])->where('id', $detailId);

        if ($latestCurrentDatas->count() > 0) {
            $latestDatas = $latestCurrentDatas->get();

            $dirName = $latestDatas[0]->dir_name;
            $latestContent = $latestDatas[0]->content;
            $groupDatas = $this->common->arrangeGraphDatas($dirName, $latestContent);
            $asianContent = $groupDatas['asian'];
        }

        return response()->json($asianContent);
    }

    // --------- start unused API --------- //
    public function contentDetail(Request $request)
    {
        $masterData = array();
        $contentList = array();

        $linkCode = $request->link;
        $fileName = substr($linkCode, 14, (strlen($linkCode) - 1));
        $dirName = $request->dir_name;

        $realLink = '';
        $findLinkDatas = ContentDetail::select(['link'])->where('dir_name', $dirName)->where('file_name', $fileName);

        if ($findLinkDatas->count() > 0) {
            $row = $findLinkDatas->get();
            $realLink = $row[0]->link;

            $contentDatas = ContentDetail::select(['id', 'dir_name', 'file_name', 'content'])->where('link', $realLink)->where('dir_name', $dirName);

            if ($contentDatas->count() > 0) {
                $rows = $contentDatas->get();
                $masterData = $rows[0];

                // $message = 'ID: ' . $masterData->id;
                // Log::info($message);

                $contentList = $this->arrangeContentDetail($masterData->content);

                $datas = array('master_data' => $masterData, 'arrange_data' => $contentList);
                return response()->json($datas);
            } else {
                $datas = array('master_data' => $masterData, 'arrange_data' => $contentList);
                return response()->json($datas);
            }
        } else {
            $datas = array('master_data' => $masterData, 'arrange_data' => $contentList);
            return response()->json($datas);
        }
    }

    public function arrangeContentDetail($htmlContent = '')
    {
        $headList = array();

        if ($htmlContent) {
            $result = preg_replace($this->attrWithLinkPatterns, '', $htmlContent);
            $finalHtml = preg_replace($this->removeLink, $this->rplByScript, $result);
            $finalHtml = preg_replace($this->textInClasspatterns, $this->textInClassReplaces, $finalHtml);
            $patternToReplace = array('/title/', '/" >/', '/OddsL\//', '/\/Draw/');
            $replaceWith = array('', '/">/', 'OddsL', 'Draw');
            $htmlForGraph = preg_replace($patternToReplace, $replaceWith, $finalHtml);

            $MarketT = explode('class="MarketT">', $htmlForGraph);
            array_shift($MarketT);
            // echo count($MarketT);

            $output = $MarketT; // array_slice($MarketT, 0, 3); // comment later

            if (count($output) > 0) {
                foreach ($output as $k => $content) {
                    preg_match('/<div class="SubHead">(.*?)<\/div>/s', $content, $datas);
                    $dtHead = (array_key_exists(1, $datas)) ? $datas[1] : '';
                    preg_match("'<span>(.*?)</span>'si", $dtHead, $rows);
                    $topHead = (array_key_exists(1, $rows)) ? $rows[1] : '';

                    $bdDatas = explode('class="MarketBd">', $content);
                    array_shift($bdDatas);
                    $tableContent = $bdDatas[0];

                    $tableDatas = explode('</tr>', $tableContent);
                    array_pop($tableDatas);
                    $tableDatas = $tableDatas; // array_slice($tableDatas, 0, 1);
                    // debug($tableDatas);

                    if (count($tableDatas) > 0) {
                        $matches = array();
                        foreach($tableDatas as $innerContent) {
                            $allVariables = $this->skudYen($innerContent, $topHead);
                            $teamLeftName = $allVariables['team_left'];
                            $teamLeftMid = $allVariables['score_left_mid'];
                            $teamLeftRight = $allVariables['score_left_last'];
                            $teamDrawText = $allVariables['draw_text'];
                            $teamDrawScore = $allVariables['draw_score'];
                            $teamRightName = $allVariables['team_right'];
                            $teamRightMid = $allVariables['score_right_mid'];
                            $teamRightRight = $allVariables['score_right_last'];

                            $matches[] = array('team_left' => $teamLeftName,
                                                'score_left_mid' => $teamLeftMid,
                                                'score_left_last' => $teamLeftRight,
                                                'draw_text' => $teamDrawText,
                                                'draw_score' => $teamDrawScore,
                                                'team_right' => $teamRightName,
                                                'score_right_mid' => $teamRightMid,
                                                'score_right_last' => $teamRightRight);
                        }
                    }

                    $headList[] = array('top_head' => $topHead, 'matches' => $matches);
                }
            }
        }

        return $headList;
    }

    public function saveToFFPTemp(Request $request)
    {
        $latestDir = $request->latest_dir;
        $finalList = $request->final_list;

        $tempId = 0;

        $findDir = DB::table('ffp_list_temp')->select(['content'])->where('dir_name', $latestDir);

        if ($findDir->count() == 0) {
            $tempId = DB::table('ffp_list_temp')->insertGetId(
                ['dir_name' => $latestDir,
                'content' => $finalList]
            );

            $this->common->autoUpdateSitemap();
        } else {
            $rows = $findDir->get();
            $row = $rows[0];

            if ($row->content == null || $row->content == NULL || $row->content == '') {
                DB::table('ffp_list_temp')
                    ->where('dir_name', $latestDir)
                    ->update(array('content' => $finalList));

                $this->common->autoUpdateSitemap();
            }
        }

        return response()->json(['temp_id' => $tempId]);
    }

    public function saveToMatchList()
    {
        $fileList = array();
        $qStrFile = DB::table('ffp_file')->select('link_code');
        if ($qStrFile->count() > 0) {
            $fileDatas = $qStrFile->get();
            foreach($fileDatas as $file) {
                $fileList[] = $file->link_code;
            }
        }

        // 'ffp_detail.id',
        $findBlankQuery = DB::table('ffp_detail')->select('ffp_detail.content', DB::raw('CONCAT(ffp_detail.dir_name, "-", ffp_detail.file_name) as link_code'));
        $findBlankQuery->whereNotIn(DB::raw('CONCAT(ffp_detail.dir_name, "-", ffp_detail.file_name)'), $fileList);
        // $datas = $findBlankQuery->orderBy('ffp_detail.id', 'desc');
        $datas = $findBlankQuery->take(10)->get();

        if (count($datas) > 0) {
            foreach($datas as $data) {
                $this->saveInToFileDB($data);
            }
        }
    }

    public function saveInToFileDB($data)
    {
        // $id = $data->id;
        $linkCode = $data->link_code;
        $content = $data->content;

        $rowDatas = $this->arrangeContentDetail($content);

        if (count($rowDatas) > 0) {
            foreach($rowDatas as $head) {
                $betType = '';
                $topHead = trim($head['top_head']);
                if ($topHead == 'Asian Handicap') {
                    $betType = 'asian';
                } else if ($topHead == 'Over Under') {
                    $betType = 'over';
                } else if ($topHead == '1X2') {
                    $betType = 'one';
                }

                if (! empty($betType)) {
                    if (count($head['matches']) > 0) {
                        foreach($head['matches'] as $match) {
                            if (trim($match['team_left'])) {
                                $fileSave = new FileDetail;
                                $fileSave->link_code = $linkCode;
                                $fileSave->bet_type = $betType;
                                $fileSave->home_team = $match['team_left'];
                                $fileSave->home_mid_score = $match['score_left_mid'];
                                $fileSave->home_water = $match['score_left_last'];
                                $fileSave->draw_text = $match['draw_text'];
                                $fileSave->draw_score = $match['draw_score'];
                                $fileSave->away_team = $match['team_right'];
                                $fileSave->away_mid_score = $match['score_right_mid'];
                                $fileSave->away_water = $match['score_right_last'];

                                $saved = $fileSave->save();

                                // dd($match);

                                if ($fileSave->id) {
                                    // ...
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public function ffpCustom(Request $request)
    {
        $finalList = array();

        $teamName = ($request->team_name) ? preg_replace('/-/', ' ', $request->team_name) : null;
        $teamName = ($teamName) ? preg_replace('/&/', '&amp;', $teamName) : null;

        $realFinalList = $this->common->recursiveActiveDirCustom($request->filter_name, $teamName);
        $finalList = $realFinalList['final_list'];
        $dirName = $realFinalList['dir_name'];

        $domain = request()->getHttpHost();
        $mainDatas = array('final_list' => $finalList, 'latest_dir' => $dirName, 'domain' => $domain);

        return response()->json($mainDatas);
    }

    public function leagueOdds($filterName = '')
    {
        $dayFullList = array();

        $dateList = DirList::select([DB::Raw('DATE(created_at) as date'), 'dir_name'])->groupBy(DB::Raw('DATE(created_at)'))->orderBy(DB::Raw('DATE(created_at)'), 'desc');

        if ($dateList->count() > 0) {
            $days = $dateList->get();

            foreach ($days as $dir) {
                $dirDate = explode('-', $dir->dir_name);
                $detail = $this->ffpMain($dirDate[0], $filterName);
                $dayFullList[] = array('date' => $dir->date, 'date_format' => $this->common->showDate($dir->date, 1), 'detail' => $detail);
            }
        }

        return $dayFullList;
    }

    public function ffpMain($dirDate, $filterName)
    {
        $mainDatas = array();

        $dirName = '';
        $latestDir = DirList::select(['content', 'dir_name'])->where('dir_name', 'like', $dirDate . '%')->where('scraping_status', '0')->where('except_this', '0')->where('content', '<>', '[]')->orderBy('dir_name', 'desc')->first();

        if ($latestDir) {
            $dirName = $latestDir->dir_name;
            $successList = $this->common->findSuccessList();
            $contentList = json_decode($latestDir->content);

            $resultList = $this->common->rawLeagueListCustom($contentList, $dirName, $successList, $filterName);
            $uniqueLeagueList = $resultList['unique'];
            $structureList = $resultList['structure'];

            $pmlList = $this->common->finalListStructure($uniqueLeagueList, $structureList, $successList);

            $finalList = array();

            if (array_key_exists(0, $pmlList)) {
                $finalList = $pmlList[0]['match_datas'];
            }

            $mainDatas = array('final_list' => $finalList, 'latest_dir' => $dirName);
        }

        return $mainDatas;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

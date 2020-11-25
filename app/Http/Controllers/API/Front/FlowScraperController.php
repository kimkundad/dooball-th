<?php

namespace App\Http\Controllers\API\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FlowScraperController extends Controller
{
    public $attrPatterns;
    public $attrWithLinkPatterns;
    public $textInClasspatterns;
    public $textInClassReplaces;
    public $removeLink;
    public $rplBySharp;
    public $rplByScript;

    public function __construct()
    {
        $this->attrPatterns = '#\s(id|title|onmouseover|onmouseout|border|cellpadding|cellspacing)="[^"]+"#';
        $this->attrWithLinkPatterns = '#\s(id|title|href|onmouseover|onmouseout|border|cellpadding|cellspacing)="[^"]+"#';
        $this->textInClasspatterns = array('/ Open/', '/ Closed/', '/<span class>/');
        $this->textInClassReplaces = array('', '', '/<span>/');
        $this->removeLink = "/(?<=href=(\"|'))[^\"']+(?=(\"|'))/";
        $this->rplBySharp = '#';
        $this->rplByScript = 'javascript:void(0);';
    }

    public function groupMatchlList($matchlList = array())
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
                    if ($element['name'] == $name) {
                        $league_row[] = $ele['league_row'];
                    }
                }

                $matches[] = array('name' => $name, 'league_row' => $league_row);
            }

        }

        return $matches;
    }

    public function findMin($teamSeries = array())
    {
        $min = 999;

        if (count($teamSeries) > 0) {
            foreach($teamSeries as $tsr) {
                $data = $tsr['data'];
                foreach($data as $dt) {
                    if ($min > $dt) {
                        $min = $dt;
                    }
                }
            }
        }

        // , 'min' => $min

        return $min;
    }

    public function arrangeGraphAsianHandicapDatas($htmlContent = '', $currentDir = '')
    {
        $asianHandicap = array();
        $countAsian = 0;

        $year = substr($currentDir, 0, 4);
        $month = substr($currentDir, 4, 2);
        $day = substr($currentDir, 6, 2);
        $h = substr($currentDir, 9, 2);
        $m = substr($currentDir, 11, 2);
        // $dirName = $day . '/' . $month . '/' . $year . ' ' . $h . ':' . $m;
        $dirName = $h . ':' . $m;

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

                    if ($topHead == 'Asian Handicap') {
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

                                preg_match('/<span class="OddsL">(.*?)<\/span>/s', $teamLeft, $rwsLL);
                                preg_match('/<span class="OddsM">(.*?)<\/span>/s', $teamLeft, $rwsLM);
                                preg_match('/<span class="OddsR">(.*?)<\/span>/s', $teamLeft, $rwsLR);

                                $teamLeftName = (array_key_exists(1, $rwsLL)) ? $rwsLL[1] : '';
                                $teamLeftMid = (array_key_exists(1, $rwsLM)) ? $rwsLM[1] : '';
                                $teamLeftRight = (array_key_exists(1, $rwsLR)) ? $rwsLR[1] : '';
                                $teamRightName = '';
                                $teamRightMid = '';
                                $teamRightRight = '';
                                $teamDrawText = '';
                                $teamDrawScore = '';

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

                                $teamName = '';
                                $score = 0.00;
                                $water = 0.00;

                                if ($teamLeftMid && $teamRightMid) {
                                    if ((int) $teamLeftMid == 0 && (int) $teamRightMid) {
                                        $teamName = $teamLeftName;
                                        $score = 0.00;
                                        $water = 0.00;
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
                                }

                                $matches[] = array('team_name' => $teamName, 'score' => $score, 'water' => $water);

                                // $matches[] = array('team_left' => $teamLeftName,
                                //                     'score_left_mid' => $teamLeftMid,
                                //                     'score_left_last' => $teamLeftRight,
                                //                     'draw_text' => $teamDrawText,
                                //                     'draw_score' => $teamDrawScore,
                                //                     'team_right' => $teamRightName,
                                //                     'score_right_mid' => $teamRightMid,
                                //                     'score_right_last' => $teamRightRight);
                            }

                            if ($topHead == 'Asian Handicap') {
                                $asianHandicap = array('date_time' => $dirName,
                                                        'matches' => $matches);
                                $countAsian++;
                            }
                        }
                    }
                }
            }
        }

        if ($countAsian == 0) {
            $asianHandicap = array('date_time' => $dirName,
                                    'matches' => array());
        }

        return $asianHandicap;
    }

}

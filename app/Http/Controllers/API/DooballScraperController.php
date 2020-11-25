<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Match;
use App\Models\MatchLink;
use App\Models\Link;
use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Log;

class DooballScraperController extends Controller
{
    public function syncMatch()
    {
        $matches = file_get_contents(env('SCRAP_LINK'));
        if ($matches) {
            $matches = json_decode($matches);
        }

        if (count($matches) > 0) {
            foreach($matches as $key => $value) {
                $this->algorithmCheckExistingMatch($value);
            }
        }
    }

    public function algorithmCheckExistingMatch($obj)
    {
        $total = 0;
        $match_id = 0;

        $cols = (gettype($obj) == 'object') ? (array) $obj : $obj;

        $match_time = $cols['kickoff_on'];
        $match = Match::where('match_time', $match_time)->where('home_team', trim($cols['team_home_name']));
        $total = $match->count();

        // --- start log --- //
        // $message = $match_time . ': ' . trim($cols['team_home_name']) . ': ' . $total;
        // Log::info($message);
        // --- end log --- //

        if ($total != 0) {
            $match_data = $match->get();
            $match_id = $match_data[0]->id;

            $newLinks = ($cols['links']) ? $cols['links'] : array() ;
            $newLinks = (gettype($newLinks) == 'object') ? (array) $newLinks : $newLinks;

            if (count($newLinks) > 0) {
                $matchLinks = MatchLink::where('match_id', $match_id)->where('link_type', 'Normal');
                $oldTotal = $matchLinks->count();

                if ($oldTotal == 0) {
                    // if no old, add
                    $this->addNormalLink($newLinks, $match_id);
                } else {
                    // if have old, check differrence
                    $arrOld = array();
                    $oldLinks = $matchLinks->get();
                    foreach($oldLinks as $oLink) {
                        $arrOld[] = $oLink->url;
                    }

                    foreach($newLinks as $k => $val) {
                        $val = (gettype($val) == 'object') ? (array) $val : $val;
                        $name = $val['name'];
                        $full_url = $val['url'];

                        if (! in_array($full_url, $arrOld)) {
                            $linkDb = Link::all();

                            if ($linkDb) {
                                foreach($linkDb as $ln) {
                                    if (strpos($full_url, $ln->link_url) != false) {
                                        $name = $ln->link_name;
                                    }
                                }
                            }

                            $this->addNormalLinkOneRow($match_id, $name, $full_url, $k);
                        }
                    }
                }
            }
        } else {
            $this->insertIntoMatchObject($cols);
        }

        $model = array('total' => $total, 'id' => $match_id);
        return response()->json($model);
    }

    public function insertIntoMatchObject($data = array())
    {
        $match = new Match;
        $match->match_name = trim($data['program_name']);
        $match->match_time = trim($data['kickoff_on']);
        $match->home_team = trim($data['team_home_name']);
        $match->away_team = trim($data['team_away_name']);
        $saved = $match->save();

        if ($match->id) {
            $match_id = $match->id;
            $links = (array_key_exists('links', $data)) ? $data['links'] : array() ;
            if (count($links) > 0) {
                $this->addNormalLink($links, $match_id);
            }
        }
    }

    public function addNormalLink($links = array(), $match_id = 0)
    {
        if (count($links) > 0) {
            shuffle($links);
            foreach($links as $k => $value) {
                $val = (gettype($value) == 'object') ? (array) $value : $value;
                $name = $val['name'];
                $full_url = $val['url'];
    
                $linkDb = Link::all();
                if ($linkDb) {
                    foreach($linkDb as $ln) {
                        if (strpos($full_url, $ln->link_url) != false) {
                            $name = $ln->link_name;
                        }
                    }
                }
    
                $this->addNormalLinkOneRow($match_id, $name, $full_url, $k);
            }
        }
    }

    public function addNormalLinkOneRow($match_id = 0, $name = '', $full_url = '', $k = 0)
    {
        $match_link = new MatchLink;
        $match_link->match_id = $match_id;
        $match_link->link_type = 'Normal';
        $match_link->name = $name;
        $match_link->url = $full_url;
        $match_link->link_seq = ($k+1);
        $link_saved = $match_link->save();
    }

    public function scraperBallzaaArray()
    {
        $matches = file_get_contents('https://www.ballzaa.com/linkdooball.php');
        preg_match("'<body>(.*?)</body>'si", $matches, $raws);
    
        $datas = $raws[1];
        $arr = explode('<div class="link_rows open-close">', $datas);
    
        $last_ele = end($arr);
        array_shift($arr);
        array_pop($arr);
    
        // $contents = array();
        if (count($arr) != 0) {
            DB::table('matches')->truncate();
            DB::table('match_links')->truncate();

            foreach($arr as $key => $content) {
                $data = explode('<div class="desc">', $content);
                $main = $data[0];
                $link = $data[1];
                $row = array('main' => $main, 'link' => $link);

                $data = $this->filterDataBallZaa($row, ($key + 1));
                // $contents[] = $data;
                $this->insertIntoMatch($data);
            }
        }
    
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
    
            $row = $last_data;
            $idx = count($row);
            $data = $this->filterDataBallZaa($row, ($idx + 1));
            // $contents[] = $data;
            $this->insertIntoMatch($data);
        }
    }

    public function filterDataBallZaa($row = array(), $key = 0) {
        $id = '0' . $key;
        $time = '';
        $home = '';
        $away = '';
        $home_logo = '';
        $away_logo = '';
        $program = '';

        if (array_key_exists('main', $row)) {
            preg_match("'<div class=\"l_time\"><strong>(.*?)</strong></div>'si", $row['main'], $match);
            $time = (array_key_exists(1, $match)) ? $match[1] : '';

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
                        'kickoff_on' => Date('Y-m-d') . ' ' . $time . ':00',
                        'team_home_name' => trim($home),
                        'team_away_name' => trim($away),
                        // 'team_home_logo' => $home_logo,
                        // 'team_away_logo' => $away_logo,
                        'links' => $links);

        return $noi;
    }

    public function insertIntoMatch($data = array())
    {
        $match = new Match;
        $match->match_name = trim($data['program_name']);
        $match->match_time = trim($data['kickoff_on']);
        $match->home_team = trim($data['team_home_name']);
        $match->away_team = trim($data['team_away_name']);
        $saved = $match->save();

        if ($match->id) {
            $match_id = $match->id;
            $links = $data['links'];
            if (count($links) > 0) {
                foreach($links as $k => $val) {
                    $match_link = new MatchLink;
                    $match_link->match_id = $match_id;
                    $match_link->link_type = 'Normal';
                    $match_link->name = $val['name'];
                    $match_link->url = $val['url'];
                    $match_link->link_seq = ($k+1);
                    $link_saved = $match_link->save();
                }
            }
        }
    }
}

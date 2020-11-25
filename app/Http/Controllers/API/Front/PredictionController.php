<?php

namespace App\Http\Controllers\API\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\CommonController;
use App\Http\Controllers\API\LogFileController;
use App\Models\Bet;
use App\Models\Text;
use App\Models\dirList;
use App\Models\ContentDetail;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PredictionController extends Controller
{
	private $common;
	private $midThisDate;
	private $tomorrowDate;
	private $midYesterday;
	private $todayDate;
    private $logAsFile;

	public function __construct()
	{
		$this->common = new CommonController();
		$this->midThisDate = Date('Y-m-d 12:00:00');
		$this->tomorrowDate = Date('Y-m-d 11:59:59', strtotime("+1 days"));
		$this->midYesterday = Date('Y-m-d 12:00:00', strtotime("-1 days"));
		$this->todayDate = Date('Y-m-d 11:59:59');
		$this->logAsFile = new LogFileController;
	}

    public function index()
    {
        $structureDatas = array();

        $curDatas = $this->common->realCurrentContent();
        $dirName = $curDatas['dirName'];
		// $latestContent = $curDatas['latestContent'];
		
		$this->logAsFile->logAsFile('debug-game.html', 'Dir name: ' . $dirName);

        if ($dirName) {
            // --- start check prediction temp --- //
            $latestDir = DB::table('ffp_list_temp')->select(['prediction_content'])->where('dir_name', $dirName)->whereNotNull('prediction_content')->first();
            if ($latestDir) {
				$this->logAsFile->logAsFile('debug-game.html', '<br>has temp', 'append');
                $structureDatas = json_decode($latestDir->prediction_content);
            } else {
				$this->logAsFile->logAsFile('debug-game.html', '<br>Does not has temp', 'append');
                $datas = array();
				$successList = array();

                $detailDatas = ContentDetail::select(['id', 'league_name', 'vs', 'event_time', 'link'])->where('dir_name', $dirName)->orderBy('code', 'asc');

                if ($detailDatas->count() > 0) {
					$this->logAsFile->logAsFile('debug-game.html', '<br>Total detail: ' . $detailDatas->count(), 'append');

                    // --- start special query --- //
                    $dayList = ContentDetail::select('dir_name')->groupBy('dir_name')->orderBy('dir_name', 'asc');
                    $totalInner = $dayList->count();
                    if ($totalInner > 0) {
                        $dirList = $dayList->get();
                        foreach($dirList as $key => $dName) {
                            $dlDatas = dirList::select('dir_name')->where('scraping_status', '1')->where('dir_name', $dName->dir_name);
                            if ($dlDatas->count() > 0) {
                                $successList[] = $dName->dir_name;
                            }
                        }
                    }
                    // --- end special query --- //
    
                    foreach($detailDatas->get() as $k => $v) {
                        $detailId = $v->id;
						$leagueName = $v->league_name;
						
						$this->logAsFile->logAsFile('debug-game.html', '<br>League name: ' . $leagueName, 'append');

                        if ($leagueName != '-- no league --') {
                            $eventTime = $v->event_time;
                            $vs = $v->vs;
                            $homeTeamName = '';
                            $awayTeamName = '';
                            
                            if (trim($vs) && !empty($vs)) {
                                $vsList = preg_split('/-vs-/', $vs);
                                $homeTeamName = $vsList[0];
                                $awayTeamName = array_key_exists(1, $vsList) ? $vsList[1] : '-';
                            }

                            if ($awayTeamName == '-' || $awayTeamName == '') {
                                $leagueInfo = $this->common->findLeagueInfoFromDetailLink($dirName, $v->link, $detailId);
                                $leagueName = $leagueInfo['league_name'];
                                $homeTeamName = $leagueInfo['home_team'];
								$awayTeamName = $leagueInfo['away_team'];
                            }

                            // --- start check disabled button --- //
                            $newTime = $this->common->skudTimeBeforeOneHr($eventTime, ' ');
                            $dayList = explode(' ', $newTime);
                            $monthNum = $this->common->monthNumFromText($dayList[0]);
                            $match_time = Date('Y-' . $monthNum . '-d') . ' 00:00:00';
    
                            if (array_key_exists(1, $dayList) && array_key_exists(2, $dayList)) {
                                $match_time = Date('Y-' . $monthNum . '-' . $dayList[1]) . ' ' . $dayList[2] . ':00';
                            }
    
                            $leftMinutes = $this->common->leftToMatch($match_time);
        
                            $disabled = ((int) $leftMinutes <= 30) ? 'disabled' : '';
							// --- end check disabled button --- //
							
							$scoreDatas = $this->common->scoreFromLink($v->link, $successList);

							$score = $scoreDatas['asian']['score'];
							$teamCont = $scoreDatas['asian']['team_cont'];
							$this->logAsFile->logAsFile('debug-game.html', '<br>' . $homeTeamName . '_' . $awayTeamName . '_Score datas [' . $score . ',' . $teamCont . '] (' . gettype($score) . ')', 'append');

							if (gettype($score) != 'NULL') {
								$datas[] = array('league_name' => $leagueName,
								'date_time_before' => $newTime,
								'event_time' => $eventTime,
								'disabled' => $disabled,
								'home_team' => $homeTeamName,
								'away_team' => $awayTeamName,
								'datas' => $scoreDatas,
								'link' => $v->link,
								'id' => $detailId);

								$strScore = json_encode($scoreDatas);
								$this->logAsFile->logAsFile('debug-game.html', '<br>' . $homeTeamName . '_' . $awayTeamName . '_Score datas: ' . $strScore, 'append');
							}
                        }
                    }
                }

                $lList = array();

                if (count($datas) > 0) {
                    foreach($datas as $data) {
                        if (! in_array($data['league_name'], $lList)) {
                            $lList[] = $data['league_name'];
                        }
                    }

                    foreach($lList as $league_name) {
                        $rows = array();
                        foreach($datas as $data) {
                            if ($data['league_name'] == $league_name) {
                                $rows[] = $data;
                            }
                        }
                        
                        $structureDatas[] = array('league_name' => $league_name, 'rows' => $rows);
                    }
                }
            }
            // --- end check prediction temp --- //
        }

        $mainDatas = array('latest_dir' => $dirName, 'datas' => $structureDatas);

        return response()->json($mainDatas);
	}

    public function bet(Request $request)
    {
		$total = 0;
		$message = 'Nothing...';

		$member_id = $request->member_id;
        $ffpDetailId = $request->ffp_detail_id;
		$league_name = $request->league_name;
		$bargain_price = $request->bargain_price;
		$home_team = $request->home_team;
		$away_team = $request->away_team;
		$match_continue = $request->match_continue;
		$matchContinueOrg = $request->match_continue_org;

		$dateMatch = $request->match_time;
		$dayList = explode(' ', $dateMatch);
		$monthNum = $this->common->monthNumFromText($dayList[0]);
		$match_time = Date('Y-' . $monthNum . '-' . $dayList[1]) . ' ' . $dayList[2] . ':00';
		$ip = $_SERVER['REMOTE_ADDR'];
		$created_at = Date('Y-m-d H:i:s');

		$mid_this_date = $this->midThisDate;
		$ten_tomorrow_date = $this->tomorrowDate;

        if (strtotime(date("Y-m-d H:i:s")) < strtotime(date("Y-m-d 12:00:00"))) {
			$mid_this_date = $this->midYesterday;
			$ten_tomorrow_date = $this->todayDate;
		}

		$findBetted = Bet::where('user_id', $member_id)->whereBetween('created_at', [$mid_this_date, $ten_tomorrow_date]);

        if ($findBetted->count() == 0) {
			$findBettedRealToday = Bet::where('user_id', $member_id)->where('created_at', '>', Date('Y-m-d 10:00:00'));

			if ($findBettedRealToday->count() == 0) {
				$leftMinutes = $this->common->leftToMatch($match_time);

				if ((int) $leftMinutes > 30) {
					// insert

					$preBetId = 0;

					$row = array('league_name' => $league_name, 'home_team' => $home_team, 'away_team' => $away_team);
					$foundPrediction = $this->findMatchInPrediction($row, $match_time);

					if ($foundPrediction == 0) {
						$isTded = 0;

						if ($league_name == 'SPAIN LA LIGA' || $league_name == 'GERMANY BUNDESLIGA' || $league_name == 'ENGLISH PREMIER LEAGUE') {
							$isTded = 1;
						}

						$preBetId = DB::table('prediction_bets')->insertGetId(
							['ffp_detail_id' => $ffpDetailId,
							'match_time' => $match_time,
							'league_name' => $league_name,
							'home_team' => $home_team,
							'away_team' => $away_team,
							'bargain_price' => $bargain_price,
							'match_continue' => $matchContinueOrg,
							'is_tded' => $isTded]
						);
					} else {
						$preBetDatas = $this->findMatchInPredictionData($row, $match_time);
						if ($preBetDatas) {
							$preBetId = $preBetDatas->id;
						}
					}

					$data = new Bet;
					$data->user_id = $member_id;
					$data->pre_bet_id = $preBetId;
					$data->match_continue = $match_continue;
					$data->ip = $ip;
					$data->created_at = $created_at;
	
					$saved = $data->save();
	
					if ($saved) {
						$total = 1;
						$lastInsertId = $data->bet_id;
						$message = 'ทำรายการสำเร็จ';
					}
				} else {
					$message = 'ขออภัย ระบบปิดการทายผล ก่อนแข่งครึ่งชั่วโมงค่ะ';
				}
			} else {
				$message = 'สามารถเล่นได้แค่วันละ 1 ทีมเท่านั้น';
			}
        } else {
            $message = 'สามารถเล่นได้แค่วันละ 1 ทีมเท่านั้น';
		}

        return response()->json(['total' => $total, 'message' => $message]);
	}

    public function betList($page = 1, $limit = 20)
    {
		// DB::enableQueryLog();
        $mnData = DB::table('bets')
            ->rightJoin('users', 'bets.user_id', '=', 'users.id')
            ->leftJoin('prediction_bets', 'bets.pre_bet_id', '=', 'prediction_bets.id')
			->select('bets.user_id', 'users.username', 'users.screen_name', DB::raw('SUM((SELECT w.win_num FROM win_rate w WHERE w.match_continue=bets.match_continue AND w.match_result_status=prediction_bets.match_result_status)) AS total'))
			->where('bets.active_status', 1)
			->groupBy('bets.user_id')
        	->orderByRaw('SUM((SELECT w.win_num FROM win_rate w WHERE w.match_continue=bets.match_continue AND w.match_result_status=prediction_bets.match_result_status)) desc');

        // $q = DB::getQueryLog()[0]['query'];
        // dd($q);
		// dd(DB::getQueryLog()[0]['time']);
	
		$bets = null;
		$datas = array();

		if ($mnData->count() > 0) {
			$bets = $mnData->paginate($limit, ['*'], 'page', $page);

			foreach($bets as $k => $v) {
				$displayName = (trim($v->screen_name)) ? trim($v->screen_name) : $v->username;
				$teamName = $this->betToday($v->user_id);
				$histDatas = $this->betHistoryRow($v->user_id);
				$winRate = $histDatas['win_rate'];
				$winString = $histDatas['win_string'];
				$betHis = $histDatas['result_match'];
				$ip = '';

				$datas[] = array('screen_name' => $displayName,
								'team_name' => $teamName,
								'win_rate' => $winRate,
								'win_string' => $winString,
								'bet_hist' => $betHis,
								'ip' => $ip, // $v->ip, should show with bet today
								'user_id' => $v->user_id,
								'username' => $v->username);
			}

			$volume  = array_column($datas, 'win_rate');
			array_multisort($volume, SORT_DESC, $datas);
		}

		return array('datas' => $datas, 'bets' => $bets);
	}

	public function betHistoryRow($user_id = 0) {
        $userBetDatas = DB::table('bets')
                    ->leftJoin('prediction_bets', 'bets.pre_bet_id', '=', 'prediction_bets.id')
                    ->leftJoin('users', 'bets.user_id', '=', 'users.id')
					->select(['bets.match_continue', 'prediction_bets.match_result_status'])
					->where('bets.user_id', $user_id)
					->orderBy('bets.created_at', 'desc');

		// --- start prepare to return info --- //
		$countWin = 0;
		$strWin = '';
		$resultMatch = '';

		$debugDatas = array();

		if ($userBetDatas->count() > 0) {
			$rows = $userBetDatas->skip(0)->take(10)->get();

			foreach($rows as $val) {
				$win = $this->winResult($val->match_continue, $val->match_result_status);
				$countWin += (int) $win;
				$strWin .= ($strWin != '') ? ',' . $win : $win;

				$cube = $this->colorResult($win);
				$resultMatch .= $cube;

				if ($user_id == 5) {
					$debugDatas[] = array('match_continue' => $val->match_continue,
										'result_status' => $val->match_result_status,
										'win' => $win,
										'cube' => $cube
									);
				}
			}
		}
		// --- end prepare to return info --- //

		// if ($user_id == 5) {
		// 	echo '<pre>';
		// 	print_r($debugDatas);
		// 	echo '</pre>';
		// 	exit;
		// }

		return array('win_rate' => $countWin, 'win_string' => $strWin, 'result_match' => $resultMatch);
	}

	public function winResult($match_continue, $match_result_status) {
		$win = 0;

		$mstt_rs = (int) $match_result_status;
		$match = (int) $match_continue;

		if ($match == 1 && $mstt_rs == 1) {
			$win = 5;
		} else if ($match == 1 && $mstt_rs == 2) {
			$win = 4;
		} else if ($match == 1 && $mstt_rs == 3) {
			$win = 3;
		} else if ($match == 1 && $mstt_rs == 4) {
			$win = 1;
		} else if ($match == 1 && $mstt_rs == 5) {
			$win = 5;
		} else if ($match == 2 && $mstt_rs == 1) {
			$win = 1;
		} else if ($match == 2 && $mstt_rs == 2) {
			$win = 2;
		} else if ($match == 2 && $mstt_rs == 3) {
			$win = 3;
		} else if ($match == 2 && $mstt_rs == 4) {
			$win = 5;
		} else if ($match == 2 && $mstt_rs == 5) {
			$win = 4;
		}

		return $win;
	}

	public function colorResult($winScore = 0) {
		$ret_string = '';

		if ($winScore == 5) {
			$ret_string = $this->betColor('full-win');
		} else if ($winScore == 4) {
			$ret_string = $this->betColor('half-win');
		} else if ($winScore == 3) {
			$ret_string = $this->betColor('equal');
		} else if ($winScore == 2) {
			$ret_string = $this->betColor('half-loss');
		} else if ($winScore == 1) {
			$ret_string = $this->betColor('full-loss');
		}

		return $ret_string;
	}

	public function betToday($user_id = 0) {
		$teamName = '';

		$mid_this_date = $this->midThisDate;
		$ten_tomorrow_date = $this->tomorrowDate;

		if (strtotime(date("Y-m-d H:i:s")) < strtotime(date("Y-m-d 12:00:00"))) {
			$mid_this_date = $this->midYesterday;
			$ten_tomorrow_date = $this->todayDate;
		}

        $mnData = DB::table('bets')
					->leftJoin('prediction_bets', 'bets.pre_bet_id', '=', 'prediction_bets.id')
					->select(['bets.pre_bet_id', 'bets.match_continue']);

        $mnData->where('bets.active_status', 1)->where('bets.user_id', $user_id)->orderBy('bets.created_at', 'desc');
        $mnData->whereBetween('prediction_bets.match_time', [$mid_this_date, $ten_tomorrow_date]); // ->get();

		if ($mnData->count() > 0) {
			$rows = $mnData->get();
			$v = $rows[0];
			$preBetId = $v->pre_bet_id;
			$matchContinue = $v->match_continue;

			$preBets = DB::table('prediction_bets')->select(['home_team', 'away_team'])->where('id', $preBetId);

			if ($preBets->count() > 0) {
				$rows = $preBets->get();
				$row = $rows[0];
				
				if ($matchContinue == 1) {
					$teamName = $row->home_team;
				} else {
					$teamName = $row->away_team;
				}
			}
		}

		return $teamName;
	}

	public function betColor($rs_text = '') {
		$ret_rs = '';
		$fullWin = '<div class="match-cube win">W</div>';
		$halfWin = '<div class="match-cube win half">W</div>';
		$fullLoss = '<div class="match-cube lose">L</div>';
		$halfLoss = '<div class="match-cube lose half">L</div>';
		$equal = '<div class="match-cube draw">D</div>';

		if ($rs_text == 'full-win') {
			$ret_rs = $fullWin;
		} else if ($rs_text == 'half-win') {
			$ret_rs = $halfWin;
		} else if ($rs_text == 'full-loss') {
			$ret_rs = $fullLoss;
		} else if ($rs_text == 'half-loss') {
			$ret_rs = $halfLoss;
		} else {
			$ret_rs = $equal;
		}

		return $ret_rs;
	}

	public function betMonthly($user = null, $mode = '')
	{
		$monthlyList = array();

		$user_id = 0;

		if ($mode == 'username') {
			$userData = User::where('username', $user)->first();

			if ($userData) {
				$user_id = $userData->id;
			}
		} else {
			$user_id = $user;
		}

		$datas = Bet::where('user_id', $user_id)->selectRaw(DB::raw("DATE_FORMAT(created_at,'%Y-%m') as month"))->groupBy(DB::raw('MONTH(created_at)'));
		
		if ($datas->count() > 0) {
			foreach($datas->get() as $month) {
				$monthlyList[] = $month->month;
			}
		}

		return $monthlyList;
	}

	public function betHistory($username = '', $month = '')
	{
		$bet_stats = array('total' => 0, 'records' => array());
		$month_stats = array('total' => 0, 'records' => array());

		//------------------ start bet stat --------------------//
		$user_id = 0;
		$userData = User::where('username', $username)->first();

		if ($userData) {
			$user_id = $userData->id;
		}

		$mnData = DB::table('bets')
					->leftJoin('prediction_bets', 'bets.pre_bet_id', '=', 'prediction_bets.id')
					->select('bets.match_continue', 'bets.created_at', 'prediction_bets.home_team', 'prediction_bets.away_team', 'prediction_bets.bargain_price', 'prediction_bets.match_result_status');

		$mnData->where('bets.active_status', 1)->where('bets.user_id', $user_id);

		if ($month != '') {
			$mnData->whereRaw("DATE_FORMAT(bets.created_at,'%Y-%m') = '" . $month . "'");
		}

		$mnData->orderBy('prediction_bets.match_time', 'desc');

		$datas = array();

		if ($mnData->count() > 0) {
			$datas = $mnData->get();

			foreach($datas as $k => $v) {
				$datas[$k]->betDate = $this->common->showDateTime($v->created_at);
				$result = $this->matchResultStatus($v->match_result_status);
				$win = $this->winResult($v->match_continue, $v->match_result_status);
				$color = $this->colorResult($win);
				$datas[$k]->show = ($color) ? $color . ' ' . '(' . $result . ')' : '-';
			}
		}
		//------------------ end bet stat --------------------//

		return $datas;
	}

	public function betHistoryUser($user_id = 0, $month = '')
	{
		$bet_stats = array('total' => 0, 'records' => array());
		$month_stats = array('total' => 0, 'records' => array());

		$mnData = DB::table('bets')
					->leftJoin('prediction_bets', 'bets.pre_bet_id', '=', 'prediction_bets.id')
					->select('bets.match_continue', 'bets.created_at', 'prediction_bets.home_team', 'prediction_bets.away_team', 'prediction_bets.bargain_price', 'prediction_bets.match_result_status');

		$mnData->where('bets.active_status', 1)->where('bets.user_id', $user_id);

		if ($month != '') {
			$mnData->whereRaw("DATE_FORMAT(bets.created_at,'%Y-%m') = '" . $month . "'");
		}

		$mnData->orderBy('prediction_bets.match_time', 'desc');

		$datas = array();

		if ($mnData->count() > 0) {
			$datas = $mnData->get();

			foreach($datas as $k => $v) {
				$datas[$k]->betDate = $this->common->showDateTime($v->created_at);
				$result = $this->matchResultStatus($v->match_result_status);
				$win = $this->winResult($v->match_continue, $v->match_result_status);
				$color = $this->colorResult($win);
				$datas[$k]->show = ($color) ? $color . ' ' . '(' . $result . ')' : '-';
			}
		}

		return $datas;
	}

	public function matchResultStatus($match_result_status = 0)
	{
		$status_text = '-';

		if ($match_result_status == 1) {
			$status_text = 'เจ้าบ้านชนะเต็ม';
		} else if ($match_result_status == 2) {
			$status_text = 'เจ้าบ้านชนะครึ่ง';
		} else if ($match_result_status == 3) {
			$status_text = 'เสมอ';
		} else if ($match_result_status == 4) {
			$status_text = 'ทีมเยือนชนะเต็ม';
		} else if ($match_result_status == 5) {
			$status_text = 'ทีมเยือนชนะครึ่ง';
		}

		return $status_text;
	}

	public function textList()
	{
		$textList = Text::where('active_status', 1);
		return $textList->get();
	}

    public function saveToPredictionTemp(Request $request)
    {
        $latestDir = $request->latest_dir;
        $finalList = $request->final_list;

        $tempId = 0;

        $findDir = DB::table('ffp_list_temp')->select(['prediction_content'])->where('dir_name', $latestDir);

        if ($findDir->count() == 0) {
            $tempId = DB::table('ffp_list_temp')->insertGetId(
                ['dir_name' => $latestDir,
                'prediction_content' => $finalList]
            );

            $this->common->autoUpdateSitemap();
        } else {
            $rows = $findDir->get();
            $row = $rows[0];

            if ($row->prediction_content == null || $row->prediction_content == NULL || $row->prediction_content == '') {
                DB::table('ffp_list_temp')
                    ->where('dir_name', $latestDir)
                    ->update(array('prediction_content' => $finalList));
    
                $this->common->autoUpdateSitemap();
            }
        }

        return response()->json(['temp_id' => $tempId]);
    }

    public function saveToTded(Request $request)
    {
		$tenPredictionIds = array();
		$createdAt = Date('Y-m-d H:i:s');
		// $this->logAsFile->logAsFile('save-to-tded.html', 'Hi, ' . $createdAt);

		$datas = $request->datas;

		if ($datas) {
			// $this->logAsFile->logAsFile('save-to-tded.html', '<br>Yes, has datas', 'append');

			$max = 10;
			$countPredictionToday = $this->findTotalPredictionToday();

			if (count($datas) > 0 && $countPredictionToday < $max) {
				// $this->logAsFile->logAsFile('save-to-tded.html', '<br>Yes, has datas and prediction < ' . $max, 'append');

				foreach($datas as $index => $row) {
					$countPredictionToday = $this->findTotalPredictionToday();

					if ($countPredictionToday < $max) {
						$dateMatch = $row['match_time'];
						$dayList = explode(' ', $dateMatch);
						$monthNum = $this->common->monthNumFromText($dayList[0]);
						$match_time = Date('Y-' . $monthNum . '-' . $dayList[1]) . ' ' . $dayList[2] . ':00';

						$foundPrediction = $this->findMatchInPrediction($row, $match_time);

						// $this->logAsFile->logAsFile('save-to-tded.html', '<br>foundPrediction : ' . $foundPrediction . ' && '. $countPredictionToday . ' < ' . $max , 'append');

						if ($foundPrediction == 0 && $countPredictionToday < $max) {
							$preId = $this->addPrediction($row, $match_time, $createdAt);
							$tenPredictionIds[] = $preId;
							// $this->logAsFile->logAsFile('save-to-tded.html', '<br>pre-bet ID : ' . $preId, 'append');
						}
					}
				}
			}
		}

		$preBetTodayDatas = DB::table('prediction_bets')
								->whereRaw("DATE(created_at) = '" . Date('Y-m-d') . "'")
								->where('is_tded', 1);

		// $this->logAsFile->logAsFile('save-to-tded.html', '<br>Total pre-bet today : ' . $preBetTodayDatas->count(), 'append');

		if ($preBetTodayDatas->count() > 0) {
			$rows = $preBetTodayDatas->get();

			$this->checkTded(1, 1, $rows, $createdAt); // ทีเด็ดบอลต่อ => 1
			$this->checkTded(2, 1, $rows, $createdAt); // ทีเด็ดบอลรอง => 1
			$this->checkTded(3, 1, $rows, $createdAt); // ทีเด็ดบอลเต็ง => 1 (บอลอะไรก้ได้)
			$this->checkTded(4, 2, $rows, $createdAt); // ทีเด็ดบอลสเต็ป 2 => 2
			$this->checkTded(5, 3, $rows, $createdAt); // ทีเด็ดบอลสเต็ป 3 => 3
			$this->checkTded(6, 10, $rows, $createdAt); // ทีเด็ดบอล => 10
		}

		return response()->json($tenPredictionIds);
    }

	public function findTotalPredictionToday()
	{
		$preBetTodayDatas = DB::table('prediction_bets')->whereRaw("DATE(created_at) = '" . Date('Y-m-d') . "'")->where('is_tded', 1);

		return $preBetTodayDatas->count();
	}

	public function findMatchInPrediction($row = array(), $match_time = '') {
		$preBetDatas = DB::table('prediction_bets')
							->where('league_name', $row['league_name'])
							->where('match_time', $match_time)
							->where('home_team', $row['home_team'])
							->where('away_team', $row['away_team']);

		return $preBetDatas->count();
	}

	public function findMatchInPredictionData($row = array(), $match_time = '')
	{
		$preBetDatas = DB::table('prediction_bets')
							->where('league_name', $row['league_name'])
							->where('match_time', $match_time)
							->where('home_team', $row['home_team'])
							->where('away_team', $row['away_team'])->first();

		return $preBetDatas;
	}

	public function addPrediction($row = array(), $match_time = '', $created_at = '')
	{
		$preBetId = DB::table('prediction_bets')->insertGetId(
			['ffp_detail_id' => $row['ffp_detail_id'],
			'match_time' => $match_time,
			'league_name' => $row['league_name'],
			'home_team' => $row['home_team'],
			'away_team' => $row['away_team'],
			'bargain_price' => $row['bargain_price'],
			'match_continue' => $row['match_continue'],
			'is_tded' => 1]
		);

		return $preBetId;
	}

	public function checkTded($type_id = 0, $qty = 0, $records = array(), $created_at = '')
	{
		$tenTdedIds = array();

		$tdedDatas = DB::table('tdedball_list')
							->select(['tdedball_list_id', 'team_selected'])
							->where('type_id', $type_id)
							->whereRaw("DATE(created_at) = '" . Date('Y-m-d') . "'");

		// $this->logAsFile->logAsFile('save-to-tded.html', '<br>find type: ' . $type_id . ' (QTY:' . $qty . '), found: ' . $tdedDatas->count(), 'append');

		if ($tdedDatas->count() == 0) {
			// $this->logAsFile->logAsFile('save-to-tded.html', '<br>Start insert type: ' . $type_id, 'append');
			$countType = 0;
			$typeSring = '';

			// 1. filter
			if ($type_id != 6) {
				$records = $this->shufflePrediction($records);
			}

			// $this->logAsFile->logAsFile('save-to-tded.html', '<br>records: ' . count($records), 'append');

			foreach($records as $k => $preData) {
				// $this->logAsFile->logAsFile('save-to-tded.html', '<br>check type: ' . $countType . ' < ' . $qty, 'append');
				if ($countType < $qty) {
					// $this->logAsFile->logAsFile('save-to-tded.html', '<br>Yes, '. $countType . ' < ' . $qty, 'append');
					$teamCont = $preData->match_continue;

					if ($type_id == 2) {
						$teamCont = ((int) $preData->match_continue == 1) ? 2 : 1;
					}

					if ($type_id > 2) {
						$rands = array(1, 2);
						$ranKey = array_rand($rands);
						$teamCont = $rands[$ranKey];
					}

					$idCont = $preData->id . '_' . $teamCont;
					// $this->logAsFile->logAsFile('save-to-tded.html', '<br>idCont: '. $idCont, 'append');

					$findDup = DB::table('tdedball_list')
								->where('type_id', $type_id)
								->whereRaw("DATE(created_at) = '" . Date('Y-m-d') . "'")
								->where('team_selected', 'like', '%'. $preData->id . '_%')
								->count();

					$strDup = 'Dup type_id ' . $type_id . ', ' . Date('Y-m-d') . ', ' . $preData->id . '_ : ' . $findDup;
					// $this->logAsFile->logAsFile('save-to-tded.html', '<br>' . $strDup, 'append');

					if ($findDup == 0) {
						$typeSring .= ($typeSring) ? ',' . $idCont : $idCont;
						$countType++;
					}
				}
			}

			$tdedInsertId = $this->addTdedType($type_id, $typeSring, $created_at);

			$tenTdedIds[] = $tdedInsertId;
		} else {
			// $this->logAsFile->logAsFile('save-to-tded.html', '<br>Start update type: ' . $type_id, 'append');
			$rows = $tdedDatas->get();
			$row = $rows[0];

			$typeSring = trim($row->team_selected);
			$tdedUpdateId = $row->tdedball_list_id;

			$teamSelected = array();

			if (trim($typeSring)) {
				$teamSelected = explode(',' , $typeSring);
			}

			$countType = count($teamSelected);

			if ($countType < $qty) {
				// 1. filter
				if ($type_id != 6) {
					$records = shufflePrediction($records);
				}

				foreach($records as $k => $preData) {
					$teamCont = $preData->match_continue;

					if ($type_id == 2) {
						$teamCont = ((int) $preData->match_continue == 1) ? 2 : 1;
					}

					if ($type_id > 2) {
						$rands = array(1, 2);
						$ranKey = array_rand($rands);
						$teamCont = $rands[$ranKey];
					}
					
					$idCont = $preData->id . '_' . $teamCont;

					if ($countType < $qty && ! in_array($preData->id . '_1', $teamSelected) && ! in_array($preData->id . '_2', $teamSelected)) {
						$typeSring .= ($typeSring) ? ',' . $idCont : $idCont;
						$countType++;
					}
				}

				$this->editTdedType($type_id, $typeSring, $tdedUpdateId);

				$tenTdedIds[] = $tdedUpdateId;
			}
		}
	}

	public function shufflePrediction($records = array())
	{
		$newArray = array();

		$arrList = json_decode($records, true);

		$shuffleKeys = array_keys($arrList);
		shuffle($shuffleKeys);

		foreach($shuffleKeys as $key) {
			$newArray[$key] = $records[$key];
		}

		return $newArray;
	}
	
	public function addTdedType($type_id = 0, $typeSring = '', $created_at = '') {
		$insertId = DB::table('tdedball_list')->insertGetId(
			['type_id' => $type_id,
			'team_selected' => $typeSring,
			'created_at' => $created_at]
		);

		return $insertId;
	}

	public function editTdedType($type_id = 0, $typeSring = '', $tdedball_list_id = 0) {
		$affected = DB::table('tdedball_list')
						->where('tdedball_list_id', $tdedball_list_id)
						->update(['team_selected' => $typeSring]);
	}

	public function tdedBallDatas($type_id = 0, $date = '')
	{
		$tdedList = array();

		$tdedDatas = DB::table('tdedball_list')->whereRaw("DATE(created_at) = '" . $date . "'")->where('type_id', $type_id)->first();

		if ($tdedDatas) {
			$selected = $tdedDatas->team_selected;
			$tdList = explode(',', $selected);

			if (count($tdList) > 0) {
				foreach($tdList as $team) {
					$preBet = explode('_', $team);
					$pbId = $preBet[0];
					$tdCont = $preBet[1];

					$pbData = DB::table('prediction_bets')->where('id', $pbId)->first();

					if ($pbData) {
						$win = $this->winResult($tdCont, $pbData->match_result_status);
						$matchResult = ($win >= 3) ? $this->colorResult($win) : '';

						$tdedList[] = array('pb_id' => $pbData->id,
											'match_time_full' => $pbData->match_time,
											'match_time' => substr($pbData->match_time, 11, 5),
											'home_team' => $pbData->home_team,
											'away_team' => $pbData->away_team,
											'match_continue' => $tdCont,
											'pb_match_continue' => $pbData->match_continue,
											'bargain_price' => $pbData->bargain_price,
											'result' => $matchResult
										);
					}
				}
			}
		}

		return $tdedList;
	}

	public function tdedballOneMatch($type_id = 0, $month = '')
	{
		$tdedData = DB::table('tdedball_list')->where('type_id', $type_id)->where('active_status', 1);

		// 1, 2, 3, 8 only
		if ($month != '') {
			$tdedData->whereRaw("DATE_FORMAT(created_at,'%Y-%m') = '" . $month . "'");
			$tdedData->orderBy('created_at', 'desc');
		}

		$tdedList = array();

		if ($tdedData->count() > 0) {
			foreach($tdedData->get() as $tded) {
				$teamSelected = $tded->team_selected;
				$preBet = explode('_', $teamSelected);
				$pbId = $preBet[0];
				$tdCont = $preBet[1];

				$pbData = DB::table('prediction_bets')->where('id', $pbId)->first();

				if ($pbData) {
					$win = $this->winResult($tdCont, $pbData->match_result_status);
					$matchResult = ($win >= 3) ? $this->colorResult($win) : '';
					$dateFormat = $this->common->showDate(substr($pbData->match_time, 0, 10));

					$tdedList[] = array('pb_id' => $pbData->id,
										'match_time_full' => $pbData->match_time,
										'match_date' => $dateFormat,
										'match_time' => substr($pbData->match_time, 11, 5),
										'home_team' => $pbData->home_team,
										'away_team' => $pbData->away_team,
										'match_continue' => $tdCont,
										'pb_match_continue' => $pbData->match_continue,
										'bargain_price' => $pbData->bargain_price,
										'result' => $matchResult
									);
				}
			}
		}

		return $tdedList;
	}

	public function tdedtepTwo($type_id = 0, $month = '')
	{
		$tdedData = DB::table('tdedball_list')->where('type_id', $type_id)->where('active_status', 1);

		// 4 only
		if ($month != '') {
			$tdedData->whereRaw("DATE_FORMAT(created_at,'%Y-%m') = '" . $month . "'");
			$tdedData->orderBy('created_at', 'desc');
		}

		$tdedList = array();

		if ($tdedData->count() > 0) {
			foreach($tdedData->get() as $tded) {
				$teamSelected = $tded->team_selected;
				$teamList = explode(',', $teamSelected);

				if (count($teamList) > 0) {
					$teamSelectedOne = $teamList[0];
					$oneRow = $this->resultStep($teamSelectedOne);

					$slTwo = '';
					$rsTwo = '';

					if (array_key_exists(1, $teamList)) {
						$teamSelectedTwo = $teamList[1];
						$twoRow = $this->resultStep($teamSelectedTwo);
						$slTwo = $twoRow['name'];
						$rsTwo = $twoRow['result'];
					}

					$tdedList[] = array('match_date' => $this->common->showDate($tded->created_at),
								'selected_one' => $oneRow['name'],
								'result_one' => $oneRow['result'],
								'selected_two' => $slTwo,
								'result_two' => $rsTwo
							);
				}
				
			}
		}

		return $tdedList;
	}

	public function tdedtepThree($type_id = 0, $month = '')
	{
		$tdedData = DB::table('tdedball_list')->where('type_id', $type_id)->where('active_status', 1);

		// 5 only
		if ($month != '') {
			$tdedData->whereRaw("DATE_FORMAT(created_at,'%Y-%m') = '" . $month . "'");
			$tdedData->orderBy('created_at', 'desc');
		}

		$tdedList = array();

		if ($tdedData->count() > 0) {
			foreach($tdedData->get() as $tded) {
				$teamSelected = $tded->team_selected;
				$teamList = explode(',', $teamSelected);

				if (count($teamList) > 0) {
					$teamSelectedOne = $teamList[0];
					$oneRow = $this->resultStep($teamSelectedOne);

					// $teamSelectedTwo = $teamList[1];
					// $twoRow = $this->resultStep($teamSelectedTwo);
					$slTwo = '';
					$rsTwo = '';

					if (array_key_exists(1, $teamList)) {
						$teamSelectedTwo = $teamList[1];
						$twoRow = $this->resultStep($teamSelectedTwo);
						$slTwo = $twoRow['name'];
						$rsTwo = $twoRow['result'];
					}

					// $teamSelectedThree = $teamList[2];
					// $threeRow = $this->resultStep($teamSelectedThree);
					$slThree = '';
					$rsThree = '';

					if (array_key_exists(2, $teamList)) {
						$teamSelectedThree = $teamList[2];
						$twoRow = $this->resultStep($teamSelectedThree);
						$slThree = $twoRow['name'];
						$rsThree = $twoRow['result'];
					}

					$tdedList[] = array('match_date' => $this->common->showDate($tded->created_at),
								'selected_one' => $oneRow['name'],
								'result_one' => $oneRow['result'],
								'selected_two' => $slTwo,
								'result_two' => $rsTwo,
								'selected_three' => $slThree,
								'result_three' => $rsThree
							);
				}
				
			}
		}

		return $tdedList;
	}

	public function resultStep($teamSelected)
	{
		$preBet = explode('_', $teamSelected);
		$pbId = $preBet[0];
		$tdCont = $preBet[1];
		$pbData = DB::table('prediction_bets')->where('id', $pbId)->first();

		$name = '';
		$matchResult = '';

		if ($pbData) {
			$win = $this->winResult($tdCont, $pbData->match_result_status);
			$matchResult = ($win >= 3) ? $this->colorResult($win) : '';
			$name = ((int) $tdCont == 1) ? $pbData->home_team : $pbData->away_team;
		}

		return array('name' => $name, 'result' => $matchResult); // , 'date' => $pbData->match_time);
	}

	public function prevNextTdedLink($dateParams = '', $mode = '', $type_id = 0)
	{
		$dateSelected = '';

		$tdedballList = null;

		if ($mode == 'prev') {
			$tdedballList = DB::table('tdedball_list')->selectRaw(DB::raw("DATE(created_at) as createdDate"))->where('type_id', $type_id)->whereRaw("DATE(created_at) < '" . $dateParams . "'")->orderBy('created_at', 'desc')->first();
		} else {
			$tdedballList = DB::table('tdedball_list')->selectRaw(DB::raw("DATE(created_at) as createdDate"))->where('type_id', $type_id)->whereRaw("DATE(created_at) > '" . $dateParams . "'")->orderBy('created_at', 'asc')->first();
		}

		if ($tdedballList) {
			$dateSelected = $tdedballList->createdDate;
		}

		$link = ($dateSelected) ? '<a href="' . route('tdedball', $dateSelected) . '" class="c-theme">' . $this->common->showDate($dateSelected) . '</a>' : '';

		return $link;
	}

	public function prevPageTdedContLink($type_id, $monthParams = '', $mode = '')
	{
		$monthSelected = '';

		$tdedballList = null;

		if ($mode == 'prev') {
			$tdedballList = DB::table('tdedball_list')->selectRaw(DB::raw("DATE_FORMAT(created_at,'%Y-%m') as month"))->where('type_id', $type_id)->whereRaw("DATE_FORMAT(created_at,'%Y-%m') < '" . $monthParams . "'")->orderBy('created_at', 'desc')->first();
		} else {
			$tdedballList = DB::table('tdedball_list')->selectRaw(DB::raw("DATE_FORMAT(created_at,'%Y-%m') as month"))->where('type_id', $type_id)->whereRaw("DATE_FORMAT(created_at,'%Y-%m') > '" . $monthParams . "'")->orderBy('created_at', 'asc')->first();
		}

		if ($tdedballList) {
			$monthSelected = $tdedballList->month;
		}

		$route = '';

		if ($type_id == 1) {
			$route = route('tdedball-cont', $monthSelected);
		} else if ($type_id == 2) {
			$route = route('tdedball-not-cont', $monthSelected);
		} else if ($type_id == 3) {
			$route = route('tdedball-teng', $monthSelected);
		} else if ($type_id == 4) {
			$route = route('tdedball-step-two', $monthSelected);
		} else if ($type_id == 5) {
			$route = route('tdedball-step-three', $monthSelected);
		}

		$link = ($monthSelected) ? '<a href="' . $route . '" class="c-theme">' . $this->common->showMonthYearOnly($monthSelected) . '</a>' : '';

		return $link;
	}

}

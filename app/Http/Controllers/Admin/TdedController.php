<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\CheckSystemController;
use App\Http\Controllers\API\CommonController;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;
use \stdClass;

class TdedController extends Controller
{
    private $conn;
    private $menus;
    private $common;

    public function __construct()
    {
        $this->common = new CommonController();
        $this->conn = CheckSystemController::checkTableExist('tdedball_list');

        if ($this->conn['table']) {
            $this->menus = Menu::allMenus();
        }
    }

    public function index()
    {
        return view('backend/tded/index', ['menus' => $this->menus]);
    }

    public function create()
    {
        // $datas = $this->getPredictions();
        return null;
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $form = new stdClass();

        $preBetDatas = DB::table('tdedball_list')->where('tdedball_list_id', $id);

        if ($preBetDatas->count() > 0) {
            $rows = $preBetDatas->get();
            $form = $rows[0];

            $teamSelectedList = array();
    
            $tdedData = DB::table('tdedball_list')->select(['team_selected'])->where('tdedball_list_id', $id)->first();
            if ($tdedData) {
                $selected = $tdedData->team_selected;
                $teamSelectedList = explode(',', trim($selected));
            }

            $tdedPredictionList = $this->getTdedPredictions($teamSelectedList);

            $tdedType = DB::table('tdedball_type')->select(['tdedball_type_id', 'type_name'])->where('active_status', 1);

            return view('backend/tded/edit',
                        ['menus' => $this->menus,
                        'form' => $form,
                        'team_selected_list' => $teamSelectedList,
                        'tded_type' => $tdedType,
                        'datas' => $tdedPredictionList]
                    );
        } else {
            echo 'Not found this ID.';
        }

    }

	public function getTdedPredictions($teamSelectedList = array()) {
        $datas = array();

		if (count($teamSelectedList) != 0) {
            $records = array();
            $names = array();

			foreach($teamSelectedList as $pid_team) {
				$arrPidTeam = explode('_', $pid_team); // 19_2
				$pbId = $arrPidTeam[0];

                $preBetData = DB::table('prediction_bets')->where('id', $pbId)->first();

				if ($preBetData) {
					$records[] = array('prediction_id'=> $preBetData->id,
                                        'league_name'=> trim($preBetData->league_name),
                                        'match_time'=> $preBetData->match_time,
                                        'home_team' => $preBetData->home_team,
                                        'away_team' => $preBetData->away_team,
                                        'bargain_price' => $preBetData->bargain_price,
                                        'match_continue' => $preBetData->match_continue
                                    );

                    if (! in_array(trim($preBetData->league_name), $names)) {
                        $names[] = $preBetData->league_name;
                    }
				}
            }

            foreach ($names as $lName) {
                $matches = array();

                foreach($records as $row) {
                    if ($row['league_name'] == $lName) {
                        $matches[] = $row;
                    }
                }

                $datas[] = array('name' => $lName, 'datas' => $matches);
            }
		}

		return $datas;
    }

    /*
	public function getPredictions() {
        // $preBetDatas = DB::table('prediction_bets')->whereRaw("DATE(created_at) = '" . Date('Y-m-d', strtotime("-1 days")) . "'")->where('is_tded', 1);
    
        $datas = array();

		if ($preBetDatas->count() > 0) {
			$names = array();

			foreach($preBetDatas->get() as $val) {
				if(! in_array(trim($val->league_name), $names)){
					$names[] = trim($val->league_name);
				}
			}

			foreach($names as $name) {
                $data = array();

				foreach($preBetDatas->get() as $val) {
					if (trim($val->league_name) == $name) {
						$data[] = array('prediction_id' => $val->id
										,'match_time' => $this->common->showDayOnly(strtotime($val->match_time))
										,'match_time_other' => $this->common->showDateTime($val->match_time)
										,'left' => $this->common->leftToMatch($val->match_time)
										,'home_team' => $val->home_team
										,'away_team' => $val->away_team
										,'bargain_price' => $val->bargain_price
                                        ,'match_continue' => $val->match_continue
                                );
					}
				}

				$datas[] = array('name' => $name, 'datas' => $data);
			}
        }

		return $datas;
    }
    */
}

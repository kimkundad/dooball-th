<?php

namespace App\Http\Controllers\API\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\CommonController;
// use App\Models\ContentDetail;
use Illuminate\Support\Facades\DB;

class TdedController extends Controller
{
    private $order_by;
    private $common;

    public function __construct()
    {
        $this->order_by = array('tdedball_list_id', 'type_id', 'team_selected', 'created_at', 'created_at');
        $this->common = new CommonController();
    }

    public function index(Request $request)
    {
        $ret_data = array();
        $draw = (int) $request->input('draw');
        $start = (int) $request->input('start');
        $length = (int) $request->input('length');
        $order = $request->input('order');
        $searchText = $request->input('search');
        $searchText = trim($searchText);

        // ------------- start total --------------- //
        $mnTotal = DB::table('tdedball_list')
                        ->leftJoin('tdedball_type', 'tdedball_list.type_id', '=', 'tdedball_type.tdedball_type_id');

        // if ($searchText) {
        //     $mnTotal->where('title', 'like', $searchText . '%');
        // }

        $recordsTotal = $mnTotal->count();
        // ------------- end total --------------- //

        // ------------- start datas --------------- //
        $mnData = DB::table('tdedball_list')
                        ->leftJoin('tdedball_type', 'tdedball_list.type_id', '=', 'tdedball_type.tdedball_type_id')
                        ->select(['tdedball_list.tdedball_list_id', 'tdedball_type.type_name', 'tdedball_list.team_selected', 'tdedball_list.created_at', 'tdedball_list.active_status']);

        // if (trim($searchText)) {
        //     $datas = $mnData->where('title', 'like', $searchText . '%');
        // }

        if (array_key_exists('column', $order[0]) && array_key_exists('dir', $order[0])) {
            $mnData->orderBy($this->order_by[$order[0]['column']], $order[0]['dir']);
        }

        $datas = $mnData->skip((int) $start)->take($length)->get();
        $total = count($datas);
        // ------------- end datas --------------- //

        if ($total > 0) {
            foreach ($datas as $tded) {
                $createDate = $this->common->showDate($tded->created_at);

                $teamSelectedList = (trim($tded->team_selected)) ? explode(',', trim($tded->team_selected)) : array();

                // --- start show tded format --- //
                $tded_detail = '';

				if (count($teamSelectedList) != 0) {
					$tded_detail .= '<table class="table table-condensed table-striped">';
					$tded_detail .= 	'<tr>';
					$tded_detail .= 		'<th class="text-center">เวลาแข่ง</th>';
					$tded_detail .= 		'<th class="text-center">ทีเด็ดบอลที่เลือก</th>';
					$tded_detail .= 		'<th class="text-center">ทีมที่เลือก</th>';
                    $tded_detail .= 	'</tr>';
                    
                    foreach($teamSelectedList as $v) {
						$arr_pre = explode('_', $v);
                        $preBetId = $arr_pre[0];
                        $cont = $arr_pre[1];
                        $team_pick = '';

                        $data = DB::table('prediction_bets')->where('id', $preBetId)->first();

						if ($data) {
							$home_team = $data->home_team;
							$away_team = $data->away_team;
                            $team_pick = ((int)$cont == 1) ? $home_team : $away_team;
                            $pre_cond = $data->match_continue;

							if ((int) $pre_cond == 1) {
								$home_team = '<span class="continue">' . $data->home_team . '</span>';
							} else {
								$away_team = '<span class="continue">' . $data->away_team . '</span>';
							}

							// $result symbol with team_pick
							$tded_detail .= '<tr>';
							$tded_detail .=		'<td class="text-center">' . $this->common->showDateTime($data->match_time) . '</td>';
							$tded_detail .=		'<td class="text-center">' . $home_team . ' Vs ' . $away_team . '</td>';
							$tded_detail .=		'<td class="text-center">' . $team_pick . '</td>';
							$tded_detail .= '</tr>';
						}
                    }

					$tded_detail .= '</table>';
                }
                // --- end show tded format --- //

                $status = ((int) $tded->active_status == 1) ? '<span class="label label-success">ใช้งาน</span>' : '<span class="label label-danger">ไม่ใช้งาน</span>';

                $options = '<div class="flex-option">';
                $options .= '<a href="' . URL('/') . '/admin/tded/' . $tded->tdedball_list_id . '/edit" class="btn btn-warning btn-sm tooltips" title="แก้ไขทีเด็ด"><i class="fa fa-pencil"></i></a>';
                $options .= '</div>';

                $ret_data[] = array($tded->tdedball_list_id
                                    , $tded->type_name
                                    , $tded_detail
                                    , $createDate
                                    , $status
                                    , $options
                                );
            }

            $datas = ["draw" => ($draw) ? $draw : 0,
                "recordsTotal" => (int) $recordsTotal,
                "recordsFiltered" => (int) $recordsTotal,
                "data" => $ret_data];

            echo json_encode($datas);
        } else {
            $datas = ["draw" => ($draw) ? $draw : 0,
                "recordsTotal" => (int) $recordsTotal,
                "recordsFiltered" => (int) $recordsTotal,
                "data" => $ret_data];

            echo json_encode($datas);
        }
    }


    public function saveUpdate(Request $request)
    {
        $total = 0;
        $message = '';
        
		$id = $request->tdedball_list_id;
		$type_id = $request->type_id;
        $team_selected = $request->team_selected;

        $created_at = Date('Y-m-d H:i:s');

        $affected = DB::table('tdedball_list')
                        ->where('tdedball_list_id', $id)
                        ->update(['team_selected' => $team_selected]);

        if ($affected) {
            $total = 1;
            $message = 'Save success';
        } else {
            $message = 'Save error!';
        }

        $model = array('total' => $total, 'message' => $message);

        return response()->json($model);
    }

    public function info($id)
    {
        //
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

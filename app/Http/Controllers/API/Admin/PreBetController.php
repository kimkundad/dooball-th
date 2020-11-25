<?php

namespace App\Http\Controllers\API\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\CommonController;
use App\Models\ContentDetail;
use Illuminate\Support\Facades\DB;

class PreBetController extends Controller
{
    private $order_by;
    private $common;

    public function __construct()
    {
        $this->order_by = ['id', 'ffp_detail_id', 'match_time', 'league_name', 'home_team', 'away_team', 'bargain_price', 'match_continue', 'match_result_status', 'created_at', 'ip', 'options'];
        $this->common = new CommonController();
    }

    function list(Request $request) {
        $ret_data = array();
        $draw = (int) $request->input('draw');
        $start = (int) $request->input('start');
        $length = (int) $request->input('length');
        $order = $request->input('order');
        $searchText = $request->input('search');
        $searchText = trim($searchText);

        // ------------- start total --------------- //
        // DB::enableQueryLog();
        $mnTotal = DB::table('prediction_bets')
                    ->select(['id', 'ffp_detail_id', 'match_time', 'league_name', 'home_team', 'away_team', 'bargain_price', 'match_continue', 'match_result_status', 'created_at']);

        if ($searchText) {
            $mnTotal->where('league_name', 'like', '%' . $searchText . '%')
                    ->orWhere('home_team', 'like', '%' . $searchText . '%')
                    ->orWhere('away_team', 'like', '%' . $searchText . '%');
        }

        $recordsTotal = $mnTotal->count();
        // $q = DB::getQueryLog()[0]['query'];
        // dd($q);
        // ------------- end total --------------- //

        // ------------- start datas --------------- //
        // DB::enableQueryLog();
        $mnData = DB::table('prediction_bets')
                    ->select(['id', 'ffp_detail_id', 'match_time', 'league_name', 'home_team', 'away_team', 'bargain_price', 'match_continue', 'match_result_status', 'created_at']);

        if (trim($searchText)) {
            $mnData->where('league_name', 'like', $searchText . '%')
                    ->orWhere('home_team', 'like', '%' . $searchText . '%')
                    ->orWhere('away_team', 'like', '%' . $searchText . '%');
        }

        if (array_key_exists('column', $order[0]) && array_key_exists('dir', $order[0])) {
            $mnData->orderBy($this->order_by[$order[0]['column']], $order[0]['dir']);
        }

        $datas = $mnData->skip((int) $start)->take($length)->get();
        $total = count($datas);

        // $q = DB::getQueryLog()[0]['query'];
        // dd($q);
        // dd(DB::getQueryLog()[0]['time']);
        // ------------- end datas --------------- //

        if ($total > 0) {
            foreach ($datas as $val) {
                $options = '<div class="flex-option">';

                $options .= '<a href="' . URL('/') . '/admin/pre-bet/' . $val->id . '/edit" class="btn btn-warning btn-sm tooltips" title="แก้ไขเกมส์ทายผล"><i class="fa fa-pencil"></i></a>';

                // if ((int) $val->active_status == 1) {
                //     $options .= '<button type="button" class="btn btn-danger btn-del btn-sm tooltips"  onclick="deleteItem(' . $val->id . ', \'prediction\');" title="ลบเกมทายผล"><i class="fa fa-trash"></i></button>';
                // } else {
                //     $options .= '<button type="button" class="btn btn-primary btn-del btn-sm tooltips"  onclick="restoreItem(' . $val->id . ', \'prediction\');" title="เรียกคืนเกมทายผล"><i class="fa fa-refresh"></i></button>';
                // }

                // $options .= $q;
                $options .= '</div>';

                $matchResult = ($val->match_result_status == 0) ? '-' : '';
                $matchResult = ($val->match_result_status == 1) ? 'เจ้าบ้านชนะเต็ม' : $matchResult;
                $matchResult = ($val->match_result_status == 2) ? 'เจ้าบ้านชนะครึ่ง' : $matchResult;
                $matchResult = ($val->match_result_status == 3) ? 'เสมอ' : $matchResult;
                $matchResult = ($val->match_result_status == 4) ? 'ทีมเยือนชนะเต็ม' : $matchResult;
                $matchResult = ($val->match_result_status == 5) ? 'ทีมเยือนชนะครึ่ง' : $matchResult;

                $link = $val->ffp_detail_id;

                $checkExistingGraph = ContentDetail::where('id', $val->ffp_detail_id)->count();

                if ($checkExistingGraph > 0) {
                    $link .= '<br><a href="' . route('football-price-detail') . '?link=' . $val->ffp_detail_id . '" target="_BLANK"><i class="fa fa-line-chart"></i>&nbsp;กราฟบอลไหล</a>';
                }

                $ret_data[] = array($val->id
                    , $link
                    , $val->league_name
                    , $val->match_time
                    , $val->home_team
                    , $val->away_team
                    , $val->bargain_price
                    , (((int) $val->match_continue ==  1) ? 'ทีมเหย้า' : 'ทีมเยือน')
                    , $matchResult
                    , $val->created_at
                    , $options);
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
        $preBetId = $request->pre_bet_id;
        $matchResultStatus = $request->match_result_status;

        $sameDatas = DB::table('prediction_bets')->where('id', $preBetId);

        if ($sameDatas->count() > 0) {
            $rows = $sameDatas->get();
            $row = $rows[0];

            $homeTeam = $row->home_team;
            $awayTeam = $row->away_team;

            $affected = DB::table('prediction_bets')
                        ->where('home_team', $homeTeam)
                        ->where('away_team', $awayTeam)
                        ->update(['match_result_status' => $matchResultStatus]);

            if ($affected) {
                $total = $affected;
                $message = 'Save success';
            }
        }


        $model = array('total' => $total, 'message' => $message);

        return response()->json($model);
    }

    public function info($id)
    {
        //
    }

}

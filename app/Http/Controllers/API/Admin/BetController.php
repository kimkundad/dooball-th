<?php

namespace App\Http\Controllers\API\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\CommonController;
use App\Models\Bet;
use Illuminate\Support\Facades\DB;

class BetController extends Controller
{
    private $order_by;
    private $common;

    public function __construct()
    {
        $this->order_by = ['', 'id', 'user_id', 'ffp_detail_id', 'match_time', 'league_name', 'home_team', 'away_team', 'bargain_price', 'match_continue', '', 'created_at', 'ip'];
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
        $mnTotal = DB::table('bets')
                    ->leftJoin('prediction_bets', 'bets.pre_bet_id', '=', 'prediction_bets.id')
                    ->leftJoin('users', 'bets.user_id', '=', 'users.id')
                    ->select(['bets.id', 'users.screen_name', 'prediction_bets.ffp_detail_id', 'prediction_bets.match_time', 'prediction_bets.league_name', 'prediction_bets.home_team', 'prediction_bets.away_team', 'prediction_bets.bargain_price', 'bets.match_continue', 'prediction_bets.match_result_status', 'bets.created_at', 'bets.ip', 'bets.active_status']);

        if ($searchText) {
            $mnTotal->where('prediction_bets.league_name', 'like', '%' . $searchText . '%')
                    ->orWhere('prediction_bets.home_team', 'like', '%' . $searchText . '%')
                    ->orWhere('prediction_bets.away_team', 'like', '%' . $searchText . '%');
        }

        $recordsTotal = $mnTotal->count();
        // $q = DB::getQueryLog()[0]['query'];
        // dd($q);
        // ------------- end total --------------- //

        // ------------- start datas --------------- //
        // DB::enableQueryLog();
        $mnData = DB::table('bets')
                    ->leftJoin('prediction_bets', 'bets.pre_bet_id', '=', 'prediction_bets.id')
                    ->leftJoin('users', 'bets.user_id', '=', 'users.id')
                    ->select(['bets.id', 'users.screen_name', 'prediction_bets.ffp_detail_id', 'prediction_bets.match_time', 'prediction_bets.league_name', 'prediction_bets.home_team', 'prediction_bets.away_team', 'prediction_bets.bargain_price', 'bets.match_continue', 'prediction_bets.match_result_status', 'bets.created_at', 'bets.ip', 'bets.active_status']);

        if (trim($searchText)) {
            $mnData->where('prediction_bets.league_name', 'like', $searchText . '%')
                    ->orWhere('prediction_bets.home_team', 'like', '%' . $searchText . '%')
                    ->orWhere('prediction_bets.away_team', 'like', '%' . $searchText . '%');
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
                $check = '<label class="ctainer">';
                $check .= '<input type="checkbox" class="chk-box" name="chk[]" id="chk_' . $val->id . '" onclick="tickCheckbox()" />';
                $check .= '<span class="checkmark"></span>';
                $check .= '</label>';

                $matchResult = ($val->match_result_status == 0) ? '-' : '';
                $matchResult = ($val->match_result_status == 1) ? 'เจ้าบ้านชนะเต็ม' : $matchResult;
                $matchResult = ($val->match_result_status == 2) ? 'เจ้าบ้านชนะครึ่ง' : $matchResult;
                $matchResult = ($val->match_result_status == 3) ? 'เสมอ' : $matchResult;
                $matchResult = ($val->match_result_status == 4) ? 'ทีมเยือนชนะเต็ม' : $matchResult;
                $matchResult = ($val->match_result_status == 5) ? 'ทีมเยือนชนะครึ่ง' : $matchResult;

                $ret_data[] = array($check
                    , $val->id
                    , $val->screen_name
                    , $val->ffp_detail_id
                    , $val->league_name
                    , $val->match_time
                    , $val->home_team
                    , $val->away_team
                    , $val->bargain_price
                    , (((int) $val->match_continue ==  1) ? 'ทีมเหย้า' : 'ทีมเยือน')
                    , $matchResult
                    , $val->created_at
                    , $val->ip);
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

    public function saveCreate(Request $request)
    {
        $total = 0;
        $message = '';
        $dateTime = Date('Y-m-d H:i:s');

        $data = new Prediction;
        $data->league_name = $request->league_name;
        $data->match_time = $request->match_time;
        $data->home_team = $request->home_team;
        $data->away_team = $request->away_team;
        $data->bargain_price = $request->bargain_price;
        $data->match_continue = $request->match_continue;
        $data->match_result = $request->match_result;
        $data->match_result_status = $request->match_result_status;
        $data->created_at =  $dateTime;

        $saved = $data->save();
        $id = $data->id;

        if ($id) {
            $total = 1;
            $message = 'Save success';
        } else {
            $message = 'Save error!';
        }

        $model = array('total' => $total, 'message' => $message);

        return response()->json($model);
    }

    public function saveUpdate(Request $request)
    {
        $total = 0;
        $message = '';
        $preBetId = $request->pre_bet_id;
        $matchResultStatus = $request->match_result_status;

        $affected = DB::table('prediction_bets')
                ->where('id', $preBetId)
                ->update(['match_result_status' => $matchResultStatus]);

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

    public function deletePrediction(Request $request)
    {
        $total = 0;
        $message = '';

        if ((int) $request->input('id')) {
            $datas = Prediction::find((int) $request->input('id'));
            $datas->active_status = 2;
            $saved = $datas->save();

            $total = 1;
            $message = 'Success';
        }

        $model = array('total' => $total, 'message' => $message);
        return response()->json($model);
    }

    public function multipleDelete(Request $request)
    {
        $total = 0;
        $message = '';

        if ($request->input('ids')) {
            $strIds = $request->input('ids');
            $ids = explode(',', $strIds);
            $datas = Prediction::whereIn('id', $ids)->update(['active_status' => '2']);

            if ($datas) {
                $total = 1;
                $message = 'Success';
            }
        }

        $model = array('total' => $total, 'message' => $message);
        return response()->json($model);
    }

    public function multipleRestore(Request $request)
    {
        $total = 0;
        $message = '';

        if ($request->input('ids')) {
            $strIds = $request->input('ids');
            $ids = explode(',', $strIds);
            $datas = Prediction::whereIn('id', $ids)->update(['active_status' => '1']);

            if ($datas) {
                $total = 1;
                $message = 'Success';
            }
        }

        $model = array('total' => $total, 'message' => $message);
        return response()->json($model);
    }

    public function restorePrediction(Request $request)
    {
        $total = 0;
        $message = '';

        if ((int) $request->input('id')) {
            $datas = Prediction::find((int) $request->input('id'));
            $datas->active_status = 1;
            $saved = $datas->save();

            $total = 1;
            $message = 'Success';
        }

        $model = array('total' => $total, 'message' => $message);
        return response()->json($model);
    }

}

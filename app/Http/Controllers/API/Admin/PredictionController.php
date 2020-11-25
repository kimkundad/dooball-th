<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\CommonController;
use App\Models\Prediction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PredictionController extends Controller
{
    private $order_by;
    private $common;

    public function __construct()
    {
        $this->order_by = array('', 'id', '', 'title', '', 'created_at');
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
        $mnTotal = DB::table('predictions');
            // ->select('users.username', 'id', 'predictions.league_name', 'articles.media_id', 'articles.created_at', 'articles.article_status', 'articles.active_status');
        $dts = $mnTotal;

        if ($searchText) {
            $dts = $mnTotal->where('league_name', 'like', $searchText . '%');
        }

        $recordsTotal = $dts->count();
        // $q = DB::getQueryLog()[0]['query'];
        // dd($q);
        // ------------- end total --------------- //

        // ------------- start datas --------------- //
        // DB::enableQueryLog();
        $mnData = DB::table('predictions');
            // ->select('id', 'articles.league_name', 'articles.media_id', 'articles.created_at', 'articles.article_status', 'articles.active_status');

        if (trim($searchText)) {
            $datas = $mnData->where('league_name', 'like', $searchText . '%');
        }

        if (array_key_exists('column', $order[0]) && array_key_exists('dir', $order[0])) {
            $datas = $mnData->orderBy($this->order_by[$order[0]['column']], $order[0]['dir']);
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

                $options = '<div class="flex-option">';
                $options .= '<a href="' . URL('/') . '/admin/prediction/' . $val->id . '/edit" class="btn btn-warning btn-sm tooltips" title="แก้ไขเกมทายผล"><i class="fa fa-pencil"></i></a>';

                if ((int) $val->active_status == 1) {
                    $options .= '<button type="button" class="btn btn-danger btn-del btn-sm tooltips"  onclick="deleteItem(' . $val->id . ', \'prediction\');" title="ลบเกมทายผล"><i class="fa fa-trash"></i></button>';
                } else {
                    $options .= '<button type="button" class="btn btn-primary btn-del btn-sm tooltips"  onclick="restoreItem(' . $val->id . ', \'prediction\');" title="เรียกคืนเกมทายผล"><i class="fa fa-refresh"></i></button>';
                }

                // $options .= $q;
                $options .= '</div>';

                $ret_data[] = array($check
                    , $val->id
                    , $val->league_name
                    , $val->match_time
                    , $val->home_team
                    , $val->away_team
                    , $val->bargain_price
                    , (((int) $val->match_continue ==  1) ? 'ทีมเหย้า' : 'ทีมเยือน')
                    , $val->match_result
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
        $predictionId = $request->prediction_id;

        $data = Prediction::find($predictionId);
        $data->league_name = $request->league_name;
        $data->match_time = $request->match_time;
        $data->home_team = $request->home_team;
        $data->away_team = $request->away_team;
        $data->bargain_price = $request->bargain_price;
        $data->match_continue = $request->match_continue;
        $data->match_result = $request->match_result;
        $data->match_result_status = $request->match_result_status;
        $saved = $data->save();

        if ($saved) {
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

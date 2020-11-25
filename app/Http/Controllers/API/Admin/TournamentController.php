<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
// use App\Http\Requests\Backend\TournamentRequest;
use App\Models\League;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\OnPage;

class TournamentController extends Controller
{
    private $order_by;

    public function __construct()
    {
        $this->order_by = array('id', 'highlight', 'name_th', 'name_en', 'api_name', 'short_name', 'long_name', 'url', 'years', 'highlight', '');
    }

    function qList(Request $request) {
        $searchText = $request->input('q');
        // dd($request->all());
        $datas = League::all();
        return response()->json(['items' => $datas]);
    }

    function index(Request $request) {
        $ret_data = array();
        $draw = (int) $request->input('draw');
        $start = (int) $request->input('start');
        $length = (int) $request->input('length');
        $order = $request->input('order');
        $searchText = $request->input('search');
        $searchText = trim($searchText);

        $mnTotal = DB::table('leagues');

        if ($searchText) {
            $mnTotal->where('name_th', 'like', '%' . $searchText . '%');
            $mnTotal->orWhere('name_en', 'like', '%' . $searchText . '%');
        }

        $recordsTotal = $mnTotal->count();

        $mnData = DB::table('leagues');

        if (trim($searchText)) {
            $mnData->where('name_th', 'like', '%' . $searchText . '%');
            $mnData->orWhere('name_en', 'like', '%' . $searchText . '%');
        }

        if (array_key_exists('column', $order[0]) && array_key_exists('dir', $order[0])) {
            $datas = $mnData->orderBy($this->order_by[$order[0]['column']], $order[0]['dir']);
        }

        $datas = $mnData->skip((int) $start)->take($length)->get();
        $total = count($datas);

        if ($total > 0) {
            foreach ($datas as $league) {
                // $check = '<label class="ctainer">';
                // $check .= '<input type="checkbox" class="chk-box" name="chk[]" id="chk_' . $league->id . '" onclick="tickCheckbox()" />';
                // $check .= '<span class="checkmark"></span>';
                // $check .= '</label>';

                $options = '<div class="flex-option">';
                $options .= '<a href="' . URL('/') . '/admin/tournament/' . $league->id . '/edit" class="btn btn-warning btn-sm tooltips" title="แก้ไขลีก"><i class="fa fa-pencil"></i></a>';

                if ((int) $league->active_status == 1) {
                    $options .= '<button type="button" class="btn btn-danger btn-del btn-sm tooltips"  onclick="deleteItem(' . $league->id . ', \'tournament\');" title="ลบทัวร์นาเม้นท์"><i class="fa fa-trash"></i></button>';
                } else {
                    $options .= '<button type="button" class="btn btn-primary btn-del btn-sm tooltips"  onclick="restoreItem(' . $league->id . ', \'tournament\');" title="เรียกคืนทัวร์นาเม้นท์"><i class="fa fa-refresh"></i></button>';
                }

                // $options .= $q;
                $options .= '</div>';

                $ret_data[] = array($league->id
                    , ((int) $league->highlight == 1) ? '<span class="label label-success">ใหญ่</span>' : '<span class="label label-default">เล็ก</span>'
                    , $league->name_th
                    , $league->name_en
                    , $league->api_name
                    , $league->short_name
                    , $league->long_name
                    , $league->url
                    , '<div class="tud-bun-tud" style="width:200px;">' . $league->years . '</div>'
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

        $data = new League;
        $data->name_th = $request->name_th;
        $data->name_en = $request->name_en;
        $data->api_name = $request->api_name;
        $data->short_name = $request->short_name;
        $data->long_name = $request->long_name;
        $data->url = $request->url;
        $data->years = $request->years;
        $data->onpage_id = $request->onpage_id;
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

    public function saveUpdate(Request $request)
    {
        $total = 0;
        $message = '';

        $data = League::find($request->league_id);
        $data->name_th = $request->name_th;
        $data->name_en = $request->name_en;
        $data->api_name = $request->api_name;
        $data->short_name = $request->short_name;
        $data->long_name = $request->long_name;
        $data->url = $request->url;
        $data->years = $request->years;
        $data->active_status = $request->active_status;
        $data->onpage_id = $request->onpage_id;
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

    public function deleteTournament(Request $request)
    {
        $total = 0;
        $message = '';

        if ((int) $request->input('id')) {
            $datas = League::find((int) $request->input('id'));
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
            $datas = League::whereIn('id', $ids)->update(['active_status' => '2']);

            if ($datas) {
                $total = 1;
                $message = 'Success';
            }
        }

        $model = array('total' => $total, 'message' => $message);
        return response()->json($model);
    }

    public function restoreTournament(Request $request)
    {
        $total = 0;
        $message = '';

        if ((int) $request->input('id')) {
            $datas = League::find((int) $request->input('id'));
            $datas->active_status = 1;
            $saved = $datas->save();

            $total = 1;
            $message = 'Success';
        }

        $model = array('total' => $total, 'message' => $message);
        return response()->json($model);
    }

}

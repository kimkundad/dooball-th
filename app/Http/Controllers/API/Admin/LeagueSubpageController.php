<?php

namespace App\Http\Controllers\API\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\League;
use App\Models\LeagueSubpage;
use App\Models\OnPage;
use Illuminate\Support\Facades\DB;

class LeagueSubpageController extends Controller
{
    private $order_by;

    public function __construct()
    {
        $this->order_by = array('id', 'league_url', 'page_url', '', '');
    }

    function index(Request $request) {
        $ret_data = array();
        $draw = (int) $request->input('draw');
        $start = (int) $request->input('start');
        $length = (int) $request->input('length');
        $order = $request->input('order');
        $searchText = $request->input('search');
        $searchText = trim($searchText);

        $mnTotal = DB::table('league_subpages');

        if ($searchText) {
            $mnTotal->where('page_url', 'like', '%' . $searchText . '%');
            $mnTotal->orWhere('league_url', 'like', '%' . $searchText . '%');
        }

        $recordsTotal = $mnTotal->count();

        $mnData = DB::table('league_subpages');

        if (trim($searchText)) {
            $mnData->where('page_url', 'like', '%' . $searchText . '%');
            $mnData->orWhere('league_url', 'like', '%' . $searchText . '%');
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
                $options .= '<a href="' . URL('/') . '/admin/leaguesubpage/' . $league->id . '/edit" class="btn btn-warning btn-sm tooltips" title="แก้ไขลีก"><i class="fa fa-pencil"></i></a>';

                // if ((int) $league->active_status == 1) {
                //     $options .= '<button type="button" class="btn btn-danger btn-del btn-sm tooltips"  onclick="deleteItem(' . $league->id . ', \'leaguesubpage\');" title="ลบทัวร์นาเม้นท์"><i class="fa fa-trash"></i></button>';
                // } else {
                //     $options .= '<button type="button" class="btn btn-primary btn-del btn-sm tooltips"  onclick="restoreItem(' . $league->id . ', \'leaguesubpage\');" title="เรียกคืนทัวร์นาเม้นท์"><i class="fa fa-refresh"></i></button>';
                // }

                // $options .= $q;
                $options .= '</div>';
                
                $onpage = '';

                if ((int) $league->onpage_id != 0) {
                    $onpageData = OnPage::findOrFail($league->onpage_id);

                    if ($onpageData) {
                        $onpage = $onpageData->code_name;
                    }
                }

                $ret_data[] = array($league->id
                    , $league->league_url
                    , $league->page_url
                    , $onpage
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

        $data = new LeagueSubpage;
        $data->page_url = $request->page_url;
        $data->league_url = $request->league_url;
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

        $data = LeagueSubpage::find($request->leaguesubpage_id);
        $data->page_url = $request->page_url;
        $data->league_url = $request->league_url;
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
}

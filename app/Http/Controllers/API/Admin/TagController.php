<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\API\CommonController;
use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TagController extends Controller
{
    private $order_by;
    private $common;

    public function __construct()
    {
        $this->order_by = array('id', 'tag_name', 'title', 'description', 'detail', 'options');
        $this->common = new CommonController();
    }

    function qList(Request $request) {
        $searchText = $request->input('q');
        // dd($request->all());
        $datas = Tag::all();
        return response()->json(['items' => $datas]);
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
        $mnTotal = DB::table('tags');
        $dts = $mnTotal;

        if ($searchText) {
            $dts = $mnTotal->where('tag_name', 'like', $searchText . '%');
        }

        $recordsTotal = $dts->count();
        // $q = DB::getQueryLog()[0]['query'];
        // dd($q);
        // ------------- end total --------------- //

        // ------------- start datas --------------- //
        // DB::enableQueryLog();
        $mnData = DB::table('tags');

        if (trim($searchText)) {
            $datas = $mnData->where('tag_name', 'like', $searchText . '%');
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
            foreach ($datas as $tag) {
                $check = '<label class="ctainer">';
                $check .= '<input type="checkbox" class="chk-box" name="chk[]" id="chk_' . $tag->id . '" onclick="tickCheckbox()" />';
                $check .= '<span class="checkmark"></span>';
                $check .= '</label>';

                $options = '<div class="flex-option">';
                $options .= '<a href="' . URL('/') . '/admin/tag/' . $tag->id . '/edit" class="btn btn-warning btn-sm tooltips" title="แก้ไขทีม"><i class="fa fa-pencil"></i></a>';
                $options .= '<button type="button" class="btn btn-danger btn-del btn-sm tooltips"  onclick="deleteItem(' . $tag->id . ', \'tag\');" title="ลบทีม"><i class="fa fa-trash"></i></button>';

                // $options .= $q;
                $options .= '</div>';

                $ret_data[] = array($check
                    , $tag->tag_name
                    , $tag->title
                    , $tag->description
                    , $tag->detail
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

        $data = new Tag;
        $data->tag_name = $request->tag_name;
        $data->title = $request->title;
        $data->description = $request->description;
        $data->detail = $request->detail;
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

        $data = Tag::find($request->tag_id);
        $data->tag_name = $request->tag_name;
        $data->title = $request->title;
        $data->description = $request->description;
        $data->detail = $request->detail;
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

    public function deleteTag(Request $request)
    {
        $total = 0;
        $message = '';

        if ((int) $request->input('id')) {
            $datas = Tag::find((int) $request->input('id'));
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
            $datas = Tag::whereIn('id', $ids)->update(['active_status' => '2']);

            if ($datas) {
                $total = 1;
                $message = 'Success';
            }
        }

        $model = array('total' => $total, 'message' => $message);
        return response()->json($model);
    }

    public function restoreTag(Request $request)
    {
        $total = 0;
        $message = '';

        if ((int) $request->input('id')) {
            $datas = Tag::find((int) $request->input('id'));
            $datas->active_status = 1;
            $saved = $datas->save();

            $total = 1;
            $message = 'Success';
        }

        $model = array('total' => $total, 'message' => $message);
        return response()->json($model);
    }
}

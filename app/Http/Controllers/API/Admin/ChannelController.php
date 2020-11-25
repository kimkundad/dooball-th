<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\ChannelRequest;
use App\Models\Channel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChannelController extends Controller
{
    private $order_by;

    public function __construct()
    {
        $this->order_by = array('id', 'channel_name', '');
    }

    function qList(Request $request) {
        $searchText = $request->input('q');
        // dd($request->all());
        $datas = Channel::all();
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
        $mnTotal = DB::table('channels');
        $dts = $mnTotal;

        if ($searchText) {
            $dts = $mnTotal->where('channel_name', 'like', $searchText . '%');
        }

        $recordsTotal = $dts->count();
        // $q = DB::getQueryLog()[0]['query'];
        // dd($q);
        // ------------- end total --------------- //

        // ------------- start datas --------------- //
        // DB::enableQueryLog();
        $mnData = DB::table('channels');

        if (trim($searchText)) {
            $datas = $mnData->where('channel_name', 'like', $searchText . '%');
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
            foreach ($datas as $channel) {
                $check = '<label class="ctainer">';
                $check .= '<input type="checkbox" class="chk-box" name="chk[]" id="chk_' . $channel->id . '" onclick="tickCheckbox()" />';
                $check .= '<span class="checkmark"></span>';
                $check .= '</label>';

                $options = '<div class="flex-option">';
                $options .= '<a href="' . URL('/') . '/admin/channel/' . $channel->id . '/edit" class="btn btn-warning btn-sm tooltips" title="แก้ไขแมทช์"><i class="fa fa-pencil"></i></a>';

                if ((int) $channel->active_status == 1) {
                    $options .= '<button type="button" class="btn btn-danger btn-del btn-sm tooltips"  onclick="deleteItem(' . $channel->id . ', \'channel\');" title="ลบช่องรายการทีวี"><i class="fa fa-trash"></i></button>';
                } else {
                    $options .= '<button type="button" class="btn btn-primary btn-del btn-sm tooltips"  onclick="restoreItem(' . $channel->id . ', \'channel\');" title="เรียกคืนช่องรายการทีวี"><i class="fa fa-refresh"></i></button>';
                }

                // $options .= $q;
                $options .= '</div>';

                $ret_data[] = array($check
                    , $channel->channel_name
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

    public function saveCreate(ChannelRequest $request)
    {
        $total = 0;
        $message = '';

        $datas = new Channel;
        $datas->channel_name = $request->channel_name;
        $saved = $datas->save();

        if ($saved) {
            $total = 1;
            $message = 'Save success';
        } else {
            $message = 'Save error!';
        }

        $model = array('total' => $total, 'message' => $message);

        return response()->json($model);
    }

    public function saveUpdate(ChannelRequest $request)
    {
        $total = 0;
        $message = '';

        $datas = Channel::find($request->channel_id);
        $datas->channel_name = $request->channel_name;
        // $datas->active_status = $request->active_status;
        $saved = $datas->save();

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

    public function deleteChannel(Request $request)
    {
        $total = 0;
        $message = '';

        if ((int) $request->input('id')) {
            $datas = Channel::find((int) $request->input('id'));
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
            $datas = Channel::whereIn('id', $ids)->update(['active_status' => '2']);

            if ($datas) {
                $total = 1;
                $message = 'Success';
            }
        }

        $model = array('total' => $total, 'message' => $message);
        return response()->json($model);
    }

    public function restoreChannel(Request $request)
    {
        $total = 0;
        $message = '';

        if ((int) $request->input('id')) {
            $datas = Channel::find((int) $request->input('id'));
            $datas->active_status = 1;
            $saved = $datas->save();

            $total = 1;
            $message = 'Success';
        }

        $model = array('total' => $total, 'message' => $message);
        return response()->json($model);
    }

}

<?php

namespace App\Http\Controllers\API\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Link;
use App\Models\Match;
use App\Models\MatchLink;
use Illuminate\Support\Facades\DB;

class LinkController extends Controller
{
    private $order_by;
    private $order_mby;

    public function __construct()
    {
        $this->order_by = array('id', 'link_url', 'link_name', '');
        $this->order_mby = array('id', 'match_id', 'link_type', 'name', 'url', 'link_seq', '');
    }

    public function list(Request $request)
    {
        $ret_data = array();
        $draw = (int)$request->input('draw');
        $start = (int)$request->input('start');
        $length = (int)$request->input('length');
        $order = $request->input('order');

        $mnTotal = DB::table('links');
        $recordsTotal = $mnTotal->count();

        // DB::enableQueryLog();
        $mnData = DB::table('links');
        $datas = $mnData;

        if (array_key_exists('column', $order[0]) && array_key_exists('dir', $order[0])) {
            $datas = $mnData->orderBy($this->order_by[$order[0]['column']], $order[0]['dir']);
        }

        $datas = $mnData->skip((int)$start)->take($length)->get();
        $total = count($datas);

        // $q = DB::getQueryLog()[0]['query'];
        // dd($q);
        // dd(DB::getQueryLog()[0]['time']);

        if ($total > 0) {
            foreach($datas as $link) {
				$options = '<div class="flex-option">';
				$options .=     '<a href="'. URL('/') .'/admin/link/'. $link->id .'/edit" class="btn btn-warning btn-sm tooltips" title="แก้ไขลิ้งค์"><i class="fa fa-pencil"></i></a>';
				$options .=     '<button type="button" class="btn btn-danger btn-del btn-sm tooltips"  onclick="deleteItem('. $link->id .', \'link\');" title="ลบรายการ"><i class="fa fa-trash"></i></button>';
                $options .= '</div>';

                $ret_data[] = array($link->id
                                    , trim($link->link_url)
                                    , $link->link_name
                                    , $options);
            }
        }

        $datas = ["draw"	=> ($draw) ? $draw : 0,
        "recordsTotal" => (int)$recordsTotal,
        "recordsFiltered" => (int)$recordsTotal,
        "data" => $ret_data];

        echo json_encode($datas);
    }

    public function matchList(Request $request)
    {
        $ret_data = array();
        $draw = (int)$request->input('draw');
        $start = (int)$request->input('start');
        $length = (int)$request->input('length');
        $order = $request->input('order');

        $mnTotal = DB::table('match_links');
        $recordsTotal = $mnTotal->count();

        // DB::enableQueryLog();
        $mnData = DB::table('match_links');
        $datas = $mnData;

        if (array_key_exists('column', $order[0]) && array_key_exists('dir', $order[0])) {
            $datas = $mnData->orderBy($this->order_mby[$order[0]['column']], $order[0]['dir']);
        }

        $datas = $mnData->skip((int)$start)->take($length)->get();
        $total = count($datas);

        // $q = DB::getQueryLog()[0]['query'];
        // dd($q);
        // dd(DB::getQueryLog()[0]['time']);

        if ($total > 0) {
            foreach($datas as $mLink) {
                $match = Match::find($mLink->match_id);
                $match_name = ($match) ? $match->match_name : '-';

                $link_seq = '<input type="number" class="form-control seq" name="link_seq[]" id="link_seq_' . $mLink->id . '" value="' . $mLink->link_seq . '" min="1" max="9999">';

                $link_url = '<div class="content-link">' . $mLink->url . '</div>';

                $options = '<button type="button" class="btn btn-danger btn-del btn-sm tooltips"  onclick="deleteItem('. $mLink->id .', \'link-match\');" title="ลบรายการ"><i class="fa fa-trash"></i></button>';

                $ret_data[] = array($mLink->id
                                    , $match_name
                                    , $mLink->link_type
                                    , $mLink->name
                                    , $link_url
                                    , $link_seq
                                    , $options);
            }
        }

        $datas = ["draw"	=> ($draw) ? $draw : 0,
        "recordsTotal" => (int)$recordsTotal,
        "recordsFiltered" => (int)$recordsTotal,
        "data" => $ret_data];

        echo json_encode($datas);
    }

    public function saveCreate(Request $request)
    {
        $total = 0;
        $message = '';

        $data = new Link;
        $data->link_url = trim($request->link_url);
        $data->link_name = trim($request->link_name);
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

        $data = Link::find($request->link_id);
        $data->link_url = trim($request->link_url);
        $data->link_name = trim($request->link_name);
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

    public function deleteLink(Request $request)
    {
        $total = 0;
        $message = '';

        if ((int)$request->input('id')) {
            DB::table('links')->where('id', (int)$request->input('id'))->delete();
            $total = 1;
            $message = 'Success';
        }

        $model = array('total' => $total, 'message' => $message);
        return response()->json($model);
    }

    public function deleteLinkMatch(Request $request)
    {
        $total = 0;
        $message = '';

        if ((int)$request->input('id')) {
            DB::table('match_links')->where('id', (int)$request->input('id'))->delete();
            $total = 1;
            $message = 'Success';
        }

        $model = array('total' => $total, 'message' => $message);
        return response()->json($model);
    }

    public function saveSeq(Request $request)
    {
        $total = 0;
        $arrSeq = $request->input('arrSeq');

        if (count($arrSeq) > 0) {
            foreach($arrSeq as $val) {
                if ((int) $val['id']) {
                    $data = MatchLink::find((int) $val['id']);
                    $data->link_seq = (int) $val['seq'];
                    $saved = $data->save();
                    if ($saved) {
                        $total++;
                    }
                }
            }
        }

        $model = array('total' => $total, 'message' => 'บันทึกลำดับสำเร็จ');
        return response()->json($model);
    }
}

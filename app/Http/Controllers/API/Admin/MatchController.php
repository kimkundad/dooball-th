<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\CommonController;
use App\Http\Requests\Backend\MatchRequest;
use App\Models\Match;
use App\Models\MatchLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MatchController extends Controller
{
    private $common;
    private $order_by;

    public function __construct()
    {
        $this->common = new CommonController();
        $this->order_by = array('id', 'match_name', 'match_time', 'home_team', 'away_team', 'match_seq', '');
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
        $mnTotal = DB::table('matches');
        $dts = $mnTotal;

        if ($searchText) {
            $dts = $mnTotal->where('match_name', 'like', $searchText . '%')
                ->orWhere('home_team', 'like', $searchText . '%')
                ->orWhere('away_team', 'like', $searchText . '%');
        }

        $recordsTotal = $dts->count();
        // $q = DB::getQueryLog()[0]['query'];
        // dd($q);
        // ------------- end total --------------- //

        // ------------- start datas --------------- //
        // DB::enableQueryLog();
        $mnData = DB::table('matches');

        if (trim($searchText)) {
            $datas = $mnData->where('match_name', 'like', $searchText . '%')
                ->orWhere('home_team', 'like', $searchText . '%')
                ->orWhere('away_team', 'like', $searchText . '%');
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
            foreach ($datas as $match) {
                $check = '<label class="ctainer">';
                $check .=   '<input type="checkbox" class="chk-box" name="chk[]" id="chk_' . $match->id . '" onclick="tickCheckbox()" />';
                $check .=   '<span class="checkmark"></span>';
                $check .= '</label>';

                $match_seq = '<input type="number" class="form-control seq" name="match_seq[]" id="match_seq_' . $match->id . '" value="' . $match->match_seq . '" min="1" max="9999">';

                $options = '<div class="flex-option">';
                $options .= '<a href="' . URL('/') . '/admin/match/' . $match->id . '/edit" class="btn btn-warning btn-sm tooltips" title="แก้ไขแมทช์"><i class="fa fa-pencil"></i></a>';

                if ((int) $match->active_status == 1) {
                    $options .= '<button type="button" class="btn btn-danger btn-del btn-sm tooltips"  onclick="deleteItem(' . $match->id . ', \'match\');" title="ลบแมทช์"><i class="fa fa-trash"></i></button>';
                } else {
                    $options .= '<button type="button" class="btn btn-primary btn-del btn-sm tooltips"  onclick="restoreItem(' . $match->id . ', \'match\');" title="เรียกคืนแมทช์"><i class="fa fa-refresh"></i></button>';
                }

                // $options .= $q;
                $options .= '</div>';

                $ret_data[] = array($check
                    , $match->match_name
                    , $this->common->showDateTime($match->match_time, 1)
                    , $match->home_team
                    , $match->away_team
                    , $match_seq
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

    public function saveCreate(MatchRequest $request)
    {
        $total = 0;
        $message = '';

        $datas = new Match;
        $datas->match_name = trim($request->match_name);
        $datas->match_time = trim($request->match_time);
        $datas->home_team = trim($request->home_team);
        $datas->away_team = trim($request->away_team);
        $datas->channels = $request->channels;
        $datas->more_detail = $request->more_detail;
        $saved = $datas->save();

        if ($saved) {
            $total = 1;
            $message = 'Save success';
        } else {
            $message = 'Save error!';
        }

        if ($datas->id) {
            $matchId = $datas->id;
            $multiLink = $request->links;

            if (count($multiLink) > 0) {
                foreach ($multiLink as $val) {
                    if ($this->checkData($val['name'])) {
                        $links = new MatchLink;
                        $links->match_id = $matchId;
                        $links->link_type = 'Normal';
                        $links->name = $val['name'];
                        $links->url = $val['url'];
                        $links->link_seq = $val['link_seq'];
                        $links->desc = $val['desc'];
                        $link_saved = $links->save();
                    }
                }
            }
        }

        $model = array('total' => $total, 'message' => $message);

        return response()->json($model);
    }

    public function saveUpdate(Request $request)
    {
        $total = 0;
        $message = '';

        $datas = Match::find($request->match_id);
        $datas->match_name = trim($request->match_name);
        $datas->match_time = trim($request->match_time);
        $datas->home_team = trim($request->home_team);
        $datas->away_team = trim($request->away_team);
        $datas->channels = $request->channels;
        $datas->more_detail = $request->more_detail;
        $datas->active_status = $request->active_status;
        $saved = $datas->save();

        if ($saved) {
            $total = 1;
            $message = 'Save success';
        } else {
            $message = 'Save error!';
        }

        if ($datas->id) {
            $lDatas = MatchLink::where('match_id', $request->match_id);
            $lDatas->delete();

            $matchId = $datas->id;
            $multiLink = $request->links;

            if (count($multiLink) > 0) {
                foreach ($multiLink as $val) {
                    if ($this->checkData($val['name'])) {
                        $links = new MatchLink;
                        $links->match_id = $matchId;
                        $links->link_type = 'Normal';
                        $links->name = $val['name'];
                        $links->url = $val['url'];
                        $links->link_seq = $val['link_seq'];
                        $links->desc = $val['desc'];
                        $link_saved = $links->save();
                    }
                }
            }

            $multiSponLink = $request->sponlinks;
            if (count($multiSponLink) > 0) {
                foreach ($multiSponLink as $val) {
                    if ($this->checkData($val['name'])) {
                        $sLinks = new MatchLink;
                        $sLinks->match_id = $matchId;
                        $sLinks->link_type = 'Sponsor';
                        $sLinks->name = $val['name'];
                        $sLinks->url = $val['url'];
                        $links->link_seq = $val['link_seq'];
                        $sLinks->desc = $val['desc'];
                        $slink_saved = $sLinks->save();
                    }
                }
            }
        }

        $model = array('total' => $total, 'message' => $message);

        return response()->json($model);
    }

    public function info($id)
    {
        //
    }

    public function deleteMatch(Request $request)
    {
        $total = 0;
        $message = '';

        if ((int) $request->input('id')) {
            $datas = Match::find((int) $request->input('id'));
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
            $datas = Match::whereIn('id', $ids)->update(['active_status' => '2']);

            if ($datas) {
                $total = 1;
                $message = 'Success';
            }
        }

        $model = array('total' => $total, 'message' => $message);
        return response()->json($model);
    }

    public function restoreMatch(Request $request)
    {
        $total = 0;
        $message = '';

        if ((int) $request->input('id')) {
            $datas = Match::find((int) $request->input('id'));
            $datas->active_status = 1;
            $saved = $datas->save();

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
                    $data = Match::find((int) $val['id']);
                    $data->match_seq = (int) $val['seq'];
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

    public function resetSeq(Request $request)
    {
        $total = 0;

        DB::table('matches')->update(array('match_seq' => 999));

        $model = array('total' => 1, 'message' => 'Reset ลำดับสำเร็จ');
        return response()->json($model);
    }

    public function checkData($name = null)
    {
        $hasData = false;

        if ($name) {
            if (trim($name)) {
                $hasData = true;
            }
        }

        return $hasData;
    }

    public function keyFilter()
    {
        $filters = $this->common->getTableColumns('matches');

        $model = array('total' => count($filters), 'records' => $filters);

        return response()->json($model);
    }

}

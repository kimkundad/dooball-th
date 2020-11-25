<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\API\CommonController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\TeamRequest;
use App\Models\Media;
use App\Models\Team;
use App\Models\OnPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeamController extends Controller
{
    private $order_by;
    private $team_path;
    private $common;

    public function __construct()
    {
        $this->order_by = array('id', 'league_url', 'api_id', 'team_name_th', 'team_name_en', 'short_name_th', 'long_name_th', 'search_dooball', 'search_graph', 'onpage_id', '');
        $this->team_path = 'public/teams';
        $this->common = new CommonController();
    }

    function qList(Request $request) {
        $searchText = $request->input('q');
        // dd($request->all());
        $datas = Team::all();
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
        $mnTotal = DB::table('teams');
        $dts = $mnTotal;

        if ($searchText) {
            $mnTotal->where('team_name_th', 'like', '%' . $searchText . '%');
            $mnTotal->orWhere('team_name_en', 'like', '%' . $searchText . '%');
        }

        $recordsTotal = $mnTotal->count();
        // ------------- end total --------------- //

        // ------------- start datas --------------- //
        $mnData = DB::table('teams');

        if (trim($searchText)) {
            $mnData->where('team_name_th', 'like', '%' . $searchText . '%');
            $mnData->orWhere('team_name_en', 'like', '%' . $searchText . '%');
        }

        if (array_key_exists('column', $order[0]) && array_key_exists('dir', $order[0])) {
            $mnData->orderBy($this->order_by[$order[0]['column']], $order[0]['dir']);
        }

        $datas = $mnData->skip((int) $start)->take($length)->get();
        $total = count($datas);
        // ------------- end datas --------------- //

        if ($total > 0) {
            foreach ($datas as $team) {
                // $check = '<label class="ctainer">';
                // $check .= '<input type="checkbox" class="chk-box" name="chk[]" id="chk_' . $team->id . '" onclick="tickCheckbox()" />';
                // $check .= '<span class="checkmark"></span>';
                // $check .= '</label>';

                // $image = $this->common->getImage($team->media_id);
                // $cover = ($image) ? '<img src="' . asset('storage/' . $image) . '" width="75">' : '<i class="fa fa-camera fa-5x"></i>';

                $options = '<div class="flex-option">';
                $options .= '<a href="' . URL('/') . '/admin/team/' . $team->id . '/edit" class="btn btn-warning btn-sm tooltips" title="แก้ไขทีม"><i class="fa fa-pencil"></i></a>';

                if ((int) $team->active_status == 1) {
                    $options .= '<button type="button" class="btn btn-danger btn-del btn-sm tooltips"  onclick="deleteItem(' . $team->id . ', \'team\');" title="ลบทีม"><i class="fa fa-trash"></i></button>';
                } else {
                    $options .= '<button type="button" class="btn btn-primary btn-del btn-sm tooltips"  onclick="restoreItem(' . $team->id . ', \'team\');" title="เรียกคืนทีม"><i class="fa fa-refresh"></i></button>';
                }

                // $options .= $q;
                $options .= '</div>';

                $onpage = '';

                if ((int) $team->onpage_id != 0) {
                    $onpageData = OnPage::findOrFail($team->onpage_id);

                    if ($onpageData) {
                        $onpage = $onpageData->code_name;
                    }
                }

                $ret_data[] = array($team->id
                    , $team->league_url
                    , $team->api_id
                    , $team->team_name_th
                    , $team->team_name_en
                    , $team->short_name_th
                    , $team->long_name_th
                    , $team->search_dooball
                    , $team->search_graph
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

    public function saveCreate(TeamRequest $request)
    {
        $total = 0;
        $message = '';

        $data = new Team;
        $data->api_id = (int) $request->api_id;
        $data->team_name_th = $request->team_name_th;
        $data->team_name_en = $request->team_name_en;
        $data->short_name_th = $request->short_name_th;
        $data->long_name_th = $request->long_name_th;
        $data->search_dooball = $request->search_dooball;
        $data->search_graph = $request->search_graph;
        $data->league_url = $request->league_url;
        $data->onpage_id = $request->onpage_id;
        $saved = $data->save();
        $team_id = $data->id;

        if ($team_id) {
            $total = 1;
            $message = 'Save success';

            if ($request->hasFile('card_file')) {
                $media_data = $this->common->uploadImage($request->img_name, $request->alt, $request->witdh, $request->height, $request->img_ext, $this->team_path);
                $media_id = $media_data['id'];
                $team_saved = $this->updateTeamLogoRef($media_id, $team_id);
                if ($team_saved) {
                    $path = $this->common->storeImage($request->card_file, $this->team_path, $media_data['file_name']);
                }
            }
        } else {
            $message = 'Save error!';
        }

        $model = array('total' => $total, 'message' => $message);

        return response()->json($model);
    }

    public function saveUpdate(TeamRequest $request)
    {
        $total = 0;
        $message = '';

        $data = Team::find($request->team_id);
        $data->api_id = (int) $request->api_id;
        $data->team_name_th = $request->team_name_th;
        $data->team_name_en = $request->team_name_en;
        $data->short_name_th = $request->short_name_th;
        $data->long_name_th = $request->long_name_th;
        $data->search_dooball = $request->search_dooball;
        $data->search_graph = $request->search_graph;
        $data->league_url = $request->league_url;
        $data->onpage_id = $request->onpage_id;
        // $data->active_status = $request->active_status;
        $saved = $data->save();

        $media = Media::find($request->media_id);
        if ($media) {
            $old_name = $media->media_name;
            // $media->media_name = trim($request->img_name);
            $media->alt = $request->alt;
            $media_saved = $media->save();
        }

        if ($saved) {
            $total = 1;
            $message = 'Save success';

            if ($request->hasFile('card_file')) {
                $media_data = $this->common->uploadImage($request->img_name, $request->alt, $request->witdh, $request->height, $request->img_ext, $this->team_path);
                $media_id = $media_data['id'];
                $team_saved = $this->updateTeamLogoRef($media_id, $request->team_id);
                if ($team_saved) {
                    $path = $this->common->storeImage($request->card_file, $this->team_path, $media_data['file_name']);
                    $del = $this->common->deleteImageFromId($request->media_id);
                }
            } else {
                // if ($old_name != trim($request->img_name)) {
                    // update media_name
                // }
            }
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

    public function deleteTeam(Request $request)
    {
        $total = 0;
        $message = '';

        if ((int) $request->input('id')) {
            $datas = Team::find((int) $request->input('id'));
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
            $datas = Team::whereIn('id', $ids)->update(['active_status' => '2']);

            if ($datas) {
                $total = 1;
                $message = 'Success';
            }
        }

        $model = array('total' => $total, 'message' => $message);
        return response()->json($model);
    }

    public function restoreTeam(Request $request)
    {
        $total = 0;
        $message = '';

        if ((int) $request->input('id')) {
            $datas = Team::find((int) $request->input('id'));
            $datas->active_status = 1;
            $saved = $datas->save();

            $total = 1;
            $message = 'Success';
        }

        $model = array('total' => $total, 'message' => $message);
        return response()->json($model);
    }

    public function updateTeamLogoRef($media_id = 0, $team_id = 0)
    {
        $team_saved = null;

        if ($media_id && $media_id != 0) {
            $team = Team::find($team_id);
            $team->media_id = $media_id;
            $team_saved = $team->save();
        }

        return $team_saved;
    }
}

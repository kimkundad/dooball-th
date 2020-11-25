<?php

namespace App\Http\Controllers\API\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\League;
use App\Models\LeagueDecoration;
use App\Models\LeagueDecorationItem;
use Illuminate\Support\Facades\DB;

class WidgetLeagueController extends Controller
{
    private $order_by;

    public function __construct()
    {
        $this->order_by = array('id', 'widget_title', 'league_id', 'page_url', '');
    }

    public function index(Request $request)
    {
        $ret_data = array();
        $draw = (int)$request->input('draw');
        $start = (int)$request->input('start');
        $length = (int)$request->input('length');
        $order = $request->input('order');

        $mnTotal = DB::table('league_decorations');
        $recordsTotal = $mnTotal->count();

        $mnData = DB::table('league_decorations');
        $datas = $mnData;

        if (array_key_exists('column', $order[0]) && array_key_exists('dir', $order[0])) {
            $datas = $mnData->orderBy($this->order_by[$order[0]['column']], $order[0]['dir']);
        }

        $datas = $mnData->skip((int)$start)->take($length)->get();
        $total = count($datas);

        if ($total > 0) {
            foreach($datas as $wgl) {
				$options = '<div class="flex-option">';
				$options .=     '<a href="'. URL('/') .'/admin/settings/league-decoration/'. $wgl->id .'/edit" class="btn btn-warning btn-sm tooltips" title="แก้ไขหัวข้อ"><i class="fa fa-pencil"></i></a>';
                $options .=     '<a href="'. URL('/') .'/admin/settings/league-decoration/'. $wgl->id .'/items" class="btn btn-info btn-sm tooltips" title="แก้ไขรายละเอียด"><i class="fa fa-list-ol"></i></a>';
                $options .= '</div>';

                $ret_data[] = array($wgl->id
                                    , trim($wgl->widget_title)
                                    , ((int) $wgl->league_id == 0) ? '-' : $this->leagueName((int) $wgl->league_id)
                                    , trim($wgl->page_url)
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

        $leagueId = (int) $request->league_id;

        $datas = new LeagueDecoration;
        $datas->league_id = $leagueId;
        $datas->widget_title = trim($request->widget_title);
        $datas->page_url = trim($request->page_url);
        // $datas->dec_seq = (int)$request->dec_seq;
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

    public function saveUpdate(Request $request)
    {
        $total = 0;
        $message = '';

        $leagueId = (int) $request->league_id;

        $datas = LeagueDecoration::find($request->wgl_id);
        $datas->league_id = $leagueId;
        $datas->widget_title = trim($request->widget_title);
        $datas->page_url = trim($request->page_url);
        // $datas->dec_seq = (int)$request->dec_seq;
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

    public function leagueName($leagueId)
    {
        $name = '';
        $nameData = League::where('id', $leagueId)->first();

        if ($nameData) {
            $name = $nameData->name_en;
        }

        return $name;
    }

    public function liList(Request $request)
    {
        $ret_data = array();
        $draw = (int)$request->input('draw');
        $start = (int)$request->input('start');
        $length = (int)$request->input('length');
        $order = $request->input('order');
        $decorationId = $request->input('decoration_id');

        $mnTotal = DB::table('league_decoration_items')->where('decoration_id', $decorationId);
        $recordsTotal = $mnTotal->count();

        $mnData = DB::table('league_decoration_items')->where('decoration_id', $decorationId);
        $datas = $mnData;

        if (array_key_exists('column', $order[0]) && array_key_exists('dir', $order[0])) {
            $datas = $mnData->orderBy($this->order_by[$order[0]['column']], $order[0]['dir']);
        }

        $datas = $mnData->skip((int)$start)->take($length)->get();
        $total = count($datas);

        if ($total > 0) {
            foreach($datas as $wgl_li) {
				$options = '<div class="flex-option">';
				$options .=     '<a href="'. URL('/') .'/admin/settings/league-decoration/'. $wgl_li->decoration_id . '/items/' . $wgl_li->id . '" class="btn btn-warning btn-sm tooltips" title="แก้ไขรายการย่อย"><i class="fa fa-pencil"></i></a>';
				// $options .=     '<a href="'. URL('/') .'/admin/settings/league-decoration/'. $wgl_li->decoration_id . '/items/' . $wgl_li->id . '" class="btn btn-info btn-sm tooltips" title="ลบรายการย่อย"><i class="fa fa-list-ol"></i></a>';
                // $options .= '<button type="button" class="btn btn-danger btn-del btn-sm tooltips"  onclick="deleteItem(' . $tag->id . ', \'tag\');" title="ลบทีม"><i class="fa fa-trash"></i></button>';
                $options .= '</div>';

                $ret_data[] = array($wgl_li->id
                                    , trim($wgl_li->title)
                                    , trim($wgl_li->slug)
                                    , $options);
            }
        }

        $datas = ["draw"	=> ($draw) ? $draw : 0,
        "recordsTotal" => (int)$recordsTotal,
        "recordsFiltered" => (int)$recordsTotal,
        "data" => $ret_data];

        echo json_encode($datas);
    }

    public function saveLiCreate(Request $request)
    {
        $total = 0;
        $message = '';

        $decorationId = (int) $request->decoration_id;

        $datas = new LeagueDecorationItem;
        $datas->decoration_id = $decorationId;
        $datas->title = trim($request->title);
        $datas->slug = trim($request->slug);
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

    public function saveLiUpdate(Request $request)
    {
        $total = 0;
        $message = '';

        $datas = LeagueDecorationItem::find($request->wgl_id);
        $datas->title = trim($request->title);
        $datas->slug = trim($request->slug);
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

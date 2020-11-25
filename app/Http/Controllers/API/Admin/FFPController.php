<?php

namespace App\Http\Controllers\API\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\CommonController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class FFPController extends Controller
{
    private $order_by;
    private $common;

    public function __construct()
    {
        $this->order_by = array('id', 'link', 'league_name', 'home_team', 'away_team', 'event_time', 'created_at');
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
        $mnTotal = DB::table('ffp_detail');

        if ($searchText) {
            $mnTotal->where('league_name', 'like', $searchText . '%')
            ->orWhere('link', 'like', $searchText . '%');
        }

        $recordsTotal = $mnTotal->count();
        // ------------- end total --------------- //

        // ------------- start datas --------------- //
        $mnData = DB::table('ffp_detail')
            ->select('id', 'link', 'league_name', 'vs', 'event_time', 'created_at');

        if (trim($searchText)) {
            $mnData->where('league_name', 'like', $searchText . '%')
            ->orWhere('link', 'like', $searchText . '%');
        }

        if (array_key_exists('column', $order[0]) && array_key_exists('dir', $order[0])) {
            $mnData->orderBy($this->order_by[$order[0]['column']], $order[0]['dir']);
        }

        $datas = $mnData->skip((int) $start)->take($length)->get();
        $total = count($datas);
        // ------------- end datas --------------- //

        if ($total > 0) {
            foreach ($datas as $ffp) {
                $homeTeam = '';
                $awayTeam = '';

                $createDate = $ffp->created_at;
                $vs = $ffp->vs;

                if (trim($vs) && !empty($vs)) {
                    $vsList = preg_split('/-vs-/', $vs);
                    $homeTeam = $vsList[0];
                    $awayTeam = array_key_exists(1, $vsList) ? $vsList[1] : '-';
                }

                $ret_data[] = array($ffp->id
                    , '<a href="' . route('football-price-detail') . '?link=' . $ffp->id . '" target="_BLANK"><i class="fa fa-line-chart"></i>&nbsp;กราฟบอลไหล</a>'
                    , $ffp->league_name
                    , $homeTeam
                    , $awayTeam
                    , $ffp->event_time
                    , $createDate);
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

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}

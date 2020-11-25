<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\CheckSystemController;
use App\Models\Menu;
use App\Models\ContentDetail;
use Illuminate\Support\Facades\DB;
use \stdClass;

class PreBetController extends Controller
{
    private $conn;
    private $menus;

    public function __construct()
    {
        $this->conn = CheckSystemController::checkTableExist('prediction_bets');

        if ($this->conn['table']) {
            $this->menus = Menu::allMenus();
        }
    }

    public function index()
    {
        return view('backend/pre-bet/index', ['menus' => $this->menus]);
    }

    public function create()
    {
        return view('backend/pre-bet/create', ['menus' => $this->menus]);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $preBetId = 0;
        $ffpDetailId = '';
        $matchResultStatus = 0;
        $leagueName = '';
        $matchTime = '';
        $homeTeam = '';
        $awayTeam = '';
        $bargainPrice = '';
        $matchContinue = '';
        $ip = '';

        $preBetDatas = DB::table('prediction_bets')->where('id', $id);

        if ($preBetDatas->count() > 0) {
            $rows = $preBetDatas->get();
            $preBet = $rows[0];

            $preBetId = $preBet->id;
            $ffpDetailId = $preBet->ffp_detail_id;
            $leagueName = $preBet->league_name;
            $matchTime = $preBet->match_time;
            $homeTeam = $preBet->home_team;
            $awayTeam = $preBet->away_team;
            $bargainPrice = $preBet->bargain_price;
            $matchContinue = $preBet->match_continue;
            $matchResultStatus = $preBet->match_result_status;
        }

        $form = new stdClass();
        $form->id = $preBetId;
        $form->match_result_status = $matchResultStatus;
        $form->league_name = $leagueName;
        $form->match_time = $matchTime;
        $form->home_team = $homeTeam;
        $form->away_team = $awayTeam;
        $form->bargain_price = $bargainPrice;
        $form->match_continue = $matchContinue;
        $form->link = '';

        $checkExistingGraph = ContentDetail::where('id', $ffpDetailId)->count();

        if ($checkExistingGraph > 0) {
            $form->link = '<a href="' . route('football-price-detail') . '?link=' . $ffpDetailId . '" target="_BLANK">กราฟบอลไหล</a>';
        }

        return view('backend/pre-bet/edit', ['menus' => $this->menus, 'form' => $form]);
    }
}

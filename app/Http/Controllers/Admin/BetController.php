<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\CheckSystemController;
use App\Models\Menu;
use App\Models\Bet;
use Illuminate\Support\Facades\DB;
use \stdClass;

class BetController extends Controller
{
    private $conn;
    private $menus;

    public function __construct()
    {
        $this->conn = CheckSystemController::checkTableExist('bets');

        if ($this->conn['table']) {
            $this->menus = Menu::allMenus();
        }
    }

    public function index()
    {
        return view('backend/bet/index', ['menus' => $this->menus]);
    }

    public function create()
    {
        return view('backend/bet/create', ['menus' => $this->menus]);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $preBetId = 0;
        $matchResultStatus = 0;
        $leagueName = '';
        $matchTime = '';
        $homeTeam = '';
        $awayTeam = '';
        $bargainPrice = '';
        $matchContinue = '';
        $ip = '';

        $bet = Bet::find($id);

        if ($bet) {
            $preBetId = $bet->pre_bet_id;
            $leagueName = $bet->league_name;
            $matchTime = $bet->match_time;
            $homeTeam = $bet->home_team;
            $awayTeam = $bet->away_team;
            $bargainPrice = $bet->bargain_price;
            $matchContinue = $bet->match_continue;
            $ip = $bet->ip;

            $preBetDatas = DB::table('prediction_bets')->where('id', $preBetId);

            if ($preBetDatas->count() > 0) {
                $preBetRows = $preBetDatas->get();
                $data = $preBetRows[0];
                $preBetId = $data->id;
                $matchResultStatus = $data->match_result_status;
            }
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
        $form->ip = $ip;

        return view('backend/bet/edit', ['menus' => $this->menus, 'form' => $form]);
    }
}

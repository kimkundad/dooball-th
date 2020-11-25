<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\Front\HomeController as HomeAPI;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    private $home;

    public function __construct()
    {
        $this->middleware('auth');
        $this->home = new HomeAPI();
    }

    public function index()
    {
        return redirect('/game');

        // if (Auth::user()->side != 'Front') {
        //     Auth::logout();
        //     return redirect('login');
        // } else {
            // $widgetRelation = ($date) ? $this->home->widgetRelation() : array();

            $datas = [];

            return view('frontend/home', ['datas' => $datas]);
        // }
    }
}

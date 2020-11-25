<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CheckConnectionController extends Controller
{
    public function checkConn()
    {
        $connected = false;
        $message = '';

        try {
            DB::connection()->getPdo();
            $connected = true;
        } catch (\Exception $ex) {
            $message = $ex->getMessage();
            // abort(403, $message);
        }

        return response()->json(['connected' => $connected, 'message' => $message]);
    }

    public function checkConnServer()
    {
        $connected = false;
        $message = '';

        try {
            DB::connection()->getPdo();
            $connected = true;
        } catch (\Exception $ex) {
            $message = $ex->getMessage();
        }

        return array('connected' => $connected, 'message' => $message);
    }
}

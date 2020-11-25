<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;

class CheckSystemController extends Controller
{
    public static function checkTableExist($table_name = '')
    {
        $foundTable = false;
        $message = '';

        try {
            if (Schema::hasTable($table_name)) {
                $foundTable = true;
            }
        } catch (\Exception $ex) {
            $message = $ex->getMessage();
        }

        return array('table' => $foundTable, 'message' => $message);
    }
}

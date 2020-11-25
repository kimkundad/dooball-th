<?php

namespace App\Http\Controllers\API\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// use App\Http\Controllers\API\CommonController;
use App\Models\Key;

class KeyController extends Controller
{
    private $common;

    public function __construct()
    {
        // $this->common = new CommonController();
    }

    public function keyList($keyType = '', $pageId = 0)
    {
        $keyList = array();

        $keyData = Key::where('key_type', $keyType)->where('key_type_id', $pageId);

        if ($keyData->count() > 0) {
            $keyList = $keyData->get();
        }

        return $keyList;
    }

    public function addKey($keyType = '', $pageId = 0, $keyNames = array(), $keyValues = array())
    {
        $total = 0;
        $message = '';

        if (count($keyNames) > 0) {
            foreach($keyNames as $k => $v) {
                $kName = trim($v);
                $vOfKey = (array_key_exists($k, $keyValues)) ? trim($keyValues[$k]) : '';

                if ($kName && $vOfKey) {
                    if ($kName == 'key_date') {
                        $vOfKey = substr($vOfKey, 0, 10);
                    }

                    if ($kName == 'key_time') {
                        $vOfKey = substr($vOfKey, 0, 5);
                    }

                    $data = new Key;
                    $data->key_type = $keyType;
                    $data->key_type_id = $pageId;
                    $data->key_name = $kName;
                    $data->key_value = $vOfKey;
            
                    $saved = $data->save();

                    if ($saved) {
                        $total++;
                    }
                }
            }
        }
    }

    public function editKey($keyType = '', $pageId = 0, $keyIds = array(), $keyNames = array(), $keyValues = array())
    {
        $total = 0;
        $totalDelete = 0;
        $totalUpdate = 0;
        $totalInsert = 0;
        $message = '';

        /*---------- 3 step เก็บทุกงาน ----
        1: Delete id in database that doesn't exist in the form.
        2: Update data in database that left from delete ids.
        3: Insert into database if not in the database.
        --------------------------------*/

        $formIds = array();
        $dbIds = array();
        $notInFormIds = array();
        $idMatchTwo = array();

        // --------- start logic --------- //
        if (count($keyIds) > 0) {
            foreach($keyIds as $k => $id) {
                $formIds[] = (int) $id;
            }

            $datas = Key::where('key_type', $keyType)->where('key_type_id', $pageId);
            if ($datas->count() > 0) {
                foreach($datas->get() as $key => $value) {
                    $dbId = (int) $value->id;
                    if (! in_array($dbId, $formIds)) {
                        $notInFormIds[] = $dbId;
                    }
                }

                if (count($notInFormIds) > 0) {
                    // 1: Delete id in database that doesn't exist in the form.
                    $delDatas = Key::whereIn('id', $notInFormIds)->delete();

                    if ($delDatas) {
                        $total++;
                        // $totalDelete = $delDatas->count(); // not sure
                        $message .= ($message) ? '<br>' . 'Delete Success.' : 'Delete Success.';
                    }
                }
            }

            $datas = Key::where('key_type', $keyType)->where('key_type_id', $pageId); // left from delete
            if ($datas->count() > 0) {
                foreach($datas->get() as $key => $idInDb) {
                    $id = (int) $idInDb->id;
                    $dbIds[] = $id; // all ids that left from delete
                    if (in_array($id, $formIds)) {
                        $idMatchTwo[] = $id; // form id that intersec with db id
                    }
                }
            }

            // 2: Update data in database that left from delete ids.
            foreach($keyIds as $kofk => $idofid) {
                if (in_array($idofid, $idMatchTwo)) {
                    $name = (array_key_exists($kofk, $keyNames)) ? $keyNames[$kofk] : '' ;
                    $value = (array_key_exists($kofk, $keyValues)) ? $keyValues[$kofk] : '' ;

                    if ($name == 'key_date') {
                        $value = substr($value, 0, 10);
                    }

                    if ($name == 'key_time') {
                        $value = substr($value, 0, 5);
                    }

                    $data = Key::find($idofid);
                    $data->key_name = $name;
                    $data->key_value = $value;
                    $saved = $data->save();

                    if ($saved) {
                        $total++;
                        $totalUpdate++;
                        $message .= ($message) ? '<br>' . 'Update Success.' : 'Update Success.';
                    }
                }
            }

            // 3: Insert into database if not in the database.
            foreach($formIds as $kk => $idInForm) {
                if (! in_array($idInForm, $dbIds)) {
                    $name = (array_key_exists($kk, $keyNames)) ? $keyNames[$kk] : '' ;
                    $value = (array_key_exists($kk, $keyValues)) ? $keyValues[$kk] : '' ;

                    $data = new Key;
                    $data->key_type = 'page';
                    $data->key_type_id = $pageId;
                    $data->key_name = $name;
                    $data->key_value = $value;
                    $saved = $data->save();
                    $kId = $data->id;

                    if ($kId) {
                        $total++;
                        $totalInsert++;
                        $message .= ($message) ? '<br>' . 'Insert Success.' : 'Insert Success.';
                    }
                }
            }
        }
        // --------- end logic --------- //

        $model = array('total' => $total, 'totalUpdate' => $totalUpdate, 'totalInsert' => $totalInsert, 'message' => $message);

        return response()->json($model);
    }

    public function checkClearForm($keyType = '', $pageId = 0)
    {
        // Delete from db if clear form
        $datas = Key::where('key_type', $keyType)->where('key_type_id', $pageId);
        if ($datas->count() > 0) {
            Key::where('key_type', $keyType)->where('key_type_id', $pageId)->delete();
        }
    }
}

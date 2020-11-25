<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use App\Http\Requests\Backend\UserRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Validator;

class UserController extends Controller
{
    private $order_by;

    public function __construct()
    {
        $this->order_by = array('id', 'username', 'first_name', 'last_name', 'screen_name');
    }

    function list(Request $request)
    {
        $ret_data = array();
        $draw = (int) $request->input('draw');
        $start = (int) $request->input('start');
        $length = (int) $request->input('length');
        $order = $request->input('order');
        $searchText = $request->input('search');
        $searchText = trim($searchText);

        // ------------- start total --------------- //
        // DB::enableQueryLog();
        $mnTotal = DB::table('users')
                    ->select('id', 'role', 'username', 'first_name', 'last_name', 'screen_name', 'user_status');
        $dts = $mnTotal;

        if ($searchText) {
            $dts = $mnTotal->where('username', 'like', '%' . $searchText . '%')
                ->orWhere('screen_name', 'like', '%' . $searchText . '%')
                ->orWhere('first_name', 'like', '%' . $searchText . '%')
                ->orWhere('last_name', 'like', '%' . $searchText . '%');
        }

        $recordsTotal = $dts->count();
        // $q = DB::getQueryLog()[0]['query'];
        // dd($q);
        // ------------- end total --------------- //

        // ------------- start datas --------------- //
        // DB::enableQueryLog();
        $mnData = DB::table('users')
                    ->select('id', 'role', 'username', 'first_name', 'last_name', 'screen_name', 'user_status');

        if (trim($searchText)) {
            $datas = $mnData->where('username', 'like', '%' . $searchText . '%')
                ->orWhere('screen_name', 'like', '%' . $searchText . '%')
                ->orWhere('first_name', 'like', '%' . $searchText . '%')
                ->orWhere('last_name', 'like', '%' . $searchText . '%');
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
            foreach ($datas as $user) {
                $options = '<div class="flex-option">';
                $options .= '<a href="'. URL('/') .'/admin/settings/user/'. $user->id .'/reset-password" class="btn btn-primary btn-sm tooltips" title="เปลี่ยนรหัสผ่าน"><i class="fa fa-key"></i></a>';
                $options .= '<a href="' . URL('/') . '/admin/settings/user/' . $user->id . '/edit" class="btn btn-warning btn-sm tooltips" title="แก้ไขผู้ใช้งาน"><i class="fa fa-pencil"></i></a>';

                if ((int)$user->user_status == 1) {
                    $options .= '<button type="button" class="btn btn-danger btn-del btn-sm tooltips"  onclick="deleteUser(' . $user->id . ');" title="ลบรายการ"><i class="fa fa-trash"></i></button>';
                } else {
                    $options .= '<button type="button" class="btn btn-primary btn-del btn-sm tooltips"  onclick="restoreUser(' . $user->id . ');" title="เรียกคืนรายการ"><i class="fa fa-refresh"></i></button>';
                }

                // $options .= $q;
                $options .= '</div>';

                $statusText = ($user->user_status == 1) ? '<span class="label label-success">ใช้งาน</span>' : '<span class="label label-danger">ไม่ใช้งาน</span>';
                $statusText = ($user->user_status == 0) ? 'รอดำเนินการ' : $statusText;

                $ret_data[] = array($user->id
                    , $user->role
                    , $user->username
                    , $user->first_name
                    , $user->last_name
                    , $user->screen_name
                    , $statusText
                    , $options);
            }
        }

        $datas = ["draw" => ($draw) ? $draw : 0,
            "recordsTotal" => (int) $recordsTotal,
            "recordsFiltered" => (int) $recordsTotal,
            "data" => $ret_data];

        echo json_encode($datas);
    }

    public function saveCreate(UserRequest $request)
    {
        $total = 0;
        $message = '';

        $validator = 1 ;// Validator::make($request->all(), $this->formAddRules(), $this->formAddMessage());

        // if ($validator->fails()) {
        //     $allErr = $validator->errors()->all();

        //     if (count($allErr) != 0) {
        //         foreach ($allErr as $err) {
        //             $message .= ($message) ? '<br>' . $err : $err;
        //         }
        //     }
        // }
        if ($validator == 1) {
            // username: regex
            // first_name: regex
            // last_name: regex
            // password: regex

            $datas = new User;
            $datas->role = $request->role;
            $datas->username = trim($request->username);
            $datas->password = Hash::make($request->password);
            $datas->first_name = trim($request->first_name);
            $datas->last_name = trim($request->last_name);
            $datas->screen_name = trim($request->screen_name);
            $datas->user_status = (int) $request->user_status;
            $saved = $datas->save();

            if ($saved) {
                $total = 1;
                $message = 'Save success';
            } else {
                $message = 'Save error!';
            }
        }

        $model = array('total' => $total, 'message' => $message);

        return response()->json($model);
    }

    public function resetPassword(Request $request)
    {
        $datas = User::find($request->user_id);
        $datas->password = Hash::make($request->password);
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

        $datas = User::find($request->user_id);
        $datas->role = $request->role;
        $datas->first_name = trim($request->first_name);
        $datas->last_name = trim($request->last_name);
        $datas->screen_name = trim($request->screen_name);
        $datas->user_status = (int)$request->user_status;
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

    public function info($id)
    {
        //
    }

    public function deleteUser(Request $request)
    {
        $total = 0;
        $message = '';

        if ((int)$request->input('id')) {
            // DB::table('users')->where('id', (int) $request->input('id'))->delete();

            $datas = User::find((int)$request->input('id'));
            $datas->user_status = 2;
            $saved = $datas->save();

            $total = 1;
            $message = 'Success';
        }

        $model = array('total' => $total, 'message' => $message);
        return response()->json($model);
    }

    public function restoreUser(Request $request)
    {
        $total = 0;
        $message = '';

        if ((int)$request->input('id')) {
            // DB::table('users')->where('id', (int) $request->input('id'))->delete();

            $datas = User::find((int)$request->input('id'));
            $datas->user_status = 1;
            $saved = $datas->save();

            $total = 1;
            $message = 'Success';
        }

        $model = array('total' => $total, 'message' => $message);
        return response()->json($model);
    }
}

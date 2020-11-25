<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\CheckConnectionController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\User;

class LoginController extends Controller
{
    private $connDatas;

    public function __construct()
    {
        session_start();

        $this->mockup = new MockupController();
        $conn = new CheckConnectionController();
        $this->connDatas = $conn->checkConnServer();
    }

    public function login(Request $request)
    {
        $total = 0;
        $message = '';

        $credentials = $request->only('username', 'password');

        if ($this->connDatas['connected']) {
            if (Auth::attempt($credentials)) {
                if (Auth::attempt(['username' => $request->input('username'), 'password' => $request->input('password'), 'user_status' => 1, 'role' => 'Admin'])) {
                    $user = User::find(Auth::user()->id);
                    $role = $user->role;
                    $id = $user->id;

                    $minutes = time() + 2880; // 2 days = 2,880 minutes = 172,800 seconds
                    $_SESSION["login_back_role"] = $role;

                    if ($role == 'Admin') {
                        $_SESSION["login_back_admin_id"] = $id;
                        $_SESSION["loginBackAdminStart"] = $minutes;
                    } else {
                        $_SESSION["login_back_member_id"] = $id;
                        $_SESSION["loginBackMemberStart"] = $minutes;
                    }

                    // dd($_SESSION);

                    $total = 1;
                } else {
                    $message = 'สถานะผู้ใช้งานนี้ ยังไม่พร้อมใช้งาน';
                }
            } else {
                $message = 'ชื่อผู้ใช้งานหรือรหัสผ่านไม่ถูกต้อง';
            }
        } else {
            $message = 'Cannot connect Database.';
        }

        $model = array('total' => $total, 'message' => $message);

        return response()->json($model);
    }

    public function registerNormal(Request $request)
    {
        $username = trim($request->username);
        $password = trim($request->password);
        $displayName = trim($request->display_name);
        $lineId = trim($request->line_id);
        $tel = trim($request->tel);

        $total = 0;
        $message = '';

        if ($username && $password && $displayName && $lineId && $tel) {
            $user = User::where('username', $username);

            if ($user->count() > 0) {
                $message = 'ชื่อนี้ ถูกใช้ไปแล้ว';
            } else {
                $datas = new User;
                $datas->role = 'Member';
                $datas->username = $username;
                $datas->password = Hash::make($password);
                $datas->screen_name = $displayName;
                $datas->line_id = $lineId;
                $datas->tel = $tel;
                // $datas->user_status = 0;
        
                $saved = $datas->save();
        
                if ($saved) {
                    $total = 1;
                    $message = 'Register success.';
                } else {
                    $message = 'เกิดความผิดพลาด กรุณาติดต่อ ผู้ดูแลระบบ!';
                }
            }
        } else {
            $message .= '<ul>';

            if (empty($username)) {
                $message .= '<li>กรุณากรอก Username</li>';
            }
            if (empty($password)) {
                $message .= '<li>กรุณากรอก Password</li>';
            }

            $message .= '</ul>';
        }

        $model = array('total' => $total, 'message' => $message);

        return response()->json($model);
    }
}

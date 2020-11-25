<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    private $left_time;

    public function __construct()
    {
        session_start();

        $this->left_time = null;
        if (isset($_SESSION["loginBackAdminStart"])) {
            $this->left_time = $_SESSION["loginBackAdminStart"];
        } else if (isset($_SESSION["loginBackMemberStart"])) {
            $this->left_time = $_SESSION["loginBackMemberStart"];
        }
    }

    public function index()
    {
        if ($this->left_time) {
            return redirect('admin/match');
        } else {
            return view('backend/login');
        }
    }

    public function logout()
    {
        // Auth::logout();
        session_destroy();
        return redirect('admin/login');
    }
}

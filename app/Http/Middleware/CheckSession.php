<?php

namespace App\Http\Middleware;

use Closure;

class CheckSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        session_start();

        $left_time = null;
        if (isset($_SESSION["loginBackAdminStart"])) {
            $left_time = $_SESSION["loginBackAdminStart"];
        } else if (isset($_SESSION["loginBackMemberStart"])) {
            $left_time = $_SESSION["loginBackMemberStart"];
        }

        if ($left_time == null) {
            return redirect('admin/login');
        }

        return $next($request);
    }
}

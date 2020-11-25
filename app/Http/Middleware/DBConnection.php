<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;

class DBConnection
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
        $connected = false;
        $message = '';

        try {
            DB::connection()->getDatabaseName();
            $connected = true;
        } catch (\Exception $ex) {
            $message = $ex->getMessage();
            abort(403, $message);
        }

        return $next($request);
    }
}

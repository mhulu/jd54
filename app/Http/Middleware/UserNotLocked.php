<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
class UserNotLocked
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
        if (Auth::check() && Auth::user()->status == 0) {
            Auth::logout();
            abort(401);
        }
        return $next($request);
    }
}
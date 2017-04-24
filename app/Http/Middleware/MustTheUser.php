<?php

namespace App\Http\Middleware;
use Auth;
use Closure;

class MustTheUser
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
        if (Auth::guest() || $request->id != Auth::user()->id) {
            return redirect('/logout');
        }
        return $next($request);
    }
}

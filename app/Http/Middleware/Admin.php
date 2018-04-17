<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class Admin
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
        if (Auth::check() && Auth::user()->role == '2') {
        return $next($request);
        }
        elseif (Auth::check() && Auth::user()->role == '1') {
            return redirect('/management');
        }
        else {
            return redirect('/user');
        }
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        // if (Auth::guard($guard)->check()) {
        //     return redirect('/schedule');
        // }

        if (Auth::guard($guard)->check() && Auth::user()->role == '2') {
            return redirect('/admin');
        }

        else if (Auth::guard($guard)->check() && Auth::user()->role == '1') {
            return redirect('/management');
        }

        else if(Auth::guard($guard)->check() && Auth::user()->role == '0') {
            return redirect('/user');
        }
        elseif (Auth::guard($guard)->check()) {
            return redirect('/schedule');
        }

        return $next($request);
    }
}

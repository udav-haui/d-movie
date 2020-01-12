<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Session;
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
        if (Auth::guard($guard)->check()) {
            $prefix = $request->route()->getPrefix();
            return redirect(
                $prefix == '/admin' ? RouteServiceProvider::ADMIN_PATH : RouteServiceProvider::FRONTEND_HOME
            );
        }

        return $next($request);
    }
}

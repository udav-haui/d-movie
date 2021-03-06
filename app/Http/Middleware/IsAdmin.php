<?php

namespace App\Http\Middleware;

use Closure;

/**
 * Class IsAdmin
 *
 * @package App\Http\Middleware
 */
class IsAdmin
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
        if (\Auth::user()->isAdmin() || \Auth::user()->isStaffAccount()) {
            return $next($request);
        }
        return redirect(route('frontend.home'));
    }
}

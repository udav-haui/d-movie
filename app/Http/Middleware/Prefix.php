<?php

namespace App\Http\Middleware;

use Closure;

class Prefix
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
        /**
         * After access to admin page will put a prefix to session
         */
        \Session::put('prefix', request()->route()->getPrefix() ?? 'member');
        return $next($request);
    }
}

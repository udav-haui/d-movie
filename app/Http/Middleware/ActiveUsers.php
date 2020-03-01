<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class ActiveUsers
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
        if (auth()->check()) {
            /** @var \App\User $user */
            $user = auth()->user();
            if (!$user->isActive()) {
                auth()->guard()->logout();
                return redirect()->back()->with('error', __('Your session has timed out.'));
            }
            $expireTime = Carbon::now()->addMinutes(5);
            Cache::put('active-user-' . auth()->user()->id, true, $expireTime);
        }
        return $next($request);
    }
}

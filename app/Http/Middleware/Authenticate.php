<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Exceptions\HttpResponseException;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if ($request->wantsJson() ||
            ($request->isJson() && $request->is('api/*'))
        ) {
            throw new HttpResponseException(response()->json(
                [
                    'messages'=> 'Unauthorized Request'
                ],
                401
            ));
        }
        if (!$request->route()->getPrefix()) {
            return route('frontend.login', ['action' => 'login']);
        }
        if (! $request->expectsJson()) {
            return route('login');
        }
    }
}

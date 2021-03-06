<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use App;

class Locale
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
        $locale = Session::get('locale', config('app.locale'));
        App::setLocale($locale);
        // dump('middleware locale = '.App::getLocale());
        return $next($request);
    }
}

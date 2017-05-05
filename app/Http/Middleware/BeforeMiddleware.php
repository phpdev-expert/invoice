<?php

namespace FI\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;

class BeforeMiddleware
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
        if (config('app.debug'))
        {
            DB::enableQueryLog();
        }

        return $next($request);
    }
}

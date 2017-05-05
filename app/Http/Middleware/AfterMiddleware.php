<?php

namespace FI\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AfterMiddleware
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
        $response = $next($request);

        if (config('app.debug'))
        {
            $queries = DB::getQueryLog();

            $logContent = "\r\nREQUEST URL: " . $request->fullUrl() . "\r\n";
            $logContent .= "QUERY COUNT: " . count($queries) . "\r\n\r\n";

            $queryNum = 1;

            foreach ($queries as $query)
            {
                $logContent .= 'QUERY #' . $queryNum . "\r\n";
                $logContent .= 'SQL: ' . $query['query'] . "\r\n";
                $logContent .= 'TIME: ' . $query['time'] . "\r\n\r\n";

                $queryNum++;
            }

            Log::info($logContent);
        }

        return $response;
    }
}

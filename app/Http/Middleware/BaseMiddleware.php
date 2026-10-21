<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(date('H') < 8)
        {
            $request->accountingDate = date('Y-m-d', strtotime('-1 days'));
        }
        else
        {
            $request->accountingDate = date('Y-m-d');
        }

        return $next($request);
    }
}

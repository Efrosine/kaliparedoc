<?php

namespace App\Http\Middleware;

use App\Services\LoggingService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LogUserAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Log login events
        if ($request->is('login') && $request->isMethod('post') && Auth::check()) {
            LoggingService::logLogin(Auth::id());
        }

        return $response;
    }
}

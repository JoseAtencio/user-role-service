<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;


class Autorization
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is('api/auth/register') || $request->is('api/auth/login')) {
            return $next($request);
        }
 
        if (Auth::guard('sanctum')->check()) {
            return $next($request);
        }else{
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
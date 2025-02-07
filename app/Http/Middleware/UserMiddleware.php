<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        // dd(Auth::guard('user')->check(), Auth::guard('user')->user());
        if (!Auth::guard('user')->check()) {
            return redirect()->route('login.view');
        }
    
        return $next($request);
    }
    
    
}

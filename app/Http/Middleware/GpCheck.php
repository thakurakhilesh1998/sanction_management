<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class GpCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if($user)
        {
            if($user->role=='gp')
            {
                return $next($request);
            }
            else
            {
                return redirect($user->role);
            }
        }
        else
        {
            return rediect('/');
        }
        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckPasswordExpiry
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && $user->password_expires_at && $user->password_expires_at->isPast()) {
            // Redirect to the login route with a message
            Auth::logout(); // Log the user out if their password has expired
            return redirect()->route('login')->with('message', 'Your password has expired. Please contact the admin to change your password.');
        }

        return $next($request);
    }
}

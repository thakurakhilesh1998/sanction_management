<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnforceHost
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $allowedHost=parse_url(config('app.url'),PHP_URL_HOST);
        $requestHost=$request->getHost();
        if($allowedHost && $requestHost!=$allowedHost)
        {
            abort(Response::HTTP_FORBIDDEN, 'Forbidden: Invalid Host Header');
        }
        return $next($request);
    }
}

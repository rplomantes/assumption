<?php

namespace App\Http\Middleware;

use Closure;

class EnforceJson
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

        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Authorization', 'Token b2b48b24624ad811f47632de4155de9b9a98c95b');

        return $response;
    }
}

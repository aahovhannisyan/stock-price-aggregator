<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceJson
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /**
         * Set `Accept` header to `application/json` to get response from server in json format.
         * This middleware is intended to be applied to the API routes.
         */
        $request->headers->set('Accept', 'application/json');

        return $next($request);
    }
}

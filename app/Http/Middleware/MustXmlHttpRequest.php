<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MustXmlHttpRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle ($request, Closure $next)
    {
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(['message' => 'Not found'], 404);
        }

        return $next($request);
    }
}

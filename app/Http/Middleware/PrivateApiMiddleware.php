<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\Response;

class PrivateApiMiddleware
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
        $token = $request->headers->get('Authorization');
        if (preg_match('#^Bearer\s(.+)$#', $token, $match)) {
            if ($match[1] === md5(config('APP_KEY'))) {
                return $next($request);
            }
        }

        return response('', Response::HTTP_FORBIDDEN);
    }
}

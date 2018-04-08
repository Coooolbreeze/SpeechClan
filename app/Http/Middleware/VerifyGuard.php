<?php

namespace App\Http\Middleware;

use App\Exceptions\TokenException;
use App\Services\Tokens\BaseToken;
use App\Services\Tokens\TokenFactory;
use Closure;
use Cache;

class VerifyGuard
{
    /**
     * Handle an incoming request.
     *
     * @param $request
     * @param Closure $next
     * @param array $guard
     * @return mixed
     * @throws TokenException
     * @throws \App\Exceptions\ForbiddenException
     */
    public function handle($request, Closure $next, ...$guard)
    {
        TokenFactory::needGuard($guard);

        return $next($request);
    }
}
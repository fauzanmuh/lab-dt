<?php

namespace Core\Middleware;


use Closure;

/**
 * Middleware Interface
 * All middleware must implement this interface
 */
interface MiddlewareInterface
{
    /**
     * Handle an incoming request
     *
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next);
}

<?php

namespace Core\Middleware;


use Closure;

/**
 * Authentication Middleware
 */
class AuthMiddleware implements MiddlewareInterface
{
    public function handle($request, Closure $next)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        return $next($request);
    }
}

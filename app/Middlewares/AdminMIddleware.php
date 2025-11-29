<?php

namespace App\Middlewares;

use Core\Middleware\MiddlewareInterface;


class AdminMiddleware implements MiddlewareInterface
{
    public function handle($request, \Closure $next)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SESSION['user']['role'] !== 'admin') {
            header('Location: /admin/dashboard');
            exit;
        }
        return $next($request);
    }
}
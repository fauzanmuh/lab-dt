<?php

namespace Core\Middleware;

use Core\Http\Request;
use Core\Http\Response;
use Closure;

/**
 * Authentication Middleware
 * Example middleware to check if user is authenticated
 */
class AuthMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, Closure $next)
    {
        // Example: Check if user is authenticated via session
        session_start();

        if (!isset($_SESSION['user_id'])) {
            // User is not authenticated - redirect to login page
            return Response::redirect('/login');
        }

        // User is authenticated, continue
        return $next($request);
    }
}

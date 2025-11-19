<?php

namespace Core\Middleware;

use Core\Http\Request;
use Core\Http\Response;
use Closure;

/**
 * CSRF Protection Middleware
 * Protects against Cross-Site Request Forgery attacks
 */
class CsrfMiddleware implements MiddlewareInterface
{
    protected array $exceptRoutes = [];

    public function __construct(array $exceptRoutes = [])
    {
        $this->exceptRoutes = $exceptRoutes;
    }

    public function handle(Request $request, Closure $next)
    {
        // Skip for GET, HEAD, OPTIONS requests
        if (in_array($request->method(), ['GET', 'HEAD', 'OPTIONS'])) {
            return $next($request);
        }

        // Skip for excepted routes
        if ($this->shouldSkip($request)) {
            return $next($request);
        }

        session_start();

        $token = $request->input('_token') ?? $request->header('X-CSRF-Token');

        if (!$token || !$this->validateToken($token)) {
            return new Response(
                'CSRF token mismatch. Your session has expired. Please refresh and try again.',
                419
            );
        }

        return $next($request);
    }

    protected function shouldSkip(Request $request): bool
    {
        $path = $request->path();

        foreach ($this->exceptRoutes as $route) {
            if ($path === $route || preg_match('#^' . $route . '$#', $path)) {
                return true;
            }
        }

        return false;
    }

    protected function validateToken(string $token): bool
    {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Generate a CSRF token
     */
    public static function generateToken(): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }
}

<?php

namespace Core\Middleware;


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

    public function handle($request, Closure $next)
    {
        // Skip for GET, HEAD, OPTIONS requests
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        if (in_array($method, ['GET', 'HEAD', 'OPTIONS'])) {
            return $next($request);
        }

        // Skip for excepted routes
        if ($this->shouldSkip()) {
            return $next($request);
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $token = $_POST['_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;

        if (!$token || !$this->validateToken($token)) {
            http_response_code(419);
            echo 'CSRF token mismatch. Your session has expired. Please refresh and try again.';
            exit;
        }

        return $next($request);
    }

    protected function shouldSkip(): bool
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $path = parse_url($uri, PHP_URL_PATH);

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

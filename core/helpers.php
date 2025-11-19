<?php

/**
 * Helper Functions
 * Global helper functions for convenience
 */

use Core\Http\Response;
use Core\Middleware\CsrfMiddleware;

if (!function_exists('dd')) {
    /**
     * Dump and die
     */
    function dd(...$vars): void
    {
        foreach ($vars as $var) {
            echo '<pre>';
            var_dump($var);
            echo '</pre>';
        }
        die(1);
    }
}

if (!function_exists('dump')) {
    /**
     * Dump variable
     */
    function dump(...$vars): void
    {
        foreach ($vars as $var) {
            echo '<pre>';
            var_dump($var);
            echo '</pre>';
        }
    }
}

if (!function_exists('env')) {
    /**
     * Get environment variable
     */
    function env(string $key, $default = null)
    {
        $value = $_ENV[$key] ?? getenv($key);

        if ($value === false) {
            return $default;
        }

        return $value;
    }
}

if (!function_exists('config')) {
    /**
     * Get configuration value
     */
    function config(string $key, $default = null)
    {
        static $config = null;

        if ($config === null) {
            $configPath = BASE_PATH . '/config/app.php';
            $config = file_exists($configPath) ? require $configPath : [];
        }

        $keys = explode('.', $key);
        $value = $config;

        foreach ($keys as $segment) {
            if (!isset($value[$segment])) {
                return $default;
            }
            $value = $value[$segment];
        }

        return $value;
    }
}

if (!function_exists('view')) {
    /**
     * Create a view response
     */
    function view(string $view, array $data = []): Response
    {
        return Response::view($view, $data);
    }
}

if (!function_exists('redirect')) {
    /**
     * Create a redirect response
     */
    function redirect(string $url, int $statusCode = 302): Response
    {
        return Response::redirect($url, $statusCode);
    }
}

if (!function_exists('csrf_token')) {
    /**
     * Get CSRF token
     */
    function csrf_token(): string
    {
        return CsrfMiddleware::generateToken();
    }
}

if (!function_exists('csrf_field')) {
    /**
     * Generate CSRF field HTML
     */
    function csrf_field(): string
    {
        $token = csrf_token();
        return '<input type="hidden" name="_token" value="' . $token . '">';
    }
}

if (!function_exists('old')) {
    /**
     * Get old input value
     */
    function old(string $key, $default = null)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        return $_SESSION['_old_input'][$key] ?? $default;
    }
}

if (!function_exists('session')) {
    /**
     * Get or set session value
     */
    function session(?string $key = null, $value = null)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($key === null) {
            return $_SESSION;
        }

        if ($value === null) {
            return $_SESSION[$key] ?? null;
        }

        $_SESSION[$key] = $value;
        return $value;
    }
}

if (!function_exists('base_path')) {
    /**
     * Get base path
     */
    function base_path(string $path = ''): string
    {
        return BASE_PATH . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (!function_exists('public_path')) {
    /**
     * Get public path
     */
    function public_path(string $path = ''): string
    {
        return base_path('public' . ($path ? DIRECTORY_SEPARATOR . $path : $path));
    }
}

if (!function_exists('storage_path')) {
    /**
     * Get storage path
     */
    function storage_path(string $path = ''): string
    {
        return base_path('storage' . ($path ? DIRECTORY_SEPARATOR . $path : $path));
    }
}

<?php

/**
 * Helper Functions
 * Global helper functions for convenience
 */


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
     * Render a view
     */
    function view(string $view, array $data = []): string
    {
        extract($data);

        $viewPath = BASE_PATH . '/app/Views/' . str_replace('.', '/', $view) . '.php';

        if (!file_exists($viewPath)) {
            throw new \Exception("View [{$view}] not found.");
        }

        ob_start();
        include $viewPath;
        $content = ob_get_clean();

        // Check if a specific layout is requested
        $layoutName = $data['layout'] ?? 'layouts/main';

        // Allow disabling layout by passing false or null
        if ($layoutName === false || $layoutName === null) {
            return $content;
        }

        $layoutPath = BASE_PATH . '/app/Views/' . $layoutName . '.php';

        if (!file_exists($layoutPath)) {
            // Fallback to checking if it's just the name without directory
            $layoutPath = BASE_PATH . '/app/Views/layouts/' . $layoutName . '.php';

            if (!file_exists($layoutPath)) {
                throw new \Exception("Layout [{$layoutName}] not found.");
            }
        }

        ob_start();
        include $layoutPath;
        return ob_get_clean();
    }
}

if (!function_exists('redirect')) {
    /**
     * Redirect to a URL
     */
    function redirect(string $url, int $statusCode = 302): void
    {
        header("Location: " . $url, true, $statusCode);
        exit;
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

<?php

namespace Core;


use Core\Database\Database;

/**
 * Application Class
 * Main application bootstrap
 */
class Application
{
    protected static ?Application $instance = null;
    protected Router $router;
    protected ?Database $database = null;
    protected array $config;

    public function __construct(string $basePath)
    {
        self::$instance = $this;

        $this->config = $this->loadConfig($basePath);
        $this->router = new Router();

        $this->bootstrap();
    }

    /**
     * Get application instance
     */
    public static function getInstance(): ?Application
    {
        return self::$instance;
    }

    /**
     * Load configuration files
     */
    protected function loadConfig(string $basePath): array
    {
        $configPath = $basePath . '/config/app.php';

        if (file_exists($configPath)) {
            return require $configPath;
        }

        return [];
    }

    /**
     * Bootstrap the application
     */
    protected function bootstrap(): void
    {
        // Set error reporting based on environment
        $debug = $this->config['debug'] ?? false;

        if ($debug) {
            error_reporting(E_ALL);
            ini_set('display_errors', '1');
        } else {
            error_reporting(0);
            ini_set('display_errors', '0');
        }

        // Set timezone
        date_default_timezone_set($this->config['timezone'] ?? 'UTC');

        // Start session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Get the router instance
     */
    public function router(): Router
    {
        return $this->router;
    }

    /**
     * Get the database instance
     */
    public function database(): Database
    {
        if ($this->database === null) {
            $this->database = new Database($this->config['database'] ?? []);
        }

        return $this->database;
    }

    /**
     * Handle incoming request
     */
    /**
     * Handle incoming request
     */
    public function handle(): void
    {
        try {
            $this->router->dispatch();
        } catch (\Throwable $e) {
            $this->handleException($e);
        }
    }

    /**
     * Handle exceptions
     */
    protected function handleException(\Throwable $e): void
    {
        $debug = $this->config['debug'] ?? false;

        http_response_code(500);

        if ($debug) {
            echo sprintf(
                "<h1>Error: %s</h1><pre>%s</pre><pre>%s</pre>",
                $e->getMessage(),
                $e->getTraceAsString(),
                'File: ' . $e->getFile() . ' Line: ' . $e->getLine()
            );
        } else {
            echo 'Internal Server Error';
        }
        exit;
    }

    /**
     * Run the application
     */
    public function run(): void
    {
        $this->handle();
    }

    /**
     * Get a configuration value
     */
    public function config(string $key, $default = null)
    {
        $keys = explode('.', $key);
        $value = $this->config;

        foreach ($keys as $segment) {
            if (!isset($value[$segment])) {
                return $default;
            }
            $value = $value[$segment];
        }

        return $value;
    }
}

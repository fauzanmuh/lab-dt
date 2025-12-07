<?php

namespace Core;


use Core\Database\Database;

/**
 * Base Controller Class
 * All application controllers should extend this class
 */
abstract class Controller
{
    /**
     * Load a model
     */
    protected function loadModel(string $modelClass)
    {
        // Get database instance from application
        $app = Application::getInstance();
        $db = $app->database();

        // Create and return model instance
        return new $modelClass($db);
    }

    /**
     * Get database instance
     */
    protected function db(): Database
    {
        return Application::getInstance()->database();
    }

    /**
     * Render a view with data
     */
    /**
     * Render a view with data
     */
    protected function view(string $view, array $data = []): string
    {
        extract($data);

        // Inject user data from session if available and not already provided
        if (!isset($user) && isset($_SESSION['user'])) {
            $user = $_SESSION['user'];
        }

        // Inject info_lab data if not already provided
        if (!isset($infoLab)) {
            // Simple query to get info_lab, assuming single row
            try {
                $infoLabResult = $this->db()->query("SELECT * FROM info_lab LIMIT 1");
                $infoLab = $infoLabResult[0] ?? null;
            } catch (\Exception $e) {
                // Ignore if table doesn't exist or other DB error, to prevent breaking all pages
                $infoLab = null;
            }
        }

        $viewPath = __DIR__ . '/../app/Views/' . str_replace('.', '/', $view) . '.php';

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

        $layoutPath = __DIR__ . '/../app/Views/' . $layoutName . '.php';

        if (!file_exists($layoutPath)) {
            // Fallback to checking if it's just the name without directory
            $layoutPath = __DIR__ . '/../app/Views/layouts/' . $layoutName . '.php';

            if (!file_exists($layoutPath)) {
                throw new \Exception("Layout [{$layoutName}] not found.");
            }
        }

        ob_start();
        include $layoutPath;
        return ob_get_clean();
    }

    /**
     * Redirect to a URL
     */
    protected function redirect(string $url, int $statusCode = 302): void
    {
        header("Location: " . $url, true, $statusCode);
        exit;
    }

    /**
     * Return a response
     */
    protected function response(string $content, int $statusCode = 200, array $headers = []): void
    {
        http_response_code($statusCode);
        foreach ($headers as $key => $value) {
            header("$key: $value");
        }
        echo $content;
        exit;
    }
}

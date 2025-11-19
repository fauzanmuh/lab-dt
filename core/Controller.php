<?php

namespace Core;

use Core\Http\Response;
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
    protected function view(string $view, array $data = []): Response
    {
        return Response::view($view, $data);
    }

    /**
     * Redirect to a URL
     */
    protected function redirect(string $url, int $statusCode = 302): Response
    {
        return Response::redirect($url, $statusCode);
    }

    /**
     * Return a response
     */
    protected function response(string $content, int $statusCode = 200, array $headers = []): Response
    {
        return new Response($content, $statusCode, $headers);
    }
}

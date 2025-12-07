<?php

namespace Core;


use Exception;

/**
 * Router
 * Handles route registration, parameter matching, and middleware execution
 */
class Router
{
    protected array $routes = [];
    protected array $groupStack = [];

    /**
     * Register a GET route
     */
    public function get(string $uri, $action): Route
    {
        return $this->addRoute('GET', $uri, $action);
    }

    /**
     * Register a POST route
     */
    public function post(string $uri, $action): Route
    {
        return $this->addRoute('POST', $uri, $action);
    }

    /**
     * Register a PUT route
     */
    public function put(string $uri, $action): Route
    {
        return $this->addRoute('PUT', $uri, $action);
    }

    /**
     * Register a PATCH route
     */
    public function patch(string $uri, $action): Route
    {
        return $this->addRoute('PATCH', $uri, $action);
    }

    /**
     * Register a DELETE route
     */
    public function delete(string $uri, $action): Route
    {
        return $this->addRoute('DELETE', $uri, $action);
    }

    /**
     * Register a route that matches any HTTP method
     */
    public function any(string $uri, $action): Route
    {
        return $this->addRoute(['GET', 'POST', 'PUT', 'PATCH', 'DELETE'], $uri, $action);
    }

    /**
     * Register a route with multiple HTTP methods
     */
    public function match(array $methods, string $uri, $action): Route
    {
        return $this->addRoute($methods, $uri, $action);
    }

    /**
     * Create a route group with shared attributes
     */
    public function group(array $attributes, callable $callback): void
    {
        $this->groupStack[] = $attributes;
        $callback($this);
        array_pop($this->groupStack);
    }

    /**
     * Add a route to the collection
     */
    protected function addRoute($methods, string $uri, $action): Route
    {
        $methods = (array) $methods;
        $uri = $this->applyGroupPrefix($uri);

        $route = new Route($methods, $uri, $action);

        // Apply group middleware
        $route->middleware($this->getGroupMiddleware());

        foreach ($methods as $method) {
            $this->routes[$method][$uri] = $route;
        }

        return $route;
    }

    /**
     * Apply group prefix to URI
     */
    protected function applyGroupPrefix(string $uri): string
    {
        $prefix = $this->getGroupPrefix();
        $uri = trim($uri, '/');
        $prefix = trim($prefix, '/');

        return '/' . trim($prefix . '/' . $uri, '/');
    }

    /**
     * Get the prefix from the last group
     */
    protected function getGroupPrefix(): string
    {
        $prefix = '';

        foreach ($this->groupStack as $group) {
            if (isset($group['prefix'])) {
                $prefix .= '/' . trim($group['prefix'], '/');
            }
        }

        return $prefix;
    }

    /**
     * Get middleware from group stack
     */
    protected function getGroupMiddleware(): array
    {
        $middleware = [];

        foreach ($this->groupStack as $group) {
            if (isset($group['middleware'])) {
                $middleware = array_merge($middleware, (array) $group['middleware']);
            }
        }

        return $middleware;
    }

    /**
     * Dispatch the request to a matching route
     */
    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $uri = $_SERVER['REQUEST_URI'] ?? '/';

        // Remove query string
        if (($pos = strpos($uri, '?')) !== false) {
            $uri = substr($uri, 0, $pos);
        }

        // Find matching route
        $route = $this->findRoute($method, $uri);

        if ($route === null) {
            // Handle 404 via HomeController to use layout
            $controller = new \App\Controllers\HomeController();
            echo $controller->notFound();
            return;
        }

        // Extract route parameters
        $parameters = $this->extractParameters($route->uri(), $uri);

        // Execute middleware
        $middlewares = $route->getMiddleware();

        foreach ($middlewares as $middlewareClass) {
            $middleware = new $middlewareClass();

            // Check if middleware returns early
            $result = $middleware->handle(null, function ($request) {
                return $request;
            });

            // If middleware returns something (not null/true/false but a response-like thing or just exits), usually it exits or redirects.
            // But if it returns a value, we might want to stop?
            // In the previous code: if ($result instanceof Response) return $result;
            // Now middleware handles redirection/exit itself.
            // So we can probably ignore return value unless we want to support some convention.
            // But let's assume middleware exits if it fails (like AuthMiddleware).
        }

        // Run the actual route action
        $this->runRoute($route, $parameters);
    }

    /**
     * Find a route that matches the given method and URI
     */
    protected function findRoute(string $method, string $uri): ?Route
    {
        if (!isset($this->routes[$method])) {
            return null;
        }

        foreach ($this->routes[$method] as $routeUri => $route) {
            if ($this->matchRoute($routeUri, $uri)) {
                return $route;
            }
        }

        return null;
    }

    /**
     * Check if route matches the URI
     */
    protected function matchRoute(string $routeUri, string $uri): bool
    {
        // Convert route parameters to regex
        $pattern = preg_replace('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', '([^/]+)', $routeUri);
        $pattern = '#^' . $pattern . '$#';

        return preg_match($pattern, $uri) === 1;
    }

    /**
     * Extract parameters from URI
     */
    protected function extractParameters(string $routeUri, string $uri): array
    {
        $pattern = preg_replace('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', '([^/]+)', $routeUri);
        $pattern = '#^' . $pattern . '$#';

        preg_match($pattern, $uri, $matches);
        array_shift($matches); // Remove full match

        // Extract parameter names
        preg_match_all('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', $routeUri, $paramNames);
        $paramNames = $paramNames[1];

        return array_combine($paramNames, $matches) ?: [];
    }

    protected function runRoute(Route $route, array $parameters): void
    {
        $action = $route->action();

        if ($action instanceof \Closure) {
            echo call_user_func_array($action, array_values($parameters));
        } elseif (is_string($action)) {
            $this->callControllerAction($action, $parameters);
        } elseif (is_array($action)) {
            $this->callControllerAction($action, $parameters);
        } else {
            throw new Exception('Invalid route action');
        }
    }

    protected function callControllerAction($action, array $parameters): void
    {
        if (is_string($action)) {
            [$controller, $method] = explode('@', $action);
        } else {
            [$controller, $method] = $action;
        }

        $instance = new $controller();

        $response = call_user_func_array([$instance, $method], array_values($parameters));

        if (is_string($response)) {
            echo $response;
        }
    }

    protected function prepareResponse($result)
    {
        // Deprecated
        return $result;
    }

    /**
     * Get all registered routes
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }
}

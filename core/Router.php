<?php

namespace Core;

use Core\Http\Request;
use Core\Http\Response;
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
    public function dispatch(Request $request): Response
    {
        $method = $request->method();
        $uri = $request->path();

        // Find matching route
        $route = $this->findRoute($method, $uri);

        if ($route === null) {
            return new Response('404 Not Found', 404);
        }

        // Extract route parameters
        $parameters = $this->extractParameters($route->uri(), $uri);
        $request->setRouteParameters($parameters);

        // Execute middleware (simple loop, no pipeline abstraction)
        $middlewares = $route->getMiddleware();

        foreach ($middlewares as $middlewareClass) {
            $middleware = new $middlewareClass();

            // Check if middleware returns early (e.g., auth failed)
            $result = $middleware->handle($request, function ($request) {
                return $request; // Continue to next middleware
            });

            // If middleware returns a Response, stop and return it
            if ($result instanceof Response) {
                return $result;
            }
        }

        // Run the actual route action
        return $this->runRoute($route, $request, $parameters);
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

    /**
     * Run the route action
     */
    protected function runRoute(Route $route, Request $request, array $parameters): Response
    {
        $action = $route->action();

        // If action is a closure
        if ($action instanceof \Closure) {
            $result = call_user_func_array($action, array_merge([$request], array_values($parameters)));
        }
        // If action is a controller@method string
        elseif (is_string($action)) {
            $result = $this->callControllerAction($action, $request, $parameters);
        }
        // If action is an array [Controller::class, 'method']
        elseif (is_array($action)) {
            $result = $this->callControllerAction($action, $request, $parameters);
        } else {
            throw new Exception('Invalid route action');
        }

        return $this->prepareResponse($result);
    }

    /**
     * Call a controller action
     */
    protected function callControllerAction($action, Request $request, array $parameters): mixed
    {
        if (is_string($action)) {
            [$controller, $method] = explode('@', $action);
        } else {
            [$controller, $method] = $action;
        }

        // Create controller instance
        $instance = new $controller();

        // Call the method with request and parameters
        return call_user_func_array([$instance, $method], array_merge([$request], array_values($parameters)));
    }

    /**
     * Prepare the response
     */
    protected function prepareResponse($result): Response
    {
        if ($result instanceof Response) {
            return $result;
        }

        return new Response((string) $result, 200);
    }

    /**
     * Get all registered routes
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }
}

<?php

namespace Core;

/**
 * Route Class
 * Represents a single route with its attributes
 */
class Route
{
    protected array $methods;
    protected string $uri;
    protected $action;
    protected array $middleware = [];
    protected ?string $name = null;

    public function __construct(array $methods, string $uri, $action)
    {
        $this->methods = $methods;
        $this->uri = $uri;
        $this->action = $action;
    }

    /**
     * Add middleware to the route
     */
    public function middleware($middleware): self
    {
        $this->middleware = array_merge($this->middleware, (array) $middleware);
        return $this;
    }

    /**
     * Set route name
     */
    public function name(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get route methods
     */
    public function methods(): array
    {
        return $this->methods;
    }

    /**
     * Get route URI
     */
    public function uri(): string
    {
        return $this->uri;
    }

    /**
     * Get route action
     */
    public function action()
    {
        return $this->action;
    }

    /**
     * Get route middleware
     */
    public function getMiddleware(): array
    {
        return $this->middleware;
    }

    /**
     * Get route name
     */
    public function getName(): ?string
    {
        return $this->name;
    }
}

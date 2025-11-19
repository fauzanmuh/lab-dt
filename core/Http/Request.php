<?php

namespace Core\Http;

/**
 * HTTP Request Class
 * Encapsulates all request data and provides convenient methods
 */
class Request
{
    protected array $query;
    protected array $request;
    protected array $server;
    protected array $files;
    protected array $cookies;
    protected array $headers;
    protected ?string $content;
    protected array $routeParameters = [];

    public function __construct(
        array $query = [],
        array $request = [],
        array $server = [],
        array $files = [],
        array $cookies = []
    ) {
        $this->query = $query;
        $this->request = $request;
        $this->server = $server;
        $this->files = $files;
        $this->cookies = $cookies;
        $this->headers = $this->parseHeaders();
        $this->content = null;
    }

    /**
     * Create a request from PHP globals
     */
    public static function capture(): self
    {
        return new static(
            $_GET,
            $_POST,
            $_SERVER,
            $_FILES,
            $_COOKIE
        );
    }

    /**
     * Get the request method
     */
    public function method(): string
    {
        $method = $this->server['REQUEST_METHOD'] ?? 'GET';

        // Check for method override
        if ($method === 'POST' && $this->has('_method')) {
            return strtoupper($this->input('_method'));
        }

        return strtoupper($method);
    }

    /**
     * Get the request URI path
     */
    public function path(): string
    {
        $uri = $this->server['REQUEST_URI'] ?? '/';

        // Remove query string
        if (($pos = strpos($uri, '?')) !== false) {
            $uri = substr($uri, 0, $pos);
        }

        return $uri ?: '/';
    }

    /**
     * Get the full URL
     */
    public function url(): string
    {
        return $this->scheme() . '://' . $this->host() . $this->path();
    }

    /**
     * Get the request scheme (http or https)
     */
    public function scheme(): string
    {
        return $this->isSecure() ? 'https' : 'http';
    }

    /**
     * Check if request is secure (HTTPS)
     */
    public function isSecure(): bool
    {
        return isset($this->server['HTTPS']) && $this->server['HTTPS'] !== 'off';
    }

    /**
     * Get the host
     */
    public function host(): string
    {
        return $this->server['HTTP_HOST'] ?? 'localhost';
    }

    /**
     * Get an input value
     */
    public function input(string $key, $default = null)
    {
        return $this->request[$key] ?? $this->query[$key] ?? $default;
    }

    /**
     * Get all input data
     */
    public function all(): array
    {
        return array_merge($this->query, $this->request);
    }

    /**
     * Check if input has a key
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->request) || array_key_exists($key, $this->query);
    }

    /**
     * Get only specified keys from input
     */
    public function only(array $keys): array
    {
        $results = [];
        $input = $this->all();

        foreach ($keys as $key) {
            if (isset($input[$key])) {
                $results[$key] = $input[$key];
            }
        }

        return $results;
    }

    /**
     * Get all input except specified keys
     */
    public function except(array $keys): array
    {
        $input = $this->all();

        foreach ($keys as $key) {
            unset($input[$key]);
        }

        return $input;
    }

    /**
     * Get a query parameter
     */
    public function query(string $key, $default = null)
    {
        return $this->query[$key] ?? $default;
    }

    /**
     * Get raw request body
     */
    public function content(): string
    {
        if ($this->content === null) {
            $this->content = file_get_contents('php://input');
        }

        return $this->content;
    }

    /**
     * Check if request is AJAX
     */
    public function ajax(): bool
    {
        return $this->header('X-Requested-With') === 'XMLHttpRequest';
    }

    /**
     * Get a header value
     */
    public function header(string $key, $default = null)
    {
        return $this->headers[$key] ?? $default;
    }

    /**
     * Get all headers
     */
    public function headers(): array
    {
        return $this->headers;
    }

    /**
     * Parse headers from server variables
     */
    protected function parseHeaders(): array
    {
        $headers = [];

        foreach ($this->server as $key => $value) {
            if (str_starts_with($key, 'HTTP_')) {
                $header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
                $headers[$header] = $value;
            } elseif (in_array($key, ['CONTENT_TYPE', 'CONTENT_LENGTH'])) {
                $header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower($key))));
                $headers[$header] = $value;
            }
        }

        return $headers;
    }

    /**
     * Get a file from the request
     */
    public function file(string $key)
    {
        return $this->files[$key] ?? null;
    }

    /**
     * Check if request has a file
     */
    public function hasFile(string $key): bool
    {
        return isset($this->files[$key]) && $this->files[$key]['error'] !== UPLOAD_ERR_NO_FILE;
    }

    /**
     * Get route parameters
     */
    public function route(string $key, $default = null)
    {
        return $this->routeParameters[$key] ?? $default;
    }

    /**
     * Set route parameters
     */
    public function setRouteParameters(array $parameters): void
    {
        $this->routeParameters = $parameters;
    }

    /**
     * Get client IP address
     */
    public function ip(): string
    {
        return $this->server['REMOTE_ADDR'] ?? '0.0.0.0';
    }

    /**
     * Get user agent
     */
    public function userAgent(): string
    {
        return $this->server['HTTP_USER_AGENT'] ?? '';
    }
}

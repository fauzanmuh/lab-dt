<?php

namespace Core\Http;

/**
 * HTTP Response Class
 * Encapsulates response data and provides convenient methods
 */
class Response
{
    protected string $content;
    protected int $statusCode;
    protected array $headers;

    protected static array $statusTexts = [
        200 => 'OK',
        201 => 'Created',
        204 => 'No Content',
        301 => 'Moved Permanently',
        302 => 'Found',
        304 => 'Not Modified',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        422 => 'Unprocessable Entity',
        500 => 'Internal Server Error',
        503 => 'Service Unavailable',
    ];

    public function __construct(string $content = '', int $statusCode = 200, array $headers = [])
    {
        $this->content = $content;
        $this->statusCode = $statusCode;
        $this->headers = $headers;
    }

    /**
     * Create a redirect response
     */
    public static function redirect(string $url, int $statusCode = 302): self
    {
        return new static('', $statusCode, ['Location' => $url]);
    }

    /**
     * Create a view response
     */
    public static function view(string $view, array $data = [], int $statusCode = 200): self
    {
        extract($data);

        $content = self::renderView($view, $data);

        $layoutPath = __DIR__ . '/../../app/Views/layouts/main.php';

        if (!file_exists($layoutPath)) {
            throw new \Exception("Layout [main] not found.");
        }

        ob_start();
        include $layoutPath;
        $finalContent = ob_get_clean();

        return new static($finalContent, $statusCode);
    }

    /**
     * Render a view file
     */
    protected static function renderView(string $view, array $data = []): string
    {
        $viewPath = __DIR__ . '/../../app/Views/' . str_replace('.', '/', $view) . '.php';

        if (!file_exists($viewPath)) {
            throw new \Exception("View [{$view}] not found.");
        }

        extract($data);
        ob_start();
        include $viewPath;
        return ob_get_clean();
    }

    /**
     * Set response content
     */
    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Get response content
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Set status code
     */
    public function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * Get status code
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Set a header
     */
    public function setHeader(string $key, string $value): self
    {
        $this->headers[$key] = $value;
        return $this;
    }

    /**
     * Get a header
     */
    public function getHeader(string $key): ?string
    {
        return $this->headers[$key] ?? null;
    }

    /**
     * Get all headers
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Send the response to the client
     */
    public function send(): void
    {
        $this->sendHeaders();
        $this->sendContent();
    }

    /**
     * Send HTTP headers
     */
    protected function sendHeaders(): void
    {
        if (headers_sent()) {
            return;
        }

        // Send status line
        $statusText = self::$statusTexts[$this->statusCode] ?? 'Unknown';
        header("HTTP/1.1 {$this->statusCode} {$statusText}");

        // Send headers
        foreach ($this->headers as $name => $value) {
            header("{$name}: {$value}", false);
        }
    }

    /**
     * Send response content
     */
    protected function sendContent(): void
    {
        echo $this->content;
    }

    /**
     * Convert response to string
     */
    public function __toString(): string
    {
        return $this->content;
    }
}

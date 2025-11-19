# Understanding the Complete Request-Response Flow

This document explains what happens from the moment a user clicks a link until they see the result.

## The Complete Journey

```
┌─────────────────────────────────────────────────────────────────┐
│                        User's Browser                            │
│  User types: http://localhost:8000/about                        │
└────────────────────────────┬────────────────────────────────────┘
                             │
                             │ 1. HTTP Request
                             ▼
┌─────────────────────────────────────────────────────────────────┐
│                      Web Server (PHP)                            │
│  Receives: GET /about HTTP/1.1                                  │
└────────────────────────────┬────────────────────────────────────┘
                             │
                             │ 2. Route to Entry Point
                             ▼
┌─────────────────────────────────────────────────────────────────┐
│                    public/index.php                              │
│  • Loads autoloader                                             │
│  • Creates Application                                          │
│  • Loads routes                                                 │
│  • Runs application                                             │
└────────────────────────────┬────────────────────────────────────┘
                             │
                             │ 3. Create Request Object
                             ▼
┌─────────────────────────────────────────────────────────────────┐
│                    core/Http/Request.php                         │
│  Request Object Contains:                                        │
│  • method: 'GET'                                                │
│  • uri: '/about'                                                │
│  • headers: [User-Agent, Accept, etc.]                         │
│  • query: []                                                    │
│  • data: []                                                     │
└────────────────────────────┬────────────────────────────────────┘
                             │
                             │ 4. Find Matching Route
                             ▼
┌─────────────────────────────────────────────────────────────────┐
│                      core/Router.php                             │
│  Matches: GET /about → HomeController::about                    │
└────────────────────────────┬────────────────────────────────────┘
                             │
                             │ 5. Check Middleware (if any)
                             ▼
┌─────────────────────────────────────────────────────────────────┐
│                   core/Middleware/*                              │
│  • AuthMiddleware (check login)                                 │
│  • CsrfMiddleware (check token)                                │
│  • Custom middleware                                            │
│  ✓ All passed → Continue                                        │
└────────────────────────────┬────────────────────────────────────┘
                             │
                             │ 6. Call Controller Method
                             ▼
┌─────────────────────────────────────────────────────────────────┐
│              app/Controllers/HomeController.php                  │
│  public function about(Request $request): Response              │
│  {                                                              │
│      return $this->view('about', ['title' => 'About']);        │
│  }                                                              │
└────────────────────────────┬────────────────────────────────────┘
                             │
                             │ 7. Render View
                             ▼
┌─────────────────────────────────────────────────────────────────┐
│                core/Http/Response.php                            │
│  • Finds: app/Views/about.php                                   │
│  • Extracts data: $title = 'About'                             │
│  • Starts output buffer: ob_start()                             │
│  • Includes view file                                           │
│  • Captures output: ob_get_clean()                              │
│  • Returns: Response object with HTML                           │
└────────────────────────────┬────────────────────────────────────┘
                             │
                             │ 8. Send Response
                             ▼
┌─────────────────────────────────────────────────────────────────┐
│                  Response->send()                                │
│  1. Set status: 200 OK                                          │
│  2. Set headers: Content-Type: text/html                        │
│  3. Send content: <html>...</html>                              │
└────────────────────────────┬────────────────────────────────────┘
                             │
                             │ 9. HTTP Response
                             ▼
┌─────────────────────────────────────────────────────────────────┐
│                     User's Browser                               │
│  Receives HTML → Renders Page → User Sees Result                │
└─────────────────────────────────────────────────────────────────┘
```

## Detailed Step-by-Step

### Step 1: User Action

**What happens:**

```
User: Types URL or clicks link
Browser: Creates HTTP request
        GET /about HTTP/1.1
        Host: localhost:8000
        User-Agent: Mozilla/5.0...
        Accept: text/html...
```

### Step 2: Web Server Receives Request

**What happens:**

```
PHP Built-in Server (or Apache/Nginx):
1. Receives request on port 8000
2. Checks .htaccess or server config
3. Routes ALL requests to public/index.php
```

**Why public/index.php?**

- Single entry point for all requests
- Security: Only /public is web-accessible
- Consistency: Same bootstrap for all pages

### Step 3: Application Bootstrap

**File: `public/index.php`**

```php
<?php
// 1. Load Composer's autoloader
require_once __DIR__ . '/../vendor/autoload.php';

// 2. Create application instance
$app = new Core\Application(__DIR__ . '/..');

// 3. Load routes
require_once __DIR__ . '/../routes/web.php';

// 4. Run application
$app->run();
```

**What each line does:**

**Line 1: Autoloader**

```php
require_once __DIR__ . '/../vendor/autoload.php';
```

- Loads Composer's autoloader
- Allows using classes without manual `require`
- When you write `new Core\Application`, PHP knows where to find it

**Line 2: Create Application**

```php
$app = new Core\Application(__DIR__ . '/..');
```

- Creates the main application instance
- Loads configuration from `config/app.php`
- Sets up database connection
- Creates router

**Line 3: Load Routes**

```php
require_once __DIR__ . '/../routes/web.php';
```

- Loads your route definitions
- Registers all URLs and their handlers
- Each route tells router: "When user visits X, call Y"

**Line 4: Run**

```php
$app->run();
```

- Creates Request from $\_SERVER, $\_GET, $\_POST
- Asks router to handle the request
- Sends response back to browser

### Step 4: Create Request Object

**File: `core/Http/Request.php`**

```php
public function __construct(/* ... */)
{
    $this->method = $_SERVER['REQUEST_METHOD'];      // 'GET'
    $this->uri = $_SERVER['REQUEST_URI'];            // '/about'
    $this->query = $_GET;                             // URL params
    $this->data = $_POST;                             // Form data
    $this->server = $_SERVER;                         // Server info
    $this->headers = $this->parseHeaders();           // HTTP headers
}
```

**Request object now contains:**

```php
Request {
    method: 'GET'
    uri: '/about'
    query: []
    data: []
    headers: [
        'Host' => 'localhost:8000',
        'User-Agent' => 'Mozilla...',
        'Accept' => 'text/html...'
    ]
}
```

### Step 5: Router Finds Route

**File: `core/Router.php`**

**The dispatcher:**

```php
public function dispatch(Request $request): Response
{
    // 1. Get method and path
    $method = $request->method();  // 'GET'
    $uri = $request->path();       // '/about'

    // 2. Find matching route
    $route = $this->findRoute($method, $uri);

    // 3. Extract parameters (if any)
    $parameters = $this->extractParameters($route->uri(), $uri);

    // 4. Run middleware
    foreach ($route->getMiddleware() as $middleware) {
        // Check auth, CSRF, etc.
    }

    // 5. Run the controller
    return $this->runRoute($route, $request, $parameters);
}
```

**Finding the route:**

```php
protected function findRoute(string $method, string $uri): ?Route
{
    // Look in registered routes
    // GET /about → HomeController::about

    foreach ($this->routes[$method] as $routeUri => $route) {
        if ($this->matchRoute($routeUri, $uri)) {
            return $route;  // Found it!
        }
    }

    return null;  // 404 Not Found
}
```

### Step 6: Execute Middleware (Optional)

**If route has middleware:**

```php
$router->get('/dashboard', [Controller::class, 'index'])
    ->middleware([AuthMiddleware::class]);
```

**Middleware chain:**

```php
foreach ($middlewares as $middlewareClass) {
    $middleware = new $middlewareClass();
    $result = $middleware->handle($request, $next);

    // If middleware returns Response (e.g., redirect), stop
    if ($result instanceof Response) {
        return $result;  // Stop and return
    }
}
```

**Example: AuthMiddleware**

```php
public function handle(Request $request, Closure $next)
{
    // Check if logged in
    if (!isset($_SESSION['user_id'])) {
        // Not logged in → Redirect
        return Response::redirect('/login');
    }

    // Logged in → Continue
    return $next($request);
}
```

### Step 7: Call Controller

**File: `app/Controllers/HomeController.php`**

```php
public function about(Request $request): Response
{
    // This method is called with Request object
    // Must return Response object

    return $this->view('about', [
        'title' => 'About Us',
        'description' => 'Welcome to our site'
    ]);
}
```

**What $this->view() does:**

```php
// In Controller base class
protected function view(string $view, array $data = []): Response
{
    return Response::view($view, $data);
}
```

### Step 8: Render View

**File: `core/Http/Response.php`**

**Step 8a: Response::view()**

```php
public static function view(string $view, array $data = [], int $statusCode = 200): self
{
    // 1. Render view to HTML string
    $content = self::renderView($view, $data);

    // 2. Create Response object
    return new static($content, $statusCode);
}
```

**Step 8b: renderView()**

```php
protected static function renderView(string $view, array $data = []): string
{
    // 1. Build path: 'about' → 'app/Views/about.php'
    $viewPath = __DIR__ . '/../../app/Views/' . str_replace('.', '/', $view) . '.php';

    // 2. Check exists
    if (!file_exists($viewPath)) {
        throw new \Exception("View [{$view}] not found.");
    }

    // 3. Extract: ['title' => 'About'] → $title = 'About'
    extract($data);

    // 4. Start buffer (capture output)
    ob_start();

    // 5. Include view file (runs PHP, produces HTML)
    include $viewPath;

    // 6. Get buffer contents and clear
    return ob_get_clean();  // Returns HTML string
}
```

**What happens in the view:**

```php
// app/Views/about.php
<?php ob_start(); ?>  <!-- Start nested buffer -->

<h1><?= htmlspecialchars($title) ?></h1>
<p><?= htmlspecialchars($description) ?></p>

<?php $content = ob_get_clean(); ?>  <!-- Capture content -->
<?php include __DIR__ . '/layout.php'; ?>  <!-- Wrap in layout -->
```

**The layout wraps it:**

```php
// app/Views/layout.php
<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($title ?? 'My Site') ?></title>
</head>
<body>
    <?= $content ?>  <!-- Insert page content here -->
</body>
</html>
```

### Step 9: Send Response

**File: `core/Application.php`**

```php
public function run(): void
{
    $request = Request::createFromGlobals();
    $response = $this->router->dispatch($request);
    $response->send();  // ← Send to browser
}
```

**File: `core/Http/Response.php`**

```php
public function send(): void
{
    // 1. Set HTTP status code
    http_response_code($this->statusCode);  // 200 OK

    // 2. Send headers
    foreach ($this->headers as $name => $value) {
        header("{$name}: {$value}");
    }

    // 3. Send content (HTML)
    echo $this->content;
}
```

**What browser receives:**

```http
HTTP/1.1 200 OK
Content-Type: text/html; charset=UTF-8
Content-Length: 523

<!DOCTYPE html>
<html>
<head>
    <title>About Us</title>
</head>
<body>
    <h1>About Us</h1>
    <p>Welcome to our site</p>
</body>
</html>
```

### Step 10: Browser Renders

**Browser:**

1. Receives HTTP response
2. Parses HTML
3. Applies CSS
4. Executes JavaScript
5. Renders to screen
6. User sees the page!

## Why Each Step Matters

### Single Entry Point (index.php)

- **Security**: Only /public is accessible
- **Consistency**: Same setup for all requests
- **Control**: Can add logging, security checks once

### Request Object

- **Clean API**: Don't use $\_GET, $\_POST directly
- **Testable**: Can create fake requests for testing
- **Consistent**: Same interface everywhere

### Router

- **Organized**: URLs in one place
- **Flexible**: Easy to change URLs
- **RESTful**: Support different HTTP methods

### Middleware

- **Reusable**: Same auth check for many routes
- **Layered**: Can stack multiple checks
- **Clean**: Separate concerns

### Controller

- **Logic Hub**: Coordinates everything
- **Thin**: Should delegate to models
- **Clear**: One method per action

### View Buffering

- **Flexible**: Can modify before sending
- **Safe**: Can handle errors after rendering starts
- **Layouts**: Can wrap content in templates

### Response Object

- **Testable**: Can inspect what's being sent
- **Controllable**: Can modify before sending
- **Complete**: Status, headers, content together

## Common Flow Variations

### Form Submission Flow

```
1. User fills form → 2. POST request created
   ↓
3. Router matches → 4. CSRF middleware checks token
   ↓
5. Controller receives → 6. Validates input
   ↓
7. If valid: Save to database → 8. Redirect to success
   OR
   If invalid: Return to form with errors
```

### Authentication Flow

```
1. User visits /dashboard → 2. GET request
   ↓
3. Router matches → 4. AuthMiddleware checks session
   ↓
5. If logged in: Continue → Show dashboard
   OR
   If not: Redirect to /login
```

### Error Flow

```
1. User visits /invalid-page → 2. GET request
   ↓
3. Router searches → 4. No match found
   ↓
5. Return 404 Response → 6. Show error page
```

## Key Takeaways

1. **Everything starts at index.php** - Single entry point
2. **Request contains all input** - Wrapped in clean object
3. **Router maps URLs to controllers** - Like a phone book
4. **Middleware filters requests** - Security and checks
5. **Controllers coordinate** - Don't do everything themselves
6. **Views produce HTML** - Separate from logic
7. **Buffering provides control** - Can modify before sending
8. **Response encapsulates output** - Complete package to send

## Debugging Tips

**See the flow:**

```php
// In public/index.php
echo "1. Entry point\n";

// In Router
echo "2. Matched route: {$route->uri()}\n";

// In Controller
echo "3. Controller called\n";

// In View
echo "4. View rendering\n";
```

**Inspect Request:**

```php
// In controller
dd($request->all());  // See all data
dd($request->method());  // See HTTP method
dd($request->path());  // See URL path
```

**Inspect Response:**

```php
// Before sending
$response = $this->view('about', $data);
dd($response->getContent());  // See HTML
```

This flow runs for EVERY request to your application! 🚀

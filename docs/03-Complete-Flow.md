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
                             │ 3. Dispatch Request
                             ▼
┌─────────────────────────────────────────────────────────────────┐
│                      core/Router.php                             │
│  • Reads $_SERVER['REQUEST_METHOD']                             │
│  • Reads $_SERVER['REQUEST_URI']                                │
│  • Matches: GET /about → HomeController::about                  │
└────────────────────────────┬────────────────────────────────────┘
                             │
                             │ 4. Check Middleware (if any)
                             ▼
┌─────────────────────────────────────────────────────────────────┐
│                   core/Middleware/*                              │
│  • AuthMiddleware (check login)                                 │
│  • CsrfMiddleware (check token)                                 │
│  ✓ All passed → Continue                                        │
└────────────────────────────┬────────────────────────────────────┘
                             │
                             │ 5. Call Controller Method
                             ▼
┌─────────────────────────────────────────────────────────────────┐
│              app/Controllers/HomeController.php                  │
│  public function about()                                        │
│  {                                                              │
│      return $this->view('about', ['title' => 'About']);         │
│  }                                                              │
└────────────────────────────┬────────────────────────────────────┘
                             │
                             │ 6. Render View
                             ▼
┌─────────────────────────────────────────────────────────────────┐
│                  core/Controller.php                             │
│  • Finds: app/Views/about.php                                   │
│  • Extracts data: $title = 'About'                              │
│  • Starts output buffer: ob_start()                             │
│  • Includes view file                                           │
│  • Captures output: ob_get_clean()                              │
│  • Returns: HTML string                                         │
└────────────────────────────┬────────────────────────────────────┘
                             │
                             │ 7. Send Response
                             ▼
┌─────────────────────────────────────────────────────────────────┐
│                  Application / Browser                           │
│  • HTML content is echoed to the browser                        │
│  • User sees the page                                           │
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
```

### Step 2: Web Server Receives Request

**What happens:**

```
PHP Built-in Server (or Apache/Nginx):
1. Receives request on port 8000
2. Routes ALL requests to public/index.php
```

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

**What happens:**

- **Autoloader**: Loads classes automatically.
- **Application**: Initializes config, database, router, and **starts the session**.
- **Routes**: Registers URL patterns.
- **Run**: Dispatches the request.

### Step 4: Router Finds Route

**File: `core/Router.php`**

The router looks at the global `$_SERVER` variables to decide what to do.

```php
public function dispatch(): void
{
    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    $uri = $_SERVER['REQUEST_URI'] ?? '/';

    // ... find matching route ...

    // Execute middleware
    // ...

    // Run the controller
    $this->runRoute($route, $parameters);
}
```

### Step 5: Execute Middleware (Optional)

If the route has middleware (like `AuthMiddleware`), it runs before the controller.

```php
// core/Middleware/AuthMiddleware.php
public function handle($request, Closure $next)
{
    if (!isset($_SESSION['user'])) {
        header('Location: /login');
        exit;
    }
    return $next($request);
}
```

### Step 6: Call Controller

**File: `app/Controllers/HomeController.php`**

The router calls the method defined in your route.

```php
public function about()
{
    return $this->view('about', [
        'title' => 'About Us'
    ]);
}
```

### Step 7: Render View

**File: `core/Controller.php`**

The `view()` method loads the PHP file and returns the HTML.

```php
protected function view(string $view, array $data = []): string
{
    extract($data);
    ob_start();
    include $viewPath;
    return ob_get_clean();
}
```

### Step 8: Output to Browser

The HTML string returned by the controller is sent to the browser. The user sees the page.

## Common Flow Variations

### Form Submission Flow (POST)

```
1. User fills form → 2. POST request created
   ↓
3. Router sees POST method → 4. Matches POST route
   ↓
5. Controller receives request
   ↓
6. Reads data from $_POST
   ↓
7. Validates and saves
   ↓
8. Redirects: $this->redirect('/success')
```

### Authentication Flow

```
1. User visits /dashboard
   ↓
2. Router matches route
   ↓
3. AuthMiddleware checks $_SESSION['user']
   ↓
4. If logged in: Continue to DashboardController
   OR
   If not: header('Location: /login'); exit;
```

## Key Takeaways

1.  **Single Entry Point**: Everything starts at `public/index.php`.
2.  **Native PHP**: We use `$_GET`, `$_POST`, `$_SERVER` directly.
3.  **Router**: Maps URLs to Controller methods.
4.  **Controllers**: Handle the logic and return views.
5.  **Views**: Generate the HTML.

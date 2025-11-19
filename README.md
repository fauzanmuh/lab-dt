# PHP MVC Framework

A straightforward, beginner-friendly PHP MVC framework with clear code flow and robust routing.

## 📚 Documentation for Beginners

**New to the framework?** Start here:

- 📖 [Complete Beginner's Guide](docs/README.md) - Start here if you're new!
- 🚀 [Getting Started](docs/01-Getting-Started.md) - Your first steps
- 🔄 [Request & Response](docs/02-Request-Response.md) - Understanding data flow
- 🛤️ [Complete Flow](docs/03-Complete-Flow.md) - How everything connects

## Features

- **Simple & Clear**: Easy to understand code flow - perfect for learning MVC
- **No ORM Abstraction**: Write plain SQL queries - see exactly what's happening
- **Robust Router**: Full-featured routing with parameters, groups, and middleware
- **Request/Response Objects**: Clean HTTP handling
- **MVC Architecture**: Clear separation of concerns
- **Database Layer**: Simple PDO wrapper with prepared statements
- **Essential Middleware**: Auth and CSRF protection included
- **Helper Functions**: Convenient global helpers
- **View System**: Simple PHP templating with layouts

## Requirements

- PHP 8.0 or higher
- Composer
- Apache (with mod_rewrite) or Nginx
- PostgreSQL (or other PDO-supported database)

## Installation

1. Install dependencies:

```bash
composer dump-autoload
```

2. Configure your database in `config/app.php`:

```php
'database' => [
    'driver' => 'pgsql',      // pgsql for PostgreSQL, mysql for MySQL
    'host' => 'localhost',
    'port' => 5432,           // 5432 for PostgreSQL, 3306 for MySQL
    'database' => 'your_database',
    'username' => 'postgres',
    'password' => 'your_password',
    'charset' => 'utf8',
]
```

3. Point your web server to the `public` directory

4. **Quick Start** (Development Server):

```bash
cd public
php -S localhost:8000
```

Then visit `http://localhost:8000` in your browser.

## Understanding the Code Flow

### 1. Request Flow

```
User Request → public/index.php → Router → Middleware → Controller → Model → Database
                                              ↓            ↓          ↓
User Response ← View ← Controller ← Model ← Database
```

### 2. Simple Example - Display User Profile

**Route** (`routes/web.php`):

```php
$router->get('/user/{id}', [HomeController::class, 'showUser']);
```

**Controller** (`app/Controllers/HomeController.php`):

```php
public function showUser(Request $request, $id): Response
{
    // Step 1: Load the User model
    $userModel = $this->loadModel(User::class);

    // Step 2: Call model method to get data
    $user = $userModel->getUserById($id);

    // Step 3: Return view with data
    return $this->view('user/profile', [
        'title' => 'User Profile',
        'user' => $user
    ]);
}
```

**Model** (`app/Models/User.php`):

```php
public function getUserById($id)
{
    // Plain SQL query - easy to understand!
    $sql = "SELECT * FROM users WHERE id = :id";
    $result = $this->db->query($sql, ['id' => $id]);
    return $result[0] ?? null;
}
```

**View** (`app/Views/user/profile.php`):

```php
<?php ob_start(); ?>

<h1><?= htmlspecialchars($user['name']) ?></h1>
<p>Email: <?= htmlspecialchars($user['email']) ?></p>

<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layout.php'; ?>
```

**Flow:**

1. User visits `/user/123`
2. Router matches route and extracts parameter `id=123`
3. Middleware allows request to continue
4. Router calls `HomeController::showUser()` with `$id=123`
5. Controller loads `User` model
6. Model executes SQL query `SELECT * FROM users WHERE id = 123`
7. Database returns user data
8. Controller renders view with user data
9. User sees the profile page

## Creating a Model

Models are simple classes with clear SQL queries:

```php
<?php

namespace App\Models;

use Core\Model;

class User extends Model
{
    public function getAllUsers()
    {
        $sql = "SELECT * FROM users";
        return $this->db->query($sql);
    }

    public function getUserById($id)
    {
        $sql = "SELECT * FROM users WHERE id = :id";
        $result = $this->db->query($sql, ['id' => $id]);
        return $result[0] ?? null;
    }

    public function createUser($name, $email, $password)
    {
        $sql = "INSERT INTO users (name, email, password, created_at)
                VALUES (:name, :email, :password, :created_at)";

        $this->db->execute($sql, [
            'name' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return $this->db->lastInsertId();
    }
}
```

## Creating a Controller

Controllers handle requests and coordinate between models and views:

```php
<?php

namespace App\Controllers;

use Core\Controller;
use Core\Http\Request;
use Core\Http\Response;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request): Response
    {
        // Load model
        $userModel = $this->loadModel(User::class);

        // Get data
        $users = $userModel->getAllUsers();

        // Return view
        return $this->view('users/index', [
            'title' => 'All Users',
            'users' => $users
        ]);
    }

    public function show(Request $request, $id): Response
    {
        $userModel = $this->loadModel(User::class);
        $user = $userModel->getUserById($id);

        if (!$user) {
            return $this->view('errors/404', [
                'message' => 'User not found'
            ], 404);
        }

        return $this->view('users/show', [
            'title' => 'User Profile',
            'user' => $user
        ]);
    }

    public function create(Request $request): Response
    {
        if ($request->method() === 'POST') {
            $userModel = $this->loadModel(User::class);

            $userId = $userModel->createUser(
                $request->input('name'),
                $request->input('email'),
                $request->input('password')
            );

            return $this->redirect('/users/' . $userId);
        }

        return $this->view('users/create', [
            'title' => 'Create User'
        ]);
    }
}
```

## Routing

Define routes in `routes/web.php`:

```php
use App\Controllers\HomeController;
use App\Controllers\UserController;

$router = $app->router();

// Simple routes
$router->get('/', [HomeController::class, 'index']);
$router->get('/about', [HomeController::class, 'about']);

// Route with parameter
$router->get('/user/{id}', [UserController::class, 'show']);

// Form handling (GET shows form, POST processes it)
$router->match(['GET', 'POST'], '/contact', [HomeController::class, 'contact']);

// Route groups with prefix and middleware
$router->group(['prefix' => 'admin', 'middleware' => [AuthMiddleware::class]], function ($router) {
    $router->get('/dashboard', [AdminController::class, 'dashboard']);
    $router->get('/users', [AdminController::class, 'users']);
    $router->match(['GET', 'POST'], '/users/create', [AdminController::class, 'createUser']);
});

// Closure route (receives Request as first parameter)
$router->get('/hello/{name}', function ($request, $name) {
    return "Hello, {$name}!";
});

// Multiple HTTP methods
$router->match(['GET', 'POST'], '/submit', [FormController::class, 'handle']);
```

## Middleware

The framework includes two essential middleware:

### 1. Authentication Middleware

Protects routes that require login:

```php
use Core\Middleware\AuthMiddleware;

$router->get('/dashboard', [DashboardController::class, 'index'])
    ->middleware([AuthMiddleware::class]);
```

### 2. CSRF Middleware

Protects forms from Cross-Site Request Forgery:

```php
use Core\Middleware\CsrfMiddleware;

$router->post('/submit-form', [FormController::class, 'submit'])
    ->middleware([CsrfMiddleware::class]);
```

### Creating Custom Middleware

Simply implement the `MiddlewareInterface`:

```php
<?php

namespace App\Middleware;

use Core\Middleware\MiddlewareInterface;
use Core\Http\Request;
use Closure;

class CustomMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, Closure $next)
    {
        // Do something before the controller

        $response = $next($request);

        // Do something after the controller

        return $response;
    }
}
```

Apply it to routes:

```php
$router->get('/custom', [Controller::class, 'method'])
    ->middleware([CustomMiddleware::class]);
```

## Database Operations

The framework provides two simple methods:

### 1. `query()` - For SELECT queries

```php
$sql = "SELECT * FROM users WHERE status = :status";
$users = $this->db->query($sql, ['status' => 'active']);
// Returns array of results
```

### 2. `execute()` - For INSERT, UPDATE, DELETE

```php
$sql = "INSERT INTO users (name, email) VALUES (:name, :email)";
$this->db->execute($sql, [
    'name' => 'John',
    'email' => 'john@example.com'
]);
// Returns number of affected rows
```

### Get Last Insert ID

```php
$userId = $this->db->lastInsertId();
```

## Creating Views

Views are simple PHP files in `app/Views/`:

```php
<!-- app/Views/users/show.php -->
<?php ob_start(); ?>

<h1>User Profile</h1>
<p>Name: <?= htmlspecialchars($user['name'] ?? 'Unknown') ?></p>
<p>Email: <?= htmlspecialchars($user['email'] ?? 'N/A') ?></p>

<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layout.php'; ?>
```

Return views from controllers:

```php
return $this->view('users.show', ['user' => $user]);
```

## Helper Functions

```php
// Return view
return view('home', ['title' => 'Home']);

// Redirect
return redirect('/login');

// Debug (dump and die)
dd($variable);

// Get config
$dbHost = config('database.host');

// Session helpers
session('key', 'value');
$value = session('key');

// CSRF protection
csrf_token();
csrf_field(); // Returns HTML input field
```

## Complete CRUD Example

**routes/web.php:**

```php
$router->get('/users', [UserController::class, 'index']);                    // List all users
$router->get('/users/create', [UserController::class, 'create']);            // Show create form
$router->post('/users', [UserController::class, 'store']);                   // Store new user
$router->get('/users/{id}', [UserController::class, 'show']);                // Show user profile
$router->get('/users/{id}/edit', [UserController::class, 'edit']);          // Show edit form
$router->post('/users/{id}/update', [UserController::class, 'update']);     // Update user
$router->post('/users/{id}/delete', [UserController::class, 'destroy']);    // Delete user
```

**UserController.php:**

```php
public function index(Request $request): Response
{
    $users = $this->loadModel(User::class)->getAllUsers();
    return $this->view('users/index', ['users' => $users]);
}

public function show(Request $request, $id): Response
{
    $user = $this->loadModel(User::class)->getUserById($id);
    return $this->view('users/show', ['user' => $user]);
}

public function create(Request $request): Response
{
    return $this->view('users/create');
}

public function store(Request $request): Response
{
    $userId = $this->loadModel(User::class)->createUser(
        $request->input('name'),
        $request->input('email'),
        $request->input('password')
    );
    return $this->redirect('/users/' . $userId);
}
```

## Directory Structure

```
├── app/
│   ├── Controllers/     # Your controllers
│   ├── Models/          # Your models (with plain SQL)
│   └── Views/           # Your views
├── config/
│   └── app.php         # Configuration
├── core/                # Framework core
│   ├── Database/        # Database layer
│   ├── Http/            # Request/Response
│   ├── Middleware/      # Auth and CSRF middleware
│   ├── Application.php  # Main app
│   ├── Router.php       # Router
│   ├── Route.php        # Route class
│   ├── Controller.php   # Base controller
│   └── Model.php        # Base model
├── public/
│   ├── .htaccess       # Apache rewrite rules
│   └── index.php       # Entry point
├── routes/
│   └── web.php         # Your routes
└── storage/
    └── logs/           # Application logs
```

## Why This Framework is Beginner-Friendly

1. **No Magic**: You write plain SQL - you see exactly what's happening
2. **Clear Flow**: Easy to trace from route → middleware → controller → model → database
3. **No Complex Abstractions**: No ORM, no dependency injection containers, no pipelines
4. **Simple Middleware**: Just a loop checking each middleware - easy to understand
5. **Straightforward**: Just classes, methods, and plain PHP - nothing hidden
6. **Great for Learning**: Understand how MVC works without framework complexity

## License

MIT License

## Contributing

Feel free to submit issues and enhancement requests!

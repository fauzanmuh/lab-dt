# Getting Started with PHP MVC Framework

Welcome! This guide will help you understand how this framework works from the ground up.

## What is MVC?

**MVC** stands for **Model-View-Controller**. It's a way to organize your code:

```
┌─────────────┐
│   Browser   │
└──────┬──────┘
       │ 1. User visits /users
       ▼
┌─────────────┐
│   Router    │ ← Decides which controller to use
└──────┬──────┘
       │ 2. Routes to UserController
       ▼
┌─────────────┐
│ Controller  │ ← Handles the request, coordinates everything
└──────┬──────┘
       │ 3. Asks Model for data
       ▼
┌─────────────┐
│   Model     │ ← Talks to database, gets data
└──────┬──────┘
       │ 4. Returns data
       ▼
┌─────────────┐
│ Controller  │ ← Receives data
└──────┬──────┘
       │ 5. Passes data to View
       ▼
┌─────────────┐
│    View     │ ← Creates HTML using the data
└──────┬──────┘
       │ 6. Returns HTML
       ▼
┌─────────────┐
│   Browser   │ ← User sees the page
└─────────────┘
```

### Why Use MVC?

- **Organized Code**: Each part has a clear job
- **Easy to Find Things**: Know where to look for specific functionality
- **Team-Friendly**: Multiple people can work on different parts
- **Maintainable**: Changes in one part don't break others

## Framework Structure

```
your-project/
├── app/                    # YOUR CODE GOES HERE
│   ├── Controllers/        # Handle requests, coordinate actions
│   ├── Models/            # Database operations, business logic
│   └── Views/             # HTML templates
│
├── core/                  # FRAMEWORK CODE (you rarely edit this)
│   ├── Database/          # Database connection
│   ├── Middleware/        # Security and filters
│   ├── Application.php    # Main app class
│   ├── Router.php         # URL routing
│   ├── Controller.php     # Base controller
│   └── helpers.php        # Utility functions
│
├── config/                # CONFIGURATION
│   └── app.php           # Database settings, app config
│
├── public/                # WEB ROOT (publicly accessible)
│   ├── index.php         # Entry point - all requests come here
│   └── .htaccess         # Apache rules
│
└── routes/                # URL ROUTES
    └── web.php           # Define your URLs here
```

## How a Request Works

Let's follow what happens when someone visits `http://localhost:8000/about`:

### Step 1: Entry Point

```
User types: http://localhost:8000/about
           ↓
Apache/Nginx receives request
           ↓
Sends to: public/index.php
```

### Step 2: Application Boots Up

**File: `public/index.php`**

```php
require_once __DIR__ . '/../vendor/autoload.php';

// Create the application
$app = new Core\Application(__DIR__ . '/..');

// Load routes
require_once __DIR__ . '/../routes/web.php';

// Handle the request and send response
$app->run();
```

**What happens:**

1. Load Composer autoloader (so we can use classes)
2. Create Application instance
3. Load your route definitions
4. Process the request

### Step 3: Router Finds the Route

**File: `routes/web.php`**

```php
$router->get('/about', [HomeController::class, 'about']);
```

**What happens:**

1. Router looks at the URL: `/about`
2. Finds matching route: `GET /about`
3. Sees it should call: `HomeController::about()`

### Step 4: Controller Handles It

**File: `app/Controllers/HomeController.php`**

```php
public function about()
{
    return $this->view('about', [
        'title' => 'About Us'
    ]);
}
```

**What happens:**

1. `about()` method is called
2. Creates a view with the 'about' template
3. Passes data: `['title' => 'About Us']`

### Step 5: View Renders

**File: `app/Views/about.php`**

```php
<?php ob_start(); ?>

<h1><?= htmlspecialchars($title) ?></h1>
<p>Welcome to our website!</p>

<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/layout.php'; ?>
```

**What happens:**

1. PHP processes the view file
2. `$title` becomes "About Us"
3. Creates HTML
4. Wraps it in layout

### Step 6: Response Sent

```
HTML created → Sent to browser → User sees page
```

## Your First Feature

Let's create a simple "Hello" page!

### 1. Create the Route

**File: `routes/web.php`**

```php
$router->get('/hello', [HomeController::class, 'hello']);
```

### 2. Create the Controller Method

**File: `app/Controllers/HomeController.php`**

```php
public function hello()
{
    return $this->view('hello', [
        'title' => 'Hello Page',
        'message' => 'Welcome to my first page!'
    ]);
}
```

### 3. Create the View

**File: `app/Views/hello.php`**

```php
<?php ob_start(); ?>

<h1><?= htmlspecialchars($title) ?></h1>
<p><?= htmlspecialchars($message) ?></p>

<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/layout.php'; ?>
```

### 4. Visit the Page

Open your browser: `http://localhost:8000/hello`

You should see:

```
Hello Page
Welcome to my first page!
```

## Important Concepts for Beginners

### 1. Variables in Views

When you pass data to a view:

```php
$this->view('hello', ['name' => 'John', 'age' => 25])
```

In the view, you can use:

```php
<?= $name ?>  <!-- Prints: John -->
<?= $age ?>   <!-- Prints: 25 -->
```

**Why?** The `extract()` function converts array keys to variables!

### 2. Always Escape Output

**BAD:**

```php
<h1><?= $title ?></h1>
```

**GOOD:**

```php
<h1><?= htmlspecialchars($title) ?></h1>
```

**Why?** Prevents XSS attacks (security)

### 3. Handling Requests

We use native PHP superglobals:

```php
public function contact()
{
    // Get submitted data
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';

    // Get HTTP method
    $method = $_SERVER['REQUEST_METHOD']; // 'GET', 'POST', etc.
}
```

## Common Tasks

### Display a Page

```php
// Controller
return $this->view('page-name', [
    'variable1' => 'value1',
    'variable2' => 'value2'
]);
```

### Redirect to Another Page

```php
// Controller
$this->redirect('/dashboard');
```

### Get Form Data

```php
// Controller
public function submit()
{
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';

    // Process the data...
}
```

### Query Database

```php
// Controller
public function users()
{
    // Load the model
    $userModel = $this->loadModel(User::class);

    // Get data
    $users = $userModel->getAllUsers();

    // Show view
    return $this->view('users', ['users' => $users]);
}
```

## Debugging Tips

### 1. See What Variables Contain

```php
// In controller or view
dd($variable); // "Dump and Die" - shows variable and stops
```

### 2. Check PHP Errors

Look at the terminal where you ran `php -S localhost:8000`

### 3. Use Simple echo

```php
echo "Got here!"; // Simple debugging
exit; // Stop execution
```

### 4. Check if File Exists

```php
var_dump(file_exists('/path/to/file'));
```

## Next Steps

Now that you understand the basics, learn about:

1. **[Request/Response](02-Request-Response.md)** - How data flows in and out
2. **[Routing](03-Routing.md)** - How URLs map to controllers
3. **[Controllers](04-Controllers.md)** - Handling business logic
4. **[Views](05-Views.md)** - Creating HTML templates
5. **[Models](06-Models.md)** - Working with databases

## Quick Reference

### Create a New Page

1. **Add route**: `routes/web.php`
2. **Add controller method**: `app/Controllers/`
3. **Add view file**: `app/Views/`
4. **Visit**: `http://localhost:8000/your-route`

### File Locations

- Routes: `routes/web.php`
- Controllers: `app/Controllers/`
- Views: `app/Views/`
- Models: `app/Models/`
- Config: `config/app.php`

### Common Methods

```php
// In Controller:
$this->view('view-name', $data)
$this->redirect('/url')
$this->loadModel(ModelClass::class)

// In View:
htmlspecialchars($variable)
old('field-name')
error('field-name')
```

## Questions?

If something doesn't make sense, that's okay! Programming takes practice. Try:

1. Read the code slowly
2. Add `echo` statements to see what happens
3. Use `dd()` to inspect variables
4. Ask your team members
5. Refer to the other documentation files

Welcome to the team! 🚀

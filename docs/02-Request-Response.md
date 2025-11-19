# Request and Response - Understanding the Flow

## What Are Request and Response?

Think of web development like a conversation:

```
You (Browser)          Server
     │                   │
     │  "Can I see       │
     │   /about page?"   │
     ├──────────────────>│  ← REQUEST
     │                   │
     │  "Here's the      │
     │   HTML for it"    │
     │<──────────────────┤  ← RESPONSE
     │                   │
```

- **Request**: What the user asks for
- **Response**: What the server sends back

## The Request Object

When someone visits your website, all their information comes in a `Request` object.

### What's Inside a Request?

```php
class Request
{
    protected string $method;        // GET, POST, PUT, DELETE
    protected string $uri;           // /about, /users/5
    protected array $query;          // URL parameters (?name=John)
    protected array $data;           // Form data (POST)
    protected array $server;         // Server info
    protected array $headers;        // HTTP headers
    protected ?string $content;      // Raw request body
}
```

### Example Request Breakdown

When user visits: `http://localhost:8000/users/search?name=John&age=25`

And submits a form with POST data.

**The Request object contains:**

```php
// HTTP Method
$request->method()  // Returns: 'POST'

// URL Path (without query string)
$request->path()    // Returns: '/users/search'

// URL Parameters (after ?)
$request->query('name')  // Returns: 'John'
$request->query('age')   // Returns: '25'

// Form Data (POST)
$request->input('email')    // Returns: form field value
$request->input('password') // Returns: password field value

// Get all form data
$request->all()  // Returns: ['email' => '...', 'password' => '...']

// Headers
$request->header('User-Agent')     // Browser info
$request->header('Content-Type')   // Data type
```

## The Response Object

After your controller processes the request, it must return a `Response` object.

### What's Inside a Response?

```php
class Response
{
    protected string $content;       // HTML, text, or data
    protected int $statusCode;       // 200 OK, 404 Not Found, etc.
    protected array $headers;        // HTTP headers to send
}
```

### Status Codes Explained

```
200 - OK              → Everything worked!
201 - Created         → New resource created
302 - Found           → Redirect to another page
400 - Bad Request     → Invalid data sent
401 - Unauthorized    → Need to login
403 - Forbidden       → Not allowed to access
404 - Not Found       → Page doesn't exist
500 - Server Error    → Something broke on server
```

## How Request Flows Through Your App

### Step-by-Step Flow

```
1. User Action
   │
   └→ Browser sends HTTP Request
      │
      └→ public/index.php receives it
         │
         └→ Application creates Request object
            │
            └→ Router matches URL to route
               │
               └→ Middleware processes (optional)
                  │
                  └→ Controller receives Request
                     │
                     ├→ Read data: $request->input('name')
                     ├→ Process business logic
                     ├→ Query database if needed
                     │
                     └→ Controller returns Response
                        │
                        └→ Response object has HTML/redirect/etc
                           │
                           └→ Response->send() sends to browser
                              │
                              └→ User sees result
```

## Creating Responses

### 1. View Response (Most Common)

```php
public function about(Request $request): Response
{
    return $this->view('about', [
        'title' => 'About Us',
        'description' => 'Learn about our company'
    ]);
}
```

**What happens:**

1. Loads `app/Views/about.php`
2. Passes data to the view
3. Renders HTML
4. Returns Response with HTML content
5. Status code: 200 (OK)

### 2. Redirect Response

```php
public function afterLogin(Request $request): Response
{
    // Redirect to dashboard
    return $this->redirect('/dashboard');
}
```

**What happens:**

1. Creates Response with empty content
2. Sets status code: 302 (redirect)
3. Adds Location header: '/dashboard'
4. Browser automatically goes to new URL

### 3. Plain Text Response

```php
public function hello(Request $request): Response
{
    return $this->response('Hello, World!');
}
```

**What happens:**

1. Creates Response with text "Hello, World!"
2. Status code: 200
3. No HTML rendering, just plain text

### 4. Custom Status Response

```php
public function notFound(Request $request): Response
{
    return $this->view('404', [
        'message' => 'Page not found'
    ], 404);  // ← Custom status code
}
```

## Reading Request Data

### GET Parameters (URL Query String)

```
URL: /search?name=John&city=NYC
```

```php
public function search(Request $request): Response
{
    $name = $request->query('name');  // 'John'
    $city = $request->query('city');  // 'NYC'

    // With default value if not present
    $page = $request->query('page', 1);  // Returns 1 if not set
}
```

### POST Data (Form Submissions)

```html
<form method="POST" action="/contact">
  <input name="email" value="user@example.com" />
  <input name="message" value="Hello!" />
</form>
```

```php
public function contact(Request $request): Response
{
    $email = $request->input('email');      // 'user@example.com'
    $message = $request->input('message');  // 'Hello!'

    // Get all inputs at once
    $allData = $request->all();
    // ['email' => 'user@example.com', 'message' => 'Hello!']
}
```

### Route Parameters

```
Route: /users/{id}
URL:   /users/123
```

```php
public function showUser(Request $request, $id): Response
{
    // $id = '123'

    $user = $this->loadModel(User::class)->getUserById($id);

    return $this->view('user/profile', ['user' => $user]);
}
```

## Behind the Scenes: How Response Works

### Creating a Response

```php
public static function view(string $view, array $data = [], int $statusCode = 200): self
{
    // 1. Render the view to HTML string
    $content = self::renderView($view, $data);

    // 2. Create Response object with HTML
    return new static($content, $statusCode);
}
```

### The renderView Magic

```php
protected static function renderView(string $view, array $data = []): string
{
    // 1. Find view file
    $viewPath = __DIR__ . '/../../app/Views/' . str_replace('.', '/', $view) . '.php';

    // 2. Check it exists
    if (!file_exists($viewPath)) {
        throw new \Exception("View [{$view}] not found.");
    }

    // 3. Convert array to variables
    extract($data);  // ['title' => 'Home'] becomes $title = 'Home'

    // 4. Start capturing output
    ob_start();

    // 5. Run the view file
    include $viewPath;  // HTML goes into buffer, not browser

    // 6. Get captured output and clean buffer
    return ob_get_clean();  // Returns HTML as string
}
```

**Why buffer?** See [Understanding Output Buffering](#understanding-output-buffering) below.

### Sending the Response

```php
public function send(): void
{
    // 1. Set HTTP status code
    http_response_code($this->statusCode);  // 200, 404, etc.

    // 2. Send headers
    foreach ($this->headers as $name => $value) {
        header("{$name}: {$value}");
    }

    // 3. Send content (HTML, text, etc.)
    echo $this->content;
}
```

**Important Order:**

```
Status Code → Headers → Content

You CANNOT change status or headers after sending content!
```

## Understanding Output Buffering

### The Problem Without Buffering

```php
// BAD - Direct output
echo "<h1>Hello</h1>";  // Sent to browser immediately!
header('Location: /home');  // ❌ ERROR! Headers already sent!
```

### The Solution With Buffering

```php
// GOOD - Buffered output
ob_start();                 // Start capturing
echo "<h1>Hello</h1>";      // Goes to buffer, not browser
$html = ob_get_clean();     // Get buffer contents, clear it

header('Location: /home');  // ✓ Works! Nothing sent yet
```

### Why We Need It

1. **Control**: Decide when to send output
2. **Headers**: Can set headers after generating content
3. **Errors**: Can show error page instead if something fails
4. **Modification**: Can change content before sending

### Real Example

```php
// User visits page
public function profile(Request $request): Response
{
    $user = $this->loadModel(User::class)->getCurrentUser();

    if (!$user) {
        // Oops! Not logged in
        // Good: Can redirect because nothing sent yet
        return $this->redirect('/login');
    }

    // User found, show profile
    return $this->view('profile', ['user' => $user]);
}
```

**Without buffering:**

```
1. Start rendering profile
2. Some HTML sent: "<html><head>..."
3. Realize user not logged in
4. Try to redirect → ❌ FAIL! Content already sent
5. User sees broken page
```

**With buffering:**

```
1. Render profile → stored in buffer
2. Realize user not logged in
3. Discard buffer
4. Redirect to login → ✓ SUCCESS!
5. User goes to login page
```

## Common Patterns

### Pattern 1: Form Handling

```php
public function contact(Request $request): Response
{
    // Show form on GET
    if ($request->method() === 'GET') {
        return $this->view('contact');
    }

    // Process form on POST
    $name = $request->input('name');
    $email = $request->input('email');
    $message = $request->input('message');

    // Save to database, send email, etc.

    // Redirect after success
    return $this->redirect('/thank-you');
}
```

### Pattern 2: API-like Response

```php
public function checkUsername(Request $request): Response
{
    $username = $request->input('username');

    $exists = $this->loadModel(User::class)->usernameExists($username);

    if ($exists) {
        return $this->response('Username taken', 400);
    }

    return $this->response('Username available', 200);
}
```

### Pattern 3: Conditional Redirect

```php
public function dashboard(Request $request): Response
{
    if (!isset($_SESSION['user_id'])) {
        return $this->redirect('/login');
    }

    return $this->view('dashboard');
}
```

## HTTP Methods Explained

```php
// GET - Retrieve data (view pages, search)
$router->get('/users', [UserController::class, 'index']);

// POST - Submit data (create new)
$router->post('/users', [UserController::class, 'create']);

// PUT - Update existing
$router->put('/users/{id}', [UserController::class, 'update']);

// DELETE - Remove
$router->delete('/users/{id}', [UserController::class, 'delete']);
```

**In Practice:**

```php
public function handleUser(Request $request, $id = null): Response
{
    switch ($request->method()) {
        case 'GET':
            // Show user
            return $this->showUser($id);

        case 'POST':
            // Create user
            return $this->createUser($request);

        case 'PUT':
            // Update user
            return $this->updateUser($request, $id);

        case 'DELETE':
            // Delete user
            return $this->deleteUser($id);
    }
}
```

## Security Notes

### Always Escape Output

```php
// BAD - XSS vulnerability
<h1><?= $title ?></h1>

// GOOD - Safe
<h1><?= htmlspecialchars($title) ?></h1>
```

### Validate Input

```php
// Always validate and sanitize
$email = $request->input('email');
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return $this->view('error', ['message' => 'Invalid email']);
}
```

### Use CSRF Protection

```php
// In view
<form method="POST">
    <?= csrf_field() ?>  <!-- Security token -->
    <!-- form fields -->
</form>

// Route with CSRF middleware
$router->post('/submit', [Controller::class, 'submit'])
    ->middleware([CsrfMiddleware::class]);
```

## Quick Reference

### Request Methods

```php
$request->method()              // HTTP method
$request->path()                // URL path
$request->query('key')          // GET parameter
$request->input('key')          // POST parameter
$request->all()                 // All data
$request->header('name')        // HTTP header
```

### Response Methods

```php
$this->view($view, $data)       // Render view
$this->redirect($url)           // Redirect
$this->response($content)       // Plain response
```

### Common Status Codes

```
200 → OK
302 → Redirect
400 → Bad Request
401 → Unauthorized
404 → Not Found
500 → Server Error
```

## Next: Learn About Routing

Now that you understand Request and Response, learn how URLs map to your controllers:
→ **[03-Routing.md](03-Routing.md)**

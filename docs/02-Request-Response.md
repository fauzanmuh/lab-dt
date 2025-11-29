# Request and Response - Understanding the Flow

## Simplified Approach

In this framework, we use **Native PHP Superglobals** to handle requests. This makes it easier for beginners to understand how PHP works under the hood without hiding it behind complex classes.

## Handling Requests

Instead of a `Request` object, we use standard PHP variables:

- **`$_GET`**: For URL parameters
- **`$_POST`**: For form data
- **`$_SERVER`**: For server and request info

### Example: Reading Data

```php
public function contact()
{
    // 1. Get HTTP Method
    $method = $_SERVER['REQUEST_METHOD']; // 'GET', 'POST', etc.

    // 2. Get URL Parameters (e.g., /search?q=term)
    $query = $_GET['q'] ?? null;

    // 3. Get Form Data
    $email = $_POST['email'] ?? null;
    $message = $_POST['message'] ?? null;
}
```

### Common Tasks

#### Checking Request Method

```php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission
}
```

#### Getting Input with Default Value

```php
// Old way (Request object):
// $name = $request->input('name', 'Guest');

// New way (Native PHP):
$name = $_POST['name'] ?? 'Guest';
```

#### Checking if Input Exists

```php
if (isset($_POST['email'])) {
    // Email was submitted
}
```

## Handling Responses

Controllers in this framework provide helper methods to send responses. You don't need to return a `Response` object; you just call the method.

### 1. Returning a View

Use `$this->view()` to render an HTML page.

```php
public function index()
{
    return $this->view('home', [
        'title' => 'Welcome',
        'message' => 'Hello World'
    ]);
}
```

### 2. Redirecting

Use `$this->redirect()` to send the user to another page. This method automatically exits the script.

```php
public function login()
{
    // ... validate login ...

    // Redirect to dashboard
    $this->redirect('/dashboard');
}
```

### 3. JSON / API Response

Use `$this->response()` to send raw content, JSON, or custom headers.

```php
public function api()
{
    $data = ['status' => 'ok'];

    // Send JSON response
    $this->response(json_encode($data), 200, [
        'Content-Type' => 'application/json'
    ]);
}
```

## Complete Example

Here is how a typical controller method looks now:

```php
class UserController extends Controller
{
    public function store()
    {
        // 1. Check Method
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/users/create');
        }

        // 2. Get Data
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';

        // 3. Validate
        if (empty($name) || empty($email)) {
            // Show error
            return $this->view('users/create', [
                'error' => 'All fields are required'
            ]);
        }

        // 4. Save to DB (Pseudo-code)
        // $this->userModel->create($name, $email);

        // 5. Redirect
        $this->redirect('/users');
    }
}
```

## Key Differences

| Feature    | Old Way (Request Object)      | New Way (Native PHP)         |
| ---------- | ----------------------------- | ---------------------------- |
| Method     | `$request->method()`          | `$_SERVER['REQUEST_METHOD']` |
| URL Params | `$request->query('id')`       | `$_GET['id']`                |
| Form Data  | `$request->input('name')`     | `$_POST['name']`             |
| Redirect   | `return $this->redirect(...)` | `$this->redirect(...)`       |
| Headers    | `$request->header('Host')`    | `$_SERVER['HTTP_HOST']`      |

## Why This Change?

1.  **Simplicity**: You learn standard PHP that works everywhere.
2.  **Transparency**: No "magic" hiding where data comes from.
3.  **Performance**: Less overhead from creating objects.

## Security Notes

Since we are using raw superglobals, **always** sanitize and validate your input!

```php
// BAD
echo $_GET['name'];

// GOOD
echo htmlspecialchars($_GET['name']);
```

Always use `htmlspecialchars()` when outputting user data to prevent XSS attacks.

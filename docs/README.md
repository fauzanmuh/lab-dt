# Documentation Index - For Beginners

Welcome to the PHP MVC Framework documentation! This guide will help you understand how everything works.

## 📚 Learning Path

Read these documents in order if you're new to the framework:

### 1. [Getting Started](01-Getting-Started.md) ⭐ START HERE

- What is MVC?
- Framework structure
- How a request works
- Your first feature
- Common tasks
- Debugging tips

**Read this first!** It gives you the big picture.

### 2. [Request and Response](02-Request-Response.md)

- What are Request and Response objects?
- How to read user input
- How to send output
- Understanding output buffering
- Security tips

**Learn about:** How data flows in and out of your app.

### 3. [Complete Flow](03-Complete-Flow.md)

- Step-by-step request journey
- What happens at each stage
- Why each step matters
- Common flow variations
- Debugging the flow

**Deep dive:** Follow a request from browser to response.

### 4. [Visual Guide](04-Visual-Guide.md) 🎨

- Architecture diagrams
- File structure explained
- Data flow visualizations
- MVC pattern illustrated
- Component responsibilities

**Visual learner?** This one's for you!

## 📖 Documentation Structure

```
docs/
├── 01-Getting-Started.md      ⭐ Start here - Overview and basics
├── 02-Request-Response.md     → Understanding data flow
├── 03-Complete-Flow.md        → Detailed request lifecycle
├── 04-Visual-Guide.md         🎨 Diagrams and visual explanations
└── README.md                  ← You are here!
```

## 🎯 What to Read Based on Your Goal

### "I want to create a new page"

1. Read: [Getting Started - Your First Feature](01-Getting-Started.md#your-first-feature)
2. Quick steps:
   - Add route in `routes/web.php`
   - Add controller method in `app/Controllers/`
   - Add view file in `app/Views/`

### "I need to handle form submissions"

1. Read: [Request and Response - Reading Request Data](02-Request-Response.md#reading-request-data)
2. Read: [Request and Response - Pattern 1: Form Handling](02-Request-Response.md#pattern-1-form-handling)

### "I don't understand what's happening"

1. Read: [Complete Flow](03-Complete-Flow.md)
2. Add `dd()` statements to see values
3. Check terminal for error messages

### "I need to work with database"

1. Check `README.md` in project root
2. Look at `app/Models/User.php` for examples
3. See [Getting Started - Query Database](01-Getting-Started.md#query-database)

### "Something is broken"

1. Read: [Getting Started - Debugging Tips](01-Getting-Started.md#debugging-tips)
2. Check terminal output
3. Use `dd($variable)` to inspect values
4. Read error messages carefully

## 🔑 Key Concepts to Understand

### MVC Pattern

```
Model      → Database and business logic
View       → HTML templates
Controller → Coordinates between Model and View
```

### Request Flow

```
URL → Router → Middleware → Controller → Model → View → Response
```

### Important Files

```
routes/web.php              ← Define your URLs here
app/Controllers/            ← Your controller classes
app/Views/                  ← Your HTML templates
app/Models/                 ← Your database queries
config/app.php              ← Configuration settings
```

## 📝 Quick Reference

### Create a Page

```php
// 1. Route (routes/web.php)
$router->get('/hello', [HomeController::class, 'hello']);

// 2. Controller (app/Controllers/HomeController.php)
public function hello(Request $request): Response
{
    return $this->view('hello', ['message' => 'Hi!']);
}

// 3. View (app/Views/hello.php)
<h1><?= htmlspecialchars($message) ?></h1>
```

### Handle Form

```php
// Controller
if ($request->method() === 'POST') {
    $name = $request->input('name');
    // Process...
    return $this->redirect('/success');
}
return $this->view('form');
```

### Query Database

```php
// In controller
$model = $this->loadModel(User::class);
$users = $model->getAllUsers();
return $this->view('users', ['users' => $users]);
```

## 🛠️ Tools for Learning

### Debugging Tools

```php
dd($variable);              // Dump variable and stop
dump($variable);            // Dump variable, continue
var_dump($variable);        // Show variable structure
echo "Debug point";         // Simple output
```

### Check Variables in Controller

```php
public function test(Request $request): Response
{
    dd($request->all());    // See all input
    dd($request->method()); // See HTTP method
    dd($request->path());   // See URL path
}
```

### Check Variables in View

```php
<?php dd($variable); ?>
<?php var_dump(get_defined_vars()); ?>  // All variables
```

## 🎓 Learning Tips

### 1. Start Small

- Don't try to understand everything at once
- Create a simple page first
- Add complexity gradually

### 2. Read Error Messages

```
Error messages tell you:
- What went wrong
- Which file
- Which line number
```

### 3. Use dd() Liberally

```php
// See what's in a variable
dd($user);

// See if you reached a point
dd("Got here!");

// See multiple things
dd($request->all(), $user, $settings);
```

### 4. Follow the Flow

```
Start at routes/web.php
  ↓
Find the controller method
  ↓
See what it does
  ↓
Find the view file
  ↓
See the HTML
```

### 5. Compare Working Examples

- Look at `HomeController.php`
- Compare with your code
- See what's different

## 🤝 Team Collaboration

### Before Asking for Help

1. Check error message
2. Look at similar working code
3. Try `dd()` to inspect values
4. Check if file exists and has correct name

### When Asking for Help

Include:

1. What you're trying to do
2. What you expected
3. What actually happened
4. Error message (if any)
5. Code you wrote

### Good Question Example

```
"I'm trying to create a contact page. I added the route
and controller, but when I visit /contact I get a 404 error.

Route: $router->get('/contact', [HomeController::class, 'contact']);
Controller: public function contact() { ... }
Error: 404 Not Found

What am I missing?"
```

## 📚 Additional Resources

### In This Repo

- `/README.md` - Main project documentation
- `/app/Controllers/HomeController.php` - Example controller
- `/app/Views/` - Example views
- `/routes/web.php` - Example routes

### PHP Basics

If you're new to PHP, learn these concepts first:

- Variables and types
- Arrays
- Functions
- Classes and objects
- include/require

### Useful PHP Functions

```php
// Output
echo "text";
var_dump($var);

// Arrays
count($array);
in_array($value, $array);
array_map($callback, $array);

// Strings
strlen($string);
str_replace($search, $replace, $string);
htmlspecialchars($string);

// Files
file_exists($path);
is_readable($path);
```

## 🎯 Next Steps

After reading the beginner docs:

1. ✅ Create a simple page
2. ✅ Handle a form submission
3. ✅ Query the database
4. ✅ Add your own routes
5. ✅ Create your own controller
6. ✅ Build a feature from scratch

## 💡 Common Gotchas

### 1. File Not Found

```
Problem: View [home] not found
Solution: Check file exists at app/Views/home.php
         Check spelling and case sensitivity
```

### 2. Headers Already Sent

```
Problem: Cannot modify header information
Solution: Don't echo anything before redirect
         Remove spaces before <?php
```

### 3. Undefined Variable

```
Problem: Undefined variable: $title
Solution: Pass it in controller: ['title' => 'Page']
         Or use default: $title ?? 'Default'
```

### 4. Method Not Found

```
Problem: Call to undefined method
Solution: Check method name spelling
         Make sure it's public
         Check class has the method
```

## 🚀 You're Ready!

Start with [Getting Started](01-Getting-Started.md) and work your way through. Don't worry if you don't understand everything immediately - it takes practice!

**Remember:**

- Everyone was a beginner once
- Asking questions is good
- Making mistakes helps you learn
- Practice makes perfect

Happy coding! 🎉

# Visual Guide - Framework Architecture

This document uses diagrams and visuals to help you understand the framework structure.

## 🏗️ High-Level Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                         YOUR APPLICATION                         │
│                                                                  │
│  ┌────────────┐      ┌────────────┐      ┌────────────┐       │
│  │            │      │            │      │            │       │
│  │   ROUTES   │─────▶│ CONTROLLER │─────▶│    VIEW    │       │
│  │            │      │            │      │            │       │
│  │  Define    │      │  Handles   │      │  Displays  │       │
│  │  URLs      │      │  Logic     │      │  HTML      │       │
│  │            │      │     │      │      │            │       │
│  └────────────┘      └─────┼──────┘      └────────────┘       │
│                            │                                    │
│                            ▼                                    │
│                      ┌────────────┐                            │
│                      │            │                            │
│                      │   MODEL    │                            │
│                      │            │                            │
│                      │  Database  │                            │
│                      │  Queries   │                            │
│                      │            │                            │
│                      └────────────┘                            │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
                             │
                             │ Uses
                             ▼
┌─────────────────────────────────────────────────────────────────┐
│                       FRAMEWORK CORE                             │
│                                                                  │
│  Router │ Request │ Response │ Database │ Middleware │ Helpers  │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

## 📁 File Structure with Explanations

```
your-project/
│
├── 📁 public/                          ← WEB ROOT - Entry point
│   ├── index.php                       ← ALL requests come here
│   ├── .htaccess                       ← Apache URL rewriting
│   ├── css/                            ← Your CSS files
│   ├── js/                             ← Your JavaScript files
│   └── images/                         ← Your images
│
├── 📁 app/                             ← YOUR APPLICATION CODE
│   │
│   ├── 📁 Controllers/                 ← Request handlers
│   │   ├── HomeController.php          ← Example: Home page
│   │   └── UserController.php          ← Example: User management
│   │
│   ├── 📁 Models/                      ← Database operations
│   │   └── User.php                    ← Example: User model
│   │
│   └── 📁 Views/                       ← HTML templates
│       ├── layout.php                  ← Main layout wrapper
│       ├── home.php                    ← Home page view
│       └── about.php                   ← About page view
│
├── 📁 core/                            ← FRAMEWORK CODE
│   │
│   ├── 📁 Http/                        ← HTTP handling
│   │   ├── Request.php                 ← Input wrapper
│   │   └── Response.php                ← Output wrapper
│   │
│   ├── 📁 Database/                    ← Database layer
│   │   └── Database.php                ← PDO wrapper
│   │
│   ├── 📁 Middleware/                  ← Request filters
│   │   ├── AuthMiddleware.php          ← Login checks
│   │   └── CsrfMiddleware.php          ← Security tokens
│   │
│   ├── Application.php                 ← Main app class
│   ├── Router.php                      ← URL routing
│   ├── Route.php                       ← Single route
│   ├── Controller.php                  ← Base controller
│   ├── Model.php                       ← Base model
│   └── helpers.php                     ← Utility functions
│
├── 📁 routes/                          ← URL DEFINITIONS
│   └── web.php                         ← Your routes here
│
├── 📁 config/                          ← CONFIGURATION
│   └── app.php                         ← Database, settings
│
├── 📁 docs/                            ← DOCUMENTATION
│   ├── README.md                       ← Documentation index
│   ├── 01-Getting-Started.md           ← Beginner guide
│   ├── 02-Request-Response.md          ← Data flow
│   └── 03-Complete-Flow.md             ← Request lifecycle
│
├── 📁 vendor/                          ← COMPOSER PACKAGES
│   └── autoload.php                    ← Class autoloader
│
├── composer.json                       ← Composer config
└── README.md                           ← Main documentation
```

## 🔄 Request Lifecycle (Simplified)

```
1. USER ACTION
   └─▶ User visits: http://localhost:8000/about

2. WEB SERVER
   └─▶ Receives HTTP request
       └─▶ Routes to public/index.php

3. APPLICATION BOOTSTRAP
   └─▶ index.php loads:
       ├─▶ Autoloader (Composer)
       ├─▶ Application class
       ├─▶ Routes file
       └─▶ Runs app

4. REQUEST OBJECT CREATED
   └─▶ Wraps $_GET, $_POST, $_SERVER
       └─▶ Clean API to access data

5. ROUTER MATCHES URL
   └─▶ Looks at URL: /about
       └─▶ Finds: GET /about → HomeController::about

6. MIDDLEWARE (Optional)
   └─▶ Checks:
       ├─▶ Is user logged in? (Auth)
       ├─▶ Valid CSRF token? (Security)
       └─▶ Custom checks?

7. CONTROLLER CALLED
   └─▶ HomeController::about($request)
       ├─▶ May load Model
       ├─▶ Process logic
       └─▶ Returns Response

8. VIEW RENDERED
   └─▶ Finds: app/Views/about.php
       ├─▶ Extracts data to variables
       ├─▶ Buffers output
       ├─▶ Includes view file
       └─▶ Returns HTML string

9. RESPONSE SENT
   └─▶ Sets HTTP status (200 OK)
       ├─▶ Sets headers
       └─▶ Sends HTML content

10. BROWSER DISPLAYS
    └─▶ User sees the page!
```

## 🎯 MVC Pattern Explained

### Traditional Web (Without MVC)

```
┌─────────────────────────────────────┐
│  page1.php                          │
│  ├── HTML                           │
│  ├── Database queries              │
│  ├── Business logic                │
│  └── More HTML                      │
└─────────────────────────────────────┘

Problems:
❌ Everything mixed together
❌ Hard to maintain
❌ Can't reuse code easily
❌ Difficult for teams
```

### With MVC

```
┌──────────────────┐
│     MODEL        │  ← Database & Business Logic
│  (User.php)      │     • getAllUsers()
└────────┬─────────┘     • getUserById($id)
         │                • createUser($data)
         │ provides data
         ▼
┌──────────────────┐
│   CONTROLLER     │  ← Request Handler
│ (UserController) │     • Receives request
└────────┬─────────┘     • Asks Model for data
         │                • Chooses View
         │ passes data
         ▼
┌──────────────────┐
│      VIEW        │  ← Presentation
│ (users/list.php) │     • Shows HTML
└──────────────────┘     • Uses data from Controller

Benefits:
✅ Clear separation
✅ Easy to maintain
✅ Reusable components
✅ Team-friendly
```

## 🌊 Data Flow Diagram

```
┌─────────────┐
│   Browser   │
│  (Client)   │
└──────┬──────┘
       │
       │ 1. HTTP Request
       │    GET /users
       ▼
┌─────────────┐
│   Router    │ ─────── routes/web.php
│             │ $router->get('/users', [UserController::class, 'index'])
└──────┬──────┘
       │
       │ 2. Route Matched
       ▼
┌─────────────┐
│ Controller  │ ─────── app/Controllers/UserController.php
│   (Thin)    │ public function index(Request $request): Response
└──────┬──────┘
       │
       │ 3. Load Model
       ▼
┌─────────────┐
│   Model     │ ─────── app/Models/User.php
│  (Fat)      │ public function getAllUsers()
└──────┬──────┘         { SQL query here }
       │
       │ 4. Query Database
       ▼
┌─────────────┐
│  Database   │ ─────── PostgreSQL/MySQL
│             │ SELECT * FROM users
└──────┬──────┘
       │
       │ 5. Return Results
       ▼
┌─────────────┐
│   Model     │ Return array of users
└──────┬──────┘
       │
       │ 6. Pass to Controller
       ▼
┌─────────────┐
│ Controller  │ $users = $model->getAllUsers()
└──────┬──────┘ return $this->view('users/index', ['users' => $users])
       │
       │ 7. Render View
       ▼
┌─────────────┐
│    View     │ ─────── app/Views/users/index.php
│             │ <?php foreach($users as $user): ?>
└──────┬──────┘   <li><?= $user['name'] ?></li>
       │          <?php endforeach; ?>
       │
       │ 8. HTML Generated
       ▼
┌─────────────┐
│  Response   │ HTML wrapped in Response object
└──────┬──────┘
       │
       │ 9. HTTP Response
       ▼
┌─────────────┐
│   Browser   │ User sees the list of users
│  (Client)   │
└─────────────┘
```

## 🔐 Middleware Flow

```
Request comes in
      │
      ▼
┌─────────────┐
│ Middleware  │ ◄── Applied in order
│   Stack     │
└──────┬──────┘
       │
       ├─▶ 1. CsrfMiddleware
       │   └─▶ Check token
       │       ├─▶ Valid? Continue ✓
       │       └─▶ Invalid? Return 403 ✗
       │
       ├─▶ 2. AuthMiddleware
       │   └─▶ Check login
       │       ├─▶ Logged in? Continue ✓
       │       └─▶ Not logged in? Redirect to /login ✗
       │
       ├─▶ 3. Custom Middleware
       │   └─▶ Your checks
       │       ├─▶ Pass? Continue ✓
       │       └─▶ Fail? Return error ✗
       │
       ▼
  All passed!
       │
       ▼
┌─────────────┐
│ Controller  │
└─────────────┘
```

## 🗂️ Routing Pattern

```
┌─────────────────────────────────────────────────────────┐
│                  routes/web.php                          │
├─────────────────────────────────────────────────────────┤
│                                                          │
│  $router->get('/users', [UserController::class, 'index'])│
│            │      │                  │              │    │
│            │      │                  │              │    │
│       HTTP Method │                  │              │    │
│                   │                  │              │    │
│                URL Path              │              │    │
│                                      │              │    │
│                            Controller Class         │    │
│                                                      │    │
│                                         Controller Method│
└─────────────────────────────────────────────────────────┘

When user visits: http://localhost:8000/users
                                         └────┘
                                         Matches /users
                                            │
                                            ▼
                              Calls: UserController::index()
```

## 💾 Database Query Flow

```
1. CONTROLLER
   $userModel = $this->loadModel(User::class);
   $users = $userModel->getAllUsers();
                │
                ▼
2. MODEL
   public function getAllUsers() {
       $sql = "SELECT * FROM users";
       return $this->db->query($sql);
   }
                │
                ▼
3. DATABASE CLASS
   public function query($sql, $params = []) {
       $stmt = $this->pdo->prepare($sql);
       $stmt->execute($params);
       return $stmt->fetchAll();
   }
                │
                ▼
4. PDO (PHP Data Objects)
   Connects to PostgreSQL/MySQL
   Executes: SELECT * FROM users
                │
                ▼
5. DATABASE SERVER
   Returns: [
       ['id' => 1, 'name' => 'John'],
       ['id' => 2, 'name' => 'Jane']
   ]
                │
                ▼
6. BACK TO MODEL → CONTROLLER → VIEW
```

## 🎨 View Rendering Process

```
Controller calls:
$this->view('users/index', ['users' => $usersArray])
      │
      ▼
┌─────────────────────────────────────────────┐
│  Response::view()                           │
│  1. Calls renderView()                      │
└──────────────┬──────────────────────────────┘
               │
               ▼
┌─────────────────────────────────────────────┐
│  renderView()                               │
│  1. Find file: app/Views/users/index.php   │
│  2. Extract data: $users = $usersArray     │
│  3. ob_start() ← Start buffer              │
│  4. include 'users/index.php'              │
│  5. ob_get_clean() ← Get HTML              │
└──────────────┬──────────────────────────────┘
               │
               ▼
┌─────────────────────────────────────────────┐
│  Returns HTML as string                     │
│  "<h1>Users</h1><ul><li>John</li>..."      │
└──────────────┬──────────────────────────────┘
               │
               ▼
┌─────────────────────────────────────────────┐
│  new Response($html, 200)                   │
│  Response object created                    │
└──────────────┬──────────────────────────────┘
               │
               ▼
      Sent to browser
```

## 🔧 Helper Functions Map

```
helpers.php
├── dd($var)              → Dump and die (debugging)
├── dump($var)            → Dump variable
├── view($view, $data)    → Render view
├── redirect($url)        → Redirect to URL
├── config($key)          → Get config value
├── csrf_token()          → Get CSRF token
├── csrf_field()          → CSRF input HTML
├── session($key, $val)   → Session management
├── old($key, $default)   → Old form input
├── base_path($path)      → Get base path
├── public_path($path)    → Get public path
└── storage_path($path)   → Get storage path
```

## 🎯 Quick Decision Tree

```
Q: Where should I put my code?

┌─────────────────────────────────┐
│ What are you trying to do?     │
└────────────┬────────────────────┘
             │
    ┌────────┴────────┐
    │                 │
    ▼                 ▼
┌───────┐         ┌────────┐
│ HTML? │         │ Logic? │
└───┬───┘         └───┬────┘
    │                 │
    ▼                 │
 Views/               │
                      │
         ┌────────────┴──────────┐
         │                       │
         ▼                       ▼
    ┌─────────┐          ┌──────────┐
    │Database?│          │Coordinate│
    └───┬─────┘          │multiple  │
        │                │things?   │
        ▼                └────┬─────┘
    Models/                   │
                              ▼
                         Controllers/
```

## 📊 Component Responsibilities

```
┌────────────────────────────────────────────────────┐
│                    ROUTER                          │
│  Responsibility: Map URLs to Controllers          │
│  • Match HTTP method + URL                        │
│  • Extract parameters                             │
│  • Apply middleware                               │
│  Should: Be simple, just routing                  │
│  Should NOT: Contain business logic               │
└────────────────────────────────────────────────────┘

┌────────────────────────────────────────────────────┐
│                 CONTROLLER                         │
│  Responsibility: Handle requests, coordinate      │
│  • Receive Request                                │
│  • Load Models                                    │
│  • Process logic (minimal)                        │
│  • Return Response                                │
│  Should: Be thin, delegate work                   │
│  Should NOT: Have complex logic or SQL            │
└────────────────────────────────────────────────────┘

┌────────────────────────────────────────────────────┐
│                    MODEL                           │
│  Responsibility: Database & business logic        │
│  • Execute SQL queries                            │
│  • Validate data                                  │
│  • Business rules                                 │
│  • Data transformations                           │
│  Should: Be fat, contain most logic               │
│  Should NOT: Generate HTML                        │
└────────────────────────────────────────────────────┘

┌────────────────────────────────────────────────────┐
│                    VIEW                            │
│  Responsibility: Generate HTML                    │
│  • Display data                                   │
│  • Loop through arrays                            │
│  • Conditional display                            │
│  • HTML/CSS/JS                                    │
│  Should: Only presentation logic                  │
│  Should NOT: Database queries or complex logic    │
└────────────────────────────────────────────────────┘
```

## 🚀 Next Steps

Now that you've seen the visual overview:

1. Read [Getting Started](01-Getting-Started.md)
2. Try creating a simple page
3. Follow the flow with `dd()` statements
4. Build something!

Remember: It's okay if this seems complex at first. Focus on one piece at a time! 🎯

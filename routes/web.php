<?php

/**
 * Web Routes
 * Define your application routes here
 */

use App\Controllers\HomeController;
use Core\Middleware\AuthMiddleware;
use Core\Middleware\CsrfMiddleware;

$router = $app->router();

// ============================================
// Basic Routes
// ============================================

$router->get('/', [HomeController::class, 'index']);
$router->get('/about', [HomeController::class, 'about']);
$router->get('/gallery', [HomeController::class, 'galleryPage']);
$router->get('/login', [HomeController::class, 'loginPage']);

// ============================================
// Example Routes (Commented for Reference)
// ============================================

// // Simple GET route
// $router->get('/contact', [HomeController::class, 'contact']);

// // POST route (for form submissions)
// $router->post('/contact', [HomeController::class, 'submitContact']);

// // Route with parameter
// $router->get('/user/{id}', [HomeController::class, 'showUser']);

// // Route with multiple parameters
// $router->get('/posts/{category}/{id}', [HomeController::class, 'showPost']);

// // Multiple HTTP methods for same route
// $router->match(['GET', 'POST'], '/form', [HomeController::class, 'handleForm']);

// // ============================================
// // Closure Routes Examples
// // ============================================

// // Simple closure route
// $router->get('/hello/{name}', function ($request, $name) {
//     return "Hello, {$name}!";
// });

// // Closure with view
// $router->get('/welcome', function ($request) {
//     return view('home', [
//         'title' => 'Welcome',
//         'message' => 'This is a closure route!'
//     ]);
// });

// // ============================================
// // Routes with Middleware Examples
// // ============================================

// // Single middleware
// $router->get('/dashboard', [HomeController::class, 'dashboard'])
//     ->middleware([AuthMiddleware::class]);

// // Multiple middleware
// $router->post('/submit-form', [HomeController::class, 'submitForm'])
//     ->middleware([AuthMiddleware::class, CsrfMiddleware::class]);

// // Closure with middleware
// $router->get('/admin', function ($request) {
//     return view('admin', ['title' => 'Admin Panel']);
// })->middleware([AuthMiddleware::class]);

// // ============================================
// // Route Groups Examples
// // ============================================

// // Group with prefix
// $router->group(['prefix' => 'admin'], function ($router) {
//     $router->get('/users', [HomeController::class, 'adminUsers']);
//     $router->get('/settings', [HomeController::class, 'adminSettings']);
// });
// // Results in: /admin/users, /admin/settings

// // Group with middleware
// $router->group(['middleware' => [AuthMiddleware::class]], function ($router) {
//     $router->get('/profile', [HomeController::class, 'profile']);
//     $router->get('/settings', [HomeController::class, 'settings']);
//     $router->post('/update-profile', [HomeController::class, 'updateProfile']);
// });

// // Group with both prefix and middleware
// $router->group([
//     'prefix' => 'admin',
//     'middleware' => [AuthMiddleware::class]
// ], function ($router) {
//     $router->get('/dashboard', [HomeController::class, 'adminDashboard']);
//     $router->get('/users', [HomeController::class, 'adminUsers']);
//     $router->post('/users/{id}', [HomeController::class, 'updateUser']);
// });

// // ============================================
// // RESTful Resource Routes Example
// // ============================================

// // Products CRUD operations
// $router->get('/products', [HomeController::class, 'index']);           // List all products
// $router->get('/products/create', [HomeController::class, 'create']);   // Show create form
// $router->post('/products', [HomeController::class, 'store']);          // Store new product
// $router->get('/products/{id}', [HomeController::class, 'show']);       // Show single product
// $router->get('/products/{id}/edit', [HomeController::class, 'edit']); // Show edit form
// $router->put('/products/{id}', [HomeController::class, 'update']);    // Update product
// $router->delete('/products/{id}', [HomeController::class, 'destroy']); // Delete product

// // ============================================
// // Form Handling Examples
// // ============================================

// // Contact form with CSRF protection
// $router->match(['GET', 'POST'], '/contact', function ($request) {
//     if ($request->method() === 'POST') {
//         $name = $request->input('name');
//         $email = $request->input('email');
//         $message = $request->input('message');
//         
//         // Process form data here...
//         
//         return redirect('/contact?success=1');
//     }
//     
//     return view('contact', ['title' => 'Contact Us']);
// })->middleware([CsrfMiddleware::class]);

// // ============================================
// // Special Routes
// // ============================================

// // Redirect route
// $router->get('/old-page', function ($request) {
//     return redirect('/new-page');
// });

// // Catch-all route (should be at the end)
// $router->get('/{path}', function ($request, $path) {
//     return view('404', ['path' => $path]);
// });

<?php

/**
 * Front Controller
 * Entry point for all requests
 */

// Define base path
define('BASE_PATH', dirname(__DIR__));

// Load Composer autoloader
require BASE_PATH . '/vendor/autoload.php';

// Load helper functions
require BASE_PATH . '/core/helpers.php';

// Create application instance
$app = new \Core\Application(BASE_PATH);

// Load routes
require BASE_PATH . '/routes/web.php';

// Run the application
$app->run();

<?php
declare(strict_types=1);

use App\Core\Router;

// Get the router instance from the container
$router = $container->get(Router::class);

// Define your routes here
$router->get('/', [\App\Controllers\HomeController::class, 'index']);

// Example routes
// $router->get('/about', [\App\Controllers\PageController::class, 'about']);
// $router->get('/contact', [\App\Controllers\PageController::class, 'contact']);
// $router->post('/contact', [\App\Controllers\PageController::class, 'submitContact']);

// User routes example
// $router->get('/login', [\App\Controllers\AuthController::class, 'loginForm']);
// $router->post('/login', [\App\Controllers\AuthController::class, 'login']);
// $router->get('/register', [\App\Controllers\AuthController::class, 'registerForm']);
// $router->post('/register', [\App\Controllers\AuthController::class, 'register']);
// $router->post('/logout', [\App\Controllers\AuthController::class, 'logout']);

// Protected routes example with middleware
// $router->get('/dashboard', [\App\Controllers\DashboardController::class, 'index'], [\App\Middleware\AuthMiddleware::class]);
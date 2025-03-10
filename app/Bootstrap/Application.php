<?php
declare(strict_types=1);

namespace App\Bootstrap;

use App\Core\Container;
use App\Core\Config;
use App\Core\ErrorHandler;
use App\Core\Router;
use App\Core\Request;
use App\Core\Response;

class Application
{
    private Container $container;
    
    public function __construct()
    {
        // Set up error handling early
        $this->setupErrorHandling();
        
        // Load environment variables
        $this->loadEnvironment();
        
        // Initialize container
        $this->container = new Container();
        
        // Register services
        $this->registerServices();
    }
    
    public function run(): void
    {
        // Configure security headers
        $this->setupSecurityHeaders();
        
        // Start session securely
        $this->setupSession();
        
        // Create request from globals
        $request = Request::createFromGlobals();
        $this->container->set(Request::class, $request);
        
        // Get router and dispatch request
        $router = $this->container->get(Router::class);
        $response = $router->dispatch($request);
        
        // Send response
        $response->send();
    }
    
    private function setupErrorHandling(): void
    {
        error_reporting(E_ALL);
        ini_set('display_errors', '0');
        ini_set('log_errors', '1');
        ini_set('error_log', __DIR__ . '/../../logs/error.log');
        
        set_error_handler([ErrorHandler::class, 'handleError']);
        set_exception_handler([ErrorHandler::class, 'handleException']);
        register_shutdown_function([ErrorHandler::class, 'handleFatalError']);
    }
    
    private function loadEnvironment(): void
    {
        // Load .env file if it exists using a library like vlucas/phpdotenv
        if (file_exists(__DIR__ . '/../../.env')) {
            $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../..');
            $dotenv->load();
        }
    }
    
    private function registerServices(): void
    {
        // Register configuration
        $config = new Config(__DIR__ . '/../../config');
        $this->container->set(Config::class, $config);
        
        // Register router
        $this->container->set(Router::class, new Router($this->container));
        
        // Register controllers and services
        // This could be moved to a separate ServiceProvider class
        $this->registerControllers();
        $this->registerMiddleware();
        $this->registerRepositories();
    }
    
    private function setupSecurityHeaders(): void
    {
        // Content Security Policy
        header("Content-Security-Policy: default-src 'self'; script-src 'self'; object-src 'none'");
        // Prevent MIME type sniffing
        header("X-Content-Type-Options: nosniff");
        // Clickjacking protection
        header("X-Frame-Options: DENY");
        // XSS protection
        header("X-XSS-Protection: 1; mode=block");
        // Referrer policy
        header("Referrer-Policy: strict-origin-when-cross-origin");
        // Permissions policy
        header("Permissions-Policy: geolocation=(), microphone=(), camera=()");
    }
    
    private function setupSession(): void
    {
        // Secure cookie settings
        ini_set('session.cookie_httponly', '1');
        ini_set('session.use_only_cookies', '1');
        ini_set('session.cookie_samesite', 'Strict');
        
        // Set secure cookies in production
        if ($this->isProduction()) {
            ini_set('session.cookie_secure', '1');
        }
        
        // Start session
        session_start();
    }
    
    private function isProduction(): bool
    {
        return ($_ENV['APP_ENV'] ?? 'production') === 'production';
    }
    
    private function registerControllers(): void
    {
        // Register your controllers here
    }
    
    private function registerMiddleware(): void
    {
        // Register your middleware here
    }
    
    private function registerRepositories(): void
    {
        // Register your repositories here
    }
}
<?php
declare(strict_types=1);

namespace App\Core;

class Router
{
    private Container $container;
    private array $routes = [];
    private array $middleware = [];
    
    public function __construct(Container $container)
    {
        $this->container = $container;
    }
    
    public function get(string $path, array $handler, array $middleware = []): void
    {
        $this->addRoute('GET', $path, $handler, $middleware);
    }
    
    public function post(string $path, array $handler, array $middleware = []): void
    {
        $this->addRoute('POST', $path, $handler, $middleware);
    }
    
    public function put(string $path, array $handler, array $middleware = []): void
    {
        $this->addRoute('PUT', $path, $handler, $middleware);
    }
    
    public function delete(string $path, array $handler, array $middleware = []): void
    {
        $this->addRoute('DELETE', $path, $handler, $middleware);
    }
    
    private function addRoute(string $method, string $path, array $handler, array $middleware = []): void
    {
        $this->routes[$method][$path] = [
            'handler' => $handler,
            'middleware' => $middleware
        ];
    }
    
    public function addGlobalMiddleware(string $middlewareClass): void
    {
        $this->middleware[] = $middlewareClass;
    }
    
    public function dispatch(Request $request): Response
    {
        $method = $request->getMethod();
        $path = $request->getPath();
        
        // Check if route exists
        if (!isset($this->routes[$method][$path])) {
            return new Response('Not Found', 404);
        }
        
        $route = $this->routes[$method][$path];
        $handler = $route['handler'];
        
        // Merge global and route-specific middleware
        $middleware = array_merge($this->middleware, $route['middleware']);
        
        // Execute middleware chain
        $middlewareStack = $this->buildMiddlewareStack($middleware, function (Request $request) use ($handler) {
            return $this->executeHandler($handler, $request);
        });
        
        return $middlewareStack($request);
    }
    
    private function buildMiddlewareStack(array $middleware, callable $core): callable
    {
        $coreFunction = $core;
        
        // Build middleware chain from inside out
        for ($i = count($middleware) - 1; $i >= 0; $i--) {
            $middlewareClass = $middleware[$i];
            $middlewareInstance = $this->container->get($middlewareClass);
            
            $coreFunction = function (Request $request) use ($middlewareInstance, $coreFunction) {
                return $middlewareInstance->process($request, $coreFunction);
            };
        }
        
        return $coreFunction;
    }
    
    private function executeHandler(array $handler, Request $request): Response
    {
        [$controllerClass, $method] = $handler;
        
        $controller = $this->container->get($controllerClass);
        return $controller->$method($request);
    }
}
<?php
declare(strict_types=1);

namespace App\Core;

class Container
{
    private array $instances = [];
    private array $definitions = [];
    
    public function set(string $id, $instance): void
    {
        $this->instances[$id] = $instance;
    }
    
    public function get(string $id)
    {
        // Return existing instance if available
        if (isset($this->instances[$id])) {
            return $this->instances[$id];
        }
        
        // Check if we have a definition
        if (isset($this->definitions[$id])) {
            $factory = $this->definitions[$id];
            $instance = $factory($this);
            $this->instances[$id] = $instance;
            return $instance;
        }
        
        // Try to create instance if class exists
        if (class_exists($id)) {
            $reflection = new \ReflectionClass($id);
            
            if ($reflection->isInstantiable()) {
                $constructor = $reflection->getConstructor();
                
                // If no constructor or no params, create directly
                if ($constructor === null || $constructor->getNumberOfParameters() === 0) {
                    $instance = new $id();
                    $this->instances[$id] = $instance;
                    return $instance;
                }
                
                // Handle constructor with dependencies
                $params = [];
                foreach ($constructor->getParameters() as $param) {
                    $paramType = $param->getType();
                    if ($paramType !== null && !$paramType->isBuiltin()) {
                        $typeName = $paramType->getName();
                        $params[] = $this->get($typeName);
                    } elseif ($param->isDefaultValueAvailable()) {
                        $params[] = $param->getDefaultValue();
                    } else {
                        throw new \Exception("Cannot resolve parameter '{$param->getName()}' for class $id");
                    }
                }
                
                $instance = $reflection->newInstanceArgs($params);
                $this->instances[$id] = $instance;
                return $instance;
            }
        }
        
        throw new \Exception("Cannot resolve: $id");
    }
    
    public function has(string $id): bool
    {
        return isset($this->instances[$id]) || isset($this->definitions[$id]) || class_exists($id);
    }
    
    public function define(string $id, callable $factory): void
    {
        $this->definitions[$id] = $factory;
    }
}
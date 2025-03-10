<?php
declare(strict_types=1);

namespace App\Core;

class Config
{
    private array $configs = [];
    private string $configPath;
    
    public function __construct(string $configPath)
    {
        $this->configPath = $configPath;
        $this->loadConfigurations();
    }
    
    private function loadConfigurations(): void
    {
        // Load each PHP configuration file in the config directory
        foreach (glob($this->configPath . '/*.php') as $file) {
            $key = basename($file, '.php');
            $this->configs[$key] = require $file;
        }
    }
    
    public function get(string $key, $default = null)
    {
        // Allow dot notation for nested configs (e.g., 'app.name')
        $parts = explode('.', $key);
        $config = $this->configs;
        
        foreach ($parts as $part) {
            if (!isset($config[$part])) {
                return $default;
            }
            $config = $config[$part];
        }
        
        return $config;
    }
}
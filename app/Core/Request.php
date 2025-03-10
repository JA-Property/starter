<?php
declare(strict_types=1);

namespace App\Core;

class Request
{
    private array $get;
    private array $post;
    private array $server;
    private array $files;
    private array $cookies;
    private ?string $content;
    
    public function __construct(array $get, array $post, array $server, array $files, array $cookies, ?string $content)
    {
        $this->get = $get;
        $this->post = $post;
        $this->server = $server;
        $this->files = $files;
        $this->cookies = $cookies;
        $this->content = $content;
    }
    
    public static function createFromGlobals(): self
    {
        return new self(
            $_GET,
            $_POST,
            $_SERVER,
            $_FILES,
            $_COOKIE,
            file_get_contents('php://input')
        );
    }
    
    public function getMethod(): string
    {
        return strtoupper($this->server['REQUEST_METHOD'] ?? 'GET');
    }
    
    public function getPath(): string
    {
        $path = $this->server['REQUEST_URI'] ?? '/';
        $position = strpos($path, '?');
        
        if ($position !== false) {
            $path = substr($path, 0, $position);
        }
        
        return $path;
    }
    
    public function getQueryParam(string $key, $default = null)
    {
        return $this->get[$key] ?? $default;
    }
    
    public function getPostParam(string $key, $default = null)
    {
        return $this->post[$key] ?? $default;
    }
    
    public function getJson(): ?array
    {
        if ($this->content === null) {
            return null;
        }
        
        $contentType = $this->server['CONTENT_TYPE'] ?? '';
        if (strpos($contentType, 'application/json') !== false) {
            return json_decode($this->content, true);
        }
        
        return null;
    }
    
    // Additional methods as needed...
}


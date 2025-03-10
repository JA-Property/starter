<?php
declare(strict_types=1);

namespace App\Core;

class Response
{
    private int $statusCode;
    private array $headers;
    private string $content;
    
    public function __construct(string $content = '', int $statusCode = 200, array $headers = [])
    {
        $this->content = $content;
        $this->statusCode = $statusCode;
        $this->headers = $headers;
    }
    
    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }
    
    public function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;
        return $this;
    }
    
    public function addHeader(string $name, string $value): self
    {
        $this->headers[$name] = $value;
        return $this;
    }
    
    public function setJsonContent(array $data): self
    {
        $this->content = json_encode($data, JSON_THROW_ON_ERROR);
        $this->addHeader('Content-Type', 'application/json');
        return $this;
    }
    
    public function send(): void
    {
        // Set status code
        http_response_code($this->statusCode);
        
        // Set headers
        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }
        
        // Output content
        echo $this->content;
    }
    
    public static function json(array $data, int $statusCode = 200): self
    {
        return (new self('', $statusCode))
            ->setJsonContent($data);
    }
}
<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;

class HomeController
{
    public function index(Request $request): Response
    {
        // Load the view content
        $content = $this->view('home', [
            'title' => 'Welcome to JA Property',
            'message' => 'Your PHP framework is running successfully!'
        ]);
        
        // Return response with the view content
        return new Response($content);
    }
    
    private function view(string $view, array $data = []): string
    {
        // Extract data to make variables available in the view
        extract($data);
        
        // Start output buffering
        ob_start();
        
        // Include the view file (correct path)
        $viewPath = __DIR__ . "/../Views/{$view}.php";
        if (file_exists($viewPath)) {
            include $viewPath;
        } else {
            throw new \Exception("View '{$view}' not found");
        }
        
        // Get the contents of the buffer and clean it
        return ob_get_clean();
    }
}
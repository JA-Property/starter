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
    
    /**
     * Simple view renderer
     *
     * @param string $view The view name
     * @param array $data Data to pass to the view
     * @return string The rendered view
     */
    private function view(string $view, array $data = []): string
{
    // Extract data to make variables available in the view
    extract($data);
    
    // Start output buffering
    ob_start();
    
    // Include the view file
    $viewPath = __DIR__ . "/../Views/Layouts/{$view}.php";
    if (file_exists($viewPath)) {
        // Get content from the specific view
        ob_start();
        include $viewPath;
        $content = ob_get_clean();
        
        // Include the global layout
        include __DIR__ . "/../Views/Layouts/GlobalLayout.php";
    } else {
        throw new \Exception("View '{$view}' not found");
    }
    
    // Get the contents of the buffer and clean it
    return ob_get_clean();
}
}
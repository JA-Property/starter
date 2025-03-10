<?php
declare(strict_types=1);

namespace App\Core;

class ErrorHandler
{
    public static function handleException(\Throwable $exception): void
    {
        // Log the exception
        self::logError($exception->getMessage(), [
            'exception' => get_class($exception),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString()
        ]);
        
        // Determine if we're in production
        $isProduction = ($_ENV['APP_ENV'] ?? 'production') === 'production';
        
        // Send appropriate response
        http_response_code(500);
        
        if ($isProduction) {
            echo 'An unexpected error occurred. Please try again later.';
        } else {
            echo '<h1>Exception: ' . get_class($exception) . '</h1>';
            echo '<p><strong>Message:</strong> ' . $exception->getMessage() . '</p>';
            echo '<p><strong>File:</strong> ' . $exception->getFile() . ' (line ' . $exception->getLine() . ')</p>';
            echo '<h2>Stack Trace:</h2>';
            echo '<pre>' . $exception->getTraceAsString() . '</pre>';
        }
        
        exit;
    }
    
    public static function handleError(int $level, string $message, string $file, int $line): bool
    {
        if (!(error_reporting() & $level)) {
            // This error code is not included in error_reporting
            return false;
        }
        
        // Log the error
        self::logError($message, [
            'level' => $level,
            'file' => $file,
            'line' => $line
        ]);
        
        // Throw an ErrorException for errors
        throw new \ErrorException($message, 0, $level, $file, $line);
    }
    
    public static function handleFatalError(): void
    {
        $error = error_get_last();
        
        if ($error !== null && in_array($error['type'], [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE])) {
            // Log the fatal error
            self::logError($error['message'], [
                'level' => $error['type'],
                'file' => $error['file'],
                'line' => $error['line']
            ]);
            
            // Determine if we're in production
            $isProduction = ($_ENV['APP_ENV'] ?? 'production') === 'production';
            
            // Send appropriate response
            http_response_code(500);
            
            if ($isProduction) {
                echo 'An unexpected error occurred. Please try again later.';
            } else {
                echo '<h1>Fatal Error</h1>';
                echo '<p><strong>Message:</strong> ' . $error['message'] . '</p>';
                echo '<p><strong>File:</strong> ' . $error['file'] . ' (line ' . $error['line'] . ')</p>';
            }
        }
    }
    
    private static function logError(string $message, array $context = []): void
    {
        // Create logs directory if it doesn't exist
        $logDir = __DIR__ . '/../../logs';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        // Format the log entry
        $timestamp = date('Y-m-d H:i:s');
        $contextStr = !empty($context) ? json_encode($context) : '';
        $logEntry = "[$timestamp] ERROR: $message $contextStr" . PHP_EOL;
        
        // Write to log file
        file_put_contents(
            "$logDir/error.log",
            $logEntry,
            FILE_APPEND
        );
    }
}
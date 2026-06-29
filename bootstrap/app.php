<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Throwable $e) {
            if ((env('VERCEL') || isset($_SERVER['VERCEL'])) && env('APP_DEBUG')) {
                header('HTTP/1.1 500 Internal Server Error');
                header('Content-Type: text/plain');
                echo "Original Exception: " . $e->getMessage() . "\n\nStack Trace:\n" . $e->getTraceAsString();
                exit;
            }
        });
    })->create();

if (env('VERCEL') || isset($_SERVER['VERCEL'])) {
    $storagePath = '/tmp/storage';
    $app->useStoragePath($storagePath);

    // Create necessary subdirectories
    $dirs = [
        $storagePath,
        $storagePath . '/framework',
        $storagePath . '/framework/cache',
        $storagePath . '/framework/views',
        $storagePath . '/framework/sessions',
    ];
    foreach ($dirs as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
    }
}

return $app;

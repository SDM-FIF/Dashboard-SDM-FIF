<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

if (env('VERCEL') || isset($_SERVER['VERCEL'])) {
    $_ENV['APP_CONFIG_CACHE'] = '/tmp/config.php';
    $_ENV['APP_SERVICES_CACHE'] = '/tmp/services.php';
    $_ENV['APP_PACKAGES_CACHE'] = '/tmp/packages.php';
    $_ENV['APP_ROUTES_CACHE'] = '/tmp/routes.php';
    $_ENV['APP_EVENTS_CACHE'] = '/tmp/events.php';

    putenv('APP_CONFIG_CACHE=/tmp/config.php');
    putenv('APP_SERVICES_CACHE=/tmp/services.php');
    putenv('APP_PACKAGES_CACHE=/tmp/packages.php');
    putenv('APP_ROUTES_CACHE=/tmp/routes.php');
    putenv('APP_EVENTS_CACHE=/tmp/events.php');
}

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
            'check.crud' => \App\Http\Middleware\CheckCrudPeriode::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->report(function (\Throwable $e) {
            if ((env('VERCEL') || isset($_SERVER['VERCEL'])) && env('APP_DEBUG')) {
                header('HTTP/1.1 500 Internal Server Error');
                header('Content-Type: text/plain');
                echo "Original Exception (Reported): " . $e->getMessage() . "\n\nStack Trace:\n" . $e->getTraceAsString();
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

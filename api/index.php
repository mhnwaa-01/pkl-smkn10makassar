<?php

// Prepare temporary writable directories in /tmp for Vercel Serverless
$storagePaths = [
    '/tmp/storage/app/public',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/framework/views',
    '/tmp/storage/logs',
    '/tmp/bootstrap/cache',
];

foreach ($storagePaths as $path) {
    if (!is_dir($path)) {
        mkdir($path, 0755, true);
    }
}

// Set environment variables for Vercel
$_ENV['VERCEL'] = '1';
$_SERVER['VERCEL'] = '1';
putenv('VERCEL=1');

$_ENV['APP_CONFIG_CACHE'] = '/tmp/bootstrap/cache/config.php';
$_ENV['APP_EVENTS_CACHE'] = '/tmp/bootstrap/cache/events.php';
$_ENV['APP_PACKAGES_CACHE'] = '/tmp/bootstrap/cache/packages.php';
$_ENV['APP_ROUTES_CACHE'] = '/tmp/bootstrap/cache/routes.php';
$_ENV['APP_SERVICES_CACHE'] = '/tmp/bootstrap/cache/services.php';
$_ENV['VIEW_COMPILED_PATH'] = '/tmp/storage/framework/views';
$_ENV['CACHE_STORE'] = $_ENV['CACHE_STORE'] ?? 'array';
$_ENV['SESSION_DRIVER'] = $_ENV['SESSION_DRIVER'] ?? 'cookie';
$_ENV['LOG_CHANNEL'] = $_ENV['LOG_CHANNEL'] ?? 'stderr';

// Ensure APP_KEY exists so Laravel never throws 500 encryption key exception
if (empty($_ENV['APP_KEY']) && empty(getenv('APP_KEY'))) {
    $defaultKey = 'base64:3w1sP8dJgG8rQ3z4Y6kL9mNxVbC2fH0tE5uI7oP1aRs=';
    $_ENV['APP_KEY'] = $defaultKey;
    $_SERVER['APP_KEY'] = $defaultKey;
    putenv("APP_KEY=$defaultKey");
}

// Forward request to Laravel's public index
require __DIR__ . '/../public/index.php';

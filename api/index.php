<?php

// 1. Prepare temporary writable directories in /tmp for Vercel Serverless
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

// 2. Set environment variables for Vercel
$_ENV['VERCEL'] = '1';
$_SERVER['VERCEL'] = '1';
putenv('VERCEL=1');

$_ENV['APP_CONFIG_CACHE'] = '/tmp/bootstrap/cache/config.php';
$_ENV['APP_EVENTS_CACHE'] = '/tmp/bootstrap/cache/events.php';
$_ENV['APP_PACKAGES_CACHE'] = '/tmp/bootstrap/cache/packages.php';
$_ENV['APP_ROUTES_CACHE'] = '/tmp/bootstrap/cache/routes.php';
$_ENV['APP_SERVICES_CACHE'] = '/tmp/bootstrap/cache/services.php';
$_ENV['VIEW_COMPILED_PATH'] = '/tmp/storage/framework/views';

$defaults = [
    'CACHE_STORE' => 'array',
    'CACHE_DRIVER' => 'array',
    'SESSION_DRIVER' => 'cookie',
    'LOG_CHANNEL' => 'stderr',
    'QUEUE_CONNECTION' => 'sync',
    'MAIL_MAILER' => 'log',
    'DB_CONNECTION' => 'pgsql',
];

foreach ($defaults as $key => $val) {
    if (empty($_ENV[$key]) || trim((string)$_ENV[$key]) === '') {
        $_ENV[$key] = $val;
        $_SERVER[$key] = $val;
        putenv("$key=$val");
    }
}

// 3. Ensure APP_KEY exists so Laravel never throws encryption key exception
if (empty($_ENV['APP_KEY']) || trim((string)$_ENV['APP_KEY']) === '') {
    $defaultKey = 'base64:3w1sP8dJgG8rQ3z4Y6kL9mNxVbC2fH0tE5uI7oP1aRs=';
    $_ENV['APP_KEY'] = $defaultKey;
    $_SERVER['APP_KEY'] = $defaultKey;
    putenv("APP_KEY=$defaultKey");
}

// 4. Forward request to Laravel's public index
require __DIR__ . '/../public/index.php';

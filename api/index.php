<?php

/**
 * Vercel Serverless Entry Point for Laravel
 */

// 1. Prepare writable storage directories in /tmp for Vercel
$storageDirs = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/bootstrap/cache',
    '/tmp/storage/logs',
];

foreach ($storageDirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

// 2. Set environment variables for Serverless filesystem
$_ENV['VIEW_COMPILED_PATH'] = '/tmp/storage/framework/views';
$_ENV['APP_SERVICES_CACHE'] = '/tmp/storage/bootstrap/cache/services.php';
$_ENV['APP_PACKAGES_CACHE'] = '/tmp/storage/bootstrap/cache/packages.php';
$_ENV['APP_CONFIG_CACHE'] = '/tmp/storage/bootstrap/cache/config.php';
$_ENV['APP_ROUTES_CACHE'] = '/tmp/storage/bootstrap/cache/routes.php';
$_ENV['APP_EVENTS_CACHE'] = '/tmp/storage/bootstrap/cache/events.php';

putenv('VIEW_COMPILED_PATH=/tmp/storage/framework/views');
putenv('APP_SERVICES_CACHE=/tmp/storage/bootstrap/cache/services.php');
putenv('APP_PACKAGES_CACHE=/tmp/storage/bootstrap/cache/packages.php');
putenv('APP_CONFIG_CACHE=/tmp/storage/bootstrap/cache/config.php');
putenv('APP_ROUTES_CACHE=/tmp/storage/bootstrap/cache/routes.php');
putenv('APP_EVENTS_CACHE=/tmp/storage/bootstrap/cache/events.php');

// 3. Set default APP_KEY if missing
if (empty($_ENV['APP_KEY']) && empty(getenv('APP_KEY'))) {
    $defaultKey = 'base64:No0MZyhq99Erd3iFQLtGJsNPjO96wPG3DxyAcZ3XBD4=';
    putenv("APP_KEY={$defaultKey}");
    $_ENV['APP_KEY'] = $defaultKey;
    $_SERVER['APP_KEY'] = $defaultKey;
}

// 4. Handle SQLite Database setup in /tmp if DB_CONNECTION is sqlite or not set
$dbConnection = getenv('DB_CONNECTION') ?: ($_ENV['DB_CONNECTION'] ?? 'sqlite');
if ($dbConnection === 'sqlite') {
    $tmpDb = '/tmp/database.sqlite';
    $sourceDb = __DIR__ . '/../database/database.sqlite';

    if (!file_exists($tmpDb) && file_exists($sourceDb)) {
        @copy($sourceDb, $tmpDb);
    }

    if (file_exists($tmpDb)) {
        putenv("DB_DATABASE={$tmpDb}");
        $_ENV['DB_DATABASE'] = $tmpDb;
        $_SERVER['DB_DATABASE'] = $tmpDb;
    }
}

// 5. Forward request to Laravel's public/index.php
require __DIR__ . '/../public/index.php';

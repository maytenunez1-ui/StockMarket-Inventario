<?php

$basePath = dirname(__DIR__);
$autoload = $basePath . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

if (file_exists($autoload)) {
    require $autoload;

    if (class_exists(Dotenv\Dotenv::class) && file_exists($basePath . DIRECTORY_SEPARATOR . '.env')) {
        Dotenv\Dotenv::createImmutable($basePath)->safeLoad();
    }
}

$token = $_GET['token'] ?? '';
$expectedToken = $_ENV['SETUP_TOKEN'] ?? getenv('SETUP_TOKEN') ?: '';

if ($expectedToken === '' || ! hash_equals($expectedToken, $token)) {
    http_response_code(403);
    exit('Forbidden');
}

$artisan = $basePath . DIRECTORY_SEPARATOR . 'artisan';

if (! file_exists($artisan)) {
    http_response_code(500);
    exit('Artisan not found');
}

chdir($basePath);

$commands = [
    escapeshellarg(PHP_BINARY) . ' artisan config:clear',
    escapeshellarg(PHP_BINARY) . ' artisan migrate --force',
    escapeshellarg(PHP_BINARY) . ' artisan db:seed --force',
    escapeshellarg(PHP_BINARY) . ' artisan config:cache',
];

header('Content-Type: text/plain; charset=utf-8');

foreach ($commands as $command) {
    echo '$ ' . $command . PHP_EOL;
    passthru($command . ' 2>&1', $code);
    echo PHP_EOL;

    if ($code !== 0) {
        http_response_code(500);
        exit('Command failed with code ' . $code);
    }
}

echo 'Cloud setup completed. Delete public/cloud-setup.php after running this once.' . PHP_EOL;

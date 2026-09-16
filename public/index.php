<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../core/Bootstrap.php';

if (file_exists(__DIR__ . '/../core/Lang.php')) {
    require_once __DIR__ . '/../core/Lang.php';
}

$modules = glob(__DIR__ . '/../modules/*/routes.php');
foreach ($modules as $routeFile) {
    require_once $routeFile;
}

$uri = $_GET['url'] ?? '';
$uri = trim($uri, '/');

if ($uri === '' || $uri === 'public') {
    $uri = 'giris';
}

Router::calistir($_SERVER['REQUEST_METHOD'], $uri);

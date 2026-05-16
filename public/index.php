<?php

define('APP_START', microtime(true));

require_once dirname(__DIR__) . '/config/config.php';

$router = new Core\Router();

require_once dirname(__DIR__) . '/routes.php';

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$uri    = $_SERVER['REQUEST_URI']    ?? '/';

$router->dispatch($method, $uri);

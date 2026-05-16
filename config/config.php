<?php

$envFile = dirname(__DIR__) . '/.env';
if (!file_exists($envFile)) {
    die('Fichier .env manquant. Copiez .env.example en .env et renseignez les valeurs.');
}

$env = parse_ini_file($envFile);
if ($env === false) {
    die('Impossible de lire le fichier .env. Vérifiez sa syntaxe.');
}

foreach ($env as $key => $value) {
    if (!defined($key)) {
        define($key, $value);
    }
}

// Chemins internes (jamais accessibles via URL)
define('ROOT_PATH',   dirname(__DIR__));
define('APP_PATH',    ROOT_PATH . '/app');
define('PUBLIC_PATH', ROOT_PATH . '/public');

// Erreurs PHP selon l'environnement
if (defined('APP_ENV') && APP_ENV === 'production') {
    ini_set('display_errors', '0');
    ini_set('log_errors',     '1');
    $logDir = ROOT_PATH . '/logs';
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    ini_set('error_log', $logDir . '/app.log');
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
}

// Session sécurisée
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'secure'   => defined('APP_ENV') && APP_ENV === 'production',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

// Autoloader PSR-4 simplifié (namespace → app/)
spl_autoload_register(function (string $class): void {
    $file = APP_PATH . '/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Helpers globaux (fonctions libres)
require_once APP_PATH . '/Core/Helpers.php';

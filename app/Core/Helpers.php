<?php

// ─── Échappement HTML ───────────────────────────────────────────────────────

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

// ─── Composants ────────────────────────────────────────────────────────────

function component(string $name, array $props = []): void
{
    $path = APP_PATH . '/Views/components/' . $name . '.php';
    extract($props);
    include $path;
}

// ─── URLs ───────────────────────────────────────────────────────────────────

function asset(string $path): string
{
    return rtrim(APP_URL, '/') . '/assets/' . ltrim($path, '/');
}

function url(string $path = ''): string
{
    return rtrim(APP_URL, '/') . '/' . ltrim($path, '/');
}

// ─── CSRF ───────────────────────────────────────────────────────────────────

function csrf_token(): string
{
    if (empty($_SESSION['_csrf_token'])) {
        $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

function csrf_verify(): void
{
    $token = $_POST['csrf_token'] ?? '';
    if (!hash_equals(csrf_token(), $token)) {
        http_response_code(403);
        die('Token CSRF invalide.');
    }
}

// ─── Navigation active ──────────────────────────────────────────────────────

function active(string $path): string
{
    $current  = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?? '';
    $basePath = parse_url(APP_URL, PHP_URL_PATH) ?? '';
    if ($basePath && strpos($current, $basePath) === 0) {
        $current = substr($current, strlen($basePath));
    }
    $current = '/' . ltrim($current, '/');

    if ($path === '/') {
        return $current === '/' ? 'active' : '';
    }
    return strpos($current, $path) === 0 ? 'active' : '';
}

// ─── Formatage ──────────────────────────────────────────────────────────────

function format_date(string $date, string $format = 'd/m/Y'): string
{
    if ($date === '') return '';
    $ts = strtotime($date);
    return $ts ? date($format, $ts) : '';
}

function format_money(float $amount, string $currency = '€'): string
{
    return number_format($amount, 2, ',', ' ') . ' ' . $currency;
}

function truncate(string $text, int $length = 100): string
{
    if (mb_strlen($text) <= $length) return $text;
    return mb_substr($text, 0, $length) . '…';
}

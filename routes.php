<?php

/** @var \Core\Router $router */

// ─── Auth ────────────────────────────────────────────
$router->get('/login',           'AuthController', 'loginForm');
$router->post('/login',          'AuthController', 'login');
$router->get('/logout',          'AuthController', 'logout');
$router->get('/register',        'AuthController', 'registerForm');
$router->post('/register',       'AuthController', 'register');
$router->get('/forgot-password', 'AuthController', 'forgotForm');
$router->post('/forgot-password','AuthController', 'forgot');
$router->get('/reset-password',  'AuthController', 'resetForm');
$router->post('/reset-password', 'AuthController', 'reset');

// ─── Dashboard ───────────────────────────────────────
$router->get('/dashboard', 'DashboardController', 'index', ['requireAuth']);
$router->get('/',          'DashboardController', 'index', ['requireAuth']);

// ─── Utilisateurs ────────────────────────────────────
$router->get('/users',                   'UserController', 'index',   ['requireAuth', 'requireAdmin']);
$router->get('/users/create',            'UserController', 'create',  ['requireAuth', 'requireAdmin']);
$router->post('/users/store',            'UserController', 'store',   ['requireAuth', 'requireAdmin']);
$router->get('/users/edit/{id}',         'UserController', 'edit',    ['requireAuth', 'requireAdmin']);
$router->post('/users/update/{id}',      'UserController', 'update',  ['requireAuth', 'requireAdmin']);
$router->post('/users/delete/{id}',      'UserController', 'destroy', ['requireAuth', 'requireAdmin']);

// ─── Démo (pas d'auth requise) ───────────────────────
$router->get('/demo',                    'DemoController', 'index');
$router->get('/demo/ui-kit',             'DemoController', 'uiKit');
$router->get('/demo/dashboard',          'DemoController', 'dashboard');
$router->get('/demo/users',              'DemoController', 'users');
$router->get('/demo/user-detail',        'DemoController', 'userDetail');
$router->get('/demo/subscriptions',      'DemoController', 'subscriptions');
$router->get('/demo/billing',            'DemoController', 'billing');
$router->get('/demo/analytics',          'DemoController', 'analytics');
$router->get('/demo/logs',               'DemoController', 'logs');
$router->get('/demo/settings',           'DemoController', 'settings');
$router->get('/demo/notifications',      'DemoController', 'notifications');
$router->get('/demo/support',            'DemoController', 'support');

<?php

namespace Core;

class Router
{
    private array $routes = [];

    public function get(string $path, string $controller, string $action, array $middleware = []): void
    {
        $this->add('GET', $path, $controller, $action, $middleware);
    }

    public function post(string $path, string $controller, string $action, array $middleware = []): void
    {
        $this->add('POST', $path, $controller, $action, $middleware);
    }

    public function put(string $path, string $controller, string $action, array $middleware = []): void
    {
        $this->add('PUT', $path, $controller, $action, $middleware);
    }

    public function delete(string $path, string $controller, string $action, array $middleware = []): void
    {
        $this->add('DELETE', $path, $controller, $action, $middleware);
    }

    private function add(string $method, string $path, string $controller, string $action, array $middleware): void
    {
        $this->routes[] = compact('method', 'path', 'controller', 'action', 'middleware');
    }

    public function dispatch(string $method, string $uri): void
    {
        $uri = parse_url($uri, PHP_URL_PATH) ?? '/';

        // Supprimer le préfixe de chemin si APP_URL contient un sous-dossier
        $basePath = parse_url(APP_URL, PHP_URL_PATH) ?? '';
        if ($basePath !== '' && strpos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath));
        }
        $uri = '/' . ltrim($uri, '/');
        if ($uri !== '/' && substr($uri, -1) === '/') {
            $uri = rtrim($uri, '/');
        }

        // Support POST → PUT/DELETE via _method
        if ($method === 'POST' && isset($_POST['_method'])) {
            $override = strtoupper($_POST['_method']);
            if (in_array($override, ['PUT', 'DELETE'], true)) {
                $method = $override;
            }
        }

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            $pattern = $this->toRegex($route['path']);
            if (!preg_match($pattern, $uri, $matches)) {
                continue;
            }

            array_shift($matches);
            $params = array_values($matches);

            $class = 'Controllers\\' . $route['controller'];
            /** @var \Core\Controller $ctrl */
            $ctrl = new $class();

            foreach ($route['middleware'] as $mw) {
                $ctrl->$mw();
            }

            $ctrl->{$route['action']}(...$params);
            return;
        }

        $this->notFound();
    }

    private function toRegex(string $path): string
    {
        $pattern = preg_replace('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', '([^/]+)', $path);
        return '#^' . $pattern . '$#';
    }

    private function notFound(): void
    {
        http_response_code(404);
        $view = APP_PATH . '/Views/errors/404.php';
        if (file_exists($view)) {
            include $view;
        } else {
            echo '<h1>404 — Page non trouvée</h1>';
        }
        exit;
    }
}

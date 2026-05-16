<?php

namespace Core;

abstract class Controller
{
    /**
     * Rend une vue dans un layout.
     * Le contenu de la vue est capturé puis injecté dans $content du layout.
     */
    protected function render(string $view, array $data = [], string $layout = 'admin'): void
    {
        extract($data);

        $viewPath = APP_PATH . '/Views/' . str_replace('.', '/', $view) . '.php';
        ob_start();
        include $viewPath;
        $content = ob_get_clean();

        $layoutPath = APP_PATH . '/Views/layouts/' . $layout . '.php';
        include $layoutPath;
    }

    protected function redirect(string $path): void
    {
        header('Location: ' . url($path));
        exit;
    }

    protected function json(array $data, int $code = 200): void
    {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    // ─── Middleware ─────────────────────────────────────────────────────────

    public function requireAuth(): void
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('/login');
        }
    }

    public function requireAdmin(): void
    {
        $this->requireAuth();
        if (($_SESSION['user_role'] ?? '') !== 'admin') {
            $this->redirect('/dashboard');
        }
    }

    // ─── Flash messages ─────────────────────────────────────────────────────

    protected function flash(string $type, string $message): void
    {
        $_SESSION['_flash'] = ['type' => $type, 'message' => $message];
    }

    protected function getFlash(): ?array
    {
        if (isset($_SESSION['_flash'])) {
            $flash = $_SESSION['_flash'];
            unset($_SESSION['_flash']);
            return $flash;
        }
        return null;
    }
}

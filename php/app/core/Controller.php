<?php
namespace App\Core;

abstract class Controller {
    protected function startSession(): void {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    }

    protected function requireLogin(): void {
        $this->startSession();
        if (empty($_SESSION['uid'])) {
            header('Location: /?r=login'); exit;
        }
    }

    protected function render(string $view, array $params = []): void {
        extract($params, EXTR_SKIP);
        $appName = Env::get('APP_NAME', 'Logs');
        ob_start();
        require dirname(__DIR__) . '/views/' . $view . '.php';
        $content = ob_get_clean();
        require dirname(__DIR__) . '/views/layout.php';
    }

    protected function redirect(string $route): void {
        header('Location: ' . $route); exit;
    }
}

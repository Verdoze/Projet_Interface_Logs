<?php
namespace App\Core;

final class Router {
    public static function dispatch(): void {
        // routes via query string: /?r=login | /?r=logout | /?r=logs | /?r=export
        $r = $_GET['r'] ?? 'login';

        switch ($r) {
            case 'login':
                (new \App\Controllers\AuthController())->login();
                break;
            case 'logout':
                (new \App\Controllers\AuthController())->logout();
                break;
            case 'logs':
                (new \App\Controllers\LogsController())->index();
                break;
            case 'export':
                (new \App\Controllers\LogsController())->export();
                break;
            default:
                http_response_code(404);
                echo '404 Not Found';
        }
    }
}

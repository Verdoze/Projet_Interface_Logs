<?php
namespace App\Controllers;
use App\Core\Controller;

final class AuthController extends Controller {
    public function login(): void {
        $this->startSession();
        $err = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = $_POST['username'] ?? '';
            $pass = $_POST['password'] ?? '';
            // à modifier pour mettre un user de la BDD
            if ($user === 'admin' && $pass === '1234') {
                $_SESSION['uid'] = 1;
                $_SESSION['username'] = $user;
                $this->redirect('/?r=logs');
            } else {
                $err = "Identifiants invalides";
            }
        }

        $this->render('auth/login', ['err' => $err]);
    }

    public function logout(): void {
        $this->startSession();
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $p["path"], $p["domain"], $p["secure"], $p["httponly"]);
        }
        session_destroy();
        $this->redirect('/?r=login');
    }
}

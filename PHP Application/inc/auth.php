<?php // /var/www/log/inc/auth.php
session_start();

function app_name(){ return env('APP_NAME','Logs'); }
function is_logged_in(): bool { return !empty($_SESSION['uid']); }

function require_login(): void{
  if (!is_logged_in()){
    header('Location: /login.php'); exit;
  }
}

function login($login, $password): bool {
    $st = pdo()->prepare("SELECT id, password FROM utilisateurs WHERE login=?");
    $st->execute([$login]);
    $u = $st->fetch();
    // On hash le mot de passe fourni avec SHA-256 et on compare avec celui de la base
    if ($u && hash('sha256', $password) === $u['password']) {
        $_SESSION['uid']   = (int)$u['id'];
        $_SESSION['login'] = $login;
        return true;
    }
    return false;
}

function logout(){
  $_SESSION = [];
  if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time()-42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
  }
  session_destroy();
}
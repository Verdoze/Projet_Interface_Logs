<?php
require __DIR__.'/../inc/db.php';
require __DIR__.'/../inc/auth.php';

$err = '';
if ($_SERVER['REQUEST_METHOD']==='POST'){
  $ok = login($_POST['email'] ?? '', $_POST['password'] ?? '');
  if ($ok){ header('Location: /logs.php'); exit; }
  $err = "Identifiants invalides";
}
?>
<!doctype html><meta charset="utf-8">
<title><?= htmlspecialchars(app_name()) ?> — Connexion</title>
<style>
body{font-family:system-ui,Arial;margin:0;background:#0b0c10;color:#eee}
.container{max-width:420px;margin:8vh auto;background:#111;padding:24px;border-radius:12px;box-shadow:0 8px 24px rgba(0,0,0,.4)}
h1{margin:0 0 16px}
label{display:block;margin:12px 0 6px}
input{width:100%;padding:10px;border-radius:8px;border:1px solid #333;background:#0e0f14;color:#eee}
button{margin-top:16px;padding:10px 14px;border:0;border-radius:8px;background:#2d6cdf;color:#fff;cursor:pointer}
.error{color:#ff6b6b;margin-top:10px}
</style>
<div class="container">
  <h1><?= htmlspecialchars(app_name()) ?></h1>
  <form method="post">
    <label>Email</label>
    <input name="email" type="text" required>
    <label>Mot de passe</label>
    <input name="password" type="password" required>
    <button>Se connecter</button>
    <?php if($err): ?><div class="error"><?= htmlspecialchars($err) ?></div><?php endif; ?>
  </form>
</div>
<?php /* $err disponible */ ?>
<div class="container">
  <h1>Connexion</h1>
  <form method="post" action="/?r=login">
    <label>Nom d'utilisateur</label>
    <input class="input" name="username" type="text" required>
    <label>Mot de passe</label>
    <input class="input" name="password" type="password" required>
    <button class="btn btn-full" type="submit">Se connecter</button>
    <?php if(!empty($err)): ?>
      <div style="color:#ff6b6b;margin-top:10px"><?= htmlspecialchars($err) ?></div>
    <?php endif; ?>
  </form>
</div>

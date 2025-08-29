<?php
// $filters, $rows, $apps, $hosts, $levels dispo
?>
<div class="topbar">
  <h1>Journaux</h1>
  <div class="actions">
    <a class="btn" href="/?r=export&<?= http_build_query($filters) ?>">Exporter CSV</a>
    <a class="btn btn-danger" href="/?r=logout">Déconnexion</a>
  </div>
</div>

<div class="panel">
  <form class="filters" method="get" action="/">
    <input type="hidden" name="r" value="logs">
    <label>Depuis
      <input name="from" value="<?= htmlspecialchars($filters['from']) ?>" placeholder="2025-08-28 00:00:00">
    </label>
    <label>Jusqu'à
      <input name="to" value="<?= htmlspecialchars($filters['to']) ?>" placeholder="2025-08-28 23:59:59">
    </label>
    <label>Application
      <select name="app">
        <option value="">(toutes)</option>
        <?php foreach($apps as $opt): ?>
          <option value="<?= htmlspecialchars($opt) ?>" <?= $filters['app']===$opt?'selected':'' ?>><?= htmlspecialchars($opt) ?></option>
        <?php endforeach; ?>
      </select>
    </label>
    <label>Hôte
      <select name="host">
        <option value="">(tous)</option>
        <?php foreach($hosts as $opt): ?>
          <option value="<?= htmlspecialchars($opt) ?>" <?= $filters['host']===$opt?'selected':'' ?>><?= htmlspecialchars($opt) ?></option>
        <?php endforeach; ?>
      </select>
    </label>
    <label>Niveau
      <select name="level">
        <option value="">(tous)</option>
        <?php foreach($levels as $lvl): ?>
          <option value="<?= $lvl ?>" <?= $filters['level']===$lvl?'selected':'' ?>><?= $lvl ?></option>
        <?php endforeach; ?>
        <option disabled>──────────</option>
        <?php for($i=0;$i<=7;$i++): ?>
          <option value="<?= $i ?>" <?= $filters['level']===(string)$i?'selected':'' ?>>prio <?= $i ?></option>
        <?php endfor; ?>
      </select>
    </label>
    <label>Texte
      <input name="text" value="<?= htmlspecialchars($filters['text']) ?>">
    </label>
    <button class="btn" type="submit">Rechercher</button>
    <a class="btn" href="/?r=logs">Réinitialiser</a>
  </form>
</div>

<table>
  <thead>
    <tr>
      <th>Date</th><th>Application</th><th>Niveau</th><th>Hôte</th><th>Message</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach($rows as $r): ?>
    <tr>
      <td><?= htmlspecialchars($r['ReceivedAt']) ?></td>
      <td><?= htmlspecialchars($r['SysLogTag']) ?></td>
      <td><?php
        $map = \App\Models\Log::$prioToText;
        echo htmlspecialchars($map[$r['Priority']] ?? (string)$r['Priority']);
      ?></td>
      <td><?= htmlspecialchars($r['FromHost']) ?></td>
      <td><?= htmlspecialchars($r['Message']) ?></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>

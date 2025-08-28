<?php
// --- Session & protection d'accès ---
session_start();
if (empty($_SESSION['uid'])) { header('Location: /login.php'); exit; }

// --- Connexion DB (PDO) ---
require __DIR__ . '/../inc/db.php';   // nécessaire pour pdo()

// ----- Filtres -----
$from  = $_GET['from']  ?? '';
$to    = $_GET['to']    ?? '';
$text  = $_GET['text']  ?? '';
$app   = $_GET['app']   ?? '';
$host  = $_GET['host']  ?? '';
$level = $_GET['level'] ?? '';

$where = [];
$bind  = [];

// Dates
if ($from !== '') { $where[] = 'ReceivedAt >= ?'; $bind[] = $from; }
if ($to   !== '') { $where[] = 'ReceivedAt <= ?'; $bind[] = $to; }

// Texte
if ($text !== '') { $where[] = 'Message LIKE ?'; $bind[] = '%'.$text.'%'; }

// App / Host
if ($app  !== '') { $where[] = 'SysLogTag = ?'; $bind[] = $app; }
if ($host !== '') { $where[] = 'FromHost   = ?'; $bind[] = $host; }

// Mapping niveaux
$prioToText = [
  0=>'EMERGENCY',1=>'ALERT',2=>'CRITICAL',3=>'ERROR',
  4=>'WARNING',5=>'NOTICE',6=>'INFO',7=>'DEBUG'
];
function array_change_key_array(array $a, callable $f){ $o=[]; foreach($a as $k=>$v){ $o[$f($k)]=$v; } return $o; }
$textToPrio = array_change_key_array(array_flip($prioToText), 'strtoupper');

// Filtre niveau
if ($level !== '') {
  if (is_numeric($level)) {
    $where[] = 'Priority = ?'; $bind[] = (int)$level;
  } else {
    $u = strtoupper($level);
    if (isset($textToPrio[$u])) { $where[]='Priority = ?'; $bind[]=$textToPrio[$u]; }
  }
}

// ----- Requête principale -----
$sql = "SELECT ID, ReceivedAt, SysLogTag, Priority, FromHost, Message
        FROM SystemEvents ".
       ( $where ? "WHERE ".implode(' AND ', $where) : "" ).
       " ORDER BY ReceivedAt DESC
         LIMIT 200";

$st = pdo()->prepare($sql);
$st->execute($bind);
$rows = $st->fetchAll(PDO::FETCH_ASSOC);

// Listes pour les filtres
$apps  = pdo()->query("SELECT DISTINCT SysLogTag FROM SystemEvents WHERE SysLogTag<>'' ORDER BY SysLogTag")->fetchAll(PDO::FETCH_COLUMN);
$hosts = pdo()->query("SELECT DISTINCT FromHost   FROM SystemEvents ORDER BY FromHost")->fetchAll(PDO::FETCH_COLUMN);
$levels = array_values($prioToText);
?>
<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Journaux</title>
<style>
  :root{--line:#e5e5e5;--btn:#2d6cdf;--danger:#c0392b}
  body{font-family:system-ui,Arial;margin:0}
  .topbar{
    position:sticky; top:0; z-index:10;
    display:flex; align-items:center; justify-content:space-between;
    padding:12px 16px; background:#fff; border-bottom:2px solid var(--line);
  }
  .topbar h1{margin:0; font-size:28px}
  .actions{display:flex; gap:8px}
  .btn{display:inline-block; padding:8px 12px; border-radius:8px; text-decoration:none; color:#fff; background:var(--btn)}
  .btn:hover{filter:brightness(.95)}
  .btn-danger{background:var(--danger)}
  .panel{background:#fafafa; border:1px solid var(--line); border-radius:8px; padding:10px; margin:12px 16px}
  .filters{display:flex; gap:8px; flex-wrap:wrap; align-items:center}
  .filters input,.filters select{padding:6px 8px}
  table{width:calc(100% - 32px); margin:12px 16px 24px; border-collapse:collapse}
  th,td{border-bottom:1px solid var(--line); padding:8px; vertical-align:top}
  th{background:#f7f7f7}
</style>
</head>
<body>

<!-- Topbar avec bouton Export et Déconnexion -->
<div class="topbar">
  <h1>Journaux</h1>
  <div class="actions">
    <!-- Bouton export CSV -->
    <a class="btn" href="/export.php?<?= http_build_query($_GET) ?>">Exporter CSV</a>
    <!-- Bouton logout -->
    <a class="btn btn-danger" href="/logout.php">Déconnexion</a>
  </div>
</div>

<!-- Filtres -->
<div class="panel">
  <form class="filters" method="get" action="/logs.php">
    <label>Depuis
      <input name="from" value="<?= htmlspecialchars($from) ?>" placeholder="2025-08-28 00:00:00">
    </label>
    <label>Jusqu'à
      <input name="to" value="<?= htmlspecialchars($to) ?>" placeholder="2025-08-28 23:59:59">
    </label>
    <label>Application
      <select name="app">
        <option value="">(toutes)</option>
        <?php foreach($apps as $opt): ?>
          <option value="<?= htmlspecialchars($opt) ?>" <?= $app===$opt?'selected':'' ?>><?= htmlspecialchars($opt) ?></option>
        <?php endforeach; ?>
      </select>
    </label>
    <label>Hôte
      <select name="host">
        <option value="">(tous)</option>
        <?php foreach($hosts as $opt): ?>
          <option value="<?= htmlspecialchars($opt) ?>" <?= $host===$opt?'selected':'' ?>><?= htmlspecialchars($opt) ?></option>
        <?php endforeach; ?>
      </select>
    </label>
    <label>Niveau
      <select name="level">
        <option value="">(tous)</option>
        <?php foreach($levels as $lvl): ?>
          <option value="<?= $lvl ?>" <?= $level===$lvl?'selected':'' ?>><?= $lvl ?></option>
        <?php endforeach; ?>
        <option disabled>──────────</option>
        <?php for($i=0;$i<=7;$i++): ?>
          <option value="<?= $i ?>" <?= $level===(string)$i?'selected':'' ?>>prio <?= $i ?></option>
        <?php endfor; ?>
      </select>
    </label>
    <label>Texte
      <input name="text" value="<?= htmlspecialchars($text) ?>">
    </label>

    <button class="btn" type="submit">Rechercher</button>
    <a class="btn" href="/logs.php">Réinitialiser</a>
  </form>
</div>

<!-- Table -->
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
      <td><?= htmlspecialchars($prioToText[$r['Priority']] ?? (string)$r['Priority']) ?></td>
      <td><?= htmlspecialchars($r['FromHost']) ?></td>
      <td><?= htmlspecialchars($r['Message']) ?></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>

</body>
</html>
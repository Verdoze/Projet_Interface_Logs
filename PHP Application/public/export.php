<?php
// --- Auth obligatoire ---
session_start();
if (empty($_SESSION['uid'])) { header('Location: /login.php'); exit; }

// --- Connexion DB ---
require __DIR__ . '/../inc/db.php';

// (Debug minimal côté serveur ; retire display_errors en prod)
ini_set('log_errors', 1);
ini_set('error_log', '/var/log/php-errors.log');

// ----- Récup filtres (identiques à logs.php) -----
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

// ----- Exécution & Export -----
try {
  // Important : élargis la limite (plus que l’écran)
  $sql = "SELECT ID, ReceivedAt, SysLogTag, Priority, FromHost, Message
          FROM SystemEvents ".
         ( $where ? "WHERE ".implode(' AND ', $where) : "" ).
         " ORDER BY ReceivedAt DESC
           LIMIT 5000";

  $st = pdo()->prepare($sql);
  $st->execute($bind);

  // Démarre une sortie propre (évite tout écho avant headers)
  if (ob_get_level() > 0) { ob_end_clean(); }
  ob_start();

  $filename = 'logs_'.date('Ymd_His').'.csv';
  header('Content-Type: text/csv; charset=utf-8');
  header('Content-Disposition: attachment; filename="'.$filename.'"');
  // BOM UTF-8 pour Excel
  echo "\xEF\xBB\xBF";

  $out = fopen('php://output', 'w');
  fputcsv($out, ['ID','Date','Application','Niveau','Hôte','Message']);

  while ($r = $st->fetch(PDO::FETCH_ASSOC)) {
    $lvl = $prioToText[$r['Priority']] ?? (string)$r['Priority'];
    fputcsv($out, [
      $r['ID'],
      $r['ReceivedAt'],
      $r['SysLogTag'],
      $lvl,
      $r['FromHost'],
      $r['Message'],
    ]);
  }
  fclose($out);
  ob_flush();
  exit;

} catch (Throwable $e) {
  // Log serveur + réponse propre
  error_log('[export.php] '.$e->getMessage());
  http_response_code(500);
  header('Content-Type: text/plain; charset=utf-8');
  echo "Erreur lors de l'export CSV.\n";
  // Optionnel: afficher le détail en dev uniquement
  // echo $e->getMessage();
  exit;
}

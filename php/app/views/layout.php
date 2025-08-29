<?php /* $content vient du buffer; $appName dispo */ ?>
<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title><?= htmlspecialchars($appName) ?></title>
<style>
  :root{--line:#e5e5e5;--btn:#2d6cdf;--danger:#c0392b}
  body{font-family:system-ui,Arial;margin:0}
  .topbar{position:sticky;top:0;z-index:10;display:flex;align-items:center;justify-content:space-between;
          padding:12px 16px;background:#fff;border-bottom:2px solid var(--line)}
  .topbar h1{margin:0;font-size:28px}
  .actions{display:flex;gap:8px}
  .btn{display:inline-block;padding:8px 12px;border-radius:8px;text-decoration:none;color:#fff;background:var(--btn)}
  .btn:hover{filter:brightness(.95)}
  .btn-danger{background:var(--danger)}
  .panel{background:#fafafa;border:1px solid var(--line);border-radius:8px;padding:10px;margin:12px 16px}
  .filters{display:flex;gap:8px;flex-wrap:wrap;align-items:center}
  .filters input,.filters select{padding:6px 8px}
  table{width:calc(100% - 32px);margin:12px 16px 24px;border-collapse:collapse}
  th,td{border-bottom:1px solid var(--line);padding:8px;vertical-align:top}
  th{background:#f7f7f7}
  .container{max-width:420px;margin:10vh auto;background:#111;color:#eee;padding:24px;border-radius:10px}
  .input{width:100%;padding:10px;margin:6px 0 12px;background:#1b1b1b;color:#eee;border:1px solid #333;border-radius:8px}
  .btn-full{width:100%}
</style>
</head>
<body>
<?= $content ?>
</body>
</html>

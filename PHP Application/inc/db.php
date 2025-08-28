<?php // /var/www/log/inc/db.php
function env($k,$def=null){
  static $cfg=null;
  if($cfg===null){
    $cfg=[];
    foreach (file(__DIR__.'/../config/.env', FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES) as $l){
      if ($l === '' || $l[0]==='#') continue;
      [$k1,$v1]=array_map('trim', explode('=',$l,2));
      $cfg[$k1]=trim($v1,'"\'');
    }
  }
  return $cfg[$k] ?? $def;
}

function pdo(): PDO {
  static $pdo=null;
  if(!$pdo){
    $pdo = new PDO(env('DB_DSN'), env('DB_USER'), env('DB_PASS'), [
      PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,
    ]);
  }
  return $pdo;
}
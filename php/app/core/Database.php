<?php
namespace App\Core;
use PDO;

final class Database {
    private static ?PDO $pdo = null;

    public static function pdo(): PDO {
        if (!self::$pdo) {
            $dsn  = Env::get('DB_DSN');
            $user = Env::get('DB_USER');
            $pass = Env::get('DB_PASS');
            self::$pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        }
        return self::$pdo;
    }
}

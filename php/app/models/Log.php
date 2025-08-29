<?php
namespace App\Models;
use App\Core\Database;
use PDO;

final class Log {
    // mapping niveaux
    public static array $prioToText = [
        0=>'EMERGENCY',1=>'ALERT',2=>'CRITICAL',3=>'ERROR',
        4=>'WARNING',5=>'NOTICE',6=>'INFO',7=>'DEBUG'
    ];

    public static function list(array $filters, int $limit = 200): array {
        $pdo = Database::pdo();
        $where = []; $bind = [];

        if (!empty($filters['from'])) { $where[] = 'ReceivedAt >= ?'; $bind[] = $filters['from']; }
        if (!empty($filters['to']))   { $where[] = 'ReceivedAt <= ?'; $bind[] = $filters['to']; }
        if (!empty($filters['text'])) { $where[] = 'Message LIKE ?'; $bind[] = '%'.$filters['text'].'%'; }
        if (!empty($filters['app']))  { $where[] = 'SysLogTag = ?';   $bind[] = $filters['app']; }
        if (!empty($filters['host'])) { $where[] = 'FromHost = ?';    $bind[] = $filters['host']; }

        if (!empty($filters['level'])) {
            $lvl = $filters['level'];
            $textToPrio = array_change_key_case(array_flip(self::$prioToText), CASE_UPPER);
            if (is_numeric($lvl)) {
                $where[] = 'Priority = ?'; $bind[] = (int)$lvl;
            } else {
                $u = strtoupper($lvl);
                if (isset($textToPrio[$u])) { $where[] = 'Priority = ?'; $bind[] = $textToPrio[$u]; }
            }
        }

        $sql = "SELECT ID, ReceivedAt, SysLogTag, Priority, FromHost, Message
                FROM SystemEvents ".
               ($where ? "WHERE ".implode(' AND ', $where) : "").
               " ORDER BY ReceivedAt DESC
                 LIMIT ".(int)$limit;

        $st = $pdo->prepare($sql);
        $st->execute($bind);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function distinctApps(): array {
        $sql = "SELECT DISTINCT SysLogTag FROM SystemEvents WHERE SysLogTag<>'' ORDER BY SysLogTag";
        return Database::pdo()->query($sql)->fetchAll(PDO::FETCH_COLUMN);
    }

    public static function distinctHosts(): array {
        $sql = "SELECT DISTINCT FromHost FROM SystemEvents ORDER BY FromHost";
        return Database::pdo()->query($sql)->fetchAll(PDO::FETCH_COLUMN);
    }
}

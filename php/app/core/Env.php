<?php
namespace App\Core;

final class Env {
    private static ?array $cfg = null;

    public static function get(string $key, $default = null) {
        if (self::$cfg === null) {
            self::$cfg = [];
            $path = dirname(__DIR__, 2) . '/config/.env';
            foreach (@file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [] as $line) {
                if ($line === '' || $line[0] === '#') continue;
                [$k, $v] = array_map('trim', explode('=', $line, 2));
                self::$cfg[$k] = trim($v, "\"'");
            }
        }
        return self::$cfg[$key] ?? $default;
    }
}

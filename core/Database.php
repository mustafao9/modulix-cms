<?php
class Database {
    private static $instance = null;

    public static function baglan($customConfig = null) {
        if ($customConfig !== null) {
            return self::yeniBaglanti($customConfig);
        }

        if (self::$instance === null) {
            Env::yukle();
            $host = Env::al('DB_HOST', 'localhost');
            $port = Env::al('DB_PORT', '3306');
            $db_name = Env::al('DB_NAME', 'modulix_cms');
            $username = Env::al('DB_USER', 'root');
            $password = Env::al('DB_PASS', '');

            self::$instance = self::yeniBaglanti([
                'host' => $host,
                'port' => $port,
                'dbname' => $db_name,
                'user' => $username,
                'pass' => $password
            ]);
        }
        return self::$instance;
    }

    public static function yeniBaglanti($c) {
        try {
            $dsn = "mysql:host={$c['host']};port={$c['port']}";
            if (!empty($c['dbname'])) {
                $dsn .= ";dbname={$c['dbname']}";
            }
            $dsn .= ";charset=utf8mb4";

            return new PDO(
                $dsn,
                $c['user'],
                $c['pass'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => true,
                    PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
                ]
            );
        } catch (PDOException $e) {
            throw new Exception("Veritabanı Bağlantı Hatası: " . $e->getMessage());
        }
    }
}

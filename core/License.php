<?php
class License {
    public static function kontrolEt() {
        $host = $_SERVER['HTTP_HOST'] ?? '';
        if (strpos($host, 'localhost') !== false || strpos($host, '127.0.0.1') !== false) return true;
        $keyFile = __DIR__ . '/license.key';
        if (file_exists($keyFile)) {
            $token = trim(file_get_contents($keyFile));
            if (!empty($token) && strlen($token) >= 16) return true;
        }
        return false;
    }
}

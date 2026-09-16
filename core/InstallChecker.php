<?php
class InstallChecker {
    public static function kontrolEt() {
        $lockFile = __DIR__ . '/installed.lock';
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        if (!file_exists($lockFile) && strpos($uri, '/install') === false) {
            $baseUrl = Env::al('APP_URL', rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'));
            header("Location: " . rtrim($baseUrl, '/') . '/install');
            exit;
        }
    }
}

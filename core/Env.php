<?php
class Env {
    private static $path = __DIR__ . '/../.env';

    public static function yukle() {
        if (!file_exists(self::$path)) return;
        $satirlar = file(self::$path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($satirlar as $satir) {
            if (strpos(trim($satir), '#') === 0) continue;
            list($anahtar, $deger) = explode('=', $satir, 2);
            $anahtar = trim($anahtar);
            $deger = trim($deger);
            $_ENV[$anahtar] = $deger;
            putenv("{$anahtar}={$deger}");
        }
    }

    public static function al($anahtar, $varsayilan = null) {
        return $_ENV[$anahtar] ?? getenv($anahtar) ?: $varsayilan;
    }

    public static function yaz($veriler = []) {
        $icerik = "# Modulix-CMS Environment Configuration\n";
        $icerik .= "# Oluşturulma Tarihi: " . date('Y-m-d H:i:s') . "\n\n";
        foreach ($veriler as $k => $v) {
            $icerik .= "{$k}={$v}\n";
        }
        return file_put_contents(self::$path, $icerik) !== false;
    }
}

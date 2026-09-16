<?php
class Lang {
    private static $ceviriler = [];
    private static $aktifDil = 'tr';

    public static function baslat() {
        Oturum::baslat();
        $dil = Oturum::al('lang') ?? $_SESSION['install_lang'] ?? 'tr';
        if (in_array($dil, ['tr', 'en'])) {
            self::$aktifDil = $dil;
        }
        $dosya = __DIR__ . '/lang/' . self::$aktifDil . '.php';
        if (file_exists($dosya)) {
            self::$ceviriler = require $dosya;
        }
    }

    public static function dilDegistir($dil) {
        if (in_array($dil, ['tr', 'en'])) {
            self::$aktifDil = $dil;
            Oturum::yaz('lang', $dil);
            $dosya = __DIR__ . '/lang/' . $dil . '.php';
            if (file_exists($dosya)) {
                self::$ceviriler = require $dosya;
            }
        }
    }

    public static function cevir($anahtar) {
        if (empty(self::$ceviriler)) {
            self::baslat();
        }
        return self::$ceviriler[$anahtar] ?? $anahtar;
    }
}

if (!function_exists('__')) {
    function __($anahtar) {
        return Lang::cevir($anahtar);
    }
}

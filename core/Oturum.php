<?php
class Oturum {
    public static function baslat() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function yaz($anahtar, $deger) {
        self::baslat();
        $_SESSION[$anahtar] = $deger;
    }

    public static function al($anahtar) {
        self::baslat();
        return $_SESSION[$anahtar] ?? null;
    }

    public static function sil($anahtar) {
        self::baslat();
        if (isset($_SESSION[$anahtar])) unset($_SESSION[$anahtar]);
    }

    public static function mesajYaz($tur, $mesaj) {
        self::baslat();
        $_SESSION['flash_mesajlar'][$tur] = $mesaj;
    }

    public static function mesajAlVeSil($tur = 'hata') {
        self::baslat();
        $mesaj = $_SESSION['flash_mesajlar'][$tur] ?? null;
        if (isset($_SESSION['flash_mesajlar'][$tur])) {
            unset($_SESSION['flash_mesajlar'][$tur]);
        }
        return $mesaj;
    }

    public static function kapat() {
        self::baslat();
        session_destroy();
    }
}

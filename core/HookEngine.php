<?php
class HookEngine {
    private static $kancalar = [];
    private static $karantina = [];

    public static function ekle($kancaAdi, $callback, $oncelik = 10) {
        self::$kancalar[$kancaAdi][$oncelik][] = $callback;
    }

    public static function tetikle($kancaAdi, $parametreler = []) {
        if (!isset(self::$kancalar[$kancaAdi])) return;

        ksort(self::$kancalar[$kancaAdi]);
        foreach (self::$kancalar[$kancaAdi] as $oncelik => $callbacks) {
            foreach ($callbacks as $cb) {
                try {
                    if (is_callable($cb)) {
                        call_user_func_array($cb, $parametreler);
                    }
                } catch (Throwable $e) {
                    // Eklenti Çöktü -> Karantinaya Al
                    $eklentiAdi = is_string($cb) ? $cb : 'Bilinmeyen Eklenti';
                    self::$karantina[] = $eklentiAdi;
                    
                    // Hata günlüğüne yaz
                    error_log("FAZ 4 Safe-Mode: {$eklentiAdi} eklentisi çöktü ve karantinaya alındı: " . $e->getMessage());
                }
            }
        }
    }

    public static function karantinadakiler() {
        return self::$karantina;
    }
}

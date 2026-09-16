<?php
class Audit {
    public static function kaydet($islem, $detay = '') {
        try {
            if (file_exists(__DIR__ . '/installed.lock')) {
                $db = Database::baglan();
                if ($db) {
                    $stmt = $db->prepare("INSERT INTO audit_loglari (kullanici_id, kullanici_adi, islem, detay, ip, tarih) VALUES (:uid, :kadi, :islem, :detay, :ip, NOW())");
                    $stmt->execute([
                        'uid' => Oturum::al('kullanici_id') ?? 0,
                        'kadi' => Oturum::al('kullanici_adi') ?? 'Sistem',
                        'islem' => $islem,
                        'detay' => $detay,
                        'ip' => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0'
                    ]);
                    $stmt->closeCursor();
                }
            }
        } catch (\Exception $e) {}
    }
}

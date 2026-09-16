<?php
class WAF {
    public static function ipAl() {
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $iplerdizi = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            return trim($iplerdizi[0]);
        }
        return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    }

    public static function calistir() {
        $ip = self::ipAl();
        
        try {
            $db = Database::baglan();
            if (!$db) return;

            // 1. IP Karaliste Kontrolü
            $stmt = $db->prepare("SELECT COUNT(*) FROM engellenen_ipler WHERE ip = ?");
            $stmt->execute([$ip]);
            if ($stmt->fetchColumn() > 0) {
                http_response_code(403);
                die("<!DOCTYPE html><html><head><title>403 Forbidden</title><style>body{background:#0f172a;color:#f43f5e;font-family:sans-serif;display:flex;justify-content:center;align-items:center;height:100vh;margin:0;}.box{background:#151d30;padding:40px;border-radius:16px;border:1px solid rgba(244,63,94,0.3);text-align:center;}</style></head><body><div class='box'><h1>⛔ 403 - IP Adresiniz Engellendi</h1><p style='color:#94a3b8;'>Guvenlik ihlali veya otonom tehdit algılaması nedeniyle erişiminiz kısıtlandı.</p><p style='font-size:12px;color:#64748b;'>IP: {$ip}</p></div></body></html>");
            }

            // 2. Heuristic Payload Taraması (SQLi, XSS, Path Traversal)
            $zararliDesenler = [
                '/union\s+select/i', '/select\s+.*\s+from/i', '/insert\s+into/i',
                '/<script.*?>/i', '/javascript:/i', '/\.\.\/\.\.\//i', '/etc\/passwd/i',
                '/base64_decode/i', '/eval\(/i'
            ];

            $inputlar = array_merge($_GET, $_POST, $_COOKIE);
            foreach ($inputlar as $anahtar => $deger) {
                if (is_string($deger)) {
                    foreach ($zararliDesenler as $desen) {
                        if (preg_match($desen, $deger)) {
                            self::otonomBanla($ip, "Zararlı Payload Algılandı ({$anahtar})");
                        }
                    }
                }
            }
        } catch (Throwable $e) {
            // Veritabanı bağlantı hatası durumunda sessizce devam et, 500 verme
            error_log("WAF Hatası: " . $e->getMessage());
        }
    }

    public static function hataliGirisKaydet($ip = null) {
        $ip = $ip ?: self::ipAl();
        try {
            $db = Database::baglan();
            $stmt = $db->prepare("INSERT INTO saldirilar (ip, saldiri_tipi, hedef_url) VALUES (?, 'Hatalı Giriş Denemesi', ?)");
            $stmt->execute([$ip, $_SERVER['REQUEST_URI'] ?? '']);

            $stmt = $db->prepare("SELECT COUNT(*) FROM saldirilar WHERE ip = ? AND tarih >= NOW() - INTERVAL 5 MINUTE");
            $stmt->execute([$ip]);
            $hataliSayisi = $stmt->fetchColumn();

            if ($hataliSayisi >= 5) {
                self::otonomBanla($ip, 'Kaba Kuvvet (Brute-Force) Giriş Saldırısı');
            }
        } catch (Throwable $e) {}
    }

    public static function otonomBanla($ip, $sebep) {
        try {
            $db = Database::baglan();
            $stmt = $db->prepare("INSERT IGNORE INTO engellenen_ipler (ip, sebep) VALUES (?, ?)");
            $stmt->execute([$ip, 'Otonom Ban: ' . $sebep]);

            $stmt = $db->prepare("INSERT INTO saldirilar (ip, saldiri_tipi, hedef_url) VALUES (?, ?, ?)");
            $stmt->execute([$ip, $sebep, $_SERVER['REQUEST_URI'] ?? '']);

            if (class_exists('Mail')) {
                $adminMail = $db->query("SELECT deger FROM ayarlar WHERE anahtar = 'admin_eposta'")->fetchColumn() ?: 'admin@site.com';
                @Mail::gonder($adminMail, "⚠️ [GÜVENLİK ALARMI] IP Otonom Engellendi", "
                    <div style='font-family:sans-serif; background:#0f172a; color:#fff; padding:20px; border-radius:10px;'>
                        <h2 style='color:#f43f5e;'>⚠️ Otonom WAF Saldırı Engelledi</h2>
                        <p><strong>Engellenen IP:</strong> {$ip}</p>
                        <p><strong>Sebep:</strong> {$sebep}</p>
                        <p><strong>Tarih:</strong> " . date('Y-m-d H:i:s') . "</p>
                    </div>
                ");
            }
        } catch (Throwable $e) {}

        http_response_code(403);
        die("<!DOCTYPE html><html><head><title>WAF Blocked</title></head><body style='background:#0f172a;color:#f43f5e;font-family:sans-serif;padding:50px;text-align:center;'><h1>🛡️ Otonom WAF Kalkanı Devrede</h1><p>Zararlı istek parametresi algılandı. IP adresiniz ({$ip}) engellendi ve veritabanına kaydedildi.</p></body></html>");
    }
}

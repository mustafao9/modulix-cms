<?php
class SelfHealer {
    public static function otomatikanalizVeOnar() {
        $rapor = ['onarilanlar' => [], 'durum' => 'saglikli'];

        // 1. Veritabanı Tablo Kontrolü ve Otonom Onarım
        try {
            $db = Database::baglan();
            $kritikTablolar = ['ayarlar', 'kullanicilar', 'saldirilar', 'engellenen_ipler', 'audit_loglari'];
            $stmt = $db->query("SHOW TABLES");
            $mevcutTablolar = $stmt->fetchAll(PDO::FETCH_COLUMN);

            $eksikTablolar = array_diff($kritikTablolar, $mevcutTablolar);
            if (!empty($eksikTablolar)) {
                $schemaYol = __DIR__ . '/../modules/Install/schema.sql';
                if (file_exists($schemaYol)) {
                    $sql = file_get_contents($schemaYol);
                    $db->exec($sql);
                    $rapor['onarilanlar'][] = "Eksik veritabanı tabloları (" . implode(', ', $eksikTablolar) . ") schema.sql üzerinden yeniden derlendi.";
                    $rapor['durum'] = 'onarildi';
                }
            }
        } catch (Exception $e) {
            // Veritabanı bağlantı çökmesini engelle
        }

        // 2. Yapılandırma (.env) Onarımı
        $envYol = __DIR__ . '/../.env';
        if (!file_exists($envYol) || filesize($envYol) === 0) {
            $varsayilanEnv = "APP_ENV=production\nAPP_URL=http://localhost\nDB_HOST=localhost\nDB_NAME=modulix_db\nDB_USER=root\nDB_PASS=\n";
            file_put_contents($envYol, $varsayilanEnv);
            $rapor['onarilanlar'][] = "Eksik .env dosyası varsayılan ayarlarla yeniden üretildi.";
            $rapor['durum'] = 'onarildi';
        }

        // 3. Yönlendırma (.htaccess) Onarımı
        $htaccessYol = __DIR__ . '/../public/.htaccess';
        if (!file_exists($htaccessYol)) {
            $htaccessContent = "<IfModule mod_rewrite.c>\n    RewriteEngine On\n    RewriteCond %{REQUEST_FILENAME} !-f\n    RewriteCond %{REQUEST_FILENAME} !-d\n    RewriteRule ^ index.php [L]\n</IfModule>";
            file_put_contents($htaccessYol, $htaccessContent);
            $rapor['onarilanlar'][] = "Public .htaccess yönlendirme dosyası otomatik üretildi.";
            $rapor['durum'] = 'onarildi';
        }

        return $rapor;
    }
}

<?php
class AdminModel extends Model {
    public function sistemMetrikleriGetir() {
        $m = ['toplam_uye' => 0, 'engelli_ip' => 0, 'saldiri_sayisi' => 0, 'php_surum' => phpversion()];
        try {
            $s1 = $this->db->query("SELECT COUNT(*) FROM kullanicilar"); $m['toplam_uye'] = $s1->fetchColumn(); $s1->closeCursor();
            $s2 = $this->db->query("SELECT COUNT(*) FROM ip_engelleri"); $m['engelli_ip'] = $s2->fetchColumn(); $s2->closeCursor();
            $s3 = $this->db->query("SELECT COUNT(*) FROM saldiri_loglari"); $m['saldiri_sayisi'] = $s3->fetchColumn(); $s3->closeCursor();
        } catch (\Exception $e) {}
        return $m;
    }

    public function tumKullanicilariGetir($arama = '') {
        if (!empty($arama)) {
            $stmt = $this->db->prepare("SELECT * FROM kullanicilar WHERE kullanici_adi LIKE :q OR eposta LIKE :q ORDER BY id DESC");
            $stmt->execute(['q' => "%{$arama}%"]);
            $res = $stmt->fetchAll(); $stmt->closeCursor(); return $res;
        }
        $stmt = $this->db->query("SELECT * FROM kullanicilar ORDER BY id DESC");
        $res = $stmt->fetchAll(); $stmt->closeCursor(); return $res;
    }

    public function kullaniciGetir($id) {
        $stmt = $this->db->prepare("SELECT * FROM kullanicilar WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]); $res = $stmt->fetch(); $stmt->closeCursor(); return $res;
    }

    public function kullaniciEkle($veri) {
        $sifreHash = password_hash($veri['sifre'], PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO kullanicilar (kullanici_adi, eposta, sifre, rol, hesap_durumu) VALUES (:u, :e, :p, :r, :d)");
        $res = $stmt->execute(['u' => $veri['kullanici_adi'], 'e' => $veri['eposta'], 'p' => $sifreHash, 'r' => $veri['rol'], 'd' => $veri['hesap_durumu']]);
        $stmt->closeCursor(); return $res;
    }

    public function kullaniciGuncelle($id, $veri) {
        if (!empty($veri['sifre'])) {
            $sifreHash = password_hash($veri['sifre'], PASSWORD_DEFAULT);
            $stmt = $this->db->prepare("UPDATE kullanicilar SET kullanici_adi = :u, eposta = :e, sifre = :p, rol = :r, hesap_durumu = :d WHERE id = :id");
            $res = $stmt->execute(['id' => $id, 'u' => $veri['kullanici_adi'], 'e' => $veri['eposta'], 'p' => $sifreHash, 'r' => $veri['rol'], 'd' => $veri['hesap_durumu']]);
        } else {
            $stmt = $this->db->prepare("UPDATE kullanicilar SET kullanici_adi = :u, eposta = :e, rol = :r, hesap_durumu = :d WHERE id = :id");
            $res = $stmt->execute(['id' => $id, 'u' => $veri['kullanici_adi'], 'e' => $veri['eposta'], 'r' => $veri['rol'], 'd' => $veri['hesap_durumu']]);
        }
        $stmt->closeCursor(); return $res;
    }

    public function kullaniciSil($id) {
        $stmt = $this->db->prepare("DELETE FROM kullanicilar WHERE id = :id AND rol != 'super_admin'");
        $res = $stmt->execute(['id' => $id]); $stmt->closeCursor(); return $res;
    }

    public function ayarTariGetir() {
        try {
            $stmt = $this->db->query("SELECT * FROM ayarlar");
            $rows = $stmt->fetchAll(); $stmt->closeCursor();
            $res = []; foreach ($rows as $r) { $res[$r['anahtar']] = $r['deger']; } return $res;
        } catch (\Exception $e) { return []; }
    }

    public function ayarGuncelle($anahtar, $deger) {
        $stmt = $this->db->prepare("INSERT INTO ayarlar (anahtar, deger) VALUES (:k, :v) ON DUPLICATE KEY UPDATE deger = :v");
        $res = $stmt->execute(['k' => $anahtar, 'v' => $deger]); $stmt->closeCursor(); return $res;
    }

    public function engellenenIpleriGetir() {
        try {
            $stmt = $this->db->query("SELECT * FROM ip_engelleri ORDER BY id DESC");
            $res = $stmt->fetchAll(); $stmt->closeCursor(); return $res;
        } catch (\Exception $e) { return []; }
    }

    public function saldirilariGetir() {
        try {
            $stmt = $this->db->query("SELECT * FROM saldiri_loglari ORDER BY id DESC LIMIT 50");
            $res = $stmt->fetchAll(); $stmt->closeCursor(); return $res;
        } catch (\Exception $e) { return []; }
    }

    public function ipEngelle($ip, $sebep) {
        $stmt = $this->db->prepare("INSERT INTO ip_engelleri (ip, sebep, tarih) VALUES (:ip, :sebep, NOW())");
        $res = $stmt->execute(['ip' => $ip, 'sebep' => $sebep]); $stmt->closeCursor(); return $res;
    }

    public function ipEngelKalk($id) {
        $stmt = $this->db->prepare("DELETE FROM ip_engelleri WHERE id = :id");
        $res = $stmt->execute(['id' => $id]); $stmt->closeCursor(); return $res;
    }

    public function saldirilariTemizle() {
        $stmt = $this->db->query("TRUNCATE TABLE saldiri_loglari");
        if ($stmt) $stmt->closeCursor(); return true;
    }

    public function auditLoglariGetir() {
        try {
            $stmt = $this->db->query("SELECT * FROM audit_loglari ORDER BY id DESC LIMIT 100");
            $res = $stmt->fetchAll(); $stmt->closeCursor(); return $res;
        } catch (\Exception $e) { return []; }
    }

    public function auditLoglariTemizle() {
        $stmt = $this->db->query("TRUNCATE TABLE audit_loglari");
        if ($stmt) $stmt->closeCursor(); return true;
    }

    public function veritabaniOptimizeEt() {
        $stmt = $this->db->query("SHOW TABLES");
        $tablolar = $stmt->fetchAll(PDO::FETCH_COLUMN); $stmt->closeCursor();
        if (!empty($tablolar)) {
            $sql = "OPTIMIZE TABLE " . implode(', ', array_map(function($t) { return "`$t`"; }, $tablolar));
            $opt = $this->db->query($sql); if ($opt) { $opt->fetchAll(); $opt->closeCursor(); }
        }
        return true;
    }

    public function hataLoglariniOku() {
        $logFile = __DIR__ . '/../../../core/error.log';
        return file_exists($logFile) ? array_reverse(file($logFile)) : [];
    }

    public function hataLoglariniTemizle() {
        $logFile = __DIR__ . '/../../../core/error.log';
        if (file_exists($logFile)) @file_put_contents($logFile, '');
    }
}

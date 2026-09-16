<?php
class AdminController extends Controller {
    public function __construct() {
        Oturum::baslat();
        $rol = Oturum::al('rol');
        if (!$rol || !in_array($rol, ['super_admin', 'admin'])) {
            Oturum::mesajYaz('hata', 'Yetkisiz erişim.');
            $baseUrl = Env::al('APP_URL', rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'));
            header("Location: " . rtrim($baseUrl, '/') . '/giris'); exit;
        }
    }

    public function index() {
        $m = $this->model('AdminModel', 'Admin');
        $this->view('dashboard', ['metrikler' => $m->sistemMetrikleriGetir(), 'sonUyeler' => array_slice($m->tumKullanicilariGetir(), 0, 5)], 'Admin');
    }

    public function sistemiOnar() {
        require_once __DIR__ . '/../../../core/SelfHealer.php';
        $sonuc = SelfHealer::otomatikanalizVeOnar();
        if (!empty($sonuc['onarilanlar'])) {
            Oturum::mesajYaz('basari', 'Self-Healing Onarımı Tamamlandı: ' . implode(' | ', $sonuc['onarilanlar']));
        } else {
            Oturum::mesajYaz('basari', 'Sistem sağlık taramasından geçti. Tüm çekirdek bileşenler %100 sağlıklı.');
        }
        $baseUrl = Env::al('APP_URL', rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'));
        header("Location: " . rtrim($baseUrl, '/') . '/yonetim/yedekler'); exit;
    }

    public function kullanicilar() {
        $m = $this->model('AdminModel', 'Admin');
        $this->view('kullanicilar', ['kullanicilar' => $m->tumKullanicilariGetir($_GET['q'] ?? '')], 'Admin');
    }

    public function kullaniciEkleForm() { $this->view('kullanici-form', ['islem' => 'ekle'], 'Admin'); }

    public function kullaniciEkleKaydet() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $m = $this->model('AdminModel', 'Admin');
            if ($m->kullaniciEkle($_POST)) {
                Audit::kaydet('Kullanıcı Eklendi', $_POST['kullanici_adi']);
                Oturum::mesajYaz('basari', 'Kullanıcı başarıyla eklendi.');
            }
        }
        $baseUrl = Env::al('APP_URL', rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'));
        header("Location: " . rtrim($baseUrl, '/') . '/yonetim/kullanicilar'); exit;
    }

    public function kullaniciDuzenleForm($id) {
        $m = $this->model('AdminModel', 'Admin');
        $this->view('kullanici-form', ['islem' => 'duzenle', 'kullanici' => $m->kullaniciGetir($id)], 'Admin');
    }

    public function kullaniciDuzenleKaydet($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $m = $this->model('AdminModel', 'Admin');
            $m->kullaniciGuncelle($id, $_POST);
            Audit::kaydet('Kullanıcı Güncellendi', 'ID: ' . $id);
            Oturum::mesajYaz('basari', 'Kullanıcı güncellendi.');
        }
        $baseUrl = Env::al('APP_URL', rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'));
        header("Location: " . rtrim($baseUrl, '/') . '/yonetim/kullanicilar'); exit;
    }

    public function kullaniciSil($id) {
        $m = $this->model('AdminModel', 'Admin');
        $m->kullaniciSil($id);
        Audit::kaydet('Kullanıcı Silindi', 'ID: ' . $id);
        Oturum::mesajYaz('basari', 'Kullanıcı silindi.');
        $baseUrl = Env::al('APP_URL', rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'));
        header("Location: " . rtrim($baseUrl, '/') . '/yonetim/kullanicilar'); exit;
    }

    public function logoYonetimi() {
        $m = $this->model('AdminModel', 'Admin');
        $this->view('logo-yonetimi', ['ayarlar' => $m->ayarTariGetir()], 'Admin');
    }

    public function logoKaydet() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['site_logo']) && $_FILES['site_logo']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../../public/uploads/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            $dosya = 'logo_' . time() . '.' . pathinfo($_FILES['site_logo']['name'], PATHINFO_EXTENSION);
            if (move_uploaded_file($_FILES['site_logo']['tmp_name'], $uploadDir . $dosya)) {
                $m = $this->model('AdminModel', 'Admin');
                $m->ayarGuncelle('site_logo', $dosya);
                Audit::kaydet('Logo Güncellendi', $dosya);
                Oturum::mesajYaz('basari', 'Logo güncellendi.');
            }
        }
        $baseUrl = Env::al('APP_URL', rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'));
        header("Location: " . rtrim($baseUrl, '/') . '/yonetim/logo-yonetimi'); exit;
    }

    public function sistemAyarlari() {
        $m = $this->model('AdminModel', 'Admin');
        $this->view('ayarlar', ['ayarlar' => $m->ayarTariGetir()], 'Admin');
    }

    public function sistemAyarlariKaydet() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $m = $this->model('AdminModel', 'Admin');
            $toggleKeys = ['recaptcha_active', 'google_login_active', 'db_manager_active'];
            foreach ($toggleKeys as $tk) {
                $m->ayarGuncelle($tk, isset($_POST[$tk]) ? '1' : '0');
            }
            foreach ($_POST as $k => $v) {
                if (!in_array($k, $toggleKeys)) $m->ayarGuncelle($k, trim($v));
            }
            Audit::kaydet('Ayarlar Güncellendi');
            Oturum::mesajYaz('basari', 'Sistem ayarları kaydedildi.');
        }
        $baseUrl = Env::al('APP_URL', rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'));
        header("Location: " . rtrim($baseUrl, '/') . '/yonetim/ayarlar'); exit;
    }

    public function smtpTestEt() {
        $alici = trim($_POST['test_email'] ?? '');
        if (!empty($alici)) {
            require_once __DIR__ . '/../../../core/Mail.php';
            $sonuc = Mail::gonder($alici, "Modulix-CMS SMTP Test E-postası", "<h3>Tebrikler!</h3><p>Modulix-CMS otonom mail bildirimi ve SMTP entegrasyonu kusursuz çalışıyor.</p>");
            if ($sonuc) {
                Oturum::mesajYaz('basari', "Test e-postası {$alici} adresine başarıyla gönderildi.");
            } else {
                Oturum::mesajYaz('hata', "E-posta gönderimi başarısız oldu. SMTP ayarlarınızı kontrol edin.");
            }
        }
        $baseUrl = Env::al('APP_URL', rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'));
        header("Location: " . rtrim($baseUrl, '/') . '/yonetim/ayarlar'); exit;
    }

    public function guvenlikMerkezi() {
        $m = $this->model('AdminModel', 'Admin');
        $this->view('guvenlik', ['engellenenler' => $m->engellenenIpleriGetir(), 'saldirilar' => $m->saldirilariGetir()], 'Admin');
    }

    public function ipEngelleEkle() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $m = $this->model('AdminModel', 'Admin');
            $m->ipEngelle(trim($_POST['ip'] ?? ''), trim($_POST['sebep'] ?? 'Yönetici Engeli'));
            Audit::kaydet('IP Engellendi', $_POST['ip']);
            Oturum::mesajYaz('basari', 'IP karalisteye eklendi.');
        }
        $baseUrl = Env::al('APP_URL', rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'));
        header("Location: " . rtrim($baseUrl, '/') . '/yonetim/guvenlik'); exit;
    }

    public function ipEngelKaldir($id) {
        $m = $this->model('AdminModel', 'Admin');
        $m->ipEngelKalk($id); Audit::kaydet('IP Engeli Kaldırıldı', 'ID: ' . $id);
        Oturum::mesajYaz('basari', 'IP engeli kaldırıldı.');
        $baseUrl = Env::al('APP_URL', rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'));
        header("Location: " . rtrim($baseUrl, '/') . '/yonetim/guvenlik'); exit;
    }

    public function saldirilariTemizle() {
        $m = $this->model('AdminModel', 'Admin');
        $m->saldirilariTemizle(); Audit::kaydet('Saldırı Kayıtları Temizlendi');
        Oturum::mesajYaz('basari', 'Saldırı günlükleri temizlendi.');
        $baseUrl = Env::al('APP_URL', rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'));
        header("Location: " . rtrim($baseUrl, '/') . '/yonetim/guvenlik'); exit;
    }

    public function dosyaIzinleri() {
        $dosyalar = [
            'Proje .env Dosyası' => __DIR__ . '/../../../.env',
            'Kurulum Kilidi' => __DIR__ . '/../../../core/installed.lock',
            'Modüller Dizini' => __DIR__ . '/../../../modules',
            'Public Dizini' => __DIR__ . '/../../../public'
        ];
        $this->view('dosya-izinleri', ['dosyalar' => $dosyalar], 'Admin');
    }

    public function dosyaIzinleriniDuzelt() {
        $hedefIzinler = [
            __DIR__ . '/../../../.env' => 0644,
            __DIR__ . '/../../../core/installed.lock' => 0644,
            __DIR__ . '/../../../modules' => 0755,
            __DIR__ . '/../../../public' => 0755,
            __DIR__ . '/../../../storage' => 0755
        ];
        $basarili = true;
        foreach ($hedefIzinler as $yol => $izin) {
            if (file_exists($yol)) {
                if (!@chmod($yol, $izin)) { $basarili = false; }
            }
        }
        if ($basarili) {
            Oturum::mesajYaz('basari', 'Tüm kritik dosya ve dizin izinleri başarıyla 644/755 standartlarına onarıldı.');
            Audit::kaydet('Dosya İzinleri Otomatik Onarıldı (CHMOD)');
        } else {
            Oturum::mesajYaz('hata', 'Bazı dosya izinleri değiştirilemedi.');
        }
        $baseUrl = Env::al('APP_URL', rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'));
        header("Location: " . rtrim($baseUrl, '/') . '/yonetim/dosya-izinleri'); exit;
    }

    public function hataLoglari() {
        $m = $this->model('AdminModel', 'Admin');
        $this->view('hata-loglari', ['loglar' => $m->hataLoglariniOku()], 'Admin');
    }

    public function hataLoglariTemizle() {
        $m = $this->model('AdminModel', 'Admin');
        $m->hataLoglariniTemizle(); Audit::kaydet('Hata Günlükleri Temizlendi');
        Oturum::mesajYaz('basari', 'Hata günlükleri temizlendi.');
        $baseUrl = Env::al('APP_URL', rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'));
        header("Location: " . rtrim($baseUrl, '/') . '/yonetim/hata-loglari'); exit;
    }

    public function auditLoglari() {
        $m = $this->model('AdminModel', 'Admin');
        $this->view('audit-loglari', ['auditler' => $m->auditLoglariGetir()], 'Admin');
    }

    public function auditLoglariTemizle() {
        $m = $this->model('AdminModel', 'Admin');
        $m->auditLoglariTemizle();
        Oturum::mesajYaz('basari', 'Audit kayıtları temizlendi.');
        $baseUrl = Env::al('APP_URL', rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'));
        header("Location: " . rtrim($baseUrl, '/') . '/yonetim/audit-loglari'); exit;
    }

    public function veritabaniYedekleri() { $this->view('yedekler', [], 'Admin'); }

    public function veritabaniOptimizeEt() {
        $m = $this->model('AdminModel', 'Admin');
        $m->veritabaniOptimizeEt(); Audit::kaydet('Veritabanı Optimize Edildi');
        Oturum::mesajYaz('basari', 'Tablolar optimize edildi.');
        $baseUrl = Env::al('APP_URL', rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'));
        header("Location: " . rtrim($baseUrl, '/') . '/yonetim/yedekler'); exit;
    }

    public function sqlYedekIndir() {
        $db = Database::baglan();
        Audit::kaydet('SQL Yedeği İndirildi');
        $sql = "-- Modulix-CMS Local Backup\n-- Tarih: " . date('Y-m-d H:i:s') . "\n\n";
        $stmt = $db->query("SHOW TABLES"); $tablolar = $stmt->fetchAll(PDO::FETCH_COLUMN); $stmt->closeCursor();

        foreach ($tablolar as $t) {
            $c = $db->query("SHOW CREATE TABLE `$t`")->fetch(PDO::FETCH_ASSOC);
            $sql .= "\n\n" . $c['Create Table'] . ";\n\n";
            $satirlar = $db->query("SELECT * FROM `$t`")->fetchAll(PDO::FETCH_ASSOC);
            foreach ($satirlar as $s) {
                $cols = array_keys($s);
                $vals = array_map(function($v) use ($db) { return $v === null ? 'NULL' : $db->quote($v); }, array_values($s));
                $sql .= "INSERT INTO `$t` (`" . implode("`, `", $cols) . "`) VALUES (" . implode(", ", $vals) . ");\n";
            }
        }
        header('Content-Type: application/sql; charset=utf-8');
        header('Content-Disposition: attachment; filename=modulix_backup_' . date('Y-m-d') . '.sql');
        echo $sql; exit;
    }

    public function dagitimMerkezi() {
        $releaseDir = __DIR__ . '/../../../releases/';
        $paketler = [];
        if (is_dir($releaseDir)) {
            $files = glob($releaseDir . '*.zip');
            foreach ($files as $f) {
                $paketler[] = [
                    'ad' => basename($f),
                    'boyut' => round(filesize($f) / 1024 / 1024, 2) . ' MB',
                    'tarih' => date('Y-m-d H:i:s', filemtime($f))
                ];
            }
        }
        $this->view('dagitim', ['paketler' => array_reverse($paketler)], 'Admin');
    }

    public function dagitimPaketiUret() {
        $versiyon = preg_replace('/[^a-zA-Z0-9\.\-_]/', '', $_POST['versiyon'] ?? 'v1.0.0');
        $paketTipi = $_POST['paket_tipi'] ?? 'clean';
        $isClean = ($paketTipi === 'clean');
        
        $db = Database::baglan();
        
        if ($isClean) {
            $sql = "-- Modulix-CMS Clean Release Schema\n-- Version: {$versiyon}\n-- Generated: " . date('Y-m-d H:i:s') . "\n\nSET FOREIGN_KEY_CHECKS = 0;\n\n";
            $stmt = $db->query("SHOW TABLES"); $tablolar = $stmt->fetchAll(PDO::FETCH_COLUMN); $stmt->closeCursor();

            foreach ($tablolar as $t) {
                $c = $db->query("SHOW CREATE TABLE `$t`")->fetch(PDO::FETCH_ASSOC);
                $createSql = preg_replace('/AUTO_INCREMENT=\d+/i', '', $c['Create Table']);
                $sql .= "DROP TABLE IF EXISTS `$t`;\n" . $createSql . ";\n\n";

                if ($t === 'ayarlar') {
                    $ayarlar = $db->query("SELECT * FROM ayarlar")->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($ayarlar as $s) {
                        $cols = array_keys($s);
                        $vals = array_map(function($v) use ($db) { return $v === null ? 'NULL' : $db->quote($v); }, array_values($s));
                        $sql .= "INSERT INTO `ayarlar` (`" . implode("`, `", $cols) . "`) VALUES (" . implode(", ", $vals) . ");\n";
                    }
                    $sql .= "\n";
                }
            }
            $sql .= "SET FOREIGN_KEY_CHECKS = 1;\n";
            $schemaYol = __DIR__ . '/../../Install/schema.sql';
            file_put_contents($schemaYol, $sql);
        }

        $releaseDir = __DIR__ . '/../../../releases/';
        if (!is_dir($releaseDir)) mkdir($releaseDir, 0755, true);

        $suffix = $isClean ? '-clean.zip' : '-full.zip';
        $zipAdi = 'modulix-cms-' . $versiyon . $suffix;
        $zipYol = $releaseDir . $zipAdi;
        $zip = new ZipArchive();

        if ($zip->open($zipYol, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            $rootPath = realpath(__DIR__ . '/../../../');
            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($rootPath, RecursiveDirectoryIterator::SKIP_DOTS), RecursiveIteratorIterator::LEAVES_ONLY);

            foreach ($files as $file) {
                if (!$file->isDir()) {
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($rootPath) + 1);

                    $haric = $isClean ? [
                        'vendor', '.git', 'error.log', 'installed.lock', 
                        'releases', '.env', 'public/uploads/logo_', 'public/uploads/modulix_'
                    ] : ['vendor', '.git', 'releases'];

                    $skip = false;
                    foreach ($haric as $h) {
                        if (strpos($relativePath, $h) !== false) { $skip = true; break; }
                    }

                    if (!$skip) {
                        $zip->addFile($filePath, $relativePath);
                    }
                }
            }

            if ($isClean) {
                $envExample = "APP_ENV=production\nAPP_URL=http://localhost\nDB_HOST=localhost\nDB_NAME=modulix_db\nDB_USER=root\nDB_PASS=\n";
                $zip->addFromString('.env.example', $envExample);
            }

            $zip->close();
            
            require_once __DIR__ . '/../../../core/ReleaseDiff.php';
            ReleaseDiff::hashKaydet();

            Audit::kaydet('Sürüm Paketi Derlendi', $zipAdi);
            Oturum::mesajYaz('basari', "{$zipAdi} sürüm paketi başarıyla derlendi.");
        } else {
            Oturum::mesajYaz('hata', "ZIP paketi oluşturulurken bir sorun oluştu.");
        }

        $baseUrl = Env::al('APP_URL', rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'));
        header("Location: " . rtrim($baseUrl, '/') . '/yonetim/dagitim'); exit;
    }

    public function dagitimPaketiIndir() {
        $dosya = basename($_GET['dosya'] ?? '');
        if (!empty($dosya)) {
            $yol = __DIR__ . '/../../../releases/' . $dosya;
            if (file_exists($yol)) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/zip');
                header('Content-Disposition: attachment; filename="' . $dosya . '"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($yol));
                readfile($yol);
                exit;
            }
        }
        Oturum::mesajYaz('hata', 'Paket bulunamadı.');
        $baseUrl = Env::al('APP_URL', rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'));
        header("Location: " . rtrim($baseUrl, '/') . '/yonetim/dagitim'); exit;
    }

    public function dagitimPaketiSil() {
        $dosya = basename($_GET['dosya'] ?? '');
        if (!empty($dosya)) {
            $yol = __DIR__ . '/../../../releases/' . $dosya;
            if (file_exists($yol)) {
                unlink($yol);
                Audit::kaydet('Dağıtım Paketi Silindi', $dosya);
                Oturum::mesajYaz('basari', "{$dosya} paketi silindi.");
            }
        }
        $baseUrl = Env::al('APP_URL', rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'));
        header("Location: " . rtrim($baseUrl, '/') . '/yonetim/dagitim'); exit;
    }
}

<?php
class InstallController extends Controller {
    public function __construct() {
        if (file_exists(__DIR__ . '/../../../core/installed.lock')) {
            $baseUrl = Env::al('APP_URL', rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'));
            header("Location: " . rtrim($baseUrl, '/') . '/giris'); exit;
        }
    }

    public function index() {
        $this->view('step1', [], 'Install');
    }

    public function step2() {
        $this->view('step2', [], 'Install');
    }

    public function kur() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $host = trim($_POST['db_host'] ?? 'localhost');
            $name = trim($_POST['db_name'] ?? '');
            $user = trim($_POST['db_user'] ?? 'root');
            $pass = trim($_POST['db_pass'] ?? '');
            
            $adminUser = trim($_POST['admin_user'] ?? 'admin');
            $adminEmail = trim($_POST['admin_email'] ?? 'admin@site.com');
            $adminPass = trim($_POST['admin_pass'] ?? '');

            try {
                // PDO Bağlantı Testi
                $pdo = new PDO("mysql:host={$host};charset=utf8mb4", $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
                $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$name}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
                $pdo->exec("USE `{$name}`;");

                // schema.sql Çalıştır
                $schemaFile = __DIR__ . '/../schema.sql';
                if (file_exists($schemaFile)) {
                    $sql = file_get_contents($schemaFile);
                    $pdo->exec($sql);
                }

                // .env Oluştur
                $envContent = "APP_ENV=production\nAPP_URL=" . (isset($_SERVER['HTTPS']) ? 'https' : 'http') . "://{$_SERVER['HTTP_HOST']}\nDB_HOST={$host}\nDB_NAME={$name}\nDB_USER={$user}\nDB_PASS={$pass}\n";
                file_put_contents(__DIR__ . '/../../../.env', $envContent);

                // Admin Kullanıcısı Ekle
                $hash = password_hash($adminPass, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO kullanicilar (kullanici_adi, eposta, sifre, rol, hesap_durumu) VALUES (?, ?, ?, 'super_admin', 'aktif') ON DUPLICATE KEY UPDATE sifre = ?, eposta = ?");
                $stmt->execute([$adminUser, $adminEmail, $hash, $hash, $adminEmail]);

                // Kurulum Kilidi Oluştur
                file_put_contents(__DIR__ . '/../../../core/installed.lock', date('Y-m-d H:i:s'));

                $baseUrl = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . "://{$_SERVER['HTTP_HOST']}" . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
                header("Location: " . rtrim($baseUrl, '/') . '/giris?kurulum=basarili'); exit;

            } catch (Exception $e) {
                echo "<div style='color:red; background:#1e293b; padding:20px; font-family:sans-serif;'><h3>Kurulum Hatası:</h3>" . $e->getMessage() . "<br><br><a href='javascript:history.back()'>Geri Dön ve Düzelt</a></div>"; exit;
            }
        }
    }
}

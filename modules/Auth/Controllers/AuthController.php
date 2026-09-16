<?php
class AuthController extends Controller {
    public function girisForm() {
        Oturum::baslat();
        if (Oturum::al('rol')) {
            $baseUrl = Env::al('APP_URL', rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'));
            header("Location: " . rtrim($baseUrl, '/') . '/yonetim');
            exit;
        }
        $this->view('giris', [], 'Auth');
    }

    public function girisYap() {
        Oturum::baslat();
        $baseUrl = Env::al('APP_URL', rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'));
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $kadi = trim($_POST['kullanici_adi_veya_eposta'] ?? '');
            $sifre = $_POST['sifre'] ?? '';

            $model = $this->model('AuthModel', 'Auth');
            $kullanici = $model->kullaniciGetir($kadi);

            if ($kullanici && (password_verify($sifre, $kullanici['sifre']) || md5($sifre) === $kullanici['sifre'])) {
                if ($kullanici['hesap_durumu'] === 'pasif') {
                    Oturum::mesajYaz('hata', 'Hesabınız pasiftir.');
                    header("Location: " . rtrim($baseUrl, '/') . '/giris');
                    exit;
                }
                Oturum::yaz('kullanici_id', $kullanici['id']);
                Oturum::yaz('kullanici_adi', $kullanici['kullanici_adi']);
                Oturum::yaz('rol', $kullanici['rol']);
                header("Location: " . rtrim($baseUrl, '/') . '/yonetim');
                exit;
            } else {
                Oturum::mesajYaz('hata', 'Kullanıcı adı veya şifre hatalı.');
            }
        }
        header("Location: " . rtrim($baseUrl, '/') . '/giris');
        exit;
    }

    public function cikis() {
        Oturum::baslat();
        Oturum::kapat();
        $baseUrl = Env::al('APP_URL', rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'));
        header("Location: " . rtrim($baseUrl, '/') . '/giris');
        exit;
    }
}

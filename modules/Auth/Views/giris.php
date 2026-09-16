<?php $baseUrl = Env::al('APP_URL', rtrim(dirname($_SERVER['SCRIPT_NAME']), '/')); ?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Giriş Yap - Modulix-CMS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/install.css">
    <style>
        body { display:flex; align-items:center; justify-content:center; min-height:100vh; background:#0b0f19; }
        .login-card { background:#151d30; border:1px solid rgba(255,255,255,0.08); border-radius:20px; padding:40px; width:100%; max-width:400px; box-shadow:0 25px 50px -12px rgba(0,0,0,0.6); }
    </style>
</head>
<body>
    <div class="login-card">
        <div style="text-align: center; margin-bottom: 28px;">
            <div style="width:54px; height:54px; background:linear-gradient(135deg, #6366f1 0%, #4338ca 100%); border-radius:14px; display:inline-flex; align-items:center; justify-content:center; font-size:24px; color:#fff; margin-bottom:12px;">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <h2 style="margin: 0; font-size: 22px; color:#fff;">Modulix-CMS</h2>
            <p style="color: #94a3b8; font-size: 13px; margin-top: 4px;">Enterprise Command Center</p>
        </div>

        <?php $hata = Oturum::mesajAlVeSil('hata'); if ($hata): ?>
            <div style="background: rgba(244, 63, 94, 0.15); color: #f43f5e; border:1px solid rgba(244, 63, 94, 0.3); padding: 12px; border-radius: 10px; font-size: 13px; margin-bottom: 20px;">
                <i class="fa-solid fa-triangle-exclamation"></i> <?= htmlspecialchars($hata) ?>
            </div>
        <?php endif; ?>

        <form action="<?= $baseUrl ?>/giris-yap" method="POST">
            <div class="form-group">
                <label><i class="fa-solid fa-user"></i> Kullanıcı Adı veya E-posta</label>
                <input type="text" name="kullanici_adi_veya_eposta" class="form-control" required autofocus>
            </div>
            <div class="form-group">
                <label><i class="fa-solid fa-lock"></i> Şifre</label>
                <input type="password" name="sifre" class="form-control" required>
            </div>
            <button type="submit" name="giris" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 14px; margin-top: 10px;">Giriş Yap</button>
        </form>
    </div>
</body>
</html>

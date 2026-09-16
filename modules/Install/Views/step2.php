<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Modulix-CMS - Veritabanı Ayarları</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: sans-serif; background: #0f172a; color: #f8fafc; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .box { background: #151d30; border: 1px solid rgba(255,255,255,0.1); padding: 32px; border-radius: 16px; width: 450px; }
        .form-group { margin-bottom: 14px; }
        label { display: block; font-size: 12px; color: #94a3b8; margin-bottom: 4px; }
        input { width: 100%; background: #0b0f19; border: 1px solid #243049; color: #fff; padding: 10px; border-radius: 6px; box-sizing: border-box; }
        .btn { width: 100%; background: #6366f1; color: #fff; padding: 12px; border-radius: 8px; border: none; font-weight: bold; cursor: pointer; margin-top: 10px; }
    </style>
</head>
<body>
<div class="box">
    <h2><i class="fa-solid fa-database" style="color:#6366f1;"></i> Veritabanı ve Yönetici</h2>
    <form action="<?= $baseUrl ?>/install/kur" method="POST">
        <div class="form-group">
            <label>Veritabanı Sunucusu</label>
            <input type="text" name="db_host" value="localhost" required>
        </div>
        <div class="form-group">
            <label>Veritabanı Adı</label>
            <input type="text" name="db_name" value="modulix_db" required>
        </div>
        <div class="form-group">
            <label>Veritabanı Kullanıcı Adı</label>
            <input type="text" name="db_user" value="root" required>
        </div>
        <div class="form-group">
            <label>Veritabanı Şifresi</label>
            <input type="password" name="db_pass" placeholder="Yoksa boş bırakın">
        </div>
        <hr style="border-color:#243049; margin:16px 0;">
        <div class="form-group">
            <label>Yönetici (Admin) Kullanıcı Adı</label>
            <input type="text" name="admin_user" value="admin" required>
        </div>
        <div class="form-group">
            <label>Yönetici E-posta</label>
            <input type="email" name="admin_email" value="admin@site.com" required>
        </div>
        <div class="form-group">
            <label>Yönetici Şifresi</label>
            <input type="password" name="admin_pass" required placeholder="Güçlü bir şifre belirleyin">
        </div>
        <button type="submit" class="btn"><i class="fa-solid fa-check"></i> Kurulumu Tamamla ve Kilitle</button>
    </form>
</div>
</body>
</html>

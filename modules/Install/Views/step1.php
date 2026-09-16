<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Modulix-CMS - Kurulum Sihirbazı</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: sans-serif; background: #0f172a; color: #f8fafc; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .box { background: #151d30; border: 1px solid rgba(255,255,255,0.1); padding: 32px; border-radius: 16px; width: 450px; }
        .btn { display: inline-block; width: 100%; text-align: center; background: #6366f1; color: #fff; padding: 12px; border-radius: 8px; text-decoration: none; font-weight: bold; margin-top: 20px; box-sizing: border-box; }
        .check-item { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid rgba(255,255,255,0.05); }
    </style>
</head>
<body>
<div class="box">
    <h2><i class="fa-solid fa-cube" style="color:#6366f1;"></i> Modulix-CMS Kurulumu</h2>
    <p style="color:#94a3b8; font-size:14px;">Sistem gereksinimleri kontrol ediliyor...</p>

    <div class="check-item">
        <span>PHP Sürümü (>= 8.2)</span>
        <strong style="color: <?= version_compare(PHP_VERSION, '8.2.0', '>=') ? '#10b981' : '#f43f5e' ?>"><?= PHP_VERSION ?></strong>
    </div>
    <div class="check-item">
        <span>PDO MySQL Desteği</span>
        <strong style="color: <?= extension_loaded('pdo_mysql') ? '#10b981' : '#f43f5e' ?>"><?= extension_loaded('pdo_mysql') ? 'Aktif' : 'Pasif' ?></strong>
    </div>
    <div class="check-item">
        <span>ZipArchive Desteği</span>
        <strong style="color: <?= class_exists('ZipArchive') ? '#10b981' : '#f43f5e' ?>"><?= class_exists('ZipArchive') ? 'Aktif' : 'Pasif' ?></strong>
    </div>

    <a href="<?= $baseUrl ?>/install/step2" class="btn">Devam Et <i class="fa-solid fa-arrow-right"></i></a>
</div>
</body>
</html>

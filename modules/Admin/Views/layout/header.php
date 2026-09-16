<?php
if (file_exists(__DIR__ . '/../../../../core/Lang.php')) {
    require_once __DIR__ . '/../../../../core/Lang.php';
}
if (file_exists(__DIR__ . '/../../../../core/License.php')) {
    require_once __DIR__ . '/../../../../core/License.php';
}

if (class_exists('License') && !License::kontrolEt()) {
    die("<!DOCTYPE html><html><head><meta charset='utf-8'><title>Killswitch</title></head><body style='font-family:sans-serif;text-align:center;padding-top:100px;background:#0b0f19;color:#fff;'><h1 style='color:#ef4444;'>⚠️ Kurumsal Lisans Doğrulanamadı</h1><p>Bu sistemin telif hakları korunmaktadır.</p></body></html>");
}

$rol = Oturum::al('rol');
$dil = $_SESSION['lang'] ?? 'tr';
$baseUrl = rtrim(Env::al('APP_URL', rtrim(dirname($_SERVER['SCRIPT_NAME']), '/')), '/');
?>
<!DOCTYPE html>
<html lang="<?= $dil ?>">
<head>
    <meta charset="UTF-8">
    <title>Modulix-CMS Command Center</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/admin.css">
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-brand"><i class="fa-solid fa-shield-halved"></i> <span>Modulix-CMS</span></div>
        <ul class="sidebar-menu">
            <li><a href="<?= $baseUrl ?>/yonetim"><i class="fa-solid fa-chart-pie"></i> <?= __('ozet_kontrol') ?></a></li>
            <li><a href="<?= $baseUrl ?>/yonetim/kullanicilar"><i class="fa-solid fa-users"></i> <?= __('kullanici_yonetimi') ?></a></li>
            <li><a href="<?= $baseUrl ?>/yonetim/logo-yonetimi"><i class="fa-solid fa-image"></i> <?= __('logo_yonetimi') ?></a></li>
            <li><a href="<?= $baseUrl ?>/yonetim/ayarlar"><i class="fa-solid fa-sliders"></i> <?= __('ayarlar') ?></a></li>
            <li><a href="<?= $baseUrl ?>/yonetim/guvenlik"><i class="fa-solid fa-shield-dog"></i> <?= __('waf_guvenlik') ?></a></li>
            <li><a href="<?= $baseUrl ?>/yonetim/dosya-izinleri"><i class="fa-solid fa-file-shield"></i> <?= __('chmod_izinleri') ?></a></li>
            <li><a href="<?= $baseUrl ?>/yonetim/hata-loglari"><i class="fa-solid fa-bug"></i> <?= __('hata_loglari') ?></a></li>
            <li><a href="<?= $baseUrl ?>/yonetim/audit-loglari"><i class="fa-solid fa-clipboard-list"></i> <?= __('audit_loglari') ?></a></li>
            <li><a href="<?= $baseUrl ?>/yonetim/yedekler"><i class="fa-solid fa-database"></i> <?= __('veritabani_bakim') ?></a></li>
            <li><a href="<?= $baseUrl ?>/yonetim/dagitim"><i class="fa-solid fa-box-archive"></i> <?= __('dagitim_merkezi') ?></a></li>
        </ul>
        <div class="sidebar-footer"><a href="<?= $baseUrl ?>/cikis"><i class="fa-solid fa-right-from-bracket"></i> <?= __('oturumu_kapat') ?></a></div>
    </div>

    <div class="main-wrapper">
        <header class="top-header">
            <h1><?= __('enterprise_center') ?></h1>
            <div class="header-actions">
                <div class="lang-switch">
                    <a href="?lang=tr" class="<?= $dil === 'tr' ? 'active' : '' ?>">TR</a>
                    <a href="?lang=en" class="<?= $dil === 'en' ? 'active' : '' ?>">EN</a>
                </div>
                <div class="user-badge"><i class="fa-solid fa-user-shield"></i> Yetkili: <?= htmlspecialchars($rol ?? 'admin') ?></div>
            </div>
        </header>
        <div class="content-body">
            <?php if ($b = Oturum::mesajAlVeSil('basari')): ?>
                <div style="background: rgba(16, 185, 129, 0.15); color: #10b981; border:1px solid rgba(16, 185, 129, 0.3); padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-weight: 500;">
                    <i class="fa-solid fa-circle-check"></i> <?= htmlspecialchars($b) ?>
                </div>
            <?php endif; ?>
            <?php if ($h = Oturum::mesajAlVeSil('hata')): ?>
                <div style="background: rgba(244, 63, 94, 0.15); color: #f43f5e; border:1px solid rgba(244, 63, 94, 0.3); padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-weight: 500;">
                    <i class="fa-solid fa-triangle-exclamation"></i> <?= htmlspecialchars($h) ?>
                </div>
            <?php endif; ?>

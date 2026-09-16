<?php require_once __DIR__ . '/layout/header.php'; ?>
<div class="card">
    <h2><i class="fa-solid fa-heart-pulse" style="color:#10b981;"></i> Otonom Self-Healing & Çekirdek Bakımı</h2>
    <p style="color:var(--text-muted); font-size:14px; margin-bottom:20px;">
        Sistem eksik tabloları, bozulan yapılandırmaları ve .htaccess yönlendirmelerini otonom tarayarak tek tıkla onarır.
    </p>

    <div style="display:flex; gap:16px;">
        <a href="<?= $baseUrl ?>/yonetim/sistemi-onar" class="btn btn-primary" style="background:#10b981; border-color:#10b981;">
            <i class="fa-solid fa-wand-magic-sparkles"></i> Self-Healing Taraması Başlat & Onar
        </a>
        <a href="<?= $baseUrl ?>/yonetim/optimize" class="btn btn-primary">
            <i class="fa-solid fa-gauge-high"></i> Veritabanı Tablolarını Optimize Et
        </a>
        <a href="<?= $baseUrl ?>/yonetim/yedek-indir" class="btn btn-primary" style="background:#6366f1;">
            <i class="fa-solid fa-file-export"></i> Canlı SQL Veritabanı Yedeği İndir
        </a>
    </div>
</div>
<?php require_once __DIR__ . '/layout/footer.php'; ?>

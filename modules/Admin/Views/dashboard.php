<?php require_once __DIR__ . '/layout/header.php'; ?>
<div class="card">
    <h2><?= __('dashboard_baslik') ?></h2>
    <div class="stats-grid">
        <div class="stat-card"><h3><?= __('toplam_uye') ?></h3><p class="number"><?= $metrikler['toplam_uye'] ?></p></div>
        <div class="stat-card"><h3><?= __('engelli_ip') ?></h3><p class="number"><?= $metrikler['engelli_ip'] ?></p></div>
        <div class="stat-card"><h3><?= __('saldiri_sayisi') ?></h3><p class="number"><?= $metrikler['saldiri_sayisi'] ?></p></div>
        <div class="stat-card"><h3><?= __('php_surum') ?></h3><p class="number" style="font-size:20px;"><?= $metrikler['php_surum'] ?></p></div>
    </div>
</div>
<?php require_once __DIR__ . '/layout/footer.php'; ?>

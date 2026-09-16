<?php require_once __DIR__ . '/layout/header.php'; ?>
<div class="card">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h2>PHP Hata Günlükleri (error.log)</h2>
        <a href="<?= $baseUrl ?>/yonetim/hata-temizle" class="btn" style="background:rgba(244, 63, 94, 0.15); color:#f43f5e;">Logları Temizle</a>
    </div>
    <div style="background:#090d16; color:#38bdf8; padding:16px; border-radius:12px; font-family:monospace; max-height:400px; overflow-y:auto;">
        <?php foreach ($loglar as $l): ?><div><?= htmlspecialchars($l) ?></div><?php endforeach; ?>
    </div>
</div>
<?php require_once __DIR__ . '/layout/footer.php'; ?>

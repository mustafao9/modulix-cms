<?php require_once __DIR__ . '/layout/header.php'; ?>
<div class="card" style="max-width:600px;">
    <h2>Logo Yönetimi</h2>
    <?php if (!empty($ayarlar['site_logo'])): ?>
        <p>Mevcut Logo: <img src="<?= $baseUrl ?>/uploads/<?= htmlspecialchars($ayarlar['site_logo']) ?>" height="40"></p>
    <?php endif; ?>
    <form action="<?= $baseUrl ?>/yonetim/logo-kaydet" method="POST" enctype="multipart/form-data">
        <div class="form-group"><label>Yeni Görsel Yükle</label><input type="file" name="site_logo" class="form-control" required accept="image/*"></div>
        <button type="submit" class="btn btn-primary">Yükle ve Güncelle</button>
    </form>
</div>
<?php require_once __DIR__ . '/layout/footer.php'; ?>

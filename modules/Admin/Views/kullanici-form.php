<?php require_once __DIR__ . '/layout/header.php'; ?>
<div class="card" style="max-width:600px;">
    <h2><?= $islem === 'ekle' ? 'Yeni Kullanıcı Ekle' : 'Kullanıcı Düzenle' ?></h2>
    <form action="<?= $baseUrl ?>/yonetim/kullanici-<?= $islem === 'ekle' ? 'ekle-kaydet' : 'duzenle-kaydet/' . ($kullanici['id'] ?? '') ?>" method="POST">
        <div class="form-group"><label>Kullanıcı Adı</label><input type="text" name="kullanici_adi" class="form-control" value="<?= htmlspecialchars($kullanici['kullanici_adi'] ?? '') ?>" required></div>
        <div class="form-group"><label>E-posta</label><input type="email" name="eposta" class="form-control" value="<?= htmlspecialchars($kullanici['eposta'] ?? '') ?>" required></div>
        <div class="form-group"><label>Şifre <?= $islem === 'duzenle' ? '(Boş bırakırsanız değişmez)' : '' ?></label><input type="password" name="sifre" class="form-control" <?= $islem === 'ekle' ? 'required' : '' ?>></div>
        <div class="form-group">
            <label>Rol</label>
            <select name="rol" class="form-control">
                <option value="admin" <?= ($kullanici['rol'] ?? '') === 'admin' ? 'selected' : '' ?>>Admin</option>
                <option value="super_admin" <?= ($kullanici['rol'] ?? '') === 'super_admin' ? 'selected' : '' ?>>Super Admin</option>
                <option value="uye" <?= ($kullanici['rol'] ?? '') === 'uye' ? 'selected' : '' ?>>Üye</option>
            </select>
        </div>
        <div class="form-group">
            <label>Hesap Durumu</label>
            <select name="hesap_durumu" class="form-control">
                <option value="aktif" <?= ($kullanici['hesap_durumu'] ?? '') === 'aktif' ? 'selected' : '' ?>>Aktif</option>
                <option value="pasif" <?= ($kullanici['hesap_durumu'] ?? '') === 'pasif' ? 'selected' : '' ?>>Pasif</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Kaydet</button>
    </form>
</div>
<?php require_once __DIR__ . '/layout/footer.php'; ?>

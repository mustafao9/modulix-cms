<?php require_once __DIR__ . '/layout/header.php'; ?>
<div class="card">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h2><?= __('kullanici_yonetimi') ?></h2>
        <a href="<?= $baseUrl ?>/yonetim/kullanici-ekle" class="btn btn-primary">➕ Yeni Kullanıcı</a>
    </div>
    <table>
        <thead><tr><th>ID</th><th>Kullanıcı Adı</th><th>E-posta</th><th>Rol</th><th>Durum</th><th>İşlem</th></tr></thead>
        <tbody>
            <?php foreach ($kullanicilar as $u): ?>
            <tr>
                <td>#<?= $u['id'] ?></td>
                <td><strong><?= htmlspecialchars($u['kullanici_adi']) ?></strong></td>
                <td><?= htmlspecialchars($u['eposta']) ?></td>
                <td><?= htmlspecialchars($u['rol']) ?></td>
                <td><?= htmlspecialchars($u['hesap_durumu']) ?></td>
                <td>
                    <a href="<?= $baseUrl ?>/yonetim/kullanici-duzenle/<?= $u['id'] ?>" style="color:var(--primary); margin-right:10px;">Düzenle</a>
                    <a href="<?= $baseUrl ?>/yonetim/kullanici-sil/<?= $u['id'] ?>" onclick="return confirm('Emin misiniz?');" style="color:#f43f5e;">Sil</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require_once __DIR__ . '/layout/footer.php'; ?>

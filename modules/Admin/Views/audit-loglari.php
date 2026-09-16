<?php require_once __DIR__ . '/layout/header.php'; ?>
<div class="card">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h2>Denetim (Audit) Günlükleri</h2>
        <a href="<?= $baseUrl ?>/yonetim/audit-temizle" class="btn" style="background:rgba(244, 63, 94, 0.15); color:#f43f5e;">Temizle</a>
    </div>
    <table>
        <thead><tr><th>Kullanıcı</th><th>İşlem</th><th>Detay</th><th>IP</th><th>Tarih</th></tr></thead>
        <tbody>
            <?php foreach ($auditler as $a): ?>
            <tr>
                <td><strong><?= htmlspecialchars($a['kullanici_adi']) ?></strong></td>
                <td><span style="background:rgba(99, 102, 241, 0.15); color:#818cf8; padding:4px 8px; border-radius:6px; font-weight:600; font-size:12px;"><?= htmlspecialchars($a['islem']) ?></span></td>
                <td><?= htmlspecialchars($a['detay']) ?></td>
                <td><code><?= htmlspecialchars($a['ip']) ?></code></td>
                <td><?= htmlspecialchars($a['tarih']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require_once __DIR__ . '/layout/footer.php'; ?>

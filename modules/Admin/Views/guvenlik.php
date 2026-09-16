<?php require_once __DIR__ . '/layout/header.php'; ?>
<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px; margin-bottom: 24px;">
    <div class="card">
        <h2><i class="fa-solid fa-ban" style="color:#f43f5e;"></i> IP Engelle</h2>
        <form action="<?= $baseUrl ?>/yonetim/ip-engelle" method="POST">
            <div class="form-group"><label>IP Adresi</label><input type="text" name="ip" class="form-control" required placeholder="Örn: 192.168.1.50"></div>
            <div class="form-group"><label>Sebep</label><input type="text" name="sebep" class="form-control" required placeholder="Şüpheli Giriş"></div>
            <button type="submit" class="btn" style="background:#f43f5e; color:#fff; width:100%; justify-content:center;">Karalisteye Ekle</button>
        </form>
    </div>
    <div class="card">
        <h2>Engellenen IP Adresleri</h2>
        <table>
            <thead><tr><th>IP</th><th>Sebep</th><th>Tarih</th><th>İşlem</th></tr></thead>
            <tbody>
                <?php foreach ($engellenenler as $e): ?>
                <tr><td><code><?= htmlspecialchars($e['ip']) ?></code></td><td><?= htmlspecialchars($e['sebep']) ?></td><td><?= htmlspecialchars($e['tarih']) ?></td><td><a href="<?= $baseUrl ?>/yonetim/ip-kaldir/<?= $e['id'] ?>" style="color:#f43f5e;">Kaldır</a></td></tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<div class="card">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
        <h2>WAF & Saldırı Kayıtları</h2>
        <a href="<?= $baseUrl ?>/yonetim/saldirilari-temizle" class="btn" style="background:rgba(244, 63, 94, 0.15); color:#f43f5e;">Temizle</a>
    </div>
    <table>
        <thead><tr><th>IP</th><th>Saldırı Türü</th><th>URI</th><th>Tarih</th></tr></thead>
        <tbody>
            <?php foreach ($saldirilar as $s): ?>
            <tr><td><code><?= htmlspecialchars($s['ip']) ?></code></td><td><?= htmlspecialchars($s['saldiri_turu']) ?></td><td><code><?= htmlspecialchars($s['istek_yolu']) ?></code></td><td><?= htmlspecialchars($s['tarih']) ?></td></tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require_once __DIR__ . '/layout/footer.php'; ?>

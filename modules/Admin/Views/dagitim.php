<?php 
require_once __DIR__ . '/layout/header.php'; 
require_once __DIR__ . '/../../../core/ReleaseDiff.php';
$analiz = ReleaseDiff::analizEt();

$cleanPaketler = array_filter($paketler ?? [], function($p) { return strpos($p['ad'], '-clean') !== false; });
$fullPaketler = array_filter($paketler ?? [], function($p) { return strpos($p['ad'], '-clean') === false; });
?>

<div class="card">
    <h2><i class="fa-solid fa-box-archive"></i> Akıllı Dağıtım Merkezi (Release Builder)</h2>
    <p style="color:var(--text-muted); font-size:14px; margin-bottom:16px;">
        Sistem yapılan değişiklikleri analiz eder ve semantik versiyon etiketini otomatize eder.
    </p>

    <!-- Kod Analiz Kartı -->
    <div style="background:#0b0f19; border:1px solid var(--border); padding:16px; border-radius:10px; margin-bottom:20px; display:flex; align-items:center; justify-content:space-between;">
        <div>
            <div style="font-size:12px; color:var(--text-muted);">OTOMATİK KOD DEĞİŞİKLİK ANALİZİ</div>
            <strong style="font-size:16px; color:#fff;"><?= $analiz['etiket'] ?></strong>
        </div>
        <span class="badge <?= $analiz['tip'] === 'minor' ? 'badge-warning' : ($analiz['tip'] === 'patch' ? 'badge-info' : 'badge-success') ?>">
            Otomatik Sürüm Önerisi: <?= $analiz['oneryan_versiyon'] ?> (<?= $analiz['degisen_sayisi'] ?> Dosya)
        </span>
    </div>

    <form action="<?= $baseUrl ?>/yonetim/dagitim-uret" method="POST" style="background:#0b0f19; border:1px solid var(--border); padding:20px; border-radius:12px;">
        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:16px; margin-bottom:16px;">
            <div class="form-group">
                <label>Sürüm Etiketi (Otomatik Hesaplandı)</label>
                <input type="text" name="versiyon" class="form-control" value="<?= htmlspecialchars($analiz['oneryan_versiyon']) ?>" required>
            </div>
            <div class="form-group">
                <label>Paket Tipi</label>
                <select name="paket_tipi" class="form-control" style="background:#0b0f19; color:#fff;">
                    <option value="clean">Son Kullanıcı Paketi (Install Sihirbazlı Temiz ZIP)</option>
                    <option value="full">Geliştirici Ana Sürüm Yedeği (Tüm Veriler Dahil)</option>
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">
            <i class="fa-solid fa-rocket"></i> Sürüm Paketini Derle Ve Arşive Ekle
        </button>
    </form>
</div>

<!-- TABLO 1: SON KULLANICI SÜRÜMLERİ -->
<div class="card">
    <h2><i class="fa-solid fa-users"></i> Son Kullanıcı Dağıtım Paketleri (Clean Releases)</h2>
    <p style="color:var(--text-muted); font-size:13px; margin-bottom:12px;">GitHub veya sitenizde yayınlanmaya hazır, veritabanı verilerinden arındırılmış temiz paketler.</p>
    <?php if (!empty($cleanPaketler)): ?>
        <table>
            <thead>
                <tr>
                    <th>Paket Adı</th>
                    <th>Tür</th>
                    <th>Boyut</th>
                    <th>Tarih</th>
                    <th>İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cleanPaketler as $p): ?>
                <tr>
                    <td><strong><i class="fa-solid fa-file-zipper" style="color:#10b981; margin-right:8px;"></i> <?= htmlspecialchars($p['ad']) ?></strong></td>
                    <td><span class="badge badge-success"><i class="fa-solid fa-sparkles"></i> Saf Kurulum</span></td>
                    <td><span class="badge badge-info"><?= $p['boyut'] ?></span></td>
                    <td><?= $p['tarih'] ?></td>
                    <td>
                        <a href="<?= $baseUrl ?>/yonetim/dagitim-indir?dosya=<?= urlencode($p['ad']) ?>" class="btn btn-primary" style="padding:4px 10px; font-size:12px;"><i class="fa-solid fa-download"></i> İndir</a>
                        <a href="<?= $baseUrl ?>/yonetim/dagitim-sil?dosya=<?= urlencode($p['ad']) ?>" onclick="return confirm('Silmek istediğinize emin misiniz?');" class="btn btn-danger" style="padding:4px 10px; font-size:12px;"><i class="fa-solid fa-trash"></i> Sil</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p style="color:var(--text-muted); font-size:13px;">Henüz üretilmiş bir son kullanıcı paketi yok.</p>
    <?php endif; ?>
</div>

<!-- TABLO 2: GELİŞTİRİCİ ANA SÜRÜM YEDEKLERİ -->
<div class="card">
    <h2><i class="fa-solid fa-code-branch"></i> Geliştirici Ana Sürüm Yedekleri (Dev Snapshots)</h2>
    <p style="color:var(--text-muted); font-size:13px; margin-bottom:12px;">Tüm test verileri, loglar ve yapılandırmalarla birlikte yerel ortamınızın tam anlık görüntüsü.</p>
    <?php if (!empty($fullPaketler)): ?>
        <table>
            <thead>
                <tr>
                    <th>Paket Adı</th>
                    <th>Tür</th>
                    <th>Boyut</th>
                    <th>Tarih</th>
                    <th>İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($fullPaketler as $p): ?>
                <tr>
                    <td><strong><i class="fa-solid fa-box-archive" style="color:#6366f1; margin-right:8px;"></i> <?= htmlspecialchars($p['ad']) ?></strong></td>
                    <td><span class="badge badge-warning"><i class="fa-solid fa-database"></i> Tam Geliştirici Yedeği</span></td>
                    <td><span class="badge badge-info"><?= $p['boyut'] ?></span></td>
                    <td><?= $p['tarih'] ?></td>
                    <td>
                        <a href="<?= $baseUrl ?>/yonetim/dagitim-indir?dosya=<?= urlencode($p['ad']) ?>" class="btn btn-primary" style="padding:4px 10px; font-size:12px;"><i class="fa-solid fa-download"></i> İndir</a>
                        <a href="<?= $baseUrl ?>/yonetim/dagitim-sil?dosya=<?= urlencode($p['ad']) ?>" onclick="return confirm('Silmek istediğinize emin misiniz?');" class="btn btn-danger" style="padding:4px 10px; font-size:12px;"><i class="fa-solid fa-trash"></i> Sil</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p style="color:var(--text-muted); font-size:13px;">Henüz üretilmiş bir geliştirici yedeği yok.</p>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/layout/footer.php'; ?>

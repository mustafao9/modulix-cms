<?php require_once __DIR__ . '/layout/header.php'; ?>
<div class="card">
    <h2><i class="fa-solid fa-sliders"></i> Sistem & Entegrasyon Ayarları</h2>
    <form action="<?= $baseUrl ?>/yonetim/ayarlar-kaydet" method="POST" style="margin-top:20px;">
        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px;">
            <div class="form-group">
                <label>Site Başlığı</label>
                <input type="text" name="site_baslik" class="form-control" value="<?= htmlspecialchars($ayarlar['site_baslik'] ?? 'Modulix-CMS') ?>">
            </div>
            <div class="form-group">
                <label>Yönetici E-posta (Bildirimler İçin)</label>
                <input type="email" name="admin_eposta" class="form-control" value="<?= htmlspecialchars($ayarlar['admin_eposta'] ?? 'root@abc.com') ?>">
            </div>
        </div>
        
        <hr style="border-color:var(--border); margin:24px 0;">
        <h3 style="margin-bottom:16px; font-size:15px;"><i class="fa-solid fa-envelope-shield"></i> SMTP E-Posta Sunucusu Yapılandırması</h3>
        
        <div style="display:grid; grid-template-columns: 2fr 1fr 2fr 2fr 1fr; gap:12px; margin-bottom:16px;">
            <div class="form-group">
                <label>SMTP Host</label>
                <input type="text" name="smtp_host" class="form-control" placeholder="mail.site.com" value="<?= htmlspecialchars($ayarlar['smtp_host'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Port</label>
                <input type="text" name="smtp_port" class="form-control" placeholder="587" value="<?= htmlspecialchars($ayarlar['smtp_port'] ?? '587') ?>">
            </div>
            <div class="form-group">
                <label>SMTP Kullanıcı</label>
                <input type="text" name="smtp_user" class="form-control" placeholder="info@site.com" value="<?= htmlspecialchars($ayarlar['smtp_user'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>SMTP Şifre</label>
                <input type="password" name="smtp_pass" class="form-control" placeholder="••••••••" value="<?= htmlspecialchars($ayarlar['smtp_pass'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Güvenlik</label>
                <select name="smtp_secure" class="form-control" style="background:#0b0f19; color:#fff;">
                    <option value="tls" <?= ($ayarlar['smtp_secure'] ?? '') === 'tls' ? 'selected' : '' ?>>TLS</option>
                    <option value="ssl" <?= ($ayarlar['smtp_secure'] ?? '') === 'ssl' ? 'selected' : '' ?>>SSL</option>
                </select>
            </div>
        </div>

        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Ayarları Kaydet</button>
    </form>
</div>

<!-- TEST MAİL KARTI -->
<div class="card">
    <h2><i class="fa-solid fa-paper-plane"></i> SMTP Bağlantısını Test Et</h2>
    <form action="<?= $baseUrl ?>/yonetim/smtp-test" method="POST" style="display:flex; gap:12px; margin-top:12px; align-items:flex-end;">
        <div class="form-group" style="flex:1; margin:0;">
            <label>Test E-posta Adresi</label>
            <input type="email" name="test_email" class="form-control" required placeholder="ornek@domain.com">
        </div>
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-vial"></i> Test Maili Gönder</button>
    </form>
</div>
<?php require_once __DIR__ . '/layout/footer.php'; ?>

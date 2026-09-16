<?php require_once __DIR__ . '/layout/header.php'; ?>
<div class="card">
    <h2><i class="fa-solid fa-puzzle-piece" style="color:#6366f1;"></i> Güvenli Eklenti Motoru (Safe-Mode Hooks)</h2>
    <p style="color:var(--text-muted); font-size:14px; margin-bottom:20px;">
        Sisteme eklenen eklentiler izolasyon altında çalışır. Hatalı veya çöken eklentiler siteyi kilitlemek yerine otonom olarak karantinaya alınır.
    </p>

    <?php if (!empty($karantina)): ?>
        <div style="background:rgba(244,63,94,0.1); border:1px solid #f43f5e; padding:16px; border-radius:10px; margin-bottom:20px;">
            <h3 style="color:#f43f5e; margin-top:0;"><i class="fa-solid fa-triangle-exclamation"></i> Karantinaya Alınan Eklentiler</h3>
            <ul>
                <?php foreach ($karantina as $k): ?>
                    <li style="color:#fff;"><strong><?= htmlspecialchars($k) ?></strong> - (Çökme Engellendi, Pasife Alındı)</li>
                <?php foreach; ?>
            </ul>
        </div>
    <?php else: ?>
        <div style="background:#0b0f19; border:1px solid var(--border); padding:16px; border-radius:10px; color:#10b981;">
            <i class="fa-solid fa-shield-check"></i> Tüm eklentiler güvenli modda sorunsuz çalışıyor. Karantinada eklenti yok.
        </div>
    <?php endif; ?>
</div>
<?php require_once __DIR__ . '/layout/footer.php'; ?>

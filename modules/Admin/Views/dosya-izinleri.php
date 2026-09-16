<?php 
require_once __DIR__ . '/layout/header.php'; 
$projectBase = rtrim(dirname(dirname(dirname(dirname($_SERVER['SCRIPT_NAME'])))), '/');
if ($projectBase === '') $projectBase = '/modulix-cms';
?>
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2>Dosya İzinleri (CHMOD) Yönetimi</h2>
        <form action="<?= $projectBase ?>/yonetim/dosya-izinleri/duzelt" method="POST" style="margin: 0;">
            <button type="submit" class="btn btn-primary" style="background: #2563eb; color: white; border: none; padding: 10px 16px; border-radius: 6px; cursor: pointer; font-weight: 600;">
                <i class="fa-solid fa-wand-magic-sparkles"></i> İzinleri Otomatik Onar (Auto-Fix)
            </button>
        </form>
    </div>

    <?php if ($mesaj = Oturum::mesajAlVeSil('basari')): ?>
        <div style="background: #065f46; color: #d1fae5; padding: 12px; border-radius: 6px; margin-bottom: 15px;"><?= $mesaj ?></div>
    <?php endif; ?>

    <?php if ($mesaj = Oturum::mesajAlVeSil('hata')): ?>
        <div style="background: #991b1b; color: #fee2e2; padding: 12px; border-radius: 6px; margin-bottom: 15px;"><?= $mesaj ?></div>
    <?php endif; ?>

    <table>
        <thead><tr><th>Dizin / Dosya</th><th>Önerilen İzin</th><th>Mevcut İzin</th></tr></thead>
        <tbody>
            <?php foreach ($dosyalar as $ad => $yol): ?>
            <tr>
                <td><?= $ad ?></td>
                <td><code><?= strpos($yol, '.env') !== false || strpos($yol, '.lock') !== false ? '644' : '755' ?></code></td>
                <td><code><?= file_exists($yol) ? substr(sprintf('%o', fileperms($yol)), -3) : 'Yok' ?></code></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require_once __DIR__ . '/layout/footer.php'; ?>

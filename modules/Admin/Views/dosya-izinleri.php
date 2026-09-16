<?php require_once __DIR__ . '/layout/header.php'; ?>
<div class="card">
    <h2>Dosya İzinleri (CHMOD)</h2>
    <table>
        <thead><tr><th>Dizin / Dosya</th><th>Mevcut İzin</th></tr></thead>
        <tbody>
            <?php foreach ($dosyalar as $ad => $yol): ?>
            <tr><td><?= $ad ?></td><td><code><?= file_exists($yol) ? substr(sprintf('%o', fileperms($yol)), -3) : 'Yok' ?></code></td></tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require_once __DIR__ . '/layout/footer.php'; ?>

<?php
// Modulix-CMS Otomatik Teşhis ve Hata Denetim Aracı
echo "=== MODULIX-CMS OTOMATİK TEŞHİS RAPORU ===\n\n";

$dizinler = ['core', 'modules'];
$hataSayisi = 0;

foreach ($dizinler as $dir) {
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            $filePath = $file->getRealPath();
            $output = [];
            $code = 0;
            exec("php -l " . escapeshellarg($filePath), $output, $code);
            if ($code !== 0) {
                echo "[SYNTAX ERROR] " . $filePath . "\n";
                foreach ($output as $line) echo "  " . $line . "\n";
                $hataSayisi++;
            }
        }
    }
}

if ($hataSayisi === 0) {
    echo "[BAŞARILI] Taranan tüm PHP dosyalarında (Core & Modules) sözdizimi hatası (Syntax Error) bulunamadı.\n";
} else {
    echo "\n[DİKKAT] Toplam {$hataSayisi} adet dosyada hata tespit edildi.\n";
}

echo "\nLog Dosyası Kontrolü (core/error.log):\n";
$logFile = __DIR__ . '/core/error.log';
if (file_exists($logFile)) {
    $loglar = file($logFile);
    $sonLoglar = array_slice($loglar, -5);
    foreach ($sonLoglar as $l) {
        echo "  " . trim($l) . "\n";
    }
} else {
    echo "  error.log bulunamadı veya boş.\n";
}
echo "\n===========================================\n";

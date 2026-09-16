<?php
class ReleaseDiff {
    public static function analizEt() {
        $lastHashFile = __DIR__ . '/../releases/last_build_hash.json';
        $currentFiles = self::dosyaTreeTarat(__DIR__ . '/../modules');
        
        // Mevcut son versiyonu releases klasöründen bul
        $sonVersiyon = self::sonVersiyonuBul();
        
        if (!file_exists($lastHashFile)) {
            return [
                'tip' => 'major', 
                'etiket' => 'İlk Sürüm Paketi', 
                'degisen_sayisi' => count($currentFiles),
                'oneryan_versiyon' => 'v1.0.0'
            ];
        }

        $oldFiles = json_decode(file_get_contents($lastHashFile), true) ?: [];
        $yeniSayfaEklendi = false;
        $degisenKodSayisi = 0;

        foreach ($currentFiles as $dosya => $hash) {
            if (!isset($oldFiles[$dosya])) {
                $yeniSayfaEklendi = true;
                $degisenKodSayisi++;
            } elseif ($oldFiles[$dosya] !== $hash) {
                $degisenKodSayisi++;
            }
        }

        // SemVer Artırma Mantığı (vMAJOR.MINOR.PATCH)
        $vParcalari = explode('.', ltrim($sonVersiyon, 'v'));
        $major = (int)($vParcalari[0] ?? 1);
        $minor = (int)($vParcalari[1] ?? 0);
        $patch = (int)($vParcalari[2] ?? 0);

        if ($yeniSayfaEklendi || count($currentFiles) > count($oldFiles)) {
            $minor++; $patch = 0; // Yeni modül/sayfa eklendi -> v1.1.0
            $yeniV = "v{$major}.{$minor}.{$patch}";
            return ['tip' => 'minor', 'etiket' => 'Yeni Modül / Sayfa Eklendi (Minor)', 'degisen_sayisi' => $degisenKodSayisi, 'oneryan_versiyon' => $yeniV];
        } elseif ($degisenKodSayisi > 0) {
            $patch++; // Kod düzeltmesi / yaması -> v1.0.1
            $yeniV = "v{$major}.{$minor}.{$patch}";
            return ['tip' => 'patch', 'etiket' => 'Kod Güncellemesi / Hata Düzeltme (Patch)', 'degisen_sayisi' => $degisenKodSayisi, 'oneryan_versiyon' => $yeniV];
        }

        return ['tip' => 'none', 'etiket' => 'Değişiklik Algılanmadı', 'degisen_sayisi' => 0, 'oneryan_versiyon' => $sonVersiyon];
    }

    public static function hashKaydet() {
        $currentFiles = self::dosyaTreeTarat(__DIR__ . '/../modules');
        file_put_contents(__DIR__ . '/../releases/last_build_hash.json', json_encode($currentFiles));
    }

    private static function sonVersiyonuBul() {
        $releaseDir = __DIR__ . '/../releases/';
        if (!is_dir($releaseDir)) return 'v1.0.0';
        $files = glob($releaseDir . 'modulix-cms-v*.zip');
        if (empty($files)) return 'v1.0.0';
        
        $versiyonlar = [];
        foreach ($files as $f) {
            if (preg_match('/v(\d+\.\d+\.\d+)/', basename($f), $m)) {
                $versiyonlar[] = $m[1];
            }
        }
        if (empty($versiyonlar)) return 'v1.0.0';
        usort($versiyonlar, 'version_compare');
        return 'v' . end($versiyonlar);
    }

    private static function dosyaTreeTarat($dizin) {
        $sonuc = [];
        if (!is_dir($dizin)) return $sonuc;
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dizin));
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $sonuc[$file->getRealPath()] = md5_file($file->getRealPath());
            }
        }
        return $sonuc;
    }
}

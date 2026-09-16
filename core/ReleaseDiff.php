<?php
class ReleaseDiff {
    public static function analizEt() {
        $degisenDosyalar = [];
        $yeniSayfaEklendi = false;

        // Git deposu varsa git status üzerinden akıllı analiz yap
        if (is_dir(__DIR__ . '/../.git')) {
            $output = [];
            exec('git status --porcelain', $output);
            foreach ($output as $line) {
                $file = trim(substr($line, 3));
                $degisenDosyalar[] = $file;
                // Eğer Views veya modules dizininde yeni bir dosya eklendiyse (A veya ?? durumu)
                if ((substr($line, 0, 1) === 'A' || substr($line, 0, 2) === '??') && strpos($file, 'Views') !== false) {
                    $yeniSayfaEklendi = true;
                }
            }
        }

        $sayisi = count($degisenDosyalar);

        // Semantik versiyon ve tip belirleme
        if ($yeniSayfaEklendi || $sayisi > 10) {
            return [
                'etiket' => 'Yeni Sayfa / Modül Eklentisi Algılandı',
                'tip' => 'minor',
                'oneryan_versiyon' => '1.1.0',
                'degisen_sayisi' => max($sayisi, 1)
            ];
        } else {
            return [
                'etiket' => 'Standart Kod İyileştirmesi / Düzeltme',
                'tip' => 'patch',
                'oneryan_versiyon' => '1.0.1',
                'degisen_sayisi' => max($sayisi, 1)
            ];
        }
    }
}

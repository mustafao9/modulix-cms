<?php
// Geliştirici modunda hataları ekrana bas
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/Env.php';
require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/Oturum.php';
require_once __DIR__ . '/Audit.php';
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/Model.php';
require_once __DIR__ . '/Router.php';
require_once __DIR__ . '/Mail.php';
require_once __DIR__ . '/WAF.php';
require_once __DIR__ . '/SelfHealer.php';

// Önce çekirdek sınıflar yüklendi, şimdi modülleri dahil et
foreach (glob(__DIR__ . '/../modules/*/Controllers/*.php') as $controllerFile) {
    require_once $controllerFile;
}

foreach (glob(__DIR__ . '/../modules/*/Models/*.php') as $modelFile) {
    require_once $modelFile;
}

// Veritabanı ve Çekirdek Kontrolleri
try {
    WAF::calistir();
    SelfHealer::otomatikanalizVeOnar();
} catch (Throwable $e) {
    error_log("Bootstrap Hatası: " . $e->getMessage());
}

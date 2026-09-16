<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../core/error.log');

spl_autoload_register(function ($c) {
    if (file_exists($f = __DIR__ . '/../core/' . $c . '.php')) { require_once $f; return; }
    $mDir = __DIR__ . '/../modules';
    if (is_dir($mDir)) {
        foreach (scandir($mDir) as $m) {
            if ($m === '.' || $m === '..') continue;
            if (file_exists($f = $mDir . '/' . $m . '/Controllers/' . $c . '.php')) { require_once $f; return; }
            if (file_exists($f = $mDir . '/' . $m . '/Models/' . $c . '.php')) { require_once $f; return; }
        }
    }
});

Oturum::baslat();
if (class_exists('Lang')) {
    Lang::baslat();
}

// Modül Rotalarını Yükle
$mDir = __DIR__ . '/../modules';
if (is_dir($mDir)) {
    foreach (scandir($mDir) as $m) {
        if ($m === '.' || $m === '..') continue;
        if (file_exists($r = $mDir . '/' . $m . '/routes.php')) { require_once $r; }
    }
}

Router::calistir();

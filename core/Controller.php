<?php
require_once __DIR__ . '/Lang.php';

class Controller {
    public function view($viewName, $data = [], $module = '') {
        extract($data);
        if (!empty($module)) {
            $viewPath = __DIR__ . '/../modules/' . $module . '/Views/' . $viewName . '.php';
        } else {
            $viewPath = __DIR__ . '/../views/' . $viewName . '.php';
        }

        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            die("Görünüm dosyası bulunamadı: " . $viewPath);
        }
    }

    public function model($modelName, $module = '') {
        if (!empty($module)) {
            $modelPath = __DIR__ . '/../modules/' . $module . '/Models/' . $modelName . '.php';
            if (file_exists($modelPath)) {
                require_once $modelPath;
                return new $modelName();
            }
        }
        if (file_exists(__DIR__ . '/../models/' . $modelName . '.php')) {
            require_once __DIR__ . '/../models/' . $modelName . '.php';
            return new $modelName();
        }
        die("Model bulunamadı: " . $modelName);
    }
}

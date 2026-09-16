<?php
class Router {
    private static $rotalar = [];

    public static function ekle($method, $path, $handler) {
        $path = trim($path, '/');
        self::$rotalar[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'handler' => $handler
        ];
    }

    // Dinamik Modül Rota Yükleme Otomasyonu
    public static function modulleriYukle() {
        $moduleRoutes = glob(__DIR__ . '/../modules/*/routes.php');
        if ($moduleRoutes) {
            foreach ($moduleRoutes as $routeFile) {
                if (file_exists($routeFile)) {
                    require_once $routeFile;
                }
            }
        }
    }

    public static function calistir() {
        // İstek gelmeden önce tüm modül rotalarını otomatik olarak yükle
        self::modulleriYukle();

        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $scriptName = dirname($_SERVER['SCRIPT_NAME']);
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        $basePath = str_replace('\\', '/', $scriptName);
        if ($basePath !== '/') {
            $requestUri = substr($requestUri, strlen($basePath));
        }
        $requestUri = trim($requestUri, '/');

        foreach (self::$rotalar as $rota) {
            $routePattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', $rota['path']);
            $routePattern = "#^{$routePattern}$#";

            if ($rota['method'] === $requestMethod && preg_match($routePattern, $requestUri, $matches)) {
                array_shift($matches);

                $handler = $rota['handler'];
                if (is_array($handler)) {
                    $controller = $handler[0];
                    $action = $handler[1];

                    if (class_exists($controller)) {
                        $ctrlInstance = new $controller();
                        if (method_exists($ctrlInstance, $action)) {
                            return call_user_func_array([$ctrlInstance, $action], $matches);
                        } else {
                            http_response_code(500);
                            echo "Hata: {$controller} sınıfında '{$action}' metodu bulunamadı.";
                            return;
                        }
                    } else {
                        http_response_code(500);
                        echo "Hata: '{$controller}' sınıfı yüklenemedi.";
                        return;
                    }
                }
            }
        }

        http_response_code(404);
        echo "<h2 style='font-family:sans-serif; text-align:center; margin-top:50px;'>404 - Sayfa Bulunamadı</h2>";
        echo "<p style='font-family:sans-serif; text-align:center;'>Aradığınız sayfa Modulix-CMS rotalarında tanımlı değil.</p>";
    }
}

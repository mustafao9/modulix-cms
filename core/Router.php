<?php
class Router {
    private static $rotalar = [];

    public static function ekle($method, $path, $callback) {
        self::$rotalar[strtoupper($method)][trim($path, '/')] = $callback;
    }

    public static function calistir($method = null, $path = null) {
        $method = $method ?? strtoupper($_SERVER['REQUEST_METHOD']);
        
        if ($path === null) {
            $uri = $_GET['url'] ?? '';
            $path = trim($uri, '/');
        }
        
        if ($path === '' || $path === 'public') {
            $path = 'giris';
        }

        // İstek POST ise ve doğrudan bulunamadıysa, form işlemlerini desteklemek için esneklik sağla
        $arananMethodlar = [$method];
        if ($method === 'POST') {
            $arananMethodlar[] = 'GET'; // Geçici tolerans
        }

        foreach ($arananMethodlar as $m) {
            if (isset(self::$rotalar[$m])) {
                foreach (self::$rotalar[$m] as $rota => $callback) {
                    $rotaDesen = preg_replace('/\{[a-zA-Z0-9_]+\}/', '([a-zA-Z0-9_]+)', $rota);
                    $rotaDesen = "#^" . $rotaDesen . "$#";

                    if (preg_match($rotaDesen, $path, $matches)) {
                        array_shift($matches);
                        if (is_array($callback)) {
                            $controllerName = $callback[0];
                            $actionName = $callback[1];
                            if (class_exists($controllerName)) {
                                $controller = new $controllerName();
                                if (method_exists($controller, $actionName)) {
                                    return call_user_func_array([$controller, $actionName], $matches);
                                }
                            }
                        }
                    }
                }
            }
        }

        header("HTTP/1.0 404 Not Found");
        echo "<div style='font-family:sans-serif; text-align:center; margin-top:100px;'>";
        echo "<h2 style='color:#dc2626;'>404 - Sayfa Bulunamadı</h2>";
        echo "<p>Rota: <b>" . htmlspecialchars($path) . "</b></p>";
        echo "<p style='color:#666; font-size:13px;'>Method: {$method}</p>";
        echo "</div>";
    }
}

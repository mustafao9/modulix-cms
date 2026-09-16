<?php
class Router {
    private static $rotalar = [];

    public static function ekle($method, $path, $callback) {
        self::$rotalar[strtoupper($method)][trim($path, '/')] = $callback;
    }

    public static function calistir() {
        $scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        $path = str_replace($scriptName, '', $requestUri);
        $path = trim($path, '/');
        $method = strtoupper($_SERVER['REQUEST_METHOD']);

        if (isset(self::$rotalar[$method])) {
            foreach (self::$rotalar[$method] as $rota => $callback) {
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

        header("HTTP/1.0 404 Not Found");
        echo "<div style='font-family:sans-serif; text-align:center; margin-top:100px;'>";
        echo "<h2 style='color:#dc2626;'>404 - Sayfa Bulunamadı</h2>";
        echo "<p>Rota: <b>" . htmlspecialchars($path) . "</b></p>";
        echo "</div>";
    }
}

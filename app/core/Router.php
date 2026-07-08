<?php
class Router {
    public $routes = [];

    public function add($route, $params) {
        $this->routes[$route] = $params;
    }

    public function dispatch() {
        $url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : '';
        
        if (array_key_exists($url, $this->routes)) {
            $controller = $this->routes[$url]['controller'];
            $action = $this->routes[$url]['action'];
            
            $controllerFile = '../app/controllers/' . $controller . '.php';
            
            if (file_exists($controllerFile)) {
                require_once $controllerFile;
                $controllerInstance = new $controller();
                
                if (method_exists($controllerInstance, $action)) {
                    $controllerInstance->$action();
                } else {
                    die("Method $action tidak ditemukan di controller $controller");
                }
            } else {
                die("Controller file tidak ditemukan: $controllerFile");
            }
        } else {
            $urlParts = explode('/', $url);
            $route = $urlParts[0];
            $id = isset($urlParts[1]) ? $urlParts[1] : null;
            
            if (array_key_exists($route, $this->routes)) {
                $controller = $this->routes[$route]['controller'];
                $action = $this->routes[$route]['action'];
                
                $controllerFile = '../app/controllers/' . $controller . '.php';
                
                if (file_exists($controllerFile)) {
                    require_once $controllerFile;
                    $controllerInstance = new $controller();
                    
                    if (method_exists($controllerInstance, $action)) {
                        if ($id) {
                            $controllerInstance->$action($id);
                        } else {
                            $controllerInstance->$action();
                        }
                    } else {
                        die("Method $action tidak ditemukan di controller $controller");
                    }
                } else {
                    die("Controller file tidak ditemukan: $controllerFile");
                }
            } else {
                http_response_code(404);
                echo "404 Not Found - Halaman tidak ditemukan";
            }
        }
    }
}
?>
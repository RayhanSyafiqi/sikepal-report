<?php
session_start();

define('BASE_URL', '/sikepal-report/');
define('APP_PATH', dirname(__DIR__));

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load files
require_once '../app/config/database.php';
require_once '../app/core/Controller.php';
require_once '../app/core/Model.php';

$route = isset($_GET['route']) ? $_GET['route'] : 'login';


$controllers = [
    'login' => ['AuthController', 'login'],
    'logout' => ['AuthController', 'logout'],
    'dashboard' => ['ReportController', 'index'],
    'sales_create' => ['SalesReportController', 'create'],
    'sales_reports' => ['ReportController', 'viewAll'],
    'sales_export' => ['ReportController', 'export'],
    'visit' => ['VisitReportController', 'index'],
    'visit_create' => ['VisitReportController', 'create'],
    'visit_reports' => ['VisitReportController', 'viewAll'],
    'users' => ['UserController', 'index'],
    'users_create' => ['UserController', 'create'],
    'users_edit' => ['UserController', 'edit'],
    'users_delete' => ['UserController', 'delete'],
    'profile' => ['ProfileController', 'index'],
    'profile_update' => ['ProfileController', 'update'],
];

if (isset($controllers[$route])) {
    list($controller, $action) = $controllers[$route];
    
    $controllerFile = "../app/controllers/{$controller}.php";
    if (file_exists($controllerFile)) {
        require_once $controllerFile;
        $instance = new $controller();
        
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
        if ($id && in_array($route, ['users_edit', 'users_delete'])) {
            $instance->$action($id);
        } else {
            $instance->$action();
        }
    } else {
        die("Controller tidak ditemukan: {$controllerFile}");
    }
} else {
    require_once '../app/controllers/AuthController.php';
    $auth = new AuthController();
    $auth->login();
}
?>
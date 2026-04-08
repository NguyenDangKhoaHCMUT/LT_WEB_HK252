<?php
session_start();
require_once 'config/database.php';

// Xử lý cơ bản URL rewrite từ .htaccess: ^(.*)$ index.php?url=$1
$url_param = isset($_GET['url']) ? rtrim($_GET['url'], '/') : '';
$url = explode('/', filter_var($url_param, FILTER_SANITIZE_URL));

$controller_name = !empty($url[0]) ? ucfirst($url[0]) . 'Controller' : 'HomeController';
$action = $url[1] ?? 'index';

$controller_file = "controllers/$controller_name.php";

if (file_exists($controller_file)) {
    require_once $controller_file;
    $controller = new $controller_name();
    if (method_exists($controller, $action)) {
        unset($url[0], $url[1]);
        $params = $url ? array_values($url) : [];
        call_user_func_array([$controller, $action], $params);
    } else {
        http_response_code(404);
        echo "Lỗi 404: Không tìm thấy Action '{$action}'.";
    }
} else {
    http_response_code(404);
    echo "Lỗi 404: Không tìm thấy Controller '{$controller_name}'.";
}
?>
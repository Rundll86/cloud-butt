<?php
// 统一API入口文件

// 根据action参数分发请求到对应的处理文件
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'admin_login':
        require_once 'login.php';
        break;
        
    case 'get_resources':
        require_once 'get_resources.php';
        break;
        
    case 'add_resource':
        require_once 'add_resource.php';
        break;
        
    case 'delete_resource':
        require_once 'delete_resource.php';
        break;
        
    case 'update_resource':
        require_once 'update_resource.php';
        break;
        
    case 'get_resource':
        // 获取单个资源详情
        require_once 'get_resource.php';
        break;
        
    default:
        http_response_code(400);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['error' => '无效的action参数']);
        break;
}
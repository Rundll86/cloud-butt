<?php
require_once 'config.php';

// 获取单个资源详情
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // 获取资源ID
    $id = $_GET['id'] ?? '';
    
    if (empty($id)) {
        http_response_code(400);
        echo json_encode(['error' => '缺少资源ID']);
        exit();
    }
    
    // 读取数据
    $resources = readData();
    
    // 查找资源
    $resource = null;
    foreach ($resources as $r) {
        if ($r['id'] === $id) {
            $resource = $r;
            break;
        }
    }
    
    if (!$resource) {
        http_response_code(404);
        echo json_encode(['error' => '资源未找到']);
        exit();
    }
    
    echo json_encode([
        'success' => true,
        'data' => $resource
    ]);
    exit();
}

http_response_code(405);
echo json_encode(['error' => '方法不允许']);
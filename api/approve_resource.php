<?php
require_once 'config.php';

// 管理员审核资源
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 验证管理员权限
    verifyAdmin();
    
    // 获取POST数据
    $input = json_decode(file_get_contents('php://input'), true);
    
    // 验证必需字段
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(['error' => '缺少资源ID']);
        exit();
    }
    
    // 读取现有数据
    $resources = readData();
    
    // 查找并更新资源
    $found = false;
    foreach ($resources as &$resource) {
        if ($resource['id'] === $input['id']) {
            $resource['approved'] = true;
            $found = true;
            break;
        }
    }
    
    if (!$found) {
        http_response_code(404);
        echo json_encode(['error' => '资源未找到']);
        exit();
    }
    
    // 保存数据
    if (writeData($resources)) {
        echo json_encode([
            'success' => true,
            'message' => '资源审核通过'
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => '保存数据失败']);
    }
    exit();
}

http_response_code(405);
echo json_encode(['error' => '方法不允许']);
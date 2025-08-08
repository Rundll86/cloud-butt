<?php
require_once 'config.php';

// 删除资源
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // 验证管理员权限
    verifyAdmin();
    
    // 获取资源ID
    $id = $_GET['id'] ?? '';
    
    if (empty($id)) {
        http_response_code(400);
        echo json_encode(['error' => '缺少资源ID']);
        exit();
    }
    
    // 读取现有数据
    $resources = readData();
    
    // 查找并删除资源
    $found = false;
    foreach ($resources as $key => $resource) {
        if ($resource['id'] === $id) {
            unset($resources[$key]);
            $found = true;
            break;
        }
    }
    
    if (!$found) {
        http_response_code(404);
        echo json_encode(['error' => '资源未找到']);
        exit();
    }
    
    // 重新索引数组
    $resources = array_values($resources);
    
    // 保存数据
    if (writeData($resources)) {
        echo json_encode([
            'success' => true,
            'message' => '资源删除成功'
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => '保存数据失败']);
    }
    exit();
}

http_response_code(405);
echo json_encode(['error' => '方法不允许']);